<?php

namespace Routes;

/**
 * Class Route
 * @package Routes
 */
class Route
{
    /**
     * @var string $method // Méthode HTTP associée à la route (GET, POST, etc.)
     * @var string $path // Chemin de la route (URL)
     * @var string $controller // Nom du contrôleur associé à la route
     * @var string $action // Nom de l'action (méthode) du contrôleur à exécuter pour la route
     */
    private string $method;
    private string $path;
    private string $controller;
    private string $action;

    /**
     * Constructeur de la classe Route.
     *
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $method, string $path, string $controller, string $action)
    {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * Retourne la méthode HTTP associée à la route.
     *
     * @return string Méthode HTTP de la route
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Retourne le chemin de la route.
     *
     * @return string Chemin de la route
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retourne le nom du contrôleur associé à la route.
     *
     * @return string Nom du contrôleur
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Retourne le nom de l'action du contrôleur à exécuter pour la route.
     *
     * @return string Nom de l'action
     */
    public function getAction(): string
    {
        return $this->action;
    }
}