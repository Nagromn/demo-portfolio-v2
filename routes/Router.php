<?php

namespace Routes;

/**
 * Class Router
 * @package Routes
 */
class Router
{
    /**
     * @var array $routes
     */
    private array $routes = [];

    /**
     * Ajoute une route au routeur.
     *
     * @param Route $route La route à ajouter.
     * @return void
     */
    public function addRoute(Route $route): void
    {
        // Récupération des informations de la route
        $method = $route->getMethod();
        $path = $route->getPath();
        $controller = $route->getController();
        $action = $route->getAction();

        // Ajout de la route dans le tableau des routes
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Dispatche la requête en fonction de la méthode et du chemin.
     *
     * @param string $method La méthode HTTP de la requête.
     * @param string $path Le chemin de la requête (URL).
     * @return void
     */
    public function dispatch(string $method, string $path): void
    {
        // Vérification si la route existe pour la méthode et le chemin donnés
        if (isset($this->routes[$method][$path])) {
            // Récupération des informations de la route
            $route = $this->routes[$method][$path];
            $controller = $route['controller'];
            $action = $route['action'];

            // Exécution de l'action du contrôleur correspondant à la route
            $this->executeAction($controller, $action);
        } else {
            // Sinon, on affiche une erreur 404
            echo "Erreur 404: Page non trouvée";
        }
    }

    /**
     * Exécute l'action d'un contrôleur donné.
     *
     * @param string $controller Le nom de la classe du contrôleur.
     * @param string $action Le nom de l'action (méthode) du contrôleur.
     * @return void
     */
    private function executeAction(string $controller, string $action): void
    {
        // Instanciation du contrôleur
        $controllerInstance = new $controller();

        // Appel de l'action du contrôleur
        $controllerInstance->$action();
    }
}