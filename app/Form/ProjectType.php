<?php

namespace App\Form;

use App\Model\Project;
use App\Model\ProjectCategory;
use App\Model\Upload;
use DateTime;
use Exception;

class ProjectType
{
    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Récupérer les données du formulaire
                $files = $_FILES['files'] ?? [];
                $projectName = $_POST['projectName'] ?? '';
                $categories = $_POST['category'] ?? [];
                $content = $_POST['content'] ?? '';

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
                    $project->addCategory($categoryId);
                }

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