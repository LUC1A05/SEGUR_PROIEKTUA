<?php

$host = "db";           // docker-compose-n DB-aren izena
$erabiltzaile = "admin"; 
$pasahitza = "test";     
$base = "database";     


$konexioa = new mysqli($host, $erabiltzaile, $pasahitza, $base);


if ($konexioa->connect_error) {
    die("Ezin izan da konektatu: " . $conn->connect_error);
}


$konexioa->set_charset("utf8mb4");
?>
