<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\Model\aPageModel;
use Militer\mvcCore\Model\traits\tAdminModel;

abstract class aAdminModel extends aPageModel
{
    use tAdminModel;

    private const ADMIN_LOGIN_URI      = '/admin/login';
    private const ADMIN_ACTIVATION_URI = '/admin/admin-activation';


    public function __construct()
    {
        parent::__construct();
        $this->sitemapTable  = self::ADMIN_SITEMAP_TABLE;
        $this->layoutsTable  = self::ADMIN_LAYOUTS_TABLE;
        $this->sectionsTable = self::ADMIN_SECTIONS_TABLE;
        $this->views = \ADMIN_VIEWS;
    }


    public function init(string $requestUri): void
    {
        $this->adminCheck($requestUri);
        parent::init($requestUri);
    }


    protected function getAdminAsideData()
    {
        $sql = "SELECT `label`, `page_uri`
            FROM `{$this->sitemapTable}`
            WHERE `admin_aside`=1";
        return self::$PDO::queryFetchAll($sql);
    }


    protected function getMainPageListData()
    {
        $table = self::MAIN_SITEMAP_TABLE;
        return $this->getPageListData($table);
    }
    protected function getAdminPageListData()
    {
        $table = self::ADMIN_SITEMAP_TABLE;
        return $this->getPageListData($table);
    }
    private function getPageListData(string $table)
    {
        $sql = "SELECT
                `label`,
                `page_uri`,
                `title`,
                `description`,
                `h1`,
                `main`,
                `layout`
            FROM `{$table}`
            WHERE `admin`=1";
        return self::$PDO::queryFetchAll($sql);
    }

    // public function getMainLayoutList()
    // {
    //     $table = self::MAIN_LAYOUTS_TABLE;
    //     return $this->getLayoutList($table);
    // }
    // public function getAdminLayoutList()
    // {
    //     $table = self::ADMIN_LAYOUTS_TABLE;
    //     return $this->getLayoutList($table);
    // }
    // private function getLayoutList(string $table)
    // {
    //     $sql = "SELECT `layout` FROM `{$table}`";
    //     return self::$PDO::queryFetchAllColumn($sql);
    // }


    protected function getUsersList()
    {
        $table = self::USERS_TABLE;
        $sql = "SELECT
                `user_uuid`,
                `username`,
                `name`,
                `email`,
                `status`,
                `phone`,
                `last_visit`,
                `register_date`
            FROM `{$table}`";
        return self::$PDO::queryFetchAll($sql);
    }

    protected function getAdminsList()
    {
        $sql = "SELECT
                `admin_uuid`,
                `email`,
                `name`,
                `admin_status`,
                `status`,
                `last_visit`,
                `register_date`
            FROM `{$this->adminTable}`";
        return self::$PDO::queryFetchAll($sql);
    }


    protected function adminCheck(string $requestUri)
    {
        $requestUri === self::ADMIN_LOGIN_URI
            && $this->renderLoginPage(self::ADMIN_LOGIN_URI);
        $requestUri === self::ADMIN_ACTIVATION_URI
            && $this->renderLoginPage(self::ADMIN_ACTIVATION_URI);
        !isset($_SESSION['admin_uuid'])
            && $this->renderLoginPage(self::ADMIN_LOGIN_URI);
    }
    protected function renderLoginPage(string $uri)
    {
        parent::init($uri);
        $this->renderPage();
    }
}
