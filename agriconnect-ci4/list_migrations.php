<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

$result = $mysqli->query("SELECT id, version, class, namespace, batch FROM migrations ORDER BY id");
echo "All migrations:\n";
printf("%-5s %-30s %-60s %-10s\n", "ID", "Version", "Class", "Batch");
while ($row = $result->fetch_assoc()) {
    printf("%-5s %-30s %-60s %-10s\n", $row['id'], $row['version'], substr($row['class'],0,60), $row['batch']);
}
$mysqli->close();
