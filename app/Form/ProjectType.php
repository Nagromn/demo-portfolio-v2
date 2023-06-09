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
     * @var string $successMessage Le message de succès
     * @var array $errors Les erreurs
     */
    private string $successMessage = '';
    private array $errors = [];

    /**
     * Gère la requête du formulaire de projet.
     * @param Session $session
     * @param Project $project
     * @param ProjectCategory $projectCategory
     * @param Upload $upload
     * @return array
     */
    public function handleRequest(
        Session $session,
        Project $project,
        ProjectCategory $projectCategory,
        Upload $upload,
    ): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $files = $_FILES['files'] ?? []; // Récupérer les fichiers
            try {
                $project->setProjectName($_POST['projectName'] ?? ''); // Ajouter le nom du projet
                $project->setContent($_POST['content'] ?? ''); // Ajouter le contenu du projet
                $project->setCreatedAt(new DateTime()); // Ajouter la date de création du projet
                $project->setUserId($session->getUser()['id']); // Ajouter l'ID de l'utilisateur connecté
                $project->insert(); // Insérer le projet dans la base de données

                $projectId = $project->getLastInsertedId(); // Récupérer l'ID du projet nouvellement inséré

                $projectCategory->setProjectId($projectId); // Ajouter l'ID du projet
                $projectCategory->setCategoryId($_POST['category'] ?? ''); // Ajouter l'ID de la catégorie
                $projectCategory->insert(); // Insérer les relations entre le projet et les catégories

                $upload->setFiles($files); // Ajouter les fichiers
                $upload->setProjectId($projectId); // Ajouter l'ID du projet
                $upload->setUserId($session->getUser()['id'] ?? null); // Ajouter l'ID de l'utilisateur connecté
                $upload->insert(); // Insérer les fichiers dans la base de données

                $this->successMessage = 'Le projet a été créé avec succès.'; // Message de succès

            } catch (Exception $e) {
                // Message d'erreur si une erreur s'est produite lors de la création du projet
                $this->addError('Une erreur s\'est produite : ' . $e->getMessage());
            }
        }
        return [
            'success' => $this->getSuccess(), // Le message de succès
            'errors' => $this->getErrors(), // Les erreurs
        ];
    }

    /**
     * Envoie le message de succès
     * @return string
     */
    public function getSuccess(): string
    {
        return $this->successMessage;
    }

    /**
     * Envoie les messages d'erreur
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Ajoute un message d'erreur
     * @param string $message
     */
    private function addError(string $message): void
    {
        $this->errors[] = $message;
    }
}