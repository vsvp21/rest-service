<?php

namespace Core\Routing;

use Core\Contracts\CollectsRoutes;

class Route
{
    private $routes;

    public function __construct(CollectsRoutes &$routesCollection)
    {
        $this->routes = $routesCollection;
    }

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        $this->routes->addRoute($method, $uri, $action);
    }
}