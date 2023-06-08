<?php

namespace App\Model;

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
    protected array $categoryId;

    /**
     * Définit l'ID du projet.
     *
     * @param int $projectId L'ID du projet
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * Définit les ID des catégories.
     *
     * @param array $categoryId Les ID des catégories
     */
    public function setCategoryId(array $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Insère les relations entre le projet et les catégories dans la table correspondante.
     *
     * @param array $params Les paramètres de l'insertion
     */
    public function insert(array $params): void
    {
        $values = [];
        foreach ($this->categoryId as $categoryId) {
            $values[] = "({$this->projectId}, {$categoryId})";
        }

        $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (project_id, category_id) VALUES ' . implode(',', $values));
        $query->execute();
    }

    public function update(array $params): void
    {
        // TODO: Implement update() method.
    }
}