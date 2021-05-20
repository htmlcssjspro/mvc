<?php

namespace Militer\mvcCore\Controller;

abstract class aPageController extends aController
{


    public function __construct()
    {
        parent::__construct();
    }


    public function index(array $routerData)
    {
        \extract($routerData);
        $this->Model->init($requestUri);

        $method === 'get'  && $this->Model->renderPage();
        $method === 'post' && $this->Model->renderMain();
    }

}
