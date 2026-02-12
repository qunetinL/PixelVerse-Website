<?php

namespace PixelVerseApp\Models;

use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\BSON\UTCDateTime;

/**
 * Gestionnaire de logs NoSQL (MongoDB)
 * Utilisé pour le suivi des activités administrateur et employé.
 */
class LogManager
{
    private $manager;
    private $dbName;
    private $collection = 'admin_logs';

    public function __construct()
    {
        // On récupère les identifiants depuis l'environnement (Docker/System)
        // Les valeurs par défaut correspondent au docker-compose.yml
        $user = getenv('MONGO_INITDB_ROOT_USERNAME') ?: 'root';
        $pass = getenv('MONGO_INITDB_ROOT_PASSWORD') ?: 'rootpassword';
        $host = 'mongo'; // Nom du service dans docker-compose
        $port = 27017;

        $this->dbName = getenv('MYSQL_DATABASE') ?: 'pixelverse';

        $uri = "mongodb://{$user}:{$pass}@{$host}:{$port}/";

        try {
            $this->manager = new Manager($uri);
        } catch (\Exception $e) {
            // Silencieusement faillir si MongoDB n'est pas dispo
            $this->manager = null;
        }
    }

    /**
     * Enregistre une action dans les logs MongoDB
     */
    public function log(string $userId, string $action, array $details = []): bool
    {
        if (!$this->manager)
            return false;

        $bulk = new BulkWrite();
        $bulk->insert([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'created_at' => new UTCDateTime()
        ]);

        try {
            $this->manager->executeBulkWrite("{$this->dbName}.{$this->collection}", $bulk);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Récupère tous les logs triés par date décroissante
     */
    public function getAll(): array
    {
        if (!$this->manager)
            return [];

        $query = new Query([], ['sort' => ['created_at' => -1]]);

        try {
            $cursor = $this->manager->executeQuery("{$this->dbName}.{$this->collection}", $query);
            return $cursor->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
