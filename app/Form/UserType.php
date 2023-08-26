<?php

namespace App\Form;

use App\Models\User;
use Exception;

/**
 * Class UserType
 * @package App\Form
 */
class UserType
{
    /**
     * Permet de gérer la soumission du formulaire d'inscription.
     * @param User $user
     * @throws Exception
     */
    public function registrationForm(User $user): void
    {
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $username = $_POST['username']; // Le pseudo de l'utilisateur
            $email = $_POST['email']; // L'adresse e-mail de l'utilisateur
            $password = $_POST['password']; // Le mot de passe de l'utilisateur
            $isAdmin = $_POST['isAdmin'] ?? 0; // Le rôle de l'utilisateur (0 = utilisateur, 1 = administrateur)

            $existingUser = $user->findByEmail($email); // Vérifier si l'utilisateur existe dans la base de données

            // Si l'utilisateur existe déjà
            if ($existingUser) {
                throw new Exception("Un utilisateur avec cette adresse e-mail existe déjà.");
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hacher le mot de passe

            try {
                $user->setUsername($username); // Définir le pseudo de l'utilisateur
                $user->setEmail($email); // Définir l'adresse e-mail de l'utilisateur
                $user->setPassword($hashedPassword); // Définir le mot de passe de l'utilisateur
                $user->setIsAdmin($isAdmin); // Définir le rôle de l'utilisateur
                $user->insert(); // Insérer l'utilisateur dans la base de données

                header('Location: /admin-dashboard'); // Rediriger vers le tableau de bord de l'administrateur
                echo 'L\'utilisateur a été créé avec succès.'; // Message de succès
                exit;
            } catch (Exception $e) {
                throw new Exception("Une erreur s'est produite lors de la création de l'utilisateur.");
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
            $id = $_POST['id']; // L'identifiant de l'utilisateur
            $email = $_POST['email']; // L'adresse e-mail de l'utilisateur
            $password = $_POST['password']; // Le nouveau mot de passe de l'utilisateur

            $existingUser = $user->findById($id); // Vérifier si l'utilisateur existe dans la base de données

            // Si l'utilisateur existe
            if ($existingUser) {
                // Hacher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hacher le mot de passe

                try {
                    // Définir les paramètres de la requête
                    $params = [
                        'id' => $id, // L'identifiant de l'utilisateur
                        'email' => $email, // L'adresse e-mail de l'utilisateur
                        'password' => $hashedPassword, // Le mot de passe de l'utilisateur haché
                    ];

                    $user->update($params); // Mettre à jour les données de l'utilisateur dans la base de données
                    header('Location: /admin-dashboard'); // Rediriger vers la page d'administration
                    echo 'Les informations de l\'utilisateur ont été mises à jour avec succès.'; // Message de succès
                    exit;
                } catch (Exception $e) {
                    throw new Exception("Une erreur s'est produite lors de la mise à jour des informations de l'utilisateur: " . $e->getMessage());
                }
            } else {
                throw new Exception("L'utilisateur n'existe pas.");
            }
        }
    }
}