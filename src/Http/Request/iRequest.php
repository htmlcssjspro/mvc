<?php

namespace Militer\mvcCore\Http\Request;

interface iRequest
{
    public function getMethod();
    public function getRequest();
    public function getRequestUri();
    public function getQuery();
}
