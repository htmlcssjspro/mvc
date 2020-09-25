<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Interfaces\iController;
use Militer\mvcCore\Interfaces\iCsrf;
use Militer\mvcCore\Interfaces\iView;
use Militer\mvcCore\Interfaces\iUser;

abstract class Controller implements iController
{
    public $User;
    public $Csrf;
    public $Model;

    private $View;


    public function __construct()
    {
        $this->User = Container::get(iUser::class);
        $this->Csrf = Container::get(iCsrf::class);
        $this->View = Container::get(iView::class);
    }


    public function render()
    {
        $this->Model->getPageData($this->pageTextId);
        $this->View->render($this->Model);
    }

    abstract public function index();
}
