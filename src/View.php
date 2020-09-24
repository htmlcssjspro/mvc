<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\Interfaces\iModel;
use Militer\mvcCore\Interfaces\iView;

class View implements iView
{
    public $response;

    public function __construct(iResponse $response)
    {
        $this->response = $response;
    }

    public function render(iModel $model)
    {
        $this->response->code    = $model->code;
        $this->response->headers = $model->headers;
        \ob_start();
        require $model->layout;
        $this->response->body = \ob_get_clean();
        $this->response->send();
    }
}