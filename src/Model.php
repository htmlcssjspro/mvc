<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Interfaces\iModel;

abstract class Model implements iModel
{

    public string $title;
    public string $description;

    public string $layout;
    public string $header;
    public string $footer;
    public string $aside;
    public string $mainContent;

    public string $mainCSS;
    public string $mainJS;
    public array $pageCSS = [];
    public array $pageJS = [];

    public array $data = [];

    protected $pdo;

    protected $sitemapTable = \SITEMAP_TABLE;
    protected $usersTable = \USERS_TABLE;


    public function __construct(){
        $this->pdo = Container::get('pdo');
    }


    public function getPageData($textId)
    {
        $sql = "SELECT `title`, `description` FROM {$this->sitemapTable} WHERE `text_id`='$textId' LIMIT 1";
        $pageData = $this->pdo->query($sql)->fetch();
        $this->title       = $pageData['title'];
        $this->description = $pageData['description'];
    }

}
