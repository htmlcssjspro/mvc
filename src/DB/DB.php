<?php

namespace Militer\mvcCore\DB;

use Militer\mvcCore\DI\Container;

class DB implements iDB
{
    private static $pdo = null;
    private static $dbConfig = [];

    public function __construct()
    {
        self::$dbConfig = Container::get('dbConfig');
    }

    public static function connect()
    {
        return self::$pdo ?? self::newConnect(self::$dbConfig);
    }

    private static function newConnect(array $dbConfig)
    {
        \extract($dbConfig);
        return self::$pdo = new \PDO(
            "$driver:host=$host; dbname=$name; charset=utf8",
            $username,
            $password,
            $pdo_options
        );
    }
}
