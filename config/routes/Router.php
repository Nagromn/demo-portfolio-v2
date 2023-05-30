<?php

namespace App\Config\Routes;

require_once __DIR__ . '/Route.php';
require_once __DIR__ . '/RouterException.php';

class Router
{
    private string $url;
    private array $routes;

    public function __construct($url)
    {
        $this->url = $url;
        $this->routes = array();
    }

    public function get($path, $callable): void
    {
        $route = new Route($path, $callable);
        $this->routes['GET'][] = $route;
    }

    public function post($path, $callable): void
    {
        $route = new Route($path, $callable);
        $this->routes['POST'][] = $route;
    }

    /**
     * @throws RouterException
     */
    public function run(): void
    {
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                $route->call();
            }
        }

        throw new RouterException('No matching routes');
    }
}
