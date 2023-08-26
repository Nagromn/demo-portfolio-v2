<?php

namespace App\Controllers;

use App\Models\Session;
use App\Models\User;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Utils\Renderer;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController
{
    /**
     * Méthode pour se connecter et traiter les données du formulaire de connexion.
     * @return void
     * @throws Exception
     */
    public function login(): void
    {
        try {
            // Si la méthode de requête est POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? ''; // Récupérer l'adresse e-mail
                $password = $_POST['password'] ?? ''; // Récupérer le mot de passe

                $user = new User(); // Instancier la classe User
                $existingUser = $user->findByEmail($email); // Vérifier si l'utilisateur existe

                if (!$existingUser) {
                    echo 'L\'utilisateur n\'existe pas.'; // L'utilisateur n'existe pas
                } else {
                    // Récupérer les données de l'utilisateur
                    $user->setEmail($existingUser['email']); // L'adresse e-mail
                    $user->setPassword($existingUser['password']); // Le mot de passe

                    // Vérifier si le mot de passe est correct
                    if (password_verify($password, $user->getPassword())) {
                        new Session(); // Instancier la classe Session
                        Session::init($existingUser); // Initialiser la session

                        // Vérifier si l'utilisateur est un administrateur
                        if (Session::checkAuthorization()) {
                            header('Location: /admin-dashboard');
                            exit;
                        } else {
                            echo 'Vous n\'avez pas les droits d\'administrateur pour vous connecter.';
                        }
                    } else {
                        // Les informations d'identification sont incorrectes
                        echo 'Les informations d\'identification sont incorrectes.';
                    }
                }
            }
        } catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
        // Afficher le formulaire de connexion par défaut
        Renderer::render('app/Views/templates/forms/login.php');
    }

    /**
     * Méthode pour se déconnecter.
     * @return void
     */
    #[NoReturn] public function logout(): void
    {
        new Session; // Instancier la classe Session

        // Vérifier si l'utilisateur est connecté avant de détruire la session
        if (Session::isLoggedIn()) {
            Session::destroy(); // Détruire la session
            echo 'Vous avez été déconnecté avec succès.'; // Message de succès
        } else {
            echo 'Aucune session en cours.'; // Message indiquant qu'aucune session n'est active
        }
    }
}