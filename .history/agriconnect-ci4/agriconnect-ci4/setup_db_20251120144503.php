<?php
// Database setup script
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Select the database
    $pdo->exec("USE `$dbname`");

    // Read and execute the schema file
    $schema = file_get_contents('../src/ci4-application/database/schema.sql');

    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^(SET|DROP)/i', $statement)) {
            $pdo->exec($statement);
        }
    }

    echo "Database setup completed successfully!\n";

} catch (PDOException $e) {
    echo "Database setup failed: " . $e->getMessage() . "\n";
}