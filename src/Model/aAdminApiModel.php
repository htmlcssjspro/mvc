<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\Model\aApiModel;
use Militer\mvcCore\Model\iAdminApiModel;

abstract class aAdminApiModel extends aApiModel implements iAdminApiModel
{


    public function __construct()
    {
        parent::__construct();
    }


    public function updateMainSitemap(array $sitemapData)
    {
        $table = self::MAIN_SITEMAP_TABLE;
        $this->updateSitemap($table, $sitemapData);
    }
    public function updateAdminSitemap(array $sitemapData)
    {
        $table = self::ADMIN_SITEMAP_TABLE;
        $this->updateSitemap($table, $sitemapData);
    }
    private function updateSitemap(string $table, array $sitemapData)
    {
        \extract($sitemapData);
        \prd(\get_defined_vars(), '\get_defined_vars()');
        $sql = "UPDATE `{$table}`
            SET
                `page_uri`    = :new_page_uri,
                `label`       = :label,
                `title`       = :title,
                `description` = :description,
                `h1`          = :h1,
                `layout`      = :layout
                `main`        = :main
            WHERE `page_uri`=:page_uri
        ";
        $params = [
            ':page_uri'    => $new_page_uri,
            ':label'       => $label,
            ':title'       => $title,
            ':description' => $description,
            ':h1'          => $h1,
            ':main'        => $main,
            ':layout'      => $layout,
        ];
        return self::$PDO::execute($sql, $params);
    }




    public function preferences(array $preferencesData)
    {
        $this->adminCheck();
        \extract($preferencesData);

        \prd($preferencesData, '$preferencesData');

        $this->Response->sendResponse('adminPreferences', true);
        $this->Response->sendResponse('adminPreferences', false);
    }


    //*************************************************************************
    //***** Common
    //*************************************************************************

    protected function adminCheck()
    {
        !isset($_SESSION['admin_uuid'])
            && $this->Response->sendResponse('logout', true);
    }
}
