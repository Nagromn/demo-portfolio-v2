<?php

namespace App\Form;

use App\Model\Project;
use App\Model\ProjectCategory;
use App\Model\Session;
use App\Model\Upload;
use DateTime;
use Exception;

/**
 * Class ProjectType
 * @package App\Form
 */
class ProjectType
{
    /**
     * Gère la requête du formulaire de projet.
     * @param Session $session
     * @param Project $project
     * @param ProjectCategory $projectCategory
     * @param Upload $upload
     * @return array
     */
    public function newProjectForm(
        Session $session,
        Project $project,
        ProjectCategory $projectCategory,
        Upload $upload,
    ): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $files = $_FILES['files'] ?? []; // Récupérer les fichiers
            $projectName = $_POST['projectName'] ?? ''; // Récupérer le nom du projet
            $content = $_POST['content'] ?? ''; // Récupérer le contenu du projet
            $createdAt = new DateTime(); // Ajouter une date de création du projet
            $category = $_POST['category'] ?? ''; // Récupérer la catégorie du projet
            $userId = $session->getUser()['id'] ?? null; // Récupérer l'ID de l'utilisateur connecté

            try {
                $project->setProjectName($projectName); // Ajouter le nom du projet
                $project->setContent($content); // Ajouter le contenu du projet
                $project->setCreatedAt($createdAt); // Ajouter la date de création du projet
                $project->setUserId($userId); // Ajouter l'ID de l'utilisateur connecté
                $project->insert(); // Insérer le projet dans la base de données

                $projectId = $project->getLastInsertedId(); // Récupérer l'ID du projet nouvellement inséré

                $projectCategory->setProjectId($projectId); // Ajouter l'ID du projet
                $projectCategory->setCategoryId($category); // Ajouter l'ID de la catégorie
                $projectCategory->insert(); // Insérer les relations entre le projet et les catégories

                $upload->setFiles($files); // Ajouter les fichiers
                $upload->setProjectId($projectId); // Ajouter l'ID du projet
                $upload->setUserId($userId); // Ajouter l'ID de l'utilisateur connecté
                $upload->insert(); // Insérer les fichiers dans la base de données
                echo 'Le projet a été créé avec succès !'; // Message de succès

            } catch (Exception $e) {
                // Message d'erreur si une erreur s'est produite lors de la création du projet
                echo $e->getMessage();
            }
        }
        return []; // Retourne un tableau vide
    }

    /**
     * Gère la requête de mise à jour du formulaire de projet.
     * @param Project $project
     * @return array
     */
    public function projectUpdateForm(
        Project $project,
    ): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Vérifier si la méthode de la requête est POST
            $projectId = $_POST['id'] ?? null; // Récupérer l'ID du projet
            $projectName = $_POST['projectName'] ?? ''; // Récupérer le nom du projet
            $content = $_POST['content'] ?? ''; // Récupérer le contenu du projet

            try {
                // Vérifier si l'ID du projet est valide
                if (!$projectId) {
                    throw new Exception('ID du projet manquant.'); // Message d'erreur
                }

                // Mettre à jour le projet
                $project->setProjectName($projectName); // Ajouter le nom du projet
                $project->setContent($content); // Ajouter le contenu du projet
                $project->update(); // Mettre à jour le projet dans la base de données
                echo 'Le projet a été mis à jour avec succès !'; // Message de succès

            } catch (Exception $e) {
                echo $e->getMessage(); // Message d'erreur
            }
        }
        return []; // Retourne un tableau vide
    }
}