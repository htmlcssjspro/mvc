<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DB\iPDO;
use Militer\mvcCore\DI\Container;

abstract class aModel implements iModel
{
    protected iPDO $PDO;
    protected \PDO $pdo;
    protected $config;
    protected string $mainSitemapTable  = 'main_sitemap';
    protected string $mainLayoutsTable  = 'main_layouts';
    protected string $mainSectionsTable = 'main_sections';
    protected string $adminSitemapTable = 'admin_sitemap';
    protected string $adminTable        = 'admin';
    protected string $usersTable        = 'users';


    protected function __construct()
    {
        $this->PDO = Container::get('PDO');
        $this->pdo = Container::get('pdo');
        $this->config = Container::get('config');
    }
}
