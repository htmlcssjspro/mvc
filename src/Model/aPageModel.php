<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\User\iUser;

abstract class aPageModel extends aModel
{
    protected iUser $User;

    protected string $sitemapTable;
    protected string $layoutsTable;
    protected string $sectionsTable;
    protected string $views;

    private string $pageUri;

    private string $layout;

    public string $header;
    public string $footer;
    public string $aside;

    public array $headerNav;
    public array $asideNav;
    public array $footerNav;

    public string $mainContent;

    public string $title;
    public string $description;
    public string $h1;

    public string $mainCSS;
    public string $mainJS;

    public string $pageCSS;
    public string $pageJS;


    public function __construct()
    {
        parent::__construct();
        $this->User = Container::get(iUser::class);
    }


    public function init(string $requestUri)
    {
        $requestUri = \trim($requestUri, '/');
        $checkPageUri = $this->checkPageUri($requestUri);
        $checkPageUri ? $this->pageUri = $requestUri : $this->Response->notFound();
    }
    private function checkPageUri(string $requestUri)
    {
        $sql = "SELECT 1 FROM `{$this->sitemapTable}` WHERE `page_uri`=?";
        return self::$PDO::prepFetchColumn($sql, $requestUri);
    }


    public function renderPage()
    {
        $this->setLayoutData();
        $this->setMainData();

        \ob_start();
        $Model = $this;
        require $this->layout;
        $page = \ob_get_clean();
        $this->Response->sendPage($page);
    }
    private function setLayoutData()
    {
        \extract($this->getLayoutData());

        $this->layout = "{$this->views}/layouts/{$layout}.php";

        $this->header = "{$this->views}/components/{$header}.php";
        $this->footer = "{$this->views}/components/{$footer}.php";
        $this->aside  = "{$this->views}/components/{$aside}.php";

        $this->mainCSS = "/public/css/{$css}.css";
        $this->mainJS  = "/public/js/{$js}.js";

        $this->headerNav = $this->getHeaderNav();
        $this->footerNav = $this->getFooterNav();
        $this->asideNav  = $this->getAsideNav();
    }
    private function getLayoutData()
    {
        $sql = "SELECT `layout`, `header`, `footer`, `aside`, `css`, `js` FROM `{$this->layoutsTable}` WHERE `current`=1 LIMIT 1";
        $layoutData = self::$PDO::queryFetch($sql);
        return $this->escapeOutput($layoutData);
    }

    private function getHeaderNav()
    {
        $sql = "SELECT `label`, `page_uri` FROM `{$this->sitemapTable}` WHERE `header_nav`=1 ORDER by `header_nav_order`";
        $headerNav = self::$PDO::queryFetchAll($sql);
        return $this->escapeOutput($headerNav);
    }
    private function getFooterNav()
    {
        $sql = "SELECT `label`, `page_uri` FROM `{$this->sitemapTable}` WHERE `footer_nav`=1 ORDER by `footer_nav_order`";
        $footerNav = self::$PDO::queryFetchAll($sql);
        return $this->escapeOutput($footerNav);
    }
    private function getAsideNav()
    {
        $sql = "SELECT `label`, `page_uri` FROM `{$this->sitemapTable}` WHERE `aside_nav`=1 ORDER by `aside_nav_order`";
        $asideNav = self::$PDO::queryFetchAll($sql);
        return $this->escapeOutput($asideNav);
    }


    public function renderMain()
    {
        $this->setMainData();

        \ob_start();
        $Model = $this;
        require $this->mainContent;
        $main['content'] = \ob_get_clean();
        $main['title'] = $this->title;
        $main['description'] = $this->description;
        $main['h1'] = $this->h1;
        $this->Response->sendMain($main);
    }
    private function setMainData()
    {
        \extract($this->getMainData());
        $this->mainContent = "{$this->views}/pages/{$main_content}.php";
        $this->pageCSS = $page_css ? "/public/css/{$page_css}.css" : '';
        $this->pageJS  = $page_js  ? "/public/js/{$page_js}.js" : '';
        $this->title       = $title;
        $this->description = $description;
        $this->h1          = $h1;
    }
    private function getMainData()
    {
        $sql = "SELECT `main_content`, `title`, `description`, `h1`, `page_css`, `page_js`
        FROM `{$this->sitemapTable}` WHERE `page_uri`=?";
        $mainData = self::$PDO::prepFetch($sql, $this->pageUri);
        return $this->escapeOutput($mainData);
    }



    protected function getSection(string $section)
    {
        $sql = "SELECT `file` FROM `{$this->sectionsTable}` WHERE `name`='{$section}'";
        $sectionFile = self::$PDO::queryFetchColumn($sql);
        return require "{$this->views}/sections/{$sectionFile}.php";
    }

    protected function getUserDictionary()
    {
        return Container::get('dictionary', 'user');
    }

    protected function escapeOutput(array &$data)
    {
        \array_walk_recursive($data, function (&$item) {
            $item = \htmlspecialchars($item, \ENT_QUOTES | \ENT_HTML5 | \ENT_SUBSTITUTE, 'UTF-8');
        });
        return $data;
    }
}
