<?php
ini_set('display_errors', 0); 
ini_set('log_errors', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_only_cookies', 1);
header('Content-Type: application/json');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");

if (isset($_COOKIE[session_name()])) {
    setcookie(
        session_name(),
        session_id(),
        0,
        '/; samesite=Lax',
        '',    // dominio
        false, // secure
        true   // httponly
    );
}


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

$nan = $_GET['nan'] ?? '';
$response = ['available' => false];
$pdo = null;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Si la conexión falla, establecemos un mensaje de error claro en la respuesta
    $response['error'] = 'DB Connection Failed';
    // No salimos con die(), para que el JSON se devuelva correctamente
}

if (!empty($nan)) {
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        $sql = "SELECT COUNT(*) FROM erabiltzaileak WHERE nan = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nan]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $response['available'] = true; 
        } else {
            $response['available'] = false; 
        }

    } catch (PDOException $e) {
        $response['available'] = false;
    }
}

echo json_encode($response);
exit;
?>