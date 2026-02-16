<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "--- Admin Hash Generation ---\n";
echo "Hash for 'AdminPass123!': " . password_hash('AdminPass123!', PASSWORD_ARGON2ID) . "\n\n";

echo "--- MongoDB Connection Test ---\n";
$mongoUri = getenv('MONGO_URL');
echo "MONGO_URL: " . ($mongoUri ? 'Set (hidden)' : 'Not Set') . "\n";

try {
    if (!$mongoUri) {
        throw new Exception("MONGO_URL not defined.");
    }

    // Parse the URI to get db name if present, though Manager doesn't need it
    $options = [
        'tls' => true,
        'tlsAllowInvalidCertificates' => true
    ];
    $manager = new MongoDB\Driver\Manager($mongoUri, $options);
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $cursor = $manager->executeCommand('admin', $command);
    $response = $cursor->toArray()[0];

    echo "MongoDB Ping: " . ($response->ok ? 'OK' : 'Failed') . "\n";
    print_r($response);

} catch (Throwable $e) {
    echo "MongoDB Error: " . $e->getMessage() . "\n";
    if (class_exists('MongoDB\Driver\Manager')) {
        echo "MongoDB Extension: Loaded\n";
    } else {
        echo "MongoDB Extension: NOT Loaded\n";
    }
}
