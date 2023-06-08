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
    public function registrationForm(array $params, User $user): void
    {
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer les données du formulaire
            $email = $params['email'];
            $password = $params['password'];
            $isAdmin = $params['isAdmin'] ?? 0;

            // Vérifier si l'utilisateur existe déjà dans la base de données
            $existingUser = $user->findByEmail($email);

            // Si l'utilisateur existe
            if ($existingUser) {
                throw new Exception("Un utilisateur avec cette adresse e-mail existe déjà.");
            }

            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer les données de l'utilisateur dans la base de données
            try {
                $params = [
                    'email' => $email,
                    'password' => $hashedPassword,
                    'isAdmin' => $isAdmin,
                ];

                // Insérer les données de l'utilisateur dans la base de données
                $user->insert($params);

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