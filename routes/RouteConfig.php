<?php

namespace Routes;

use App\Controller\HomeController;
use App\Controller\AdminController;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class RouteConfig
{
    private const ROUTES = [
        HomeController::class => [
            'home' => [
                'path' => '/',
                'method' => 'GET',
            ],
            'professional' => [
                'path' => '/professional',
                'method' => 'GET',
            ],
            'project' => [
                'path' => '/project',
                'method' => 'GET',
            ],
            'skill' => [
                'path' => '/skill',
                'method' => 'GET',
            ],
        ],
        AdminController::class => [
            'dashboard' => [
                'path' => '/admin-dashboard',
                'method' => 'GET',
            ],
            'projectForm' => [
                'path' => '/admin-project-form',
                'method' => ['GET', 'POST'],
            ],
        ],
    ];

    /**
     * @throws ReflectionException
     */
    public static function getRoutes(): array
    {
        $routes = [];

        foreach (self::ROUTES as $controller => $actions) {
            $reflection = new ReflectionClass($controller);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->class === $controller && $method->name !== '__construct') {
                    $action = $method->name;
                    $routeData = $actions[$action] ?? null;

                    if ($routeData !== null) {
                        $routePath = $routeData['path'];
                        $routeMethods = $routeData['method'];
                        $routeMethods = is_array($routeMethods) ? $routeMethods : [$routeMethods];
                        foreach ($routeMethods as $routeMethod) {
                            $routes[] = new Route($routeMethod, $routePath, $controller, $action);
                        }
                    }
                }
            }
        }

        return $routes;
    }
}