<?php

namespace App\Model;

use Exception;

class ProjectCategory extends Model
{
    protected string $table = 'project_category';
    protected int $projectId;
    protected array $categoryId;
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function setCategoryId(array $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function insert(array $params): void
    {
        $values = [];

        foreach ($this->categoryId as $categoryId) {
            $values[] = "({$this->projectId}, {$categoryId})";
        }

        $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (project_id, category_id) VALUES ' . implode(',', $values));
        $query->execute();
    }
}