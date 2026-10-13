<?php
/**
 * Remote Database Migration Script
 * Imports database/schema.sql and database/seed.sql into a remote MySQL database (e.g. Aiven)
 *
 * Usage:
 *   php scripts/migrate_remote.php <host> <port> <dbname> <user> <password> [ssl_ca_path]
 *
 * Or using environment variables:
 *   DB_HOST=... DB_PORT=... DB_NAME=... DB_USER=... DB_PASS=... php scripts/migrate_remote.php
 */

$host   = $argv[1] ?? getenv('DB_HOST') ?: '127.0.0.1';
$port   = $argv[2] ?? getenv('DB_PORT') ?: '3306';
$dbname = $argv[3] ?? getenv('DB_NAME') ?: 'defaultdb';
$user   = $argv[4] ?? getenv('DB_USER') ?: 'avnadmin';
$pass   = $argv[5] ?? getenv('DB_PASS') ?: '';
$sslCa  = $argv[6] ?? getenv('DB_SSL_CA') ?: null;

echo "====================================================\n";
echo "Remote MySQL Migration to: $host:$port ($dbname)\n";
echo "User: $user\n";
echo "====================================================\n\n";

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
];

// If connecting to cloud provider without local CA file, disable peer verification or use provided CA
if ($sslCa && file_exists($sslCa)) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
} else {
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
    echo "Connecting to remote database... ";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected successfully!\n\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

$schemaFile = __DIR__ . '/../database/schema.sql';
$seedFile   = __DIR__ . '/../database/seed.sql';

if (!file_exists($schemaFile)) {
    die("Error: schema.sql not found at $schemaFile\n");
}

echo "Applying schema (database/schema.sql)... ";
$schemaSql = file_get_contents($schemaFile);
try {
    $pdo->exec($schemaSql);
    echo "Done!\n";
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage() . "\n";
    exit(1);
}

if (file_exists($seedFile)) {
    echo "Applying seed data (database/seed.sql)... ";
    $seedSql = file_get_contents($seedFile);
    try {
        $pdo->exec($seedSql);
        echo "Done!\n";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// Verification
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "\nMigration complete! Total tables in $dbname: " . count($tables) . "\n";
foreach ($tables as $table) {
    echo " - $table\n";
}
echo "\nAll done! Refresh your live Render site.\n";
