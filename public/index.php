<?php

require __DIR__ . '/../config/Autoloader.php';
require __DIR__ . '/../config/routes/Router.php';

use App\Config\Autoloader;
use App\Config\Routes\Router;
use App\Config\Routes\RouterException;

Autoloader::register();

$url = $_SERVER['REQUEST_URI'];
$router = new Router($url);

//Test
$router->get('home', function () {
    echo 'Home';});
$router->get('contact', function () {
    echo 'Contact';});

try {
    $router->run();
} catch (RouterException $e) {
}