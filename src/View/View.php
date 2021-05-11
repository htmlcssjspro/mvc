<?php

namespace Militer\mvcCore\View;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Response\iResponse;

class View implements iView
{
    private static $Response;


    private function __construct()
    {
    }


    private static function response()
    {
        self::$Response = Container::get(iResponse::class);
    }


    public static function renderPage($page)
    {
        self::response();
        self::$Response->body = $page;
        self::$Response->send();
    }

    public static function renderMain($main)
    {
        self::response();
        self::$Response->response = $main;
        self::$Response->sendResponse();
    }

    public static function renderNotFound()
    {
        self::response();
        self::$Response->notFound();
    }
}
