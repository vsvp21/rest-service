<?php

namespace Core\Routing;

use Core\Http\Response;
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
            $response = new Response();
            
            $methodNotAllowed = $this->checkForBadMethod();
            if($methodNotAllowed) {
                
                $response->setStatusCode(405);
                $response->setContent(json_encode(['message' => $methodNotAllowed]));

                return $response->send();
            } else {
                $response->setStatusCode(404);
                $response->setContent(json_encode(['message' => 'Not Found']));

                return $response->send();
            }
        }
        die;
    }

    private function dispatchMethod() {
        return $this->request->get('REQUEST_METHOD');
    }

    private function dispatchUri() {
        return $this->request->get('PATH_INFO');
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

        if($methods != false) {
            return 'Method ' . $this->method . ' Not Allowed, Supported ' . implode(' ,', $methods);
        }

        return false;
    }

    private function runController($controllerWithAction) {
        list($controller, $action) = explode('@', $controllerWithAction);
        $controller = "App\\Controllers\\" . $controller;
        
        $reflector = new ControllerReflector($controller, $action);
        return (new $controller)->$action(...$reflector->getDependencies());
    }
}