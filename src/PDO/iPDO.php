<?php

namespace Militer\mvcCore\PDO;

interface iPDO
{
    public static function prepare(string $sql, array|string $params);
    public static function execute(string $sql, string|array $params);

    public static function prepFetch(string $sql, string|array $params);
    public static function prepFetchColumn(string $sql, string|array $params);
    public static function prepFetchAll(string $sql, string|array $params);
    public static function prepFetchAllColumn(string $sql, array|string $params, int $colNo = 0);
    public static function prepFetchAllColumnGroup(string $sql, array|string $params, int $colNo = 0);
    public static function prepFetchAllKeyPare(string $sql, array|string $params);


    public static function query(string $sql);

    public static function queryFetch(string $sql);
    public static function queryFetchColumn(string $sql);
    public static function queryFetchAll(string $sql);
    public static function queryFetchAllColumn(string $sql, int $colNo = 0);
    public static function queryFetchAllColumnGroup(string $sql, int $colNo = 0);
    public static function queryFetchAllKeyPare(string $sql, int $colNo = 0);
}
