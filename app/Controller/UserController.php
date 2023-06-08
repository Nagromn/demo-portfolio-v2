<?php

namespace App\Controller;


use App\Model\Session;
use App\Model\User;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Utils\Renderer;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController
{
    /**
     * Méthode pour se connecter et traiter les données du formulaire de connexion.
     * @throws Exception
     * @return void
     */
    public function login(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                // Vérifier les informations d'identification de l'utilisateur
                $user = new User();
                $existingUser = $user->findByEmail($email);

                if(!$existingUser) {
                    // L'utilisateur n'existe pas
                    throw new Exception("Aucun utilisateur avec cette adresse e-mail n'a été trouvé.");
                } else {
                    // Récupérer les données de l'utilisateur
                    $user->setEmail($existingUser['email']);
                    $user->setPassword($existingUser['password']);

                    // Vérifier si le mot de passe est correct
                    if (password_verify($password, $user->getPassword())) {
                        // Démarrer une nouvelle session
                        new Session();
                        Session::init($existingUser);

                        // Les informations d'identification sont correctes, connectez l'utilisateur
                        $successMessage = "Vous êtes maintenant connecté.";
                        Renderer::render('app/View/templates/pages/home.php', ['success' => $successMessage]);
                    } else {
                        // Les informations d'identification sont incorrectes
                        $errorMessage = "Identifiants invalides. Veuillez réessayer.";
                        Renderer::render('app/View/templates/forms/login.php', ['error' => $errorMessage]);
                    }

                    return;
                }
            }
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
            die;
        }
        // Afficher le formulaire de connexion
        Renderer::render('app/View/templates/forms/login.php');
    }

    /**
     * Méthode pour se déconnecter.
     * @return void
     * @throws Exception
     */
    #[NoReturn] public function logout(): void
    {
        // Détruire la session active
        new Session;
        Session::destroy();
        $successMessage = "Vous êtes maintenant déconnecté.";
        Renderer::render('app/View/templates/pages/home.php', ['success' => $successMessage]);
    }
}