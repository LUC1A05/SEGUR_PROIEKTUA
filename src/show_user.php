<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
session_start();
require_once 'config/db.php';
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");



// user parametroa jaso den egiaztatu
$erabId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$erabId) {
    header('Location: index.php');
    exit;
}

$fila = null;
try {
    $sql = "SELECT id, izen_abizen, nan, telefonoa, jaiotze_data, email, erabiltzaile_izena FROM erabiltzaileak WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$erabId]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Errorea datu-basean.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailearen informazioa</title>
    <link rel="icon" type="image/png" href="images/button.png">
    <link rel="stylesheet" href="assets/styleShow.css">
</head>
<body>
    <header class="header">
        <p><a href="/">Hasiera</a></p>
    </header>

    <div class="fitxa">
        <h1>Erabiltzailearen informazioa: <br> <?= htmlspecialchars($fila['izen_abizen']) ?></h1>
        <div class="item-details">
            <p>Izen Abizenak: <?= htmlspecialchars($fila['izen_abizen']) ?></p>
            <p>NAN: <?= htmlspecialchars($fila['nan']) ?></p>
            <p>Telefonoa: <?= htmlspecialchars($fila['telefonoa']) ?></p>
            <p>Jaiotze Data: <?= htmlspecialchars($fila['jaiotze_data']) ?></p>
            <p>Email: <?= htmlspecialchars($fila['email']) ?></p>
            <p>Erabiltzaile izena: <?= htmlspecialchars($fila['erabiltzaile_izena']) ?></p>
        </div>
    </div>
</body>
</html>
