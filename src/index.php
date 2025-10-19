<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Albaitaria - Home</title>
</head>
<body>
    <h1>Ongi etorri albaitariarenera!</h1>

    <?php if(isset($_SESSION['erabiltzailea'])): ?>
        <p>Kaixo, <?= htmlspecialchars($_SESSION['erabiltzailea']) ?>!</p>
        <ul>
            <li><a href="show_user.php?user=<?= $_SESSION['id'] ?>">Nire perfila kontsultatu</a></li>
            <li><a href="modify_user.php?user=<?= $_SESSION['id'] ?>">Nire datuak aldatu</a></li>
            <li><a href="items.php">Ikusi maskotak</a></li>
            <li><a href="add_item.php">Gehitu maskota</a></li>
            <li><a href="logout.php">Saioa itxi</a></li>
        </ul>
    <?php else: ?>
        <ul>
            <li><a href="register.php">Erregistroa</a></li>
            <li><a href="login.php">Saioa hasi</a></li>
            <li><a href="items.php">Ikusi maskotak</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>
