<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

$result = $mysqli->query("DESCRIBE users");
echo "Users table columns:\n";
printf("%-30s %-20s %s\n", "Field", "Type", "Null");
echo str_repeat("-", 70) . "\n";
while ($row = $result->fetch_assoc()) {
    printf("%-30s %-20s %s\n", $row['Field'], $row['Type'], $row['Null']);
}
$mysqli->close();
