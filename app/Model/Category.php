<?php

namespace App\Model;

/**
 * Class Category
 * @package App\Model
 */
class Category extends Model
{
    /**
     * @var string $table
     */
    protected string $table = 'category';

    /**
     * Insère un nouvel enregistrement dans la table.
     * @return void
     */
    public function insert(): void
    {}

    /**
     * Met à jour un enregistrement dans la table.
     * @return void
     */
    public function update(): void
    {}
}