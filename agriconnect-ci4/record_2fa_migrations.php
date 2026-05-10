<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'agriconnect';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) die("Connect failed: " . $mysqli->connect_error);

// Get current max batch
$res = $mysqli->query("SELECT MAX(batch) as max_batch FROM migrations");
$row = $res->fetch_assoc();
$batch = ($row['max_batch'] ?: 0) + 1;

$now = time();
$migrations = [
    [
        'version' => '2026-05-08-000001',
        'class' => 'App\Database\Migrations\AddLoginSecurityTrackingToUsers',
        'group' => 'default',
        'namespace' => 'App',
        'time' => $now - 100,
        'batch' => $batch
    ],
    [
        'version' => '2026-05-08-000002',
        'class' => 'App\Database\Migrations\AddTwoFactorAuthToUsers',
        'group' => 'default',
        'namespace' => 'App',
        'time' => $now - 50,
        'batch' => $batch
    ],
    [
        'version' => '2026-05-08-000003',
        'class' => 'App\Database\Migrations\CreateTwoFactorAttemptsTable',
        'group' => 'default',
        'namespace' => 'App',
        'time' => $now,
        'batch' => $batch
    ],
];

$stmt = $mysqli->prepare("
    INSERT IGNORE INTO migrations (`version`, `class`, `group`, `namespace`, `time`, `batch`)
    VALUES (?, ?, ?, ?, ?, ?)
");

foreach ($migrations as $m) {
    $stmt->bind_param('ssssii', $m['version'], $m['class'], $m['group'], $m['namespace'], $m['time'], $m['batch']);
    if ($stmt->execute()) {
        echo "Recorded: {$m['version']} - {$m['class']}\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
}

$stmt->close();
$mysqli->close();
echo "Done.\n";
