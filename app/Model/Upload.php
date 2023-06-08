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
     * @var string $location Emplacement du fichier
     * @var DateTime $createdAt Date de création
     */
    protected string $table = 'upload';
    protected array $files = [];
    protected string $location = 'uploads/';
    protected DateTime $createdAt;

    /**
     * Constructeur.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Définit les fichiers à traiter.
     *
     * @param array $files Fichiers à traiter
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * Définit l'emplacement du fichier.
     *
     * @param string $location Emplacement du fichier
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * Définit la date de création.
     *
     * @param DateTime $createdAt Date de création
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Insère les données dans la base de données.
     *
     * @throws Exception En cas d'erreur lors de l'exécution de la requête SQL ou de la vérification de l'image
     */
    public function insert(array $params): void
    {
        // Récupérer les paramètres
        $this->setFiles($_FILES['files'] ?? []);
        $this->setCreatedAt(new DateTime());

        // Vérifier l'upload des images
        $uploadedPaths = $this->uploadImages($this->files);

        // Vérifier si au moins une image a été chargée avec succès
        if (empty($uploadedPaths)) {
            throw new Exception('Aucune image valide n\'a été chargée.');
        }

        // Parcourir les chemins des images chargées
        foreach ($uploadedPaths as $imagePath) {
            $fileName = basename($imagePath);

            // Préparer la requête SQL
            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (fileName, location, createdAt) VALUES (:fileName, :location, :createdAt)');

            // Exécuter la requête avec les paramètres
            $query->execute([
                'fileName' => $fileName,
                'location' => $this->location . $fileName,
                'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Télécharge les images dans le dossier des uploads.
     * @throws Exception
     */
    public function uploadImages(array $files): array
    {
        $uploadedPaths = [];

        // Parcourir les fichiers
        foreach ($files['tmp_name'] as $index => $tmpName) {
            // Récupérer le nom du fichier
            $fileName = $files['name'][$index];

            // Vérifier si le fichier a été téléchargé avec succès
            if ($tmpName === '') {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été téléchargé avec succès.');
            }

            // Récupérer l'extension du fichier
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Générer un nom de fichier unique
            $uniqueFileName = uniqid() . '.' . $extension;

            // Déplacer le fichier téléchargé vers le dossier des téléchargements
            $uploaded = move_uploaded_file($tmpName, $this->location . $uniqueFileName);

            // Vérifier si le fichier a été déplacé avec succès
            if (!$uploaded) {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été déplacé avec succès.');
            }

            // Ajouter le chemin du fichier dans le tableau
            $uploadedPaths[] = $this->location . $uniqueFileName;
        }

        return $uploadedPaths;
    }

    public function update(array $params): void
    {
        // TODO: Implement update() method.
    }
}