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

        // Vérifier si l'utilisateur est un administrateur, sinon bloquer l'accès
        if (!Session::checkAuthorization()) {
            echo 'Accès non autorisé.'; // Message d'erreur
            exit; // Arrêter le script
        }
    }

    /**
     * Affiche le tableau de bord de l'administrateur.
     * @return void
     * @throws Exception
     */
    public function dashboard(): void
    {
        $projectData = $this->project->findAll(); // Récupérer tous les projets

        // Parcourir les projets
        foreach ($projectData as &$project) {
            $projectImages = $this->upload->getProjectUploads($project['id']); // Récupérer les images correspondantes
            $project['images'] = $projectImages;
        }

        // Le reste du code pour afficher le tableau de bord de l'administrateur
        Renderer::render('app/View/templates/pages/admin/dashboard.php', [
            'projectData' => $projectData, // Les projets
        ]);
    }

    /**
     * Permet l'insertion d'utilisateur par l'administrateur.
     * @param User $user
     * @param UserType $userType
     * @return void
     * @throws Exception
     */
    public function registration(
        User $user,
        UserType $userType,
    ): void
    {
        $userType->registrationForm($user); // Gère la soumission du formulaire
        // Rend le fichier de template registration.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/registration.php', [
            'form' => $userType, // Le formulaire UserType
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
    public function newProject(
        Session $session,
        Project $project,
        Category $category,
        ProjectCategory $projectCategory,
        ProjectType $projectType,
        Upload $upload,
    ): void
    {
        // Gère la soumission du formulaire
        $projectType = $projectType->newProjectForm(
            $session,
            $project,
            $projectCategory,
            $upload
        );
        // Rend le fichier de template projectForm.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/projectForm.php', [
            'form' => $projectType, // Le formulaire ProjectType
            'categories' => $category->findAll(), // La liste des catégories
        ]);
    }

    /**
     * Permet la mise à jour d'un utilisateur par l'administrateur.
     * @param User $user
     * @param UserType $userType
     * @return void
     */
    public function userUpdate(
        User $user,
        UserType $userType,
    ): void
    {
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

            $userType->updateUserForm($user); // Gère la soumission du formulaire de mise à jour de l'utilisateur

        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n";
            die;
        }

        // Rend le fichier de template userUpdate.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/userUpdate.php', [
            'form' => $userType, // Le formulaire UserType
            'userData' => $userData, // Les informations de l'utilisateur
        ]);
    }

    /**
     * Permet la mise à jour d'un projet par l'administrateur.
     * @param Project $project
     * @param ProjectType $projectType
     * @return void
     */
    public function projectUpdate(
        Project $project,
        ProjectType $projectType
    ): void {
        new Session(); // Crée une instance de la classe Session
        $projectId = $_GET['id']; // Récupérer l'identifiant du projet à mettre à jour

        // Vérifier si l'utilisateur est connecté
        if (!Session::isLoggedIn()) {
            Renderer::render('app/View/templates/forms/login.php'); // Rend le fichier de template login.php
            exit; // Arrêter le script
        }

        try {
            $projectData = $project->findById($projectId); // Rechercher le projet par son identifiant

            // Vérifier si les informations du projet ont été trouvées
            if (!$projectId) {
                throw new Exception("Impossible de trouver les informations du projet."); // Afficher une exception
            }

            // Gérer la soumission du formulaire de mise à jour du projet
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $projectType->projectUpdateForm($project); // Gère la soumission du formulaire
                header('Location: /admin-dashboard'); // Rediriger vers le tableau de bord de l'administrateur
                exit; // Arrêter le script
            }
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n"; // Afficher l'exception
            die; // Arrêter le script
        }

        // Rend le fichier de template updateProject.php en utilisant la classe Renderer
        Renderer::render('app/View/templates/forms/projectUpdate.php', [
            'form' => $projectType, // Le formulaire ProjectType
            'projectData' => $projectData, // Les informations du projet
        ]);
    }
}