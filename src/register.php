<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
error_reporting(E_ALL);
session_start();

header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");



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

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("CSRF token invalid or missing.");
}

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
            $error_message = "Eremu gutziak bete behar dira.";
        } else {
            $hashedPassword = md5($pasahitza);
    
            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
    
                $sql = "INSERT INTO erabiltzaileak 
                        (izen_abizen, nan, telefonoa, jaiotze_data, email, erabiltzaile_izena, pasahitza)
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

        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $error_message = "Errorea: NAN, email edo erabiltzaile izena dagoeneko erregistratuta dago.";
        } else {
            $error_message = "Errorea datu-basean: " . $e->getMessage();
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="icon" type="image/png" href="images/button.png">
    <link rel="stylesheet" href="assets/styleHas.css">
</head> 
<body>
    <?php
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    
    <form id="register_form" method="POST" action="register.php">
        <h1>Erabiltzailearen erregistroa</h1>   
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <?php if (!empty($error_message)): ?>
            <p style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <div><label for="izen_abizen">Izen Abizenak</label>
            <input type="text" id="izen_abizen" name="izen_abizen" required placeholder="Juan Perez">
            <span id="izen_abizen_errorea" class="error"></span>
        </div>

        <div><label for="nan">NAN</label>
            <input type="text" id="nan" name="nan" required placeholder="11111111-Z">
            <span id="nan_errorea" class="error"></span>
        </div>

        <div><label for="telefonoa">Telefonoa</label>
            <input type="tel" id="telefonoa" name="telefonoa" required placeholder="999999999">
            <span id="telefonoa_errorea" class="error"></span>
        </div>

        <div><label for="jaiotze_data">Jaiotze Data</label>
            <input type="text" id="jaiotze_data" name="jaiotze_data" required placeholder="YYYY-MM-DD">
            <span id="jaiotze_data_errorea" class="error"></span>
        </div>

        <div><label for="email">Emaila</label>
            <input type="email" id="email" name="email" required placeholder="email@adibidea.com">
            <span id="email_errorea" class="error"></span>
        </div>

        <div><label for="erabiltzaile_izena">Erabiltzaile Izena</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required placeholder="username123">
            <span id="erabiltzaile_izena_errorea" class="error"></span>
        </div>

        <div><label for="pasahitza">Pasahitza</label>
            <input type="password" id="pasahitza" name="pasahitza" required placeholder="******">
            <span id="pasahitza_errorea" class="error"></span>
        </div>

        <button type="submit" id="register_submit">Erregistratu</button>
    </form>

    <p id="login_link">Baduzu jada kontu bat? <a href="login.php">Saioa hasi hemen</a></p>
    <script src="assets/validation.js"></script>
</body>
</html>
