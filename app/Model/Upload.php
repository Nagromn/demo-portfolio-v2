<?php

namespace App\Model;

use DateTime;
use Exception;

/**
 * Gère les uploads.
 * @package App\Model
 */
class Upload extends Model
{
    /**
     * @var string $table Nom de la table
     * @var array $files Fichiers à traiter
     * @var string $fileName Nom du fichier
     * @var string $location Emplacement du fichier
     * @var DateTime $createdAt Date de création
     * @var int|null $projectId Identifiant du projet
     * @var int|null $userId Identifiant de l'utilisateur
     */
    protected string $table = 'upload';
    protected array $files = [];
    protected string $fileName;
    protected string $location = 'uploads/';
    protected DateTime $createdAt;
    protected ?int $projectId = null;
    protected ?int $userId = null;

    /**
     * Insère les URL des fichiers dans la base de données.
     * @return void
     * @throws Exception
     */
    public function insert(): void
    {
        try {
            $this->setFiles($_FILES['files'] ?? []); // Récupérer les fichiers téléchargés
            $this->setCreatedAt(new DateTime()); // Récupérer la date de création

            new Session; // Récupérer l'utilisateur connecté
            $username = Session::getUser()['username'] ?? null; // Récupérer l'username depuis la session

            $projectName = $_POST['projectName'] ?? ''; // Récupérer le nom du projet depuis $_POST du formulaire
            $uploadedPaths = $this->uploadImages($this->files, $username, $projectName); // Vérifier l'upload des images

            // Vérifier si au moins une image a été chargée avec succès
            if (empty($uploadedPaths)) {
                throw new Exception('Aucune image valide n\'a été chargée.');
            }

            // Parcourir les chemins des images téléchargées
            foreach ($uploadedPaths as $imagePath) {
                // Traiter les données avant de les insérer dans la base de données
                $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (fileName, location, createdAt, user_id, project_id) VALUES (:fileName, :location, :createdAt, :user_id, :project_id)');
                $query->bindValue(':fileName', basename($imagePath));
                $query->bindValue(':location', $this->getLocation() . $username . '/' . $projectName . '/');
                $query->bindValue(':createdAt', $this->getCreatedAt()->format('Y-m-d H:i:s'));
                $query->bindValue(':user_id', $this->getUserId());
                $query->bindValue(':project_id', $this->getProjectId());
                $query->execute(); // Exécuter la requête SQL
            }
        } catch (Exception $e) {
            echo 'Erreur lors de l\'enregistrement des fichers du projet : ' . $e->getMessage();
        }
    }

    /**
     * @return array Chemins des images téléchargées
     * @throws Exception
     */
    public function uploadImages(array $files, string $username, string $projectName): array
    {
        $uploadedPaths = []; // Tableau des images téléchargées

        $projectFolder = $this->location . $username . '/' . $projectName . '/'; // URL locale du dossier pour le projet

        // Vérifier si le dossier existe
        if (!is_dir($projectFolder)) {
            mkdir($projectFolder, 0777, true);
        }

        // Parcourir les fichiers téléchargés
        foreach ($files['tmp_name'] as $index => $tmpName) {
            // Récupérer le nom du fichier
            $fileName = $files['name'][$index];

            // Vérifier si le fichier a été téléchargé avec succès
            if ($tmpName === '') {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été téléchargé avec succès.');
            }

            $extension = pathinfo($fileName, PATHINFO_EXTENSION); // Récupérer l'extension du fichier
            $uniqueFileName = uniqid() . '.' . $extension; // Générer un nom de fichier unique
            $uploaded = move_uploaded_file($tmpName, $projectFolder . $uniqueFileName); // Déplacer le fichier téléchargé vers le dossier des téléchargements

            // Vérifier si le fichier a été déplacé avec succès
            if (!$uploaded) {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été déplacé avec succès.');
            }

            $uploadedPaths[] = $this->location . $uniqueFileName; // Ajouter le chemin du fichier dans le tableau
        }
        return $uploadedPaths; // Retourner le tableau des chemins des images téléchargées une fois traitées
    }

    /**
     * @return string Nom du fichier
     */
    private function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string Emplacement du fichier
     */
    private function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return DateTime Date de création
     */
    private function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return int|null ID de l'utilisateur
     *
     */
    private function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @return int|null ID du projet
     */
    private function getProjectId(): ?int
    {
        return $this->projectId;
    }

    /**
     * @param array $files Fichiers à traiter
     * @return void
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @param string $fileName Nom du fichier
     * @return void
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @param string $location Emplacement du fichier
     * @return void
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @param DateTime $createdAt Date de création
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param int|null $projectId ID du projet
     * @return void
     */
    public function setProjectId(?int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @param int|null $userId ID de l'utilisateur
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }
}