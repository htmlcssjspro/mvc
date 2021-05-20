<?php

namespace Militer\mvcCore\Router;

use Admin\Controllers\AdminApiController;
use Admin\Controllers\AdminController;
use Main\Controllers\MainApiController;
use Main\Controllers\MainController;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Response\iResponse;

class Router implements iRouter
{
    private $Request;
    private $Response;
    private $PDO;
    private $config;
    private $dbTables;


    public function __construct(iRequest $Request, iResponse $Response)
    {
        $this->Request  = $Request;
        $this->Response = $Response;
        $this->PDO = Container::get('pdo');
        $this->config = Container::get('config');
        $this->dbTables = Container::get('config', 'dbTables');

        $this->init();
    }


    private function init()
    {
        $method     = $this->Request->getMethod();
        $requestUri = $this->Request->getRequestUri();
        $query      = $this->Request->getQuery();

        $sitemapTable = $this->dbTables['sitemap'];
        $sql = "SELECT `page_id` `controller`, `action` FROM `$sitemapTable` WHERE `page_url`=?";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$requestUri]);
        $page = $pdostmt->fetch();
        !$page && $this->Response->notFound();

        $pageId = $page['page_id'];
        // $controller = $page['controller'];
        // $action     = $page['action'] ?? 'index';


        $admin    = '/admin/';
        $adminApi = '/admin/api/';
        $api      = '/api/';

        if (\mb_eregi("^$admin", $requestUri)) {
            if ($method === 'post' && \mb_eregi("^$adminApi", $requestUri)) {
                $action     = \mb_eregi_replace("^$adminApi", '', $requestUri);
                controller(AdminApiController::class, $action);
            } else {
                $action = \mb_eregi_replace("^$admin", '', $requestUri);
                controller(AdminController::class, 'index', $pageId);
            }
        } elseif ($method === 'post' && \mb_eregi("^$api", $requestUri)) {
            $action = \mb_eregi_replace("^$api", '', $requestUri);
            controller(MainApiController::class, $action);
        } else {
            controller(MainController::class, 'index', $pageId);
        }

        function controller(string $controller, string $action, $parameter = NULL){
            $controller = Container::get($controller);
            $parameter ? $controller->$action($parameter) : $controller->$action();
        }

        // \eco($requestUri, '$requestUri');
        // \eco($query, '$query');
        // \eco($controller, '$controller');
        // \eco($action, '$action');
        // exit;

    }
}
