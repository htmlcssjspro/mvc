<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\Interfaces\iRouter;

class Router implements iRouter
{
    private $Request;
    private $Response;
    private $pdo;
    private static array $routes = [];


    public function __construct(iRequest $request, iResponse $response)
    {
        $this->Request  = $request;
        $this->Response = $response;
        $this->pdo = Container::get('pdo');

        $this->dispatch();
    }

    public function dispatch()
    {
        $method     = $this->Request->getMethod();
        $requestUri = $this->Request->getRequestUri();
        $sitemap_table = \SITEMAP_TABLE;
        $sql = "SELECT `controller`, `action` FROM $sitemap_table WHERE `method`=:method AND `page_url`=:page_url LIMIT 1";
        $pdostmt = $this->pdo->prepare($sql);
        $pdostmt->execute([':method' => $method, ':page_url' => $requestUri]);
        $page = $pdostmt->fetch();

        if (!$page) {
            $this->Response->notFound();
        }

        $controller = $page['controller'];
        $action     = $page['action'] ?? 'index';

        $controller = Container::get("App\Controllers\\$controller");
        $controller->$action();

    }

    private static function matchRoute(String $method, String $requestUri)
    {
        foreach (self::$routes[$method] as $pattern => $value) {
            if (\preg_match("~$pattern~i", $requestUri, $matches)) {
                $route['controller'] = $value['controller'];
                $route['action']     = $value['action'];
                foreach ($matches as $key => $val) {
                    if (\is_string($key)) {
                        $route['query'][$key] = $val;
                    }
                }
                return $route;
            }
        }
    }

    public static function get(String $pattern, String $controller, String $action = 'index')
    {
        self::set('get', $pattern, $controller, $action);
    }

    public static function post(String $pattern, String $controller, String $action = 'index')
    {
        self::set('post', $pattern, $controller, $action);
    }

    public static function set(String $method, String $pattern, String $controller, String $action)
    {
        self::$routes[$method][$pattern] = ['controller' => $controller, 'action' => $action];
        // \array_unshift(self::$routes[$method], $array[$pattern] = ['controller' => $controller, 'action' => $action]);
        // \array_unshift(self::$routes[$method], $array[$pattern] = [$controller => $action]);
    }
}
