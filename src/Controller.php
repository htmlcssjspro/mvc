<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Interfaces\iController;
use Militer\mvcCore\Interfaces\iView;
use Militer\mvcCore\Interfaces\iUser;

abstract class Controller implements iController
{
    public $model;
    public $user;
    // public $view;
    private $view;


    public function __construct()
    {
        $this->view  = Container::get(iView::class);
        $this->user  = Container::get(iUser::class);
    }


    public function render()
    {
        $this->view->render($this->model);
    }

    abstract public function index();
}
