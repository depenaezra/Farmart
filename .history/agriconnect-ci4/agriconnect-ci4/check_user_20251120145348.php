<?php
// Check user script
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['juan.santos@example.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "User found:\n";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Status: " . $user['status'] . "\n";
        echo "Password hash: " . $user['password'] . "\n";

        // Test passwords
        $testPasswords = ['password', 'password123', 'Password123', 'admin123'];
        foreach ($testPasswords as $testPassword) {
            if (password_verify($testPassword, $user['password'])) {
                echo "Password verification for '$testPassword': SUCCESS\n";
                break;
            } else {
                echo "Password verification for '$testPassword': FAILED\n";
            }
        }
    } else {
        echo "User not found\n";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}