<?php
session_start();
require_once 'config/db.php';

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
