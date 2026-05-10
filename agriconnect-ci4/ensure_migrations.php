<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

// Get max batch
$max = $mysqli->query("SELECT MAX(batch) as m FROM migrations")->fetch_assoc()['m'] ?? 0;
$batch = $max + 1;

$now = time();
$new = [
    ['2026-05-08-000001', 'App\Database\Migrations\AddLoginSecurityTrackingToUsers'],
    ['2026-05-08-000002', 'App\Database\Migrations\AddTwoFactorAuthToUsers'],
    ['2026-05-08-000003', 'App\Database\Migrations\CreateTwoFactorAttemptsTable'],
];

$stmt = $mysqli->prepare("
    INSERT INTO migrations (`version`, `class`, `group`, `namespace`, `time`, `batch`)
    VALUES (?, ?, 'default', 'App', ?, ?)
    ON DUPLICATE KEY UPDATE time=VALUES(time), batch=VALUES(batch)
");

foreach ($new as $v) {
    $stmt->bind_param('ssii', $v[0], $v[1], $now, $batch);
    $stmt->execute();
    echo "Ensured: {$v[0]}\n";
}
$stmt->close();
$mysqli->close();
echo "Done.\n";
