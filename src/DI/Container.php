<?php

namespace Militer\mvcCore\DI;

use Militer\mvcCore\Exception\ContainerException;
use Militer\mvcCore\Exception\NotFoundException;
use Militer\mvcCore\Interfaces\iContainer;

class Container implements iContainer
{
    protected array $definitions = [];
    protected array $container = [];

    public function get(string $name)
    {
        return $this->container[$name] ?? $this->getDefinition($name);
    }

    protected function getDefinition(string $name)
    {
        if (!$d = $this->definitions[$name]) {
            throw new NotFoundException("В Контейнере не найдено определение зависимости '$name'");
        }
        if (!$this->container[$name] = \is_callable($d) ? $d($this) : null) {
            throw new ContainerException("Ошибка определения зависимости '$name'. Проверьте файл 'dependencies.php'");
        }
        return $this->container[$name];
    }

    // protected function resolveDefinition(callable $definition, string $name)
    // {
    //     $this->container[$name] = $definition();
    // }

    public function setDefinitions(array $definitions)
    {
        $this->definitions = $definitions;
        // foreach ($definitions as $name => $definition) {
        //     $this->definitions[$name] = $definition;
        // }
    }

    // public function show()
    // {
    //     return [
    //         $this->definitions,
    //         $this->container,
    //     ];
    // }
}
