<?php

namespace App\Controller;

use App\Form\UserType;
use App\Form\ProjectType;
use App\Model\Category;
use App\Model\Session;
use App\Model\User;
use Exception;
use Utils\Renderer;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     * @return void
     * @throws Exception
     */
    public function dashboard(): void
    {
        // Rend le fichier de template dashboard.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/pages/admin/dashboard.php');
    }

    /**
     * Permet l'insertion d'utilisateur par l'administrateur.
     * @return void
     * @throws Exception
     */
    public function registration(): void
    {
        // Crée une instance de la classe User pour gérer les utilisateurs
        $form = new UserType();

        // Crée une instance de la classe User pour gérer les utilisateurs
        $user = new User();

        $form->registrationForm($_POST, $user);

        // Rend le fichier de template registration.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/registration.php', [
            'form' => $form, // Le formulaire UserType
        ]);
    }

    /**
     * Permet l'insertion de nouveau projet par l'administrateur.
     * Récupère la liste des catégories depuis le modèle Category.
     * Gère la soumission du formulaire via ProjectType.
     * @return void
     * @throws Exception
     */
    public function projectForm(): void
    {
        // Crée une instance de la classe Category pour gérer les catégories de projet
        $category = new Category();

        // Récupère la liste complète des catégories depuis le modèle Category
        $categories = $category->findAll();

        // Crée une instance du formulaire ProjectType
        $form = new ProjectType();

        // Gère la soumission du formulaire
        $form->handleRequest();

        // Rend le fichier de template projectForm.php en utilisant la classe Renderer
        // Les variables 'form' et 'categories' seront disponibles dans le template
        Renderer::render('app/View/templates/forms/projectForm.php', [
            'form' => $form, // Le formulaire ProjectType
            'categories' => $categories, // La liste des catégories
        ]);
    }

    /**
     * Permet la mise à jour d'un utilisateur par l'administrateur.
     * @return void
     * @throws Exception
     */
    public function userUpdate(): void
    {
        // Crée une instance de la classe UserType pour gérer les utilisateurs
        $form = new UserType();
        $user = new User();

        // Crée une instance de la classe Session pour gérer la session utilisateur
        new Session();

        // Vérifier si l'utilisateur est connecté
        if (!Session::isLoggedIn()) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            header('Location: /admin-login');
            exit;
        }

        // Récupérer l'identifiant de l'utilisateur connecté
        $id = $_SESSION['auth']['id'];

        try {
            // Rechercher l'utilisateur par son identifiant
            $userData = $user->findById($id);

            // Vérifier si les informations de l'utilisateur ont été trouvées
            if (!$userData) {
                throw new Exception("Impossible de trouver les informations de l'utilisateur.");
            }

            // Afficher le formulaire de mise à jour avec les informations de l'utilisateur
            $form->updateUserForm($user);

        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
            die;
        }

        // Rend le fichier de template userUpdate.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/userUpdate.php', [
            'form' => $form, // Le formulaire UserType
            'userData' => $userData, // Les informations de l'utilisateur
        ]);
    }
}