<?php

namespace Militer\mvcCore\Http\Request;

class Request implements iRequest
{

    public function getMethod(): String
    {
        return \strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getUri(): String
    {
        return \urldecode($_SERVER['REQUEST_URI']);
    }

    public function getRequest(): array
    {
        return \parse_url(\urldecode($_SERVER['REQUEST_URI']));
    }

    public function getRequestUri(): String
    {
        $request = $this->getRequest();
        return $request['path'];
    }

    public function getGET(): array
    {
        return $_GET;
    }

    public function getPOST(): array
    {
        return $_POST ?? json_decode(file_get_contents('php://input'), true);
    }

    public function getFILES(): array
    {
        return $_FILES;
    }
}