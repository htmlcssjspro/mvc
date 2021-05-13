<?php

namespace Militer\mvcCore\PDO;

use Militer\mvcCore\DI\Container;

class PDO implements iPDO
{
    private static $PDO = null;
    // private static $dbConfig = [];

    private function __construct()
    {
    }

    public static function connect()
    {
        self::$PDO === null && self::newConnect();
    }

    private static function newConnect()
    {
        $dbConfig = Container::get('dbConfig');
        \extract($dbConfig);
        self::$PDO = new \PDO(
            "{$driver}:host={$host}; dbname={$name}; charset=utf8",
            $username,
            $password,
            $pdo_options
        );
    }


    public static function prepFetch(string $sql, string|array $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetch();
    }
    public static function prepFetchAll(string $sql, string|array $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchAll();
    }
    public static function prepFetchColumn(string $sql, string|array $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchColumn();
    }
    private static function prepare(string $sql, string|array $params)
    {
        self::connect();
        $pdostmt = self::$PDO->prepare($sql);
        if (\is_array($params)){
            $pdostmt->execute($params);
        } elseif (\is_string($params)) {
            $pdostmt->execute([$params]);
        }
        return $pdostmt;
    }


    public static function queryFetch(string $sql)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetch();
    }
    public static function queryFetchAll(string $sql)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchAll();
    }
    public static function queryFetchColumn(string $sql)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchColumn();
    }
    private static function query(string $sql)
    {
        self::connect();
        $pdostmt = self::$PDO->query($sql);
        return $pdostmt;
    }
}
