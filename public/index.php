<?php

use App\Controller\HomeController;

require 'vendor/autoload.php';

$homeController = new HomeController();
$homeController->index();