<?php

namespace Routes;

use App\Controller\HomeController;

class RouteConfig
{
    public static function getRoutes(): array
    {
        $routes = [];

        // Ajouter les routes au tableau $routes
        $routes[] = new Route('GET', '/', HomeController::class, 'index');
        // ...

        return $routes;
    }
}