<?php

namespace Militer\mvcCore\DI;

use Militer\mvcCore\Exception\ContainerException;

class Container implements iContainer
{

    private static $container = [];


    private function __construct()
    {
    }


    public static function set($abstract, $concrete = NULL)
    {
        if ($concrete === NULL) {
            $concrete = $abstract;
        }
        self::$container[$abstract] = $concrete;
    }

    public static function sets(array $definitions)
    {
        foreach ($definitions as $abstract => $concrete) {
            self::$container[$abstract] = $concrete;
        }
    }

    public static function get($name, $parameter = NULL)
    {
        !isset(self::$container[$name]) && self::set($name);
        $concrete = self::$container[$name];
        if ($concrete instanceof \Closure) {
            return $parameter ? $concrete($parameter) : $concrete();
        }
        return self::resolve($concrete);
    }

    private static function resolve($concrete)
    {
        $reflector = new \ReflectionClass($concrete);
        if ($reflector->isInterface()) {
            throw new ContainerException("Interface <srong>{$concrete}</srong> has no implementation");
        }
        if (!$reflector->isInstantiable()) {
            // return $reflector->getName();
            throw new ContainerException("Class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $parameters   = $constructor->getParameters();
        $dependencies = self::getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    private static function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType()->getName();
            if ($dependency === NULL) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new ContainerException("Cannot resolve class dependency '{$parameter->name}'");
                }
            } else {
                $dependencies[] = self::get($dependency);
            }
        }
        return $dependencies;
    }
}
