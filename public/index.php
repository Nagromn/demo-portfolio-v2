<?php

use Routes\Router;
use Routes\RouteConfig;

// Inclusion de l'autoloader de Composer
require 'vendor/autoload.php';

/////////////////////////////////////////////
// DEFINITION DES CHEMINS VERS LES CLASSES //
/////////////////////////////////////////////
if (!defined('_PROJET_')) {
    //nom du projet
    $aFolders = explode('\\', realpath(dirname(__FILE__)));
    //Cas particulier des appels AJAX, le repertoire est CONFIG au lieu de PROJECT_W2
    define('_PROJET_',$aFolders[sizeof($aFolders) - 2]);
}
if (!defined('_DOC_ROOT_')) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        define('_DOC_ROOT_',$_SERVER['DOCUMENT_ROOT']);
    } else {
        define('_DOC_ROOT_',$_SERVER['DOCUMENT_ROOT'].'/');
    }
}

/** DIR BASE projet */
const _DIR_BASE_ = _DOC_ROOT_ . _PROJET_ . '/';

/** URL BASE projet */
const _URL_BASE_ = 'http://localhost/' . _PROJET_ . '/';

/** le repertoire 'include' basique */
const _DIR_INCLUDES_BASE_ = _DIR_BASE_ . 'includes/';

/** le repertoire des images */
const _DIR_IMGS_ = _DIR_BASE_ . 'uploads/';
/** l'URL des images */
const _URL_IMGS_ = _URL_BASE_;

// Création d'une instance du routeur
$router = new Router();

try {
    // Récupération des routes à partir de la configuration
    $routes = RouteConfig::getRoutes();

    // Ajout de chaque route au routeur
    foreach ($routes as $route) {
        $router->addRoute($route);
    }
} catch (ReflectionException $e) {
    // Gérer l'exception ici en cas d'erreur lors de la récupération des routes
    echo 'La page n\'a pas été trouvée' . $e->getMessage();
}

// Dispatch de la requête courante vers la route correspondante
try {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    $router->dispatch($method, $path);
} catch (Exception $e) {
    // Gérer l'exception ici en cas d'erreur lors du dispatching de la requête
    echo 'Une erreur s\'est produite : ' . $e->getMessage();
}