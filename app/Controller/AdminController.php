<?php

namespace App\Controller;

use App\Form\ProjectType;
use Utils\Renderer;

class AdminController
{
    public function dashboard(): void
    {
        Renderer::render('app/View/templates/pages/admin/dashboard.php');
    }

    public function projectForm(): void
    {
        $form = new ProjectType();
        $form->handleRequest();

        Renderer::render('app/View/templates/forms/projectForm.php', [
            'form' => $form,
        ]);
    }
}