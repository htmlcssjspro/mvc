<?php

namespace Militer\mvcCore\User;

use Militer\mvcCore\DI\Container;

abstract class aUser implements iUser
{
    protected $PDO;
    protected $usersTable;
    private $config;


    public function __construct()
    {
        $this->PDO = Container::get('pdo');
        $this->config = Container::get('config');
        $this->usersTable = $this->config['dbTables']['users'];
    }


    protected function generatePassword($length = 8)
    {
        $length = $length > 8 ? $length : 8;
        $charsArr = [
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'abcdefghijklmnopqrstuvwxyz',
            '0123456789',
            '!@$%&?',
        ];
        $password = '';
        foreach ($charsArr as $chars) {
            $password .= $chars[\random_int(0, \mb_strlen($chars) - 1)];
        }
        while (\mb_strlen($password) < $length) {
            $chars = $charsArr[\random_int(0, \count($charsArr) - 1)];
            $password .= $chars[\random_int(0, \mb_strlen($chars) - 1)];
        }
        return \str_shuffle($password);
    }
}
