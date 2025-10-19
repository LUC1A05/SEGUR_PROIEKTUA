<?php
session_start();
require_once 'config/db.php';

// user parametroa jaso den egiaztatu
$erabId = $_GET['erabiltzaile'] ?? null;

if (!$erabId) {
    die("Erabiltzaile ID-a behar da.");
}

$stmt = $konexioa->prepare("SELECT id, izena, abizenak, nan, telefonoa, jaiotze_data, email FROM erabiltzaileak WHERE id = ?");
$stmt->bind_param("i", $erabId);
$stmt->execute();
$erantzuna = $stmt->get_result();

if ($fila = $erantzuna->fetch_assoc()):
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailearen informazioa</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Erabiltzailearen informazioa</h1>
    <ul>
        <li>Izena: <?= htmlspecialchars($fila['izena']) ?></li>
        <li>Abizenak: <?= htmlspecialchars($fila['abizenak']) ?></li>
        <li>NAN: <?= htmlspecialchars($fila['nan']) ?></li>
        <li>Telefonoa: <?= htmlspecialchars($fila['telefonoa']) ?></li>
        <li>Jaiotze Data: <?= htmlspecialchars($fila['jaiotze_data']) ?></li>
        <li>Email: <?= htmlspecialchars($fila['email']) ?></li>
    </ul>

    <p><a href="index.php">Itzuli hasierara</a></p>
</body>
</html>

<?php
else:
    echo "Ez da erabiltzailea aurkitu.";
endif;

$stmt->close();
$konexioa->close();
?>
