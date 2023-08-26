<?php

namespace App\Controllers;
use Utils\Renderer;

/**
 * Contrôleur de la page d'accueil.
 * @package App\Controller
 */
class HomeController
{
    /**
     * Affiche la page d'accueil.
     */
    public function home(): void
    {
        Renderer::render('app/Views/templates/pages/home.php');
    }

    /**
     * Affiche la page expériences professionnelles.
     */
    public function professional(): void
    {
        Renderer::render('app/Views/templates/pages/professional.php');
    }

    /**
     * Affiche la page des projets.
     */
    public function project(): void
    {
        Renderer::render('app/Views/templates/pages/project.php');
    }

    /**
     * Affiche la page des compétences et formations.
     */
    public function skill(): void
    {
        Renderer::render('app/Views/templates/pages/skill.php');
    }
}