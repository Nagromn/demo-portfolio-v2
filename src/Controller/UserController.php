<?php

namespace App\src\Controller;

use App\src\Model\User;

require_once __DIR__.'/../../utils/render.php';

class UserController
{
    public function index(): void
    {
        $user = new User();
        $users = $user->findAll();

        render('../templates/pages/home.php', compact('users'));
    }
}
