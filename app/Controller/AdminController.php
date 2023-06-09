<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Form\ProjectType;
use App\Form\UserType;
use App\Model\Category;
use App\Model\Project;
use App\Model\ProjectCategory;
use App\Model\Session;
use App\Model\Upload;
use App\Model\User;
use Exception;
use Utils\Renderer;

/**
 * Class AdminController
 * @package App\Controller
 */
#[AllowDynamicProperties] class AdminController
{
    /**
     * @param Category $category // La catégorie
     * @param ProjectCategory $projectCategory // La relation entre le projet et la catégorie
     * @param ProjectType $projectType // Le formulaire de projet
     * @param Upload $upload // Le/les fichier(s) uploadé(s)
     * @param User $user // L'utilisateur connecté
     * @param Session $session // La session utilisateur
     * @param Project $project // Le projet
     *
     * @return void
     * @throws Exception
     */
    public function __construct(
        Session $session,
        Project $project,
        Category $category,
        ProjectCategory $projectCategory,
        ProjectType $projectType,
        Upload $upload,
        User $user
    ) {
        $this->session = $session;
        $this->project = $project;
        $this->category = $category;
        $this->projectCategory = $projectCategory;
        $this->projectType = $projectType;
        $this->upload = $upload;
        $this->user = $user;
    }

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
     * @param User $user
     * @return void
     * @throws Exception
     */
    public function registration(
        User $user,
    ): void
    {
        $form = new UserType(); // Crée une instance du formulaire UserType
        $form->registrationForm($user); // Gère la soumission du formulaire

        // Rend le fichier de template registration.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/registration.php', [
            'form' => $form, // Le formulaire UserType
        ]);
    }

    /**
     * Permet l'insertion de nouveau projet par l'administrateur.
     * Récupère la liste des catégories depuis le modèle Category.
     * Gère la soumission du formulaire via ProjectType.
     * @param Session $session
     * @param Project $project
     * @param Category $category
     * @param ProjectCategory $projectCategory
     * @param ProjectType $projectType
     * @param Upload $upload
     * @return void
     */
    public function projectForm(
        Session $session,
        Project $project,
        Category $category,
        ProjectCategory $projectCategory,
        ProjectType $projectType,
        Upload $upload
    ): void
    {
        // Rend le fichier de template projectForm.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/projectForm.php', [
            'form' => $projectType->handleRequest($session, $project, $projectCategory, $upload), // Le formulaire ProjectType
            'categories' => $category->findAll(), // La liste des catégories
            'errors' => $projectType->getErrors(), // Les erreurs du formulaire
            'success' => $projectType->getSuccess(), // Le message de succès
        ]);
    }

    /**
     * Permet la mise à jour d'un utilisateur par l'administrateur.
     * @return void
     * @throws Exception
     */
    public function userUpdate(): void
    {
        $form = new UserType(); // Crée une instance du formulaire UserType
        $user = new User(); // Crée une instance de la classe User

        new Session(); // Crée une instance de la classe Session
        $id = Session::getUser()['id']; // Récupérer l'identifiant de l'utilisateur connecté

        // Vérifier si l'utilisateur est connecté
        if (!Session::isLoggedIn()) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            Renderer::render('app/View/templates/forms/login.php');
            exit;
        }

        try {
            $userData = $user->findById($id); // Rechercher l'utilisateur par son identifiant

            // Vérifier si les informations de l'utilisateur ont été trouvées
            if (!$userData) {
                throw new Exception("Impossible de trouver les informations de l'utilisateur.");
            }

            $form->updateUserForm($user); // Gère la soumission du formulaire de mise à jour de l'utilisateur

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