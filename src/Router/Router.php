<?php

namespace Militer\mvcCore\Router;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Response\iResponse;

class Router implements iRouter
{
    private $Request;
    private $Response;
    private $PDO;
    private $config;


    public function __construct(iRequest $Request, iResponse $Response)
    {
        $this->Request  = $Request;
        $this->Response = $Response;
        $this->PDO = Container::get('pdo');
        $this->config = Container::get('config');

        $this->dispatch();
    }


    private function dispatch()
    {
        $requestUri = $this->Request->getRequestUri();
        $sitemap_table = $this->config['dbTables']['sitemap'];
        $sql = "SELECT `controller`, `action` FROM $sitemap_table WHERE `page_url`=? LIMIT 1";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$requestUri]);
        $page = $pdostmt->fetch();

        if (!$page) {
            $this->Response->notFound();
        }

        $controller = $page['controller'];
        $action     = $page['action'] ?? 'index';

        $controller = Container::get("App\Controllers\\$controller");
        $query = $this->Request->getQuery();
        $query ? $controller->$action($query) : $controller->$action();
    }

}
