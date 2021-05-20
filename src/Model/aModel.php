<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\PDO\iPDO;

abstract class aModel implements iModel
{
    public static iPDO $PDO;

    protected iResponse $Response;

    protected const MAIN_SITEMAP_TABLE   = 'main_sitemap';
    protected const MAIN_LAYOUTS_TABLE   = 'main_layouts';
    protected const MAIN_SECTIONS_TABLE  = 'main_sections';
    protected const MAIN_POPUPS_TABLE    = 'main_popups';

    protected const ADMIN_SITEMAP_TABLE  = 'admin_sitemap';
    protected const ADMIN_LAYOUTS_TABLE  = 'admin_layouts';
    protected const ADMIN_SECTIONS_TABLE = 'admin_sections';
    protected const ADMIN_POPUPS_TABLE   = 'admin_popups';
    protected const ADMIN_TABLE          = 'admin';

    protected const USERS_TABLE          = 'users';


    protected function __construct()
    {
        self::$PDO      = Container::get('PDO');
        $this->Response = Container::get(iResponse::class);
    }
}
