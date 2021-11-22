<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\Model\aApiModel;
use Militer\mvcCore\Model\interfaces\iAdminApiModel;
use Militer\mvcCore\Model\traits\tAdminModel;

abstract class aAdminApiModel extends aApiModel implements iAdminApiModel
{
    use tAdminModel;


    public function __construct()
    {
        $this->views = \ADMIN_VIEWS;
        parent::__construct();
    }


    public function updateMainSitemap(array $mainSitemapData)
    {
        $this->updateSitemap($mainSitemapData, self::MAIN_SITEMAP_TABLE);
    }
    public function updateAdminSitemap(array $adminSitemapData)
    {
        $this->updateSitemap($adminSitemapData, self::ADMIN_SITEMAP_TABLE);
    }
    private function updateSitemap(array $sitemapData, string $sitemapTable)
    {
        \extract($sitemapData);
        $new_page_uri !== $page_uri
            && $this->checkPageUri($new_page_uri, $sitemapTable)
            && $this->Response->sendResponse('updatePage', 'exists');

        $sql = "UPDATE `{$sitemapTable}`
            SET
                `page_uri`    = :page_uri,
                `label`       = :label,
                `title`       = :title,
                `description` = :description,
                `h1`          = :h1,
                `layout`      = :layout,
                `main`        = :main
            WHERE `page_uri`='{$page_uri}'
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
        $update = self::$PDO::execute($sql, $params);
        $this->Response->sendResponse('updatePage', $update);
    }


    public function addMainNewPage(array $mainNewPageData)
    {
        $this->addNewPage($mainNewPageData, self::MAIN_SITEMAP_TABLE);
    }
    public function addAdminNewPage(array $adminNewPageData)
    {
        $this->addNewPage($adminNewPageData, self::ADMIN_SITEMAP_TABLE);
    }
    private function addNewPage(array $newPageData, string $sitemapTable)
    {
        \extract($newPageData);
        $this->checkPageUri($page_uri, $sitemapTable)
            && $this->Response->sendResponse('addNewPage', 'exists');

        $sql = "INSERT INTO `{$sitemapTable}` (
                `page_uri`,
                `label`,
                `title`,
                `description`,
                `h1`,
                `layout`,
                `main`
            )
            values (
                :page_uri,
                :label,
                :title,
                :description,
                :h1,
                :layout,
                :main
            )";
        $params = [
            ':page_uri'    => $page_uri,
            ':label'       => $label,
            ':title'       => $title,
            ':description' => $description,
            ':h1'          => $h1,
            ':layout'      => $layout,
            ':main'        => $main,
        ];
        $insert = self::$PDO::execute($sql, $params);
        $this->Response->sendResponse('addNewPage', $insert);
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

    private function checkPageUri(string $page_uri, string $sitemapTable)
    {
        $sql = "SELECT 1 FROM `{$sitemapTable}` WHERE `page_uri`=?";
        return self::$PDO::prepFetchColumn($sql, $page_uri);
    }



    public function test()
    {
    }
}
