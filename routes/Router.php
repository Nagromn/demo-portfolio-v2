<?php

namespace Routes;

use Exception;
use ReflectionClass;

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
     * @throws Exception
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
     * @throws Exception
     */
    private function executeAction(string $controller, string $action): void
    {
        // Classe ReflectionClass pour le contrôleur
        $reflectionClass = new ReflectionClass($controller);

        // Vérifie si le constructeur a des paramètres
        $constructor = $reflectionClass->getConstructor();
        if ($constructor !== null) {
            // Paramètres de type ReflectionParameters pour le constructeur
            $parameters = $constructor->getParameters();
            $dependencies = [];

            foreach ($parameters as $parameter) {
                // Type ReflectionType pour le type de paramètre
                $parameterType = $parameter->getType();

                if ($parameterType !== null && !$parameterType->isBuiltin()) {
                    // Résolution de la dépendance en instanciant la classe correspondante
                    $dependencyClass = $parameterType->getName();
                    $dependency = new $dependencyClass();

                    // Ajout de la dépendance résolue à la liste
                    $dependencies[] = $dependency;
                } else {
                    // Impossible de résoudre la dépendance, lance une exception
                    throw new Exception("Impossible de résoudre la dépendance pour le paramètre $parameter->name du constructeur de la classe $controller");
                }
            }

            // Instancie le contrôleur avec les dépendances résolues
            $controllerInstance = $reflectionClass->newInstanceArgs($dependencies);
        } else {
            // Si le constructeur n'a pas de paramètres, on peut l'instancier directement
            $controllerInstance = new $controller();
        }

        // Vérifie si la méthode d'action existe
        if ($reflectionClass->hasMethod($action)) {
            // Classe ReflectionMethod pour la méthode d'action
            $actionMethod = $reflectionClass->getMethod($action);

            // Paramètres de type ReflectionParameters pour la méthode d'action
            $actionParameters = $actionMethod->getParameters();
            $actionDependencies = [];

            foreach ($actionParameters as $parameter) {
                // Type ReflectionType pour le type de paramètre
                $parameterType = $parameter->getType();

                if ($parameterType !== null && !$parameterType->isBuiltin()) {
                    // Résolution de la dépendance en instanciant la classe correspondante
                    $dependencyClass = $parameterType->getName();
                    $dependency = new $dependencyClass();

                    // Ajout de la dépendance résolue à la liste
                    $actionDependencies[] = $dependency;
                } else {
                    // Impossible de résoudre la dépendance, lance une exception
                    throw new Exception("Impossible de résoudre la dépendance pour le paramètre $parameter->name de la méthode d'action $action de la classe $controller");
                }
            }

            // Appelle la méthode d'action avec les dépendances résolues
            $actionMethod->invokeArgs($controllerInstance, $actionDependencies);
        } else {
            // Si la méthode d'action n'existe pas, lance une exception
            throw new Exception("Méthode d'action $action non trouvée dans la classe $controller");
        }
    }
}