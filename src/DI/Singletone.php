<?php

namespace Militer\mvcCore\DI;

use Militer\mvcCore\DI\Interfaces\iSingletone;

class Singletone implements iSingletone
{
    private $singletone;


    private function __construct($singletone)
    {
        $this->singletone = $singletone;
    }


    public function get($singletone)
    {
        return $this->singletone ?? new self($singletone);
    }
}