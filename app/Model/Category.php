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
     *
     * @param array $params Les paramètres de l'insertion
     */
    public function insert(array $params): void
    {
        // TODO: Implémenter la logique d'insertion pour la table 'category'
    }

    /**
     * Met à jour un enregistrement dans la table.
     *
     * @param array $params Les paramètres de la mise à jour
     */
    public function update(array $params): void
    {}
}