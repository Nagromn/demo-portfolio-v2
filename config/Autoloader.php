<?php

namespace App\Config;

class Autoloader
{
    static function register(): void
    {
       spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class): void
    {
        $class = str_replace('App', '', $class);
        $class = str_replace('\\', '/', $class);
        if(file_exists('../' . $class . '.php')) {
            require_once '../' . $class . '.php';
        }
    }
}