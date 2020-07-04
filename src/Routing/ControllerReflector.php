<?php

namespace Core\Routing;

use ReflectionMethod;

class ControllerReflector
{
    private $method;

    public function __construct($controller, $method)
    {
        $this->method = new ReflectionMethod(
            $controller, $method
        );
    }

    private function parameters() {
        return array_filter($this->method->getParameters(), function($parameter) {
            if($parameter->getClass()) {
                return $parameter;
            }
        });
    }

    public function getDependencies() {
        return array_map(function($parameter){ 
            $namespace = $parameter->getType()->getName();
            return new $namespace;
        }, $this->parameters());
    }
}