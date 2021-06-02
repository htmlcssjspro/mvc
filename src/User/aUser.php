<?php

namespace Militer\mvcCore\User;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Model\aModel;
use Ramsey\Uuid\Uuid;

abstract class aUser extends aModel implements iUser
{
    public string $uuid    = 'guest';
    public string $name    = 'Гость';
    public string $status  = 'guest';
    public string $balance = '0';

    public string $adminUuid;
    public string $adminName;
    public string $adminStatus;

    private $usersTable = self::USERS_TABLE;
    private $adminTable = self::ADMIN_TABLE;


    protected function __construct()
    {
        parent::__construct();
        $this->init();
    }


    private function init()
    {
        if (!empty($_SESSION['user_uuid'])) {
            $this->uuid = $_SESSION['user_uuid'];
            $this->setUserData();
        }
        if (!empty($_SESSION['admin_uuid'])) {
            $this->adminUuid = $_SESSION['admin_uuid'];
            $this->setAdminData();
        }
    }


    private function setUserData()
    {
        \extract($this->getUserData());
        $this->name    = $username;
        $this->status  = $status;
        $this->balance = $balance;
    }
    private function getUserData()
    {
        $sql = "UPDATE {$this->usersTable} SET `last_visit`=CURRENT_DATE() WHERE `user_uuid`=?";
        self::$PDO::execute($sql, $this->uuid);

        $sql = "SELECT `username`, `status`, `balance`
        FROM {$this->usersTable} WHERE `user_uuid`=?";
        return self::$PDO::prepFetch($sql, $this->uuid);
    }


    private function setAdminData()
    {
        \extract($this->getAdminData());
        $this->adminName = $name;
        $this->adminEmail = $email;
        $this->adminStatus = $admin_status;
    }
    private function getAdminData()
    {
        $sql = "UPDATE {$this->adminTable}
        SET `last_visit`=CURRENT_DATE() WHERE `admin_uuid`=?";
        self::$PDO::execute($sql, $this->adminUuid);

        $sql = "SELECT `name`, `email`, `admin_status`
        FROM `{$this->adminTable}` WHERE `admin_uuid`=?";
        return self::$PDO::prepFetch($sql, $this->adminUuid);
    }


    public function login(array $loginData)
    {
        \extract($loginData);
        $loginData = $this->getLoginData($login);
        $verify = $loginData && \password_verify($password, $loginData['password']);
        $verify && $_SESSION['user_uuid'] = $loginData['user_uuid'];
        $this->Response->sendResponse('login', $verify);
    }
    private function getLoginData(string $email)
    {
        $sql = "SELECT `user_uuid`, `password`
        FROM `{$this->usersTable}` WHERE `email`=?";
        return self::$PDO::prepFetch($sql, $email);
    }

    public function logout()
    {
        unset($_SESSION['user_uuid']);
        $this->Response->sendResponse('logout', true);
    }


    public function adminLogin(array $adminLoginData)
    {
        \extract($adminLoginData);
        $adminLoginData = $this->getAdminLoginData($login);
        $verify = $adminLoginData && \password_verify($password, $adminLoginData['password']);
        $verify && $_SESSION['admin_uuid'] = $adminLoginData['admin_uuid'];
        $this->Response->sendResponse('adminLogin', $verify);
    }
    private function getAdminLoginData(string $email)
    {
        $sql = "SELECT `admin_uuid`, `password`
        FROM `{$this->adminTable}` WHERE `email`=? AND `status`='active'";
        return self::$PDO::prepFetch($sql, $email);
    }

    public function adminLogout()
    {
        unset($_SESSION['admin_uuid']);
        $this->Response->sendResponse('adminLogout', true);
    }


    public function register(array $registerData)
    {
        $this->checkEmail($this->usersTable, $registerData['email']) &&
            $this->Response->sendResponse('register', 'exists');
        $registerData['userUuid'] = Uuid::uuid4();
        $result = $this->insertRegisterData($registerData);
        $this->Response->sendResponse('register', $result);
    }
    private function insertRegisterData(array $registerData): bool
    {
        \extract($registerData);
        $sql = "INSERT INTO {$this->usersTable} (
            `user_uuid`,
            `username`,
            `name`,
            `email`,
            `password`,
            `phone`,
            `last_visit`,
            `register_date`
            )
        VALUES (
            :user_uuid,
            :username,
            :name,
            :email,
            :password,
            :phone,
            CURRENT_DATE(),
            CURRENT_DATE()
            )";

        $params = [
            ':user_uuid' => $userUuid,
            ':username'  => $login,
            ':name'      => $name,
            ':email'     => $email,
            ':password'  => $password,
            ':phone'     => $phone
        ];

        return self::$PDO::execute($sql, $params);
    }


    public function accessRestoreRequest(array $accessRestoreData)
    {
        \extract($accessRestoreData);

        !$this->checkEmail($this->usersTable, $email) &&
            $this->Response->sendResponse('accessRestore', 'noUser');
        $password = $this->generatePassword();
        $passwordHash = \password_hash($password, \PASSWORD_DEFAULT);
        $result = $this->updateRestorePassword($email, $passwordHash);
        $result = $result && $this->sendRestoreEmail($email, $password);
        $this->Response->sendResponse('accessRestore', $result);
    }
    private function updateRestorePassword(string $email, string $passwordHash)
    {
        $sql = "UPDATE {$this->usersTable} SET `restore_password`='{$passwordHash}' WHERE `email`=?";
        return self::$PDO::execute($sql, $email);
    }
    private function sendRestoreEmail(string $email, string $password): bool
    {
        $subject = 'Восстановление доступа';
        \ob_start();
        require Container::get('config', 'restoreEmail');
        $message = \ob_get_clean();
        $additional_headers = [
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'From'         => Container::get('config', 'email')['noreply'],
        ];

        return \mail($email, $subject, $message, $additional_headers);
    }

    public function accessRestore(array $accessRestoreData)
    {
        \extract($accessRestoreData);
        $restoreData = $this->getRestoreData($email);
        $verify = fn () => \password_verify($password, $restoreData['restore_password']);
        if ($restoreData && $verify()) {
            $passwordHash = \password_hash($password, \PASSWORD_DEFAULT);
            $update = $this->updatePasswordHash($email, $passwordHash);
            $update && $_SESSION['user_uuid'] = $restoreData['user_uuid'];
            $update && $this->Response->homePage();
        } else {
            $this->Response->notFoundPage();
        }
    }
    private function getRestoreData(string $email)
    {
        $sql = "SELECT `user_uuid`, `restore_password` FROM {$this->usersTable} WHERE `email`=?";
        return self::$PDO::prepFetch($sql, $email);
    }
    private function updatePasswordHash(string $email, string $passwordHash): bool
    {
        $sql = "UPDATE {$this->usersTable}
        SET `password`='{$passwordHash}', `restore_password`=NULL WHERE `email`=?";
        return self::$PDO::execute($sql, $email);
    }


    public function userPasswordChange(array $userPasswordChangeData)
    {
        \extract($userPasswordChangeData);
        $this->userPasswordVerify($password);
        $passwordHash = \password_hash($new_password, \PASSWORD_DEFAULT);
        $update = $this->updateUserPassword($passwordHash);
        $this->Response->sendResponse('userPasswordChange', $update);
    }
    private function userPasswordVerify(string $password): void
    {
        $passwordHash = $this->getUserPassword();
        $verify = \password_verify($password, $passwordHash);
        !$verify && $this->Response->sendResponse('userVerify', false);
    }
    private function getUserPassword(): string
    {
        $sql = "SELECT `password` FROM `{$this->usersTable}` WHERE `user_uuid`=?";
        return self::$PDO::prepFetchColumn($sql, $this->userUuid);
    }
    private function updateUserPassword(string $passwordHash): bool
    {
        $sql = "UPDATE `{$this->usersTable}` SET `password`='{$passwordHash}' WHERE `user_uuid`=?";
        return self::$PDO::execute($sql, $this->userUuid);
    }


    public function adminPasswordChange(array $adminPasswordChangeData)
    {
        \extract($adminPasswordChangeData);
        $this->adminPasswordVerify($password);
        $passwordHash = \password_hash($new_password, \PASSWORD_DEFAULT);
        $update = $this->updateAdminPassword($passwordHash);
        $this->Response->sendResponse('adminPasswordChange', $update);
    }
    private function adminPasswordVerify(string $password): void
    {
        $passwordHash = $this->getAdminPassword();
        $verify = \password_verify($password, $passwordHash);
        !$verify && $this->Response->sendResponse('adminVerify', false);
        // return $verify;
    }
    private function getAdminPassword(): string
    {
        $sql = "SELECT `password` FROM `{$this->adminTable}` WHERE `admin_uuid`=?";
        return self::$PDO::prepFetchColumn($sql, $this->adminUuid);
    }
    private function updateAdminPassword(string $passwordHash): bool
    {
        $sql = "UPDATE `{$this->adminTable}` SET `password`='{$passwordHash}' WHERE `admin_uuid`=?";
        return self::$PDO::execute($sql, $this->adminUuid);
    }


    public function addAdmin(array $newAdminData): void
    {
        $this->checkEmail($this->adminTable, $newAdminData['email']) &&
            $this->Response->sendResponse('adminAdd', 'exists');
        $newAdminData['adminUuid'] = Uuid::uuid4();
        $password = $this->generatePassword();
        $passwordHash = \password_hash($password, \PASSWORD_DEFAULT);
        $newAdminData['password'] = $passwordHash;
        $insert = $this->insertNewAdminData($newAdminData);
        $newAdminData['password'] = $password;
        $send = $insert && $this->sendNewAdminEmail($newAdminData);
        $this->Response->sendResponse('adminAdd', $send);
    }
    private function insertNewAdminData(array $newAdminData)
    {
        \extract($newAdminData);
        $sql = "INSERT INTO `{$this->adminTable}` (
            `admin_uuid`,
            `email`,
            `password`,
            `name`,
            `admin_status`,
            `status`,
            `last_visit`,
            `register_date`
            )
            VALUES (
                :adminUuid,
                :email,
                :password,
                :name,
                'admin',
                'active',
                CURRENT_DATE(),
                CURRENT_DATE()
                )
        ";
        $params = [
            ':adminUuid' => $adminUuid,
            ':email'     => $email,
            ':password'  => $password,
            ':name'      => $name,
        ];
        return self::$PDO::execute($sql, $params);
    }
    private function sendNewAdminEmail(array $newAdminData)
    {
        \extract($newAdminData);
        $emailFile = \ADMIN_VIEWS . '/email/newAdmin.php';
        $action = 'admin/api/activate-admin';
        $href = 'admin/activate-admin';
        $subject = 'Доступ к панели администратора';
        $from = 'noreply@htmlcssjs.pro';
        $emailData = \compact('email', 'name', 'emailFile', 'action', 'href', 'password', 'subject', 'from');
        return $this->sendEmail($emailData);
    }



    public function getAdminsData()
    {
        $sql = "SELECT
        `admin_uuid`,
        `email`,
        `name`,
        `admin_status`,
        `status`,
        `last_visit`,
        `register_date`
        FROM `{$this->adminTable}`";
        return self::$PDO::queryFetchAll($sql);
    }


    public function generatePassword($length = 8)
    {
        $length = $length > 8 ? $length : 8;
        $charsArr = [
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'abcdefghijklmnopqrstuvwxyz',
            '0123456789',
        ];
        $symbols = '!@$%^&?*()';
        $password = '';
        function random($string){
            return $string[\random_int(0, \mb_strlen($string) - 1)];
        }
        foreach ($charsArr as $chars) {
            $password .= random($chars);
        }
        while (\mb_strlen($password) < $length - 1) {
            $chars = $charsArr[\random_int(0, \count($charsArr) - 1)];
            $password .= random($chars);
        }
        $password .= random($symbols);
        return \str_shuffle($password);
    }


    private function checkEmail(string $table, string $email)
    {
        $sql = "SELECT 1 FROM `{$table}` WHERE `email`=?";
        return self::$PDO::prepFetchColumn($sql, $email);
    }

    private function sendEmail(array $emailData): bool
    {
        \extract($emailData);
        $url = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}";
        $action && $action = "{$url}/{$action}";
        $href && $href = "{$url}/{$href}";
        \ob_start();
        require $emailFile;
        $message = \ob_get_clean();
        $additional_headers = [
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'From'         => $from,
        ];
        return \mail($email, $subject, $message, $additional_headers);
    }
}
