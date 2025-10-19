<?php
session_start();
require_once 'config/db.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $erabiltzaile = $_POST['erabiltzaile_izena'] ?? '';
    $pasahitza = $_POST['pasahitza'] ?? '';

    if ($erabiltzaile && $pasahitza) {
        $stmt = $konexioa->prepare("SELECT id, izena, pasahitza FROM erabiltzaileak WHERE erabiltzailea = ?");
        $stmt->bind_param("s", $erabiltzaile);
        $stmt->execute();
        $erantzuna = $stmt->get_result();

        if ($fila = $erantzuna->fetch_assoc()) {
            // Verificar contraseña (MD5 para tu ejemplo, aunque lo ideal es password_hash)
            if ($fila['pasahitza'] === md5($pasahitza)) {
                $_SESSION['erabiltzailea'] = $erabiltzaile;
                $_SESSION['id'] = $fila['id'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Pasahitz okerra.";
            }
        } else {
            $error = "Ez da erabiltzailea aurkitu.";
        }
    } else {
        $error = "Bete atal guztiak.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Saioa hasi</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form id="login_form" method="POST" action="/login">
        <div>
            <label for="erabiltzaile_izena">Erabiltzaile Izena:</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required>
        </div>

        <div>
            <label for="pasahitza">Pasahitza:</label>
            <input type="password" id="pasahitza" name="pasahitza" required>
        </div>

        <button type="submit" id="login_submit">Saioa hasi</button>
    </form>
</body>
</html>
