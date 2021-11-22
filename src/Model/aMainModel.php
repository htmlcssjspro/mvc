<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\Model\aPageModel;

abstract class aMainModel extends aPageModel
{


    public function __construct()
    {
        $this->sitemapTable     = self::MAIN_SITEMAP_TABLE;
        $this->layoutsTable     = self::MAIN_LAYOUTS_TABLE;
        $this->sectionsTable    = self::MAIN_SECTIONS_TABLE;
        $this->views = \MAIN_VIEWS;
        parent::__construct();
    }


    protected function method()
    {
        // Code
    }
}
