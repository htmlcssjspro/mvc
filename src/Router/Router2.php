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
            if (\preg_match("~^$route~", $requestUri, $matches)) {
                $controller = $routerConfig[$key]['controller'];
                $routerData['controller'] = \trim($matches[0], '/');
                $query = \preg_replace("~^$route~", '', $requestUri);
                $queryArray = \explode('/', $query);
                $routerData['action'] = \array_shift($queryArray);
                $routerData['query'] = $queryArray;
            }
        }

        $Controller = Container::get($controller);
        $Controller->index($routerData);
    }
}
