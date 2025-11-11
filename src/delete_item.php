<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
error_reporting(E_ALL);
session_start();
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");

// Saioa hasita ez badago, login-era bideratu
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// --- datu basearen konfigurazioa ---
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

// 1. Baliozko id bat jaso dela egiaztatu
if (!$item_id) {
    $_SESSION['error_message'] = "Ez da maskota ID baliozko bat jaso.";
    header('Location: /items.php');
    exit;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 2. Ezabatzeko exekutatu
    $sql = "DELETE FROM maskotak WHERE id = ? AND jabea_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_id, $user_id]);
    
    // 3. Emaitzak kudeatu
    if ($stmt->rowCount() > 0) {
        $_SESSION['success_message'] = "Maskota arrakastaz ezabatu da.";
    } else {
        $_SESSION['error_message'] = "Ezin izan da maskota aurkitu edo ez duzu baimenik hura ezabatzeko.";
    }
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Errorea datu basean ezabatzean.";
}

// 4. Beti item-en zerrendara birbideratu
header('Location: /items.php');
exit;
?>
