<?php

namespace App\Controller;
use Utils\Renderer;

class HomeController
{
    public function home(): void
    {
        Renderer::render('app/View/templates/pages/home.php');
    }

    public function professional(): void
    {
        Renderer::render('app/View/templates/pages/professional.php');
    }

    public function project(): void
    {
        Renderer::render('app/View/templates/pages/project.php');
    }

    public function skill(): void
    {
        Renderer::render('app/View/templates/pages/skill.php');
    }
}