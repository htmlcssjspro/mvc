<?php

namespace Militer\mvcCore\DI;

use Militer\mvcCore\Exception\ContainerException;

class Container implements iContainer
{

    private static $container = [];
    private static $singletones = [];


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

    public static function get($name, $parameters = [])
    {
        // if we don't have it, just register it
        if (!isset(self::$container[$name])) {
            self::set($name);
        }

        return self::resolve(self::$container[$name], $parameters);
    }

    private static function resolve($concrete, $parameters)
    {
        if ($concrete instanceof \Closure) {
            return $concrete(new self, $parameters);
        }

        if (isset(self::$singletones[$concrete])) {
            return self::$singletones[$concrete];
        }

        $reflector = new \ReflectionClass($concrete);
        if ($reflector->isInterface()) {
            throw new ContainerException("Interface {$concrete} has no implementation");
        }
        // check if class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new ContainerException("Class {$concrete} is not instantiable");
        }
        // get class constructor
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            // get new instance from class
            if ($reflector->hasProperty('isSingletone')) {
                self::$singletones[$concrete] = $reflector->newInstance();
                return self::$singletones[$concrete];
            }
            return $reflector->newInstance();
        }
        // get constructor params
        $parameters   = $constructor->getParameters();
        $dependencies = self::getDependencies($parameters);
        // get new instance with dependencies resolved

        if ($reflector->hasProperty('isSingletone')) {
            self::$singletones[$concrete] = $reflector->newInstanceArgs($dependencies);
            return self::$singletones[$concrete];
        }

        return $reflector->newInstanceArgs($dependencies);
    }

    private static function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            // get the type hinted class
            $dependency = $parameter->getClass();
            if ($dependency === NULL) {
                // check if default value for a parameter is available
                if ($parameter->isDefaultValueAvailable()) {
                    // get default value of parameter
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Can not resolve class dependency '{$parameter->name}'");
                }
            } else {
                // get dependency resolved
                $dependencies[] = self::get($dependency->name);
            }
        }
        return $dependencies;
    }

}
