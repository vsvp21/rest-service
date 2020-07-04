<?php

namespace Core\Routing;

use Core\Support\Collection;
use Core\Contracts\CollectsRoutes;
use Core\Routing\ControllerReflector;

class Router
{
    private $request;
    
    private $routes;
    
    private $method;

    private $uri;
    
    public function __construct(CollectsRoutes &$routesCollection)
    {
        $this->request = new Collection($_SERVER);
        $this->routes = $routesCollection;
    }

    public function dispatch()
    {
        $this->method = $this->dispatchMethod();
        $this->uri = $this->dispatchUri();
        
        $controllerWithAction = $this->dispatchControllerWithAction();

        if($controllerWithAction) {
            return $this->runController($controllerWithAction);
        } else {
            if($methodNotAllowed = $this->checkForBadMethod()) {
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode(['message' => $methodNotAllowed]);
            } else {
                header("HTTP/1.1 404 Not Found");
                echo json_encode(['message' => 'Not Found']);
            }
        }
        die;
    }

    private function dispatchMethod() {
        return $this->request->get('REQUEST_METHOD');
    }

    private function dispatchUri() {
        return $this->request->get('REQUEST_URI');
    }

    private function dispatchControllerWithAction() {
        return $this->routes->getRoute($this->method, $this->uri);
    }

    private function checkForBadMethod() {
        $methods = [];

        foreach($this->routes->getRoutes() as $method => $uris) {
            foreach($uris as $uri => $controller) {
                if($this->uri == $uri) {
                    $methods[] = $method;
                }
            }
        }

        return 'Method ' . $this->method . ' Not Allowed, Supported ' . implode(' ,', $methods);
    }

    private function runController($controllerWithAction) {
        list($controller, $action) = explode('@', $controllerWithAction);
        $controller = "App\\Controllers\\" . $controller;
        
        $reflector = new ControllerReflector($controller, $action);
        return (new $controller)->$action(...$reflector->getDependencies());
    }
}