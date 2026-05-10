<?php
$mysqli = new mysqli('localhost','root','','agriconnect');
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM migrations");
echo "Total: " . $res->fetch_assoc()['cnt'] . "\n";
$res2 = $mysqli->query("SELECT version, class FROM migrations ORDER BY id");
while($r = $res2->fetch_assoc()) echo $r['version']." -> ".$r['class']."\n";