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
        // Récupération des paramètres de connexion via les variables d'environnement Docker
        $host = 'db';
        $db = getenv('MYSQL_DATABASE') ?: 'pixelverse';
        $user = getenv('MYSQL_USER') ?: 'pixeluser';
        $pass = getenv('MYSQL_PASSWORD') ?: 'pixelpassword';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne des tableaux associatifs par défaut
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new Exception("Erreur de connexion : " . $e->getMessage());
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
