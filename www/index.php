<?php

/**
 * Front Controller : Point d'entrée de l'application
 */

require_once dirname(__DIR__) . '/src/Core/Autoloader.php';

use PixelVerseApp\Core\Autoloader;
use PixelVerseApp\Router\Router;
use PixelVerseApp\Controllers\HomeController;
use PixelVerseApp\Controllers\AuthController;

// Initialisation de l'autoloader
Autoloader::register();

// Création du routeur
$router = new Router();

// Définition des routes de l'application
$router->add('GET', '/', [HomeController::class, 'index']);

// Routes Portier (Authentification)
$router->add('GET', '/connexion', [AuthController::class, 'showLogin']);
$router->add('POST', '/connexion', [AuthController::class, 'login']);
$router->add('GET', '/inscription', [AuthController::class, 'showRegister']);
$router->add('POST', '/inscription', [AuthController::class, 'register']);
$router->add('GET', '/deconnexion', [AuthController::class, 'logout']);
$router->add('GET', '/mot-de-passe-oublie', [AuthController::class, 'showForgotPassword']);
$router->add('POST', '/mot-de-passe-oublie', [AuthController::class, 'forgotPassword']);

// Dispatching de la requête entrante
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
