<?php

namespace Militer\mvcCore\PDO;

use Militer\mvcCore\DI\Container;

class DB implements iDB
{
    private static $PDO = null;


    private function __construct()
    {
    }


    public static function connect()
    {
        return self::$PDO ?? self::newConnect();
    }

    private static function newConnect()
    {
        $dbConfig = Container::get('dbConfig');
        \extract($dbConfig);
        return self::$PDO = new \PDO(
            "{$driver}:host={$host}; dbname={$name}; charset=utf8",
            $username,
            $password,
            $pdo_options
        );
    }
}
