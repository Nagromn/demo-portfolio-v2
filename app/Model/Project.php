<?php

namespace App\Model;

use AllowDynamicProperties;
use DateTime;
use Exception;

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
     * @property int|null $userId L'ID de l'utilisateur
     */
    protected string $table = 'project';
    protected string $projectName;
    protected string $content;
    protected DateTime $createdAt;
    protected array $categories = [];
    protected ?int $userId = null;

    /**
     * Permet d'insérer un projet.
     * @return void
     * @throws Exception
     */
    public function insert(): void
    {
        try {
            // On prépare la requête d'insertion en base de données
            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (projectName, content, createdAt, user_id) VALUES (:projectName, :content, :createdAt, :user_id)');
            $query->bindValue(':projectName', $this->getProjectName());
            $query->bindValue(':content', $this->getContent());
            $query->bindValue(':createdAt', $this->getCreatedAt()->format('Y-m-d H:i:s'));
            $query->bindValue(':user_id', $this->getUserId());
            $query->execute(); // On exécute la requête

            $this->lastInsertedId = $this->db->lastInsertId(); // On récupère le dernier ID du projet inséré en base de données
        } catch (Exception $e) {
           echo 'Erreur lors de l\'enregistrement du projet : ' . $e->getMessage();
        }
    }

    /**
     * @return string Le nom du projet
     */
    private function getProjectName(): string
    {
        return $this->projectName;
    }

    /**
     * @return string Le contenu du projet
     */
    private function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTime La date de création du projet
     */
    private function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return int|null L'ID de l'utilisateur
     */
    private function getUserId(): ?int
    {
        return $this->userId;
    }

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
     * @param int $categoryId L'ID de la catégorie à ajouter
     */
    public function addCategory(int $categoryId): void
    {
        $this->categories[] = $categoryId;
    }

    /**
     * @param int|null $userId L'ID de l'utilisateur
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }
}