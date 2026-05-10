<?php
// Create twofa_attempts table
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

echo "Connected.\n";

// Check if table exists
$result = $mysqli->query("SHOW TABLES LIKE 'twofa_attempts'");
if ($result->num_rows > 0) {
    echo "Table 'twofa_attempts' already exists.\n";
} else {
    $sql = "CREATE TABLE twofa_attempts (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT(11) UNSIGNED NOT NULL,
        ip_address VARCHAR(45) NULL,
        code_entered VARCHAR(20) NULL,
        success TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY idx_user_id (user_id),
        KEY idx_created_at (created_at),
        CONSTRAINT twofa_attempts_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    if ($mysqli->query($sql)) {
        echo "Created table: twofa_attempts\n";
    } else {
        echo "Error creating table: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
echo "Done.\n";
