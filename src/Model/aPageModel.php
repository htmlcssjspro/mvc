<?php

namespace Militer\mvcCore\Model;

abstract class aPageModel extends aModel
{
    public string $title;
    public string $description;

    public string $layout;
    public string $header;
    public string $footer;
    public string $aside;
    public string $mainContent;

    public array $includes = [];
    public array $layoutPopups = [];
    public array $popups = [];

    public string $mainCSS;
    public string $mainJS;
    public array $pageCSS = [];
    public array $pageJS = [];

    public array $userData = [];
    public array $data = [];


    public function __construct()
    {
        parent::__construct();
    }


    public function getPageID($pageUrl)
    {
        $sql = "SELECT `page_id` FROM {$this->sitemapTable} WHERE `page_url`=? LIMIT 1";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$pageUrl]);
        return $pdostmt->fetchColumn();
    }

    public function getPageData($pageId)
    {
        $sql = "SELECT `title`, `description`, `h1` FROM {$this->sitemapTable} WHERE `page_id`='$pageId'";
        $pageData = $this->PDO->query($sql)->fetch();
        $this->title       = $pageData['title'];
        $this->description = $pageData['description'];
        $this->h1          = $pageData['h1'];
    }


}
