<?php
require __DIR__ . '/../vendor/autoload.php';

$enc = null;

try {
    // CodeIgniter bootstrap is required for service() to work.
    // When running standalone, we just instantiate CI4 app.
    $autoloader = \Config\Autoload::class;
    // If bootstrap is missing, this script will fail gracefully.
    $enc = service('encryption');
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo (is_object($enc) ? get_class($enc) : gettype($enc)) . PHP_EOL;

