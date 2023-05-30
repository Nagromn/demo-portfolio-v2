<?php

namespace App\Controller;

use App\Model\User;
use Exception;

require_once __DIR__.'/../../src/Model/User.php';
require_once __DIR__.'/../../utils/render.php';

class UserController
{
    public function index(): void
    {
        $user = new User();
        $users = $user->findAll();

        render('../templates/pages/home.php', ['users' => $users]);
    }

    public function processForm(): void
    {
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Créer un nouvel utilisateur et insérer les données
            $user = new User();
            try {
                $user->insert([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'createdAt' => date('d-m-Y-H:i:s'), // '01-01-2021-00:00:00
                    'isAdmin' => 0
                ]);
            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
                return;
            }

            // Rediriger vers la page d'accueil après l'insertion
            header('Location: index.php');
            exit();
        }
    }
}