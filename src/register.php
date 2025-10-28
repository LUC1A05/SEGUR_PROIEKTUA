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
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/u", $izenAbizen)) {
        $error_message = "Izen-abizenak ez dira baliozkoak. Letra bakarrik eta espazioak onartzen dira.";
    } elseif (!preg_match("/^\d{8}-[A-Z]$/", strtoupper($nan))) {
        $error_message = "NAN ez da baliozkoa. Formatoa: 12345678-Z";
    } elseif (!preg_match("/^\d{9}$/", $telefonoa)) {
        $error_message = "Telefonoa ez da baliozkoa. 9 digitu behar ditu.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email ez da baliozkoa.";
    }

    if (empty($error_message)) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);

            $hashedPassword = md5($pasahitza); // o password_hash
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
    <link rel="stylesheet" href="assets/styleHas.css">
</head> 
<body>

    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form id="register_form" method="POST" action="register.php">
        <h1>Erabiltzailearen erregistroa</h1>   

        <div><label for="izen_abizen">Izen Abizenak</label>
            <input type="text" id="izen_abizen" name="izen_abizen" required placeholder="Juan Perez" value="<?php echo htmlspecialchars($_SESSION['form_data']['izen_abizen'] ?? ''); ?>">
        </div>

        <div><label for="nan">NAN</label>
            <input type="text" id="nan" name="nan" required placeholder="11111111-Z" value="<?php echo htmlspecialchars($_SESSION['form_data']['nan'] ?? ''); ?>">
        </div>

        <div><label for="telefonoa">Telefonoa</label>
            <input type="tel" id="telefonoa" name="telefonoa" required placeholder="999999999" value="<?php echo htmlspecialchars($_SESSION['form_data']['telefonoa'] ?? ''); ?>">
        </div>

        <div><label for="jaiotze_data">Jaiotze Data</label>
            <input type="text" id="jaiotze_data" name="jaiotze_data" required placeholder="YYYY-MM-DD" value="<?php echo htmlspecialchars($_SESSION['form_data']['jaiotze_data'] ?? ''); ?>">
        </div>

        <div><label for="email">Emaila</label>
            <input type="email" id="email" name="email" required placeholder="email@adibidea.com" value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>">
        </div>

        <div><label for="erabiltzaile_izena">Erabiltzaile Izena</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required placeholder="username123" value="<?php echo htmlspecialchars($_SESSION['form_data']['erabiltzaile_izena'] ?? ''); ?>">
        </div>

        <div><label for="pasahitza">Pasahitza</label>
            <input type="password" id="pasahitza" name="pasahitza" required placeholder="******">
        </div>

        <button type="submit" id="register_submit">Erregistratu</button>
    </form>

    <p id="login_link">Baduzu jada kontu bat? <a href="login.php">Saioa hasi hemen</a></p>
</body>
</html>
