<?php

namespace Militer\mvcCore\View;

use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\Model\iModel;

class View implements iView
{
    public $Request;
    public $Response;

    public function __construct(iRequest $Request, iResponse $Response)
    {
        $this->Request  = $Request;
        $this->Response = $Response;
    }

    public function render(iModel $Model)
    {
        if (!empty($Model->code)) {
            $this->Response->code = $Model->code;
        }
        if (!empty($Model->headers)) {
            $this->Response->headers = $Model->headers;
        }
        $method = $this->Request->getMethod();
        \ob_start();
        if($method === 'get'){
            require $Model->layout;
            $this->Response->body = \ob_get_clean();
            $this->Response->send();
        } else {
            require $Model->mainContent;
            $response['content'] = \ob_get_clean();
            $response['title'] = $Model->title;
            $response['description'] = $Model->description;
            $this->Response->sendJson($response);
        }
    }

}
