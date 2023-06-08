<?php

namespace App\Form;

use App\Model\Project;
use App\Model\ProjectCategory;
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
     */
    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Récupérer les données du formulaire
                $files = $_FILES['files'] ?? []; // Fichiers téléchargés
                $projectName = $_POST['projectName'] ?? ''; // Nom du projet
                $categories = $_POST['category'] ?? []; // Catégories sélectionnées
                $content = $_POST['content'] ?? ''; // Contenu du projet

                // Insertion du projet
                $params = [
                    'projectName' => $projectName,
                    'content' => $content,
                    'createdAt' => (new DateTime())->format('Y-m-d H:i:s'),
                ];

                $project = new Project();
                $project->setProjectName($projectName);
                $project->setContent($content);
                $project->setCreatedAt(new DateTime());
                foreach ($categories as $categoryId) {
                    $project->addCategory($categoryId); // Ajouter les catégories au projet
                }

                // Insérer le projet dans la base de données
                $project->insert($params);

                // Récupérer l'ID du projet nouvellement inséré
                $projectId = $project->getLastInsertedId();

                // Insérer les relations entre le projet et les catégories
                $projectCategory = new ProjectCategory();
                $projectCategory->setProjectId($projectId);
                $projectCategory->setCategoryId($categories);
                $projectCategory->insert($params);

                // Manipulation des fichiers téléchargés via Upload
                $upload = new Upload();
                $upload->insert($files);

            } catch (Exception $e) {
                // Gérer l'erreur appropriée
                echo 'Une erreur s\'est produite : ' . $e->getMessage();
            }
        }
    }
}