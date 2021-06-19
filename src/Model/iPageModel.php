<?php

namespace Militer\mvcCore\Model;

interface iPageModel
{
    public function init(string $requestUri): void;
}
