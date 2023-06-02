<?php

namespace Routes;

use App\Controller\HomeController;
use App\Controller\AdminController;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class RouteConfig
{

    /**
     * @throws ReflectionException
     */
    public static function getRoutes(): array
    {
        $routes = [];

        $controllers = [
            HomeController::class => [
                'home' => '/',
                'professional' => '/professional',
                'project' => '/project',
                'skill' => '/skill',
            ],
            AdminController::class => [
                'dashboard' => '/admin-dashboard',
            ],
        ];

        foreach ($controllers as $controller => $routeMappings) {
            $reflection = new ReflectionClass($controller);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->class === $controller && $method->name !== '__construct') {
                    $action = $method->name;
                    $routePath = $routeMappings[$action] ?? '/notfound';
                    $routes[] = new Route('GET', $routePath, $controller, $action);
                }
            }
        }

        return $routes;
    }
}
