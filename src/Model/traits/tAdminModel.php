<?php

namespace Militer\mvcCore\Model\traits;

trait tAdminModel
{
    public function getMainLayoutList()
    {
        $table = self::MAIN_LAYOUTS_TABLE;
        return $this->getLayoutList($table);
    }
    public function getAdminLayoutList()
    {
        $table = self::ADMIN_LAYOUTS_TABLE;
        return $this->getLayoutList($table);
    }
    private function getLayoutList(string $table)
    {
        $sql = "SELECT `layout` FROM `{$table}`";
        return self::$PDO::queryFetchAllColumn($sql);
    }
}
