<?php

namespace Config;

use Dotenv\Dotenv;

class EnvironmentLoader
{
    public static function load(string $basePath): void
    {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->load();
    }
}