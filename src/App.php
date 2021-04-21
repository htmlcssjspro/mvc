<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Router\iRouter;

class App
{
    public static function start()
    {
        $Router = Container::get(iRouter::class);
    }
}
