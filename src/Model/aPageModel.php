<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\User\iUser;

abstract class aPageModel extends aModel implements iPageModel
{
    protected iUser $User;

    protected string $sitemapTable;
    protected string $layoutsTable;
    protected string $sectionsTable;
    protected string $views;

    private string $pageUri;

    private string|false $layout;

    public string $header;
    public string $footer;
    public string $aside;

    public string $mainContent;

    public string $title;
    public string $description;
    public string $h1;

    public string $layoutCSS;
    public string $layoutJS;

    public string $mainCSS;
    public string $mainJS;

    public array $pageData;


    public function __construct()
    {
        $this->User = Container::get(iUser::class);
        parent::__construct();
    }


    public function init(string $requestUri): void
    {
        $requestUri = \trim($requestUri, '/');
        $this->layout = $this->getLayout($requestUri);
        // $this->checkPageUri($requestUri);
        $this->layout
            ? $this->pageUri = $requestUri
            : $this->Response->notFound();
    }
    private function getLayout(string $requestUri)
    {
        $sql = "SELECT `layout` FROM `{$this->sitemapTable}` WHERE `page_uri`=?";
        return self::$PDO::prepFetchColumn($sql, $requestUri);
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
        \file_exists($this->layout)
            ? require $this->layout
            : "<!-- {$this->layout} file not found -->";
        $page = \ob_get_clean();
        $this->Response->sendPage($page);
    }
    private function setLayoutData()
    {
        \extract($this->getLayoutData());

        $this->layout = "{$this->views}/layouts/{$this->layout}.php";

        $this->head   = $head;
        $this->header = $header;
        $this->footer = $footer;
        $this->aside  = $aside;

        $this->layoutCSS = $css;
        $this->layoutJS  = $js;
    }
    private function getLayoutData()
    {
        $sql = "SELECT `head`, `header`, `footer`, `aside`, `css`, `js`
            FROM `{$this->layoutsTable}`
            WHERE `layout`=?";
        $layoutData = self::$PDO::prepFetch($sql, $this->layout);
        return $this->escapeOutput($layoutData);
    }


    public function renderMain()
    {
        $this->setMainData();

        \ob_start();
        $this->getMainContent();
        $main['content'] = \ob_get_clean();
        $main['title'] = $this->title;
        $main['description'] = $this->description;
        $main['h1'] = $this->h1;
        $this->Response->sendMain($main);
    }
    private function setMainData()
    {
        \extract($this->getMainData());
        $this->mainContent = $main;
        $this->mainCSS = $css;
        $this->mainJS  = $js;
        $this->title       = $title ?: 'Title';
        $this->description = $description ?: 'Description';
        $this->h1          = $h1 ?: 'Заголовок H1';
    }
    private function getMainData()
    {
        $sql = "SELECT `main`, `title`, `description`, `h1`, `css`, `js`
            FROM `{$this->sitemapTable}` WHERE `page_uri`=?";
        $mainData = self::$PDO::prepFetch($sql, $this->pageUri);
        return $this->escapeOutput($mainData);
    }


    //*************************************************************************
    //***** Layout Data
    //*************************************************************************

    protected function getComponent(string $name)
    {
        $component = "{$this->views}/components/{$this->$name}.php";
        return \file_exists($component)
            ? require $component
            : "<!-- {$component} file not found -->";
    }
    protected function getMainContent()
    {
        $mainContent = "{$this->views}/pages/{$this->mainContent}.php";
        return \file_exists($mainContent)
            ? require $mainContent
            : "<!-- {$mainContent} file not found -->";
    }

    protected function getCSS(string $css, bool $preload = false)
    {
        $css = \CSS . "/{$css}.css";
        return \file_exists(\_ROOT_ . $css)
            ? ($preload
                ? "<link rel=\"preload\" href=\"{$css}\" as=\"stylesheet\">"
                : "<link rel=\"stylesheet\" href=\"{$css}\">")
            : "<!-- {$css} file not found -->";
    }
    protected function getLayoutCSS(bool $preload = false)
    {
        return $this->getCSS($this->layoutCSS, $preload);
    }
    protected function getMainCSS(bool $preload = false)
    {
        return $this->getCSS($this->mainCSS, $preload);
    }

    protected function getJS(string $js, bool $preload = false)
    {
        $js = \JS . "/{$js}.js";
        return \file_exists(\_ROOT_ . $js)
            ? ($preload
                ? "<link rel=\"preload\" href=\"{$js}\" as=\"script\">"
                : "<script defer src=\"{$js}\"></script>")
            : "<!-- {$js} file not found -->";
    }
    protected function getLayoutJS(bool $preload = false)
    {
        return $this->getJS($this->layoutJS, $preload);
    }
    protected function getMainJS(bool $preload = false)
    {
        return $this->getJS($this->mainJS, $preload);
    }


    //*************************************************************************
    //***** Page Data
    //*************************************************************************

    protected function getHeaderNav()
    {
        $sql = "SELECT `label`, `page_uri`
            FROM `{$this->sitemapTable}`
            WHERE `header_nav`=1 ORDER by `header_nav_order`";
        $headerNav = self::$PDO::queryFetchAll($sql);
        return $this->escapeOutput($headerNav);
    }
    protected function getFooterNav()
    {
        $sql = "SELECT `label`, `page_uri`
            FROM `{$this->sitemapTable}`
            WHERE `footer_nav`=1 ORDER by `footer_nav_order`";
        $footerNav = self::$PDO::queryFetchAll($sql);
        return $this->escapeOutput($footerNav);
    }
    protected function getAsideNav()
    {
        $sql = "SELECT `label`, `page_uri`
            FROM `{$this->sitemapTable}`
            WHERE `aside_nav`=1 ORDER by `aside_nav_order`";
        $asideNav = self::$PDO::queryFetchAll($sql);
        return $this->escapeOutput($asideNav);
    }


    protected function getSection(string $section)
    {
        // $sql = "SELECT `file` FROM `{$this->sectionsTable}` WHERE `name`='{$section}'";
        // $sectionFile = self::$PDO::queryFetchColumn($sql);
        // $sectionFile = "{$this->views}/sections/{$sectionFile}.php";
        $section = "{$this->views}/sections/{$section}.php";
        return \file_exists($section)
            ? require $section
            : "<!-- {$section} file not found -->";
    }

    protected function getDictionary()
    {
        return Container::get('config', 'dictionary');
    }


    //*************************************************************************
    //***** Common
    //*************************************************************************

    protected function escapeOutput(array &$data)
    {
        \array_walk_recursive($data, function (&$item) {
            $item = \htmlspecialchars($item, \ENT_QUOTES | \ENT_HTML5 | \ENT_SUBSTITUTE, 'UTF-8');
        });
        return $data;
    }
}
