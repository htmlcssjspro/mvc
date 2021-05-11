<?php

namespace Militer\mvcCore\Router;

use Admin\Controllers\AdminApiController;
use Admin\Controllers\AdminController;
use Main\Controllers\MainApiController;
use Main\Controllers\MainController;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Request\iRequest;

class Router implements iRouter
{


    private function __construct()
    {
    }


    public static function init()
    {
        $Request  = Container::get(iRequest::class);

        $method     = $Request->getMethod();
        $requestUri = $Request->getRequestUri();
        // $query      = $Request->getQuery();

        $admin    = '/admin/';
        $adminApi = '/admin/api/';
        $api      = '/api/';

        $parameters = [
            'method' => $method,
            'requestUri' => $requestUri,
            // 'query' => $query,
        ];



        $reg = function ($exp) use ($requestUri) {
            return \mb_eregi("^$exp", $requestUri);
        };
        $params = function ($exp) use (&$parameters, $requestUri) {
            $parameters['controller'] = trim($exp, '/');
            $action = \mb_eregi_replace("^$exp", '', $requestUri);
            $array = \explode('/', $action);
            $parameters['action'] = $array[0];
            $parameters['query'] = !empty($array[1]) ? \mb_eregi_replace("^{$array[0]}/", '', $action) : '';
            $parameters['hash'] = '';
        };


        if ($reg($admin)) {
            // if ($method === 'post' && $reg($adminApi)) {
            if ($reg($adminApi)) {
                $params($adminApi);
                $Controller = AdminApiController::class;
            } else {
                $params($admin);
                $Controller = AdminController::class;
            }
            // } elseif ($method === 'post' && $reg($api)) {
        } elseif ($reg($api)) {
            $params($api);
            $Controller = MainApiController::class;
        } else {
            $params('/');
            $Controller = MainController::class;
        }


        $Controller = Container::get($Controller);
        $Controller->index($parameters);
    }
}
