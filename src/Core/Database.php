<?php

namespace PixelVerseApp\Core;

use PDO;
use Exception;

/**
 * Classe Database (Singleton) pour gérer la connexion à MySQL
 */
class Database
{
    private static $instance = null;
    private $pdo;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct()
    {
        // Récupération des paramètres de connexion via les variables d'environnement
        $host = getenv('MYSQL_HOST') ?: 'db';
        $port = getenv('MYSQL_PORT') ?: '3306';
        $db = getenv('MYSQL_DATABASE') ?: 'pixelverse';
        $user = getenv('MYSQL_USER') ?: 'pixeluser';
        $pass = getenv('MYSQL_PASSWORD') ?: 'pixelpassword';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        // Support SSL pour TiDB Cloud ou autres services managés
        if (getenv('MYSQL_SSL') === 'true') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = getenv('MYSQL_SSL_CA') ?: '/etc/ssl/certs/ca-certificates.crt';
            // Note: Sur Fly.io avec PHP 8.2-apache, les certs sont généralement là.
        }

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new Exception("Erreur de connexion MySQL : " . $e->getMessage());
        }
    }

    /**
     * Récupère l'instance unique de la base de données
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne l'objet PDO pour effectuer des requêtes
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
