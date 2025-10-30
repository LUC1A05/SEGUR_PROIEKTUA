<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

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

// Aldagaiak erroreak kudeatzeko
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $erabiltzaileIzena = htmlspecialchars(trim($_POST['erabiltzaile_izena'] ?? ''));
    $pasahitza = $_POST['pasahitza'] ?? '';
    
    if (empty($erabiltzaileIzena) || empty($pasahitza)) {
        $error_message = "Erabiltzaile izena eta pasahitza bete behar dira.";
    } else {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            
        // 1. Erabiltzailea bilatu
            $sql = "SELECT id, erabiltzaile_izena, pasahitza, izen_abizen FROM erabiltzaileak WHERE erabiltzaile_izena = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$erabiltzaileIzena]);
            $user = $stmt->fetch();
            
            if ($user && md5($pasahitza) === $user['pasahitza']) {
                
        // 2. Autentifikazioa arrakastatsua
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['erabiltzaile_izena'];
                $_SESSION['user_name'] = $user['izen_abizen']; // Izena eta abizena, ongi etorriko mezuetarako erabilgarria

        // Hasierara birbideratu
                header('Location: /');
                exit;
            } else {
                // 3. Autentifikazioan errorea
                $error_message = "Erabiltzaile izena edo pasahitza okerrak dira.";
            }
            
        } catch (PDOException $e) {
            $error_message = "Errorea datu basean: Ezin izan da saioa hasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="images/button.png">
    <link rel="stylesheet" href="assets/styleHas.css">
</head>
<body>
    
    <?php if (!empty($error_message)): ?>
        <p style="color:red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    
    <form id="login_form" method="POST" action="login.php">
        <h1>Saioa hasi</h1>
        <div>
            <label for="erabiltzaile_izena">Erabiltzaile Izena:</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required placeholder="User123">
        </div>

        <div>
            <label for="pasahitza">Pasahitza:</label>
            <input type="password" id="pasahitza" name="pasahitza" required placeholder="****">
        </div>

        <button type="submit" id="login_submit">Saioa hasi</button>
    </form>
     <p id="register_link">Ez duzu konturik? <a href="register.php">Erregistratu hemen</a></p>
</body>
</html>
