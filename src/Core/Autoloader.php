<?php

namespace PixelVerseApp\Core;

/**
 * Autoloader personnalisé pour charger les classes automatiquement
 * selon la structure des dossiers et le namespace PixelVerseApp\
 */
class Autoloader
{
    /**
     * Enregistre l'autoloader dans la pile de chargement PHP
     */
    public static function register()
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    /**
     * Logique de chargement d'une classe
     * @param string $class Nom complet de la classe (ex: PixelVerseApp\Core\Database)
     */
    public static function autoload($class)
    {
        $prefix = 'PixelVerseApp\\';

        // Ignore les classes qui ne sont pas dans le namespace de l'application
        if (!str_starts_with($class, $prefix)) {
            return;
        }

        // Transforme le namespace en chemin de fichier physique
        // Ex: PixelVerseApp\Controllers\HomeController -> src/Controllers/HomeController.php
        $path = dirname(__DIR__) . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

        // Charge le fichier s'il existe
        if (is_file($path)) {
            require $path;
        }
    }
}
