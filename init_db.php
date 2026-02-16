<?php
require_once __DIR__ . '/vendor/autoload.php';

use PixelVerseApp\Core\Database;

try {
    // Get connection
    $pdo = Database::getInstance()->getConnection();
    echo "Connected successfully.\n";

    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');

    // Split SQL commands
    $commands = explode(';', $sql);

    foreach ($commands as $command) {
        if (trim($command)) {
            try {
                $pdo->exec($command);
            } catch (PDOException $e) {
                // Ignore specific errors but print for debug
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    echo "Database initialized successfully.\n";

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
}
