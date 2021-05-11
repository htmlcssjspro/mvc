<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;

abstract class aModel implements iModel
{
    protected $PDO;
    protected $config;
    protected string $mainSitemapTable  = 'main_sitemap';
    protected string $mainLayoutsTable  = 'main_layouts';
    protected string $mainSectionsTable = 'main_sections';
    protected string $adminSitemapTable = 'admin_sitemap';
    protected string $adminTable        = 'admin';
    protected string $usersTable        = 'users';


    protected function __construct()
    {
        $this->PDO = Container::get('pdo');
        $this->config = Container::get('config');
        // $dbTables = $this->config['dbTables'];
        // $this->mainSitemapTable  = $dbTables['mainSitemap'];
        // $this->mainLayoutsTable  = $dbTables['mainLayouts'];
        // $this->mainSectionsTable = $dbTables['mainSections'];
        // $this->adminSitemapTable = $dbTables['adminSitemap'];
        // $this->usersTable = $dbTables['users'];
        // $this->adminTable = $dbTables['admin'];
    }
}
