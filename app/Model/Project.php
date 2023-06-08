<?php

namespace App\Model;

use AllowDynamicProperties;
use DateTime;

/**
 * Gère les projets.
 * @package App\Model
 */
#[AllowDynamicProperties]
class Project extends Model
{
    /**
     * @var string $table Nom de la table
     * @property string $projectName Le nom du projet
     * @property string $content Le contenu du projet
     * @property DateTime $createdAt La date de création du projet
     * @property array $categories Les catégories du projet
     */
    protected string $table = 'project';
    protected string $projectName;
    protected string $content;
    protected DateTime $createdAt;
    protected array $categories = [];

    /**
     * @param string $projectName Le nom du projet
     */
    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    /**
     * @param string $content Le contenu du projet
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param DateTime $createdAt La date de création du projet
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Ajoute une catégorie au projet.
     *
     * @param int $categoryId L'ID de la catégorie à ajouter
     */
    public function addCategory(int $categoryId): void
    {
        $this->categories[] = $categoryId;
    }

    /**
     * Insère les données du projet dans la table correspondante.
     *
     * @param array $params Les paramètres de l'insertion
     */
    public function insert(array $params): void
    {
        $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (projectName, content, createdAt) VALUES (:projectName, :content, :createdAt)');
        $query->execute($params);
        $this->lastInsertedId = $this->db->lastInsertId();
    }

    public function update(array $params): void
    {
        $query = $this->db->prepare('UPDATE ' . $this->table . ' SET projectName = :projectName, content = :content, createdAt = :createdAt WHERE id = :id');
        $query->execute($params);
    }
}