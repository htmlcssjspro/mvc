<?php

namespace Militer\mvcCore;

use Militer\mvcCore\Interfaces\iRouter;

class Router implements iRouter
{
    private static array $routesGET = [];
    private static array $routesPOST = [];

    private static array $routes = [
        '^/?$' => [
            'controller' => 'Home',
            'action'     => 'index'
        ],
        '^/api/(?<controller>[a-z_-]+)/?(?<action>[a-z_-]+)?$',
        '^/(?<controller>[a-z_-]+)/?(?<action>[a-z_-]+)?/?(?<query>[a-z_-]+)?$',
    ];
    private static array $route = [];

    private string $method;
    private array $request;


    public function __construct()
    {
        \newClassInstance(__CLASS__); // Удалить в production.  // Для разработки самого фреймворка

        $this->method = $_SERVER['REQUEST_METHOD'];
        \pr($this->method); // Удалить в production.  // Для разработки самого фреймворка

        $this->request = \parse_url(\urldecode($_SERVER['REQUEST_URI']));
        \pr($this->request); // Удалить в production.  // Для разработки самого фреймворка

        // self::dispatch($this->method, $this->request['path']);
        self::dispatch($this->request['path']);
    }

    public static function add(string $regexp, ?array $route = [])
    {
        \array_unshift(self::$routes, $array[$regexp] = $route);
    }

    // public static function GET(string $regexp, ?array $route = [])
    // {
    //     \array_unshift(self::$routes, $array[$regexp] = $route);
    // }

    // public static function POST(string $regexp, ?array $route = [])
    // {
    //     self::$routesPOST[$regexp] = $route;
    // }

    // public static function API(string $regexp, ?array $route = [])
    // {
    //     self::$routesPOST[$regexp] = $route;
    // }



    private static function dispatch(string $request)
    {
        if (self::matchRoute($request)) {
            $controller = self::$route['controller'];
            if (\class_exists($controller)) {
                echo '<br>OK';
            } else {
                echo "<br>Контроллер <strong>$controller</strong> не найден";
            }
        } else {
            echo '<br>404';
        }
    }

    // private static function matchRoute(string $request, string $method)
    private static function matchRoute(string $request)
    {
        foreach (self::$routes as $pattern => $route) {
            if (\preg_match("~$pattern~i", $request, $matches)) {
                // \Militer\devCore\Debug::print($matches); // Удалить в production.  // Для разработки самого фреймворка
                foreach ($matches as $key => $value) {
                    if (\is_string($key)) {
                        $route[$key] = $value;
                    }
                }
                self::$route = $route;
                \pr(self::$route); // Удалить в production.  // Для разработки самого фреймворка
                return true;
            }
        }
    }






    public static function getRoutesGET()
    {
        \pr(self::$routesGET); // Удалить в production.  // Для разработки самого фреймворка
    }

    public static function getRoute()
    {
        \pr(self::$route); // Удалить в production.  // Для разработки самого фреймворка
    }
}
