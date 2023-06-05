<?php

namespace App\Model;

use Exception;

class Upload extends Model
{
    protected string $table = 'upload';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insère les données dans la base de données.
     *
     * @param array $params Paramètres à insérer
     * @throws Exception En cas d'erreur lors de l'exécution de la requête SQL ou de la vérification de l'image
     */
    public function insert(array $params): void
    {
        // Récupérer les paramètres
        $params = $_FILES['files'] ?? [];

        // Vérifier l'upload des images
        $uploadedPaths = $this->uploadImages($params);

        // Vérifier si au moins une image a été chargée avec succès
        if (empty($uploadedPaths)) {
            throw new Exception('Aucune image valide n\'a été chargée.');
        }

        // Récupérer la date de création
        $createdAt = date('Y-m-d H:i:s');

        // Parcourir les chemins des images chargées
        foreach ($uploadedPaths as $imagePath) {
            $fileName = basename($imagePath);

            // Préparer la requête SQL
            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (fileName, location, createdAt) VALUES (:fileName, :location, :createdAt)');

            // Exécuter la requête avec les paramètres
            $query->execute([
                'fileName' => $fileName,
                'location' => $imagePath,
                'createdAt' => $createdAt,
            ]);
        }
    }

    /**
     * @throws Exception
     */
    public function uploadImages(array $files): array
    {
        $uploadDir = 'uploads/';
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
            $uploaded = move_uploaded_file($tmpName, $uploadDir . $uniqueFileName);

            // Vérifier si le fichier a été déplacé avec succès
            if (!$uploaded) {
                throw new Exception('Le fichier ' . $fileName . ' n\'a pas été déplacé avec succès.');
            }

            // Ajouter le chemin du fichier dans le tableau
            $uploadedPaths[] = $uploadDir . $uniqueFileName;
        }

        return $uploadedPaths;
    }
}