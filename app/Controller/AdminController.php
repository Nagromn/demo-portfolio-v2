<?php

namespace App\Controller;

use Utils\Renderer;

class AdminController
{
    public function dashboard(): void
    {
        Renderer::render('app/View/templates/pages/admin/dashboard.php');
    }
}