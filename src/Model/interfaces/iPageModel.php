<?php

namespace Militer\mvcCore\Model\interfaces;

interface iPageModel
{
    public function init(string $requestUri): void;
}
