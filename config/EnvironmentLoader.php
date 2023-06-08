<?php

namespace Config;

use Dotenv\Dotenv;

/**
 * Classe permettant de charger les variables d'environnement.
 * @package Config
 */
class EnvironmentLoader
{
    /**
     * Charge les variables d'environnement à partir du fichier .env.
     *
     * @param string $basePath Le chemin de base du projet
     */
    public static function load(string $basePath): void
    {
        // Création d'une instance de Dotenv
        $dotenv = Dotenv::createImmutable($basePath);

        // Chargement des variables d'environnement à partir du fichier .env
        $dotenv->load();
    }
}