<?php

namespace Militer\mvcCore\Http\Response;

interface iResponse
{
    public function send();
    public function sendResponse();
    public function notFound();
}
