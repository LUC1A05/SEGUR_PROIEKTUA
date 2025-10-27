<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// --- CONFIGURACIÓN DE BASE DE DATOS (PDO) ---
$host = 'db';
$db   = 'database';
$user = 'admin'; 
$pass = 'test';  
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$user_id = $_SESSION['user_id'];
$item_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// 1. Verificar que se recibió un ID válido
if (!$item_id) {
    $_SESSION['error_message'] = "Ez da maskota ID baliozko bat jaso.";
    header('Location: /items.php');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 2. Ejecutar la eliminación, VERIFICANDO QUE EL PROPIETARIO COINCIDA
    $sql = "DELETE FROM maskotak WHERE id = ? AND propietario_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_id, $user_id]);
    
    // 3. Verificar si se eliminó alguna fila
    if ($stmt->rowCount() > 0) {
        $_SESSION['success_message'] = "Maskota arrakastaz ezabatu da.";
    } else {
        // Esto puede pasar si el ID no existe O si el propietario_id no coincide
        $_SESSION['error_message'] = "Ezin izan da maskota aurkitu edo ez duzu baimenik hura ezabatzeko.";
    }
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Errorea datu-basean ezabatzean.";
}

// 4. Redirigir siempre a la lista de items
header('Location: /items.php');
exit;
?>