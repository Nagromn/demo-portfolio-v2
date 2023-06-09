<?php

namespace App\Model;

/**
 * Class Session
 * Gère la session utilisateur.
 * @package App\Model
 */
class Session
{
    /**
     * Initialise la session si elle n'est pas déjà démarrée.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Méthode pour détruire la session.
     * @return void
     */
    public static function destroy(): void
    {
        $_SESSION['auth'] = [];
        unset($_SESSION['auth']);
        session_destroy();
    }

    /**
     * Méthode pour initialiser la session.
     * @param array $user
     * @return void
     */
    public static function init(array $user): void
    {
        $_SESSION['auth'] = $user;
    }

    /**
     * Vérifie si l'utilisateur est connecté.
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['auth']);
    }

    /**
     * Récupère les informations de l'utilisateur connecté.
     * @return array|null
     */
    public static function getUser(): ?array
    {
        return $_SESSION['auth'] ?? null;
    }
}