<?php

namespace Routes;

class Router
{
    private array $routes = [];

    public function addRoute(Route $route): void
    {
        $method = $route->getMethod();
        $path = $route->getPath();
        $controller = $route->getController();
        $action = $route->getAction();

        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            $this->executeAction($route['controller'], $route['action']);
        } else {
            echo "Erreur 404: Page non trouvée";
        }
    }

    private function executeAction(string $controller, string $action): void
    {
        $controllerInstance = new $controller();
        $controllerInstance->$action();
    }
}
