<?php

$host = "db";           
$user = "admin";
$pass = "test"; 
$db   = "database";     
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Si la conexión falla, se detiene el script con un mensaje
    die("Ezin izan da konektatu datu-basera: " . $e->getMessage());
}
?>
