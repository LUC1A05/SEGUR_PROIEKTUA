<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$host = 'db';
$db   = 'database';
$user = 'root';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error_message'] = '';
    $_SESSION['form_data'] = $_POST;
    
    $izenAbizen = htmlspecialchars(trim($_POST['izen_abizen'] ?? ''));
    $nan = htmlspecialchars(trim($_POST['nan'] ?? ''));
    $telefonoa = htmlspecialchars(trim($_POST['telefonoa'] ?? ''));
    $jaiotzeData = htmlspecialchars(trim($_POST['jaiotze_data'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $erabiltzaileIzena = htmlspecialchars(trim($_POST['erabiltzaile_izena'] ?? ''));
    $pasahitza = $_POST['pasahitza'] ?? '';
    
    if (empty($izenAbizen) || empty($nan) || empty($email) || empty($pasahitza)) {
        $_SESSION['error_message'] = "Todos los campos obligatorios deben ser completados.";
        header('Location: /register.php');
        exit;
    }
    
    $hashedPassword = password_hash($pasahitza, PASSWORD_DEFAULT);
    
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    
        $sql = "INSERT INTO usuarios (izen_abizen, nan, telefonoa, jaiotze_data, email, erabiltzaile_izena, pasahitza) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $izenAbizen, 
            $nan, 
            $telefonoa, 
            $jaiotzeData, 
            $email, 
            $erabiltzaileIzena, 
            $hashedPassword
        ]);
    
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $izenAbizen;
        
        unset($_SESSION['error_message']);
        unset($_SESSION['form_data']); 
    
        header('Location: home.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $_SESSION['error_message'] = "Errorea: NAN, email edo erabiltzaile izena dagoeneko erregistratuta dago.";
        } else {
            $_SESSION['error_message'] = "Errorea datu-basean: " . $e->getMessage();
        }
        header('Location: /src/register.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="assets/styleHas.css">
</head> 
<body>
    
    <form id="register_form" method="POST" action="/src/register.php">
        <h1>Erabiltzailearen erregistroa</h1>   

        <!-- Campos -->
        <div><label for="izen_abizen">Izen Abizenak</label>
            <input type="text" id="izen_abizen" name="izen_abizen" required placeholder="Juan Perez">
        </div>

        <div><label for="nan">NAN</label>
            <input type="text" id="nan" name="nan" required placeholder="11111111-Z">
        </div>

        <div><label for="telefonoa">Telefonoa</label>
            <input type="tel" id="telefonoa" name="telefonoa" required placeholder="999999999">
        </div>

        <div><label for="jaiotze_data">Jaiotze Data</label>
            <input type="text" id="jaiotze_data" name="jaiotze_data" required placeholder="YYYY-MM-DD">
        </div>

        <div><label for="email">Emaila</label>
            <input type="email" id="email" name="email" required placeholder="email@adibidea.com">
        </div>

        <div><label for="erabiltzaile_izena">Erabiltzaile Izena</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required placeholder="username123">
        </div>

        <div><label for="pasahitza">Pasahitza</label>
            <input type="password" id="pasahitza" name="pasahitza" required placeholder="******">
        </div>

        <button type="submit" id="register_submit">Erregistratu</button>
    </form>

    <p id="login_link">Baduzu jada kontu bat? <a href="login.html">Saioa hasi hemen</a></p>
</body>
</html>