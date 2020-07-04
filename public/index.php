<?php
require __DIR__.'/../vendor/autoload.php';

if(config('mode') == 'dev') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

use Core\Routing\Route;
use Core\Routing\Router;
use Core\Routing\RoutesCollection;

$routerCollection = new RoutesCollection();
$router = new Router($routerCollection);
$route = new Route($routerCollection);

require __DIR__.'/../routes/routes.php';

$router->dispatch();