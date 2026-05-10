<?php
$mysqli = new mysqli('localhost','root','','agriconnect');
if ($mysqli->connect_error) die("Connect error: ".$mysqli->connect_error);

// Get max batch
$maxBatch = $mysqli->query("SELECT IFNULL(MAX(batch),0) FROM migrations")->fetch_array()[0];
$batch = $maxBatch + 1;
$now = time();

$inserts = [
    ['2026-05-08-000001', 'App\Database\Migrations\AddLoginSecurityTrackingToUsers'],
    ['2026-05-08-000002', 'App\Database\Migrations\AddTwoFactorAuthToUsers'],
    ['2026-05-08-000003', 'App\Database\Migrations\CreateTwoFactorAttemptsTable'],
];

foreach ($inserts as $d) {
    $stmt = $mysqli->prepare("
        INSERT IGNORE INTO migrations (`version`, `class`, `group`, `namespace`, `time`, `batch`)
        VALUES (?, ?, 'default', 'App', ?, ?)
    ");
    $stmt->bind_param('ssii', $d[0], $d[1], $now, $batch);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Inserted: {$d[0]}\n";
        } else {
            echo "Already exists (ignored): {$d[0]}\n";
        }
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
    $stmt->close();
}

$mysqli->close();
echo "Done.\n";
