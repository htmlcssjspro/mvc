<?php

namespace Militer\mvcCore;

class App
{
    protected $instances = [];

    public function __construct()
    {
        \Militer\devCore\Debug::newClassInstance(__CLASS__); // Удалить в production.  // Для разработки самого фреймворка
    }

    public function getInstance($name)
    {
        return static::$instances[$name];
    }

    public function setInstance($name, $value)
    {
        static::$instances[$name] = $value;
    }
}
