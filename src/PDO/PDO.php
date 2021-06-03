<?php

namespace Militer\mvcCore\PDO;

use Militer\mvcCore\DI\Container;

class PDO implements iPDO
{
    private static \PDO|null $PDO = null;


    public function __construct()
    {
        self::connect();
    }


    private static function connect()
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


    private static function prepare(string $sql, array|string $params)
    {
        self::connect();
        $pdostmt = self::$PDO->prepare($sql);
        \is_string($params) && $params = [$params];
        $pdostmt->execute($params);
        return $pdostmt;
    }
    public static function execute(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        $errorCode = $pdostmt->errorCode();
        return $errorCode === '00000' ? true : false;
    }
    public static function prepFetch(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetch();
    }
    public static function prepFetchAll(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchAll();
    }
    public static function prepFetchColumn(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchColumn();
    }
    public static function prepPdostmt(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt;
    }


    private static function query(string $sql)
    {
        self::connect();
        $pdostmt = self::$PDO->query($sql);
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
}
