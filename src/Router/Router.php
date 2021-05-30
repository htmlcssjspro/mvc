<?php

namespace Militer\mvcCore\Router;

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
        $routerConfig = Container::get('config', 'router');

        $method     = $Request->getMethod();
        $requestUri = $Request->getRequestUri();

        $routerData = [
            'method' => $method,
            'requestUri' => $requestUri,
        ];

        foreach ($routerConfig as $key => $value) {
            $route = $value['route'];
            if (\preg_match("~^$route~", $requestUri)) {
                $controller = $routerConfig[$key]['controller'];
                // $routerData['action'] = \preg_replace("~^$route~", '', $requestUri);
                $query = \preg_replace("~^$route~", '', $requestUri);
                $queryArray = \explode('/', $query);
                $action = \array_shift($queryArray);
                $routerData['action'] = \lcfirst(\str_replace('-', '', \ucwords($action, '-')));
                $routerData['query'] = $queryArray;
            }
        }
        $Controller = Container::get($controller);
        $Controller->index($routerData);
    }
}
