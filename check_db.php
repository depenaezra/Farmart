<?php
require 'app/Config/Database.php';

$config = new \Config\Database();
$db = \Config\Database::connect();

try {
    $products = $db->table('products')->countAll();
    echo "Products: $products\n";

    $users = $db->table('users')->countAll();
    echo "Users: $users\n";

    $posts = $db->table('forum_posts')->countAll();
    echo "Forum posts: $posts\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}