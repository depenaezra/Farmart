<?php
$mysqli = new mysqli('localhost','root','','agriconnect');
$res = $mysqli->query("DESCRIBE migrations");
while($r = $res->fetch_assoc()) echo $r['Field']."\n";
$mysqli->close();
