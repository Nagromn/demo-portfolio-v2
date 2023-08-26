<?php

namespace Routes;

use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Controllers\SecurityController;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Class RouteConfig
 * @package Routes
 */
class RouteConfig
{
    /**
     * @var array $routes
     * Tableau contenant les routes de l'application.
     * Chaque route est un tableau associatif contenant les clés suivantes :
     * - path : chemin de la route (URL)
     * - method : méthode HTTP associée à la route (GET, POST, etc.)
     * - controller : nom du contrôleur associé à la route
     * - action : nom de l'action (méthode) du contrôleur à exécuter pour la route
     */
    private const ROUTES = [
        // Controller pour l'affichage de la page d'accueil du site
        HomeController::class => [
            // Route pour l'affichage de la page d'accueil
            'home' => [
                'path' => '/',
                'method' => 'GET',
            ],
            // Route pour l'affichage des expériences professionnelles
            'professional' => [
                'path' => '/professional',
                'method' => 'GET',
            ],
            // Route pour l'affichage des projets réalisés
            'project' => [
                'path' => '/project',
                'method' => 'GET',
            ],
            // Route pour l'affichage des compétences et formations
            'skill' => [
                'path' => '/skill',
                'method' => 'GET',
            ],
        ],

        // Controller pour l'administration du site
        AdminController::class => [
            // Route pour l'administration
            'dashboard' => [
                'path' => '/admin-dashboard',
                'method' => 'GET',
            ],
            // Route pour l'insertion d'utilisateur
            'registration' => [
                'path' => '/admin-registration',
                'method' => ['GET', 'POST'],
            ],
            // Route pour l'insertion de projet
            'newProject' => [
                'path' => '/admin-project-form',
                'method' => ['GET', 'POST']
            ],
            // Route pour la mise à jour d'un utilisateur
            'userUpdate' => [
                'path' => '/admin-update-user',
                'method' => ['GET', 'POST']
            ],
            // Route pour la mise à jour d'un projet
            'projectUpdate' => [
                'path' => '/admin-update-project',
                'method' => ['GET', 'POST']
            ],
        ],

        // Controller pour la sécurité (connexion/déconnexion) du site
        SecurityController::class => [
            // Route pour la connexion
            'login' => [
                'path' => '/admin-login',
                'method' => ['GET', 'POST'],
            ],
            // Route pour la déconnexion
            'logout' => [
                'path' => '/admin-logout',
                'method' => 'GET',
            ],
        ],
    ];

    /**
     * Récupère les routes configurées dans la classe.
     *
     * @throws ReflectionException
     * @return array Tableau d'objets de la classe Route
     */
    public static function getRoutes(): array
    {
        // Tableau pour stocker les routes
        $routes = [];

        // Parcours des routes configurées
        foreach (self::ROUTES as $controller => $actions) {
            // Récupération des informations sur le contrôleur
            $reflection = new ReflectionClass($controller);

            // Récupération des méthodes publiques du contrôleur
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            // Parcours des méthodes publiques du contrôleur
            foreach ($methods as $method) {
                // Si la méthode est une action du contrôleur (et non pas le constructeur)
                if ($method->class === $controller && $method->name !== '__construct') {
                    // Récupération du nom de l'action
                    $action = $method->name;

                    // Récupération des données de la route associée à l'action du contrôleur courant (si elle existe)
                    $routeData = $actions[$action] ?? null;

                    // Si la route existe, on l'ajoute au tableau des routes
                    if ($routeData !== null) {
                        // Récupération des données de la route
                        $routePath = $routeData['path'];
                        $routeMethods = $routeData['method'];

                        // Si la méthode HTTP de la route n'est pas un tableau, on le transforme en tableau
                        $routeMethods = is_array($routeMethods) ? $routeMethods : [$routeMethods];

                        // Création d'une route pour chaque méthode HTTP de la route
                        foreach ($routeMethods as $routeMethod) {
                            // Ajout de la route au tableau des routes
                            $routes[] = new Route($routeMethod, $routePath, $controller, $action);
                        }
                    }
                }
            }
        }
        // Retourne le tableau des routes
        return $routes;
    }
}