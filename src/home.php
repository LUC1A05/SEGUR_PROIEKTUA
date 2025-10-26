<?php
session_start();

// Verificar si el usuario está logeado (o recién registrado)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    // Si no está logeado, redirigir a la página de login
    header('Location: login.html');
    exit;
}

$nombreUsuario = $_SESSION['user_name'];
$idUsuario = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div style="padding: 40px; text-align: center;">
        <h1>Ongi etorri, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        <p>Zure erregistroa arrakastatsua izan da.</p>
        <p>Zure erabiltzaile IDa: <?php echo htmlspecialchars($idUsuario); ?></p>
        
        <br>
        <p><a href="logout.php">Saioa itxi</a></p>
    </div>
</body>
</html>