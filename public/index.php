<?php

use App\Config\Autoloader;
use App\src\Controller\UserController;

require '..\config\Autoloader.php';
Autoloader::register();

$controller = new UserController();
$controller->index();
