<?php

namespace Militer\mvcCore\Controller;

// use Militer\mvcCore\DI\Container;
// use Militer\mvcCore\View\iView;

abstract class aPageController extends aController
{
    // public $pageId;
    // private $View;


    public function __construct()
    {
        parent::__construct();
        // $this->View = Container::get(iView::class);
    }


    // public function render()
    // {
    //     $this->Model->getPageData($this->pageId);
    //     $this->View->render($this->Model);
    // }


}
