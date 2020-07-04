<?php

namespace Core\Contracts;

interface CollectsRoutes {
    function addRoute($method, $uri, $action);
    function getRoutes();
    function getRoute($method, $uri);
}