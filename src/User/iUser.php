<?php

namespace Militer\mvcCore\User;

interface iUser
{
    public function login(array $loginData);
    public function logout();

    public function adminLogin(array $adminLoginData);
    public function adminLogout();
    public function adminPasswordChange(array $adminPasswordChangeData);
    public function addAdmin(array $newAdminData);

    public function register(array $registerData);
    public function accessRestoreRequest(array $accessRestoreData);
    public function accessRestore(array $accessRestoreData);


    public function test();
}
