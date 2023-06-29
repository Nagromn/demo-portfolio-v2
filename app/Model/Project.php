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
            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (projectName, content, createdAt, user_id) VALUES (:projectName, :content, :createdAt, :user_id)'); // On prépare la requête d'insertion en base de données
            $query->bindValue(':projectName', $this->getProjectName()); // On associe le titre du projet à la variable $projectName
            $query->bindValue(':content', $this->getContent()); // On associe la description du projet à la variable $content
            $query->bindValue(':createdAt', $this->getCreatedAt()->format('Y-m-d H:i:s')); // On associe la date de création du projet à la variable $createdAt
            $query->bindValue(':user_id', $this->getUserId()); // On associe l'ID de l'utilisateur à la variable $userId
            $query->execute(); // On exécute la requête

            $this->lastInsertedId = $this->db->lastInsertId(); // On récupère le dernier ID du projet inséré en base de données
        } catch (Exception $e) {
           echo 'Erreur lors de l\'enregistrement du projet : ' . $e->getMessage(); // On affiche un message d'erreur
        }
    }

    /**
     * Met à jour les informations d'un projet dans la base de données.
     * @return void
     * @throws Exception En cas d'erreur lors de la mise à jour du projet
     */
    public function update(): void
    {
        $id = $params['id'] ?? null; // Récupère l'id du projet
        $projectName = $params['projectName'] ?? null; // Récupère le titre du projet
        $content = $params['content'] ?? null; // Récupère la description du projet
        $updatedAt = new DateTime(); // Récupère la date de mise à jour du projet

        try {
            $request = $this->db->prepare("UPDATE $this->table SET projectName = :projectName, content = :content, updatedAt = :updatedAt WHERE id = :id"); // Prépare la requête de mise à jour du projet
            $request->execute([
                ':id' => $id, // On associe l'id du projet à la variable $id
                ':projectName' => $projectName, // On associe le titre du projet à la variable $projectName
                ':content' => $content, // On associe la description du projet à la variable $content
                ':updatedAt' => $updatedAt->format('Y-m-d H:i:s'), // On associe la date de mise à jour du projet à la variable $updatedAt
            ]);
        } catch (Exception $e) {
            echo 'Exception reçue : ', $e->getMessage(), "\n"; // Affiche l'erreur
            throw new Exception("Erreur lors de la mise à jour du projet."); // Lève une exception
        }
    }

    /**
     * @return string Le nom du projet
     */
    public function getProjectName(): string
    {
        return $this->projectName;
    }

    /**
     * @param string $projectName Le nom du projet
     */
    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    /**
     * @return string Le contenu du projet
     */
    private function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content Le contenu du projet
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime La date de création du projet
     */
    private function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt La date de création du projet
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int|null L'ID de l'utilisateur
     */
    private function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId L'ID de l'utilisateur
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param int $categoryId L'ID de la catégorie à ajouter
     */
    public function addCategory(int $categoryId): void
    {
        $this->categories[] = $categoryId;
    }
}