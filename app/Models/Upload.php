<?php

namespace App\Models;

use DateTime;
use Exception;
use PDO;

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
            $uploadedPaths = $this->uploadFiles($this->files, $username, $projectName); // Vérifier l'upload des images

            // Vérifier si au moins une image a été chargée avec succès
            if (empty($uploadedPaths)) {
                throw new Exception('Aucune image valide n\'a été chargée.'); // Message d'erreur
            }

            // Parcourir les chemins des images téléchargées
            foreach ($uploadedPaths as $imagePath) {
                $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (fileName, location, createdAt, user_id, project_id) VALUES (:fileName, :location, :createdAt, :user_id, :project_id)'); // Préparer la requête SQL
                $query->bindValue(':fileName', basename($imagePath)); // Associer la valeur du nom du fichier
                $query->bindValue(':location', $this->getLocation() . $username . '/' . $projectName . '/'); // Associer la valeur de l'emplacement du fichier dans le dossier uploads
                $query->bindValue(':createdAt', $this->getCreatedAt()->format('Y-m-d H:i:s')); // Associer la valeur de la date de création
                $query->bindValue(':user_id', $this->getUserId()); // Associer la valeur de l'ID de l'utilisateur
                $query->bindValue(':project_id', $this->getProjectId()); // Associer la valeur de l'ID du projet
                $query->execute(); // Exécuter la requête SQL
            }
        } catch (Exception $e) {
            echo 'Erreur lors de l\'enregistrement des fichers du projet : ' . $e->getMessage();
        }
    }

    /**
     * Met à jour les informations d'un fichier dans la base de données.
     * @param int $fileId ID du fichier à mettre à jour
     * @param string $fileName Nom du fichier
     * @param string $location Emplacement du fichier
     * @return void
     * @throws Exception
     */
    public function update(int $fileId, string $fileName, string $location): void
    {
        try {
            $query = $this->db->prepare('UPDATE ' . $this->table . ' SET fileName = :fileName, location = :location WHERE id = :id'); // Préparer la requête de mise à jour
            $query->bindValue(':fileName', $fileName); // Associer la valeur du nom du fichier
            $query->bindValue(':location', $location); // Associer la valeur de l'emplacement du fichier
            $query->bindValue(':id', $fileId); // Associer la valeur de l'ID
            $query->execute(); // Exécuter la requête SQL
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour du fichier : ' . $e->getMessage()); // Message d'erreur
        }
    }

    /**
     * Supprime un fichier de la base de données.
     * @param int $id ID du fichier à supprimer
     * @return void
     * @throws Exception
     */
    public function delete(int $id): void
    {
        try {
            $query = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id'); // Préparer la requête de suppression
            $query->bindValue(':id', $id); // Associer la valeur de l'ID
            $query->execute(); // Exécuter la requête SQL
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression du fichier : ' . $e->getMessage()); // Message d'erreur
        }
    }

    /**
     * @return array Chemins des images téléchargées
     * @throws Exception
     */
    public function uploadFiles(array $files, string $username, string $projectName): array
    {
        $uploadedPaths = []; // Tableau des images téléchargées

        $projectFolder = $this->location . $username . '/' . $projectName . '/'; // URL locale du dossier pour le projet

        // Vérifier si le dossier existe
        if (!is_dir($projectFolder)) {
            mkdir($projectFolder, 0777, true);
        }

        // Parcourir les fichiers téléchargés
        foreach ($files['tmp_name'] as $index => $tmpName) {
            $fileName = $files['name'][$index]; // Récupérer le nom du fichier

            // Vérifier si le fichier a été téléchargé avec succès
            if ($tmpName === '') {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été téléchargé avec succès.'); // Message d'erreur
            }

            $extension = pathinfo($fileName, PATHINFO_EXTENSION); // Récupérer l'extension du fichier
            $uniqueFileName = uniqid() . '.' . $extension; // Générer un nom de fichier unique
            $uploaded = move_uploaded_file($tmpName, $projectFolder . $uniqueFileName); // Déplacer le fichier téléchargé vers le dossier des téléchargements

            // Vérifier si le fichier a été déplacé avec succès
            if (!$uploaded) {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été déplacé avec succès.'); // Message d'erreur
            }

            $uploadedPaths[] = $this->location . $uniqueFileName; // Ajouter le chemin du fichier dans le tableau
        }
        return $uploadedPaths; // Retourner le tableau des chemins des images téléchargées une fois traitées
    }

    /**
     * Récupère les images correspondant à l'ID du projet.
     * @param int $projectId L'ID du projet
     * @return array Un tableau d'images correspondantes
     * @throws Exception
     */
    public function getProjectUploads(int $projectId): array
    {
        try {
            $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE project_id = :projectId'); // Préparer la requête
            $query->bindParam(':projectId', $projectId, PDO::PARAM_INT); // Associer la valeur de l'ID du projet
            $query->execute(); // Exécuter la requête SQL

            return $query->fetchAll(PDO::FETCH_ASSOC); // Retourner les résultats de la requête
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération des images du projet : ' . $e->getMessage()); // Gérer l'exception
        }
    }

    /**
     * @return array Fichiers
     */
    public function getFiles(): array
    {
        return $this->files;
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
     * @return string Nom du fichier
     */
    private function getFileName(): string
    {
        return $this->fileName;
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
     * @return string Emplacement du fichier
     */
    public function getLocation(): string
    {
        return $this->location;
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
     * @return DateTime Date de création
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
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
     * @return int|null ID de l'utilisateur
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId ID de l'utilisateur
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int|null ID du projet
     */
    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    /**
     * @param int|null $projectId ID du projet
     * @return void
     */
    public function setProjectId(?int $projectId): void
    {
        $this->projectId = $projectId;
    }
}