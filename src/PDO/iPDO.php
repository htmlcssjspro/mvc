<?php

namespace Militer\mvcCore\PDO;

interface iPDO
{
    public static function connect();

    public static function prepFetch(string $sql, string|array $params);
    public static function prepFetchAll(string $sql, string|array $params);
    public static function prepFetchColumn(string $sql, string|array $params);

    public static function queryFetch(string $sql);
    public static function queryFetchAll(string $sql);
    public static function queryFetchColumn(string $sql);
}
