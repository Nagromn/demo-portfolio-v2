<?php

namespace App\Model;

use AllowDynamicProperties;
use DateTime;

#[AllowDynamicProperties]
class Project extends Model
{
    protected string $table = 'project';
    protected string $projectName;
    protected string $content;
    protected DateTime $createdAt;
    protected array $categories = [];

    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function addCategory(int $categoryId): void
    {
        $this->categories[] = $categoryId;
    }

    public function insert(array $params): void
    {
        $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (projectName, content, createdAt) VALUES (:projectName, :content, :createdAt)');
        $query->execute($params);
        $this->lastInsertedId = $this->db->lastInsertId();
    }
}