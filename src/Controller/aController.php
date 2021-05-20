<?php

namespace Militer\mvcCore\Controller;

use Militer\mvcCore\Csrf\iCsrf;
use Militer\mvcCore\DI\Container;

abstract class aController implements iController
{
    protected iCsrf $Csrf;


    public function __construct()
    {
        $this->Csrf = Container::get(iCsrf::class);
    }


    abstract public function index(array $routerData);
}
