<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

$result = $mysqli->query("SELECT version, class FROM migrations WHERE version LIKE '2026-05%'");
echo "2FA migrations in DB:\n";
while ($row = $result->fetch_assoc()) {
    echo $row['version'] . " - " . $row['class'] . "\n";
}
if ($result->num_rows === 0) {
    echo "None found.\n";
}
$mysqli->close();
