<?php

namespace Militer\mvcCore\Controller;

use Militer\mvcCore\Csrf\iCsrf;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\User\iUser;

abstract class aController implements iController
{
    protected $User;
    protected $Csrf;
    protected $config;


    public function __construct()
    {
        $this->User   = Container::get(iUser::class);
        $this->Csrf   = Container::get(iCsrf::class);
        $this->config = Container::get('config');
    }


    abstract public function index(array $routerData);
}
