<?php
declare(strict_types=1);

// Simple migration runner: run SQL from database/migrations/ directory
$dir = __DIR__ . '/../database/migrations';
if (! is_dir($dir)) {
    echo "No migrations directory found.\n";
    exit(0);
}

$files = glob($dir . '/*.sql');
if ($files === false || $files === []) {
    echo "No migration files found.\n";
    exit(0);
}

// Read DB settings from .env
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$db = getenv('DB_DATABASE') ?: 'mediai';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Unable to connect to database: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

foreach ($files as $file) {
    echo "Applying: " . basename($file) . PHP_EOL;
    $sql = file_get_contents($file);
    if ($sql === false) {
        continue;
    }
    $pdo->exec($sql);
}

echo "Migrations applied.\n";
