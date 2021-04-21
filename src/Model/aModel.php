<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;

abstract class aModel implements iModel
{
    protected $PDO;
    protected $config;
    protected $sitemapTable;
    protected $usersTable;
    protected $adminTable;


    public function __construct(){
        $this->PDO = Container::get('pdo');
        $this->config = Container::get('config');
        $dbTables = $this->config['dbTables'];
        $this->sitemapTable = $dbTables['sitemap'];
        $this->usersTable   = $dbTables['users'];
        $this->adminTable   = $dbTables['admin'];
    }


}
