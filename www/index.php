<?php

session_start();

/**
 * Front Controller : Point d'entrée de l'application
 */

require_once dirname(__DIR__) . '/src/Core/Autoloader.php';

use PixelVerseApp\Core\Autoloader;
use PixelVerseApp\Router\Router;
use PixelVerseApp\Controllers\HomeController;
use PixelVerseApp\Controllers\AuthController;
use PixelVerseApp\Controllers\CharacterController;
use PixelVerseApp\Controllers\AccessoryController;
use PixelVerseApp\Controllers\ReviewController;
use PixelVerseApp\Controllers\PageController;
use PixelVerseApp\Controllers\AdminController;
use PixelVerseApp\Core\Security;

// Initialisation de l'autoloader
Autoloader::register();

// Création du routeur
$router = new Router();

// Définition des routes de l'application
$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/galerie', [PageController::class, 'galerie']);
$router->add('GET', '/contact', [PageController::class, 'contact']);
$router->add('POST', '/contact', [PageController::class, 'submitContact']);
$router->add('GET', '/mentions-legales', [PageController::class, 'mentionsLegales']);
$router->add('GET', '/cgv', [PageController::class, 'cgv']);
$router->add('GET', '/en-savoir-plus', [PageController::class, 'enSavoirPlus']);

// Routes Portier (Authentification)
$router->add('GET', '/connexion', [AuthController::class, 'showLogin']);
$router->add('POST', '/connexion', [AuthController::class, 'login']);
$router->add('GET', '/inscription', [AuthController::class, 'showRegister']);
$router->add('POST', '/inscription', [AuthController::class, 'register']);
$router->add('GET', '/deconnexion', [AuthController::class, 'logout']);
$router->add('GET', '/mot-de-passe-oublie', [AuthController::class, 'showForgotPassword']);
$router->add('POST', '/mot-de-passe-oublie', [AuthController::class, 'forgotPassword']);

// Routes Personnages (Utilisateur)
$router->add('GET', '/mes-personnages', [CharacterController::class, 'index']);
$router->add('GET', '/creer-personnage', [CharacterController::class, 'create']);
$router->add('GET', '/personnage', [CharacterController::class, 'show']);
$router->add('POST', '/creer-personnage', [CharacterController::class, 'store']);
$router->add('GET', '/supprimer-personnage', [CharacterController::class, 'delete']);
$router->add('GET', '/dupliquer-personnage', [CharacterController::class, 'duplicate']);

$router->add('GET', '/admin/personnages', [CharacterController::class, 'adminIndex']);
$router->add('GET', '/admin/personnages/approuver', [CharacterController::class, 'approve']);
$router->add('POST', '/admin/personnages/refuser', [CharacterController::class, 'reject']);

// Routes Administration (Accessoires)
$router->add('GET', '/admin/accessoires', [AccessoryController::class, 'index']);
$router->add('GET', '/admin/accessoires/nouveau', [AccessoryController::class, 'create']);
$router->add('POST', '/admin/accessoires/nouveau', [AccessoryController::class, 'store']);
$router->add('GET', '/admin/accessoires/supprimer', [AccessoryController::class, 'delete']);
$router->add('GET', '/admin/logs', [AdminController::class, 'logs']);
$router->add('GET', '/admin/employes', [AdminController::class, 'employees']);
$router->add('POST', '/admin/employes/nouveau', [AdminController::class, 'createEmployee']);
$router->add('POST', '/admin/employes/supprimer', [AdminController::class, 'deleteEmployee']);
$router->add('GET', '/admin/utilisateurs', [AdminController::class, 'users']);
$router->add('POST', '/admin/utilisateurs/toggle-suspension', [AdminController::class, 'toggleSuspension']);

// Routes Avis
$router->add('POST', '/avis/nouveau', [ReviewController::class, 'store']);
$router->add('GET', '/admin/avis', [ReviewController::class, 'adminIndex']);
$router->add('GET', '/admin/avis/approuver', [ReviewController::class, 'approve']);
$router->add('GET', '/admin/avis/supprimer', [ReviewController::class, 'delete']);

// Protection CSRF globale pour toutes les requêtes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? null;
    if (!Security::verifyCsrfToken($token)) {
        http_response_code(403);
        die("<h1>Session Expirée</h1><p>Veuillez rafraîchir la page et réessayer. (Erreur CSRF)</p><a href='/connexion'>Retour à la connexion</a>");
    }
}

// Dispatching de la requête entrante
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
