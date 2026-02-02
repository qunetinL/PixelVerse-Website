<?php
$host = 'db';
$db = getenv('MYSQL_DATABASE') ?: 'pixelverse';
$user = getenv('MYSQL_USER') ?: 'pixeluser';
$pass = getenv('MYSQL_PASSWORD') ?: 'pixelpassword';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<h1>Connexion MySQL : <span style='color:green'>OK</span></h1>";
} catch (\PDOException $e) {
    echo "<h1>Connexion MySQL : <span style='color:red'>ERREUR</span></h1>";
    echo "Message : " . $e->getMessage();
}

try {
    $mongoUser = getenv('MONGO_INITDB_ROOT_USERNAME') ?: 'root';
    $mongoPass = getenv('MONGO_INITDB_ROOT_PASSWORD') ?: 'rootpassword';

    $manager = new MongoDB\Driver\Manager("mongodb://$mongoUser:$mongoPass@mongo:27017");
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $cursor = $manager->executeCommand('admin', $command);
    echo "<h1>Connexion MongoDB : <span style='color:green'>OK</span></h1>";
} catch (Exception $e) {
    echo "<h1>Connexion MongoDB : <span style='color:red'>ERREUR</span></h1>";
    echo "Message : " . $e->getMessage();
}

phpinfo();
