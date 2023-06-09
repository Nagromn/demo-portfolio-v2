<?php

namespace App\Form;

use App\Model\User;
use Exception;
use Utils\Renderer;

/**
 * Class UserType
 * @package App\Form
 */
class UserType
{
    /**
     * Permet de gérer la soumission du formulaire d'inscription.
     * @param array $params
     * @param User $user
     * @throws Exception
     */
    public function registrationForm(User $user): void
    {
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer les données du formulaire
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $isAdmin = $_POST['isAdmin'] ?? 0;

            $existingUser = $user->findByEmail($email); // Vérifier si l'utilisateur existe dans la base de données

            // Si l'utilisateur existe déjà
            if ($existingUser) {
                throw new Exception("Un utilisateur avec cette adresse e-mail existe déjà.");
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hacher le mot de passe

            // Insérer les données de l'utilisateur dans la base de données
            try {
                $user->setUsername($username); // Définir le pseudo de l'utilisateur
                $user->setEmail($email); // Définir l'adresse e-mail de l'utilisateur
                $user->setPassword($hashedPassword); // Définir le mot de passe de l'utilisateur
                $user->setIsAdmin($isAdmin); // Définir le rôle de l'utilisateur
                $user->insert(); // Insérer l'utilisateur dans la base de données

                // Rediriger vers la page d'administration
                Renderer::render('app/View/templates/pages/admin/dashboard.php', [
                    'success' => 'L\'utilisateur a bien été créé.'
                ]);
            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
                die;
            }
        }
    }

    /**
     * Permet de mettre à jour les données d'un utilisateur.
     * @param User $user
     * @return void
     * @throws Exception
     */
    public function updateUserForm(User $user): void
    {
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer les données du formulaire
            $id = $_POST['id'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Vérifier si l'utilisateur existe dans la base de données
            $existingUser = $user->findById($id);

            // Si l'utilisateur existe
            if ($existingUser) {
                // Hacher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Mettre à jour les données de l'utilisateur dans la base de données
                try {
                    // Définir les paramètres de la requête
                    $params = [
                        'id' => $id,
                        'email' => $email,
                        'password' => $hashedPassword,
                    ];

                    // Mettre à jour les données de l'utilisateur dans la base de données
                    $user->update($params);

                    // Rediriger vers la page d'administration
                    Renderer::render('app/View/templates/pages/admin/dashboard.php', [
                        'success' => 'Les informations de l\'utilisateur ont bien été mises à jour.'
                    ]);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            } else {
                throw new Exception("L'utilisateur n'existe pas.");
            }
        }
    }
}