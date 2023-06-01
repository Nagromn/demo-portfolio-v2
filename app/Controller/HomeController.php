<?php

namespace App\Controller;
use Utils\Renderer;

class HomeController
{
    public function index(): void
    {
        Renderer::render('app/View/templates/pages/home.php');
    }
}