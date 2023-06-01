<?php

use Routes\Router;
use Routes\RouteConfig;

require 'vendor/autoload.php';

$router = new Router();
$routes = RouteConfig::getRoutes();

foreach ($routes as $route) {
    $router->addRoute($route);
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);