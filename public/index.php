<?php

use Routes\Router;
use Routes\RouteConfig;

require 'vendor/autoload.php';

$router = new Router();

try {
    $routes = RouteConfig::getRoutes();
    foreach ($routes as $route) {
        $router->addRoute($route);
    }
} catch (ReflectionException $e) {
    // Gérer l'exception ici
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);