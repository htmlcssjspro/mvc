<?php

namespace Militer\mvcCore;

use Militer\mvcCore\Interfaces\iContainer;

class DB
{
    private static $pdo = null;

    private static $db = [];

    public function __construct(array $db)
    {
        \Militer\devCore\Debug::newClassInstance(__CLASS__); // Удалить в production.  // Для разработки самого фреймворка

        self::$db = $db;
        self::connect();
    }

    private static function connect()
    {
        return self::$pdo ?? self::newConnect(self::$db);
    }

    private static function newConnect(array $db)
    {
        \extract($db);
        return self::$pdo = new \PDO(
            "$driver:host=$host; dbname=$name; charset=utf8",
            $username,
            $password,
            $pdo_options
        );
        // OR :
        // return self::$pdo = new \PDO(
        //     "{$db['driver']}:host={$db['host']}; dbname={$db['name']}; charset=utf8",
        //     $db['$username'],
        //     $db['$password'],
        //     $db['$pdo_options']
        // );
    }
}
