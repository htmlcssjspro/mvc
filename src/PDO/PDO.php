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


    public static function prepare(string $sql, array|string $params)
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
    public static function prepFetchColumn(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchColumn();
    }
    public static function prepFetchAll(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchAll();
    }
    public static function prepFetchAllColumn(string $sql, array|string $params, int $colNo = 0)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchAll(\PDO::FETCH_COLUMN, $colNo);
    }
    public static function prepFetchAllColumnGroup(string $sql, array|string $params, int $colNo = 0)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_GROUP, $colNo);
    }
    public static function prepFetchAllKeyPare(string $sql, array|string $params)
    {
        $pdostmt = self::prepare($sql, $params);
        return $pdostmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }


    public static function query(string $sql)
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
    public static function queryFetchColumn(string $sql)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchColumn();
    }
    public static function queryFetchAll(string $sql)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchAll();
    }
    public static function queryFetchAllColumn(string $sql, int $colNo = 0)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchAll(\PDO::FETCH_COLUMN, $colNo);
    }
    public static function queryFetchAllColumnGroup(string $sql, int $colNo = 0)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_GROUP, $colNo);
    }
    public static function queryFetchAllKeyPare(string $sql, int $colNo = 0)
    {
        $pdostmt = self::query($sql);
        return $pdostmt->fetchAll(\PDO::FETCH_KEY_PAIR, $colNo);
    }
}
