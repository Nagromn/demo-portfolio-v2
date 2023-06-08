<?php

use Routes\Router;
use Routes\RouteConfig;

// Inclusion de l'autoloader de Composer
require 'vendor/autoload.php';

// Création d'une instance du routeur
$router = new Router();

try {
    // Récupération des routes à partir de la configuration
    $routes = RouteConfig::getRoutes();

    // Ajout de chaque route au routeur
    foreach ($routes as $route) {
        $router->addRoute($route);
    }
} catch (ReflectionException $e) {
    // Gérer l'exception ici en cas d'erreur lors de la récupération des routes
    echo 'La page n\'a pas été trouvée' . $e->getMessage();
}

// Dispatch de la requête courante vers la route correspondante
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);