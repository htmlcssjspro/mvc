<?php

namespace Militer\mvcCore;

use Militer\mvcCore\Interfaces\iCsrf;

abstract class Csrf implements iCsrf
{
    public function __construct()
    {
    }
}
