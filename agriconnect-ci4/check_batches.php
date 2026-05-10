<?php
$mysqli = new mysqli('localhost','root','','agriconnect');
$res = $mysqli->query("SELECT version, batch FROM migrations ORDER BY batch");
while($r = $res->fetch_assoc()) echo $r['version']." -> batch ".$r['batch']."\n";
$mysqli->close();
