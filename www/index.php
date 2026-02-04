<?php

/**
 * Front Controller : Point d'entrée de l'application
 */

require_once dirname(__DIR__) . '/src/Core/Autoloader.php';

use PixelVerseApp\Core\Autoloader;
use PixelVerseApp\Router\Router;
use PixelVerseApp\Controllers\HomeController;

// Initialisation de l'autoloader
Autoloader::register();

// Création du routeur
$router = new Router();

// Définition des routes de l'application
$router->add('GET', '/', [HomeController::class, 'index']);

// Dispatching de la requête entrante
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
