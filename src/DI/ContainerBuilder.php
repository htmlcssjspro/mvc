<?php

namespace Militer\mvcCore\DI;

class ContainerBuilder
{
    protected $definitions = [];
    // public $dependencies = [];

    protected function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    public function addDefinitions(array $definitions)
    {
        $this->definitions = $definitions;
        // foreach ($definitions as $name => $definition) {
        //     $this->definitions[$name] = $definition;
        // }
    }

    public function build()
    {
        $container = new Container();
        $container->setDefinitions($this->definitions);
        return $container;
    }
}
