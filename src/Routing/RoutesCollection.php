<?php

namespace Core\Routing;

use Core\Contracts\CollectsRoutes;

class RoutesCollection implements CollectsRoutes {

    private $routes = [];
    
    public function addRoute($method, $uri, $action) {
        $this->routes[$method][$uri] = $action;
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function getRoute($method, $uri) {
        if(isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }

        return null;
    }
}