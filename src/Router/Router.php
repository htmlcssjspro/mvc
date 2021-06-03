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
        $Request = Container::get(iRequest::class);
        $routes  = Container::get('routes');

        $method     = $Request->getMethod();
        $requestUri = $Request->getRequestUri();

        $routerData = [
            'method' => $method,
            'requestUri' => $requestUri,
        ];

        foreach ($routes as $name => $value) {
            $route = $value['route'];
            $pattern = "~^$route~";
            if (\preg_match($pattern, $requestUri)) {
                $controller = $routes[$name]['controller'];
                $query = \preg_replace($pattern, '', $requestUri);
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
