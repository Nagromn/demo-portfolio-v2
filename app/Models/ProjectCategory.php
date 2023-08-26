<?php

namespace App\Models;

use Exception;

/**
 * Table intermédiaire entre les projets et les catégories.
 * @package App\Model
 */
class ProjectCategory extends Model
{
    /**
     * @property string $table Le nom de la table
     * @property int $projectId L'ID du projet
     * @property array $categoryId Les ID des catégories
     */
    protected string $table = 'project_category';
    protected int $projectId;
    protected array $categoryId = [];

    /**
     * Permet d'insérer les catégories d'un projet.
     * @return void
     * @throws Exception
     */
    public function insert(): void
    {
        try {
            $values = []; // On initialise un tableau vide

            // On boucle sur les ID des catégories
            foreach ($this->categoryId as $categoryId) {
                $values[] = "($this->projectId, $categoryId)"; // On ajoute les ID des catégories dans le tableau
            }

            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (project_id, category_id) VALUES ' . implode(',', $values)); // On prépare la requête SQL
            $query->execute(); // On exécute la requête SQL
        } catch (Exception $e) {
            echo 'Erreur lors de l\'enregistrement des catégories : ' . $e->getMessage(); // On affiche un message d'erreur
        }
    }

    /**
     * @param array $categoryId Les ID des catégories
     */
    public function getCategoryId(array $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @param array $categoryId Les ID des catégories
     */
    public function setCategoryId(array $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int L'ID du projet
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId L'ID du projet
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }
}