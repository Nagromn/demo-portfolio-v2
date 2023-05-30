<?php

namespace App\Config\Routes;

class Route
{
    private string $path;
    private mixed $callable;
    private array $matches;

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match($url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace('#:(\w+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";

        if(!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function call(): void
    {
        if (is_callable($this->callable)) {
            call_user_func_array($this->callable, $this->matches);
        }
    }
}