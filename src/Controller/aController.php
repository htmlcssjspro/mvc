<?php

namespace Militer\mvcCore\Controller;

use Core\Csrf\iCsrf;
use Core\User\iUser;
use Militer\mvcCore\DI\Container;

abstract class aController implements iController
{
    public $Model;

    protected $User;
    protected $Csrf;
    protected $config;


    public function __construct()
    {
        $this->User = Container::get(iUser::class);
        $this->Csrf = Container::get(iCsrf::class);
        $this->config = Container::get('config');
    }


}
