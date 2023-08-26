<?php

namespace Utils;

/**
 * Class Renderer
 * @package Utils
 */
class Renderer
{
    /**
     * Rendu d'une vue avec des variables.
     *
     * @param string $path Le chemin de la vue à rendre.
     * @param array $variables Les variables à passer à la vue (par défaut, un tableau vide).
     * @return void
     */
    public static function render(string $path, array $variables = []): void
    {
        // Extraction des variables pour les rendre accessibles dans la vue
        extract($variables);

        // Mise en tampon de la sortie pour capturer le contenu de la vue
        ob_start();

        // Inclusion de la vue
        require $path;

        // Récupération du contenu de la vue depuis le tampon
        $content = ob_get_clean();

        // Inclusion du template de base pour afficher le contenu
        require dirname(__DIR__) . '/app/Views/templates/base.php';
    }
}