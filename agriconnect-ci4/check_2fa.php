<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

$result = $mysqli->query("SELECT * FROM migrations WHERE version LIKE '2026-05%'");
echo "2FA migrations:\n";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "None found.\n";
    // Show all
    $r2 = $mysqli->query("SELECT version, class FROM migrations");
    echo "\nAll:\n";
    while ($r = $r2->fetch_assoc()) {
        echo $r['version'] . " -> " . $r['class'] . "\n";
    }
}
$mysqli->close();
