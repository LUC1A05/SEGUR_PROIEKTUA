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
    $_SESSION['form_data'] = $_POST;

    $izenAbizen = htmlspecialchars(trim($_POST['izen_abizen'] ?? ''));
    $nan = htmlspecialchars(trim($_POST['nan'] ?? ''));
    $telefonoa = htmlspecialchars(trim($_POST['telefonoa'] ?? ''));
    $jaiotzeData = htmlspecialchars(trim($_POST['jaiotze_data'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $erabiltzaileIzena = htmlspecialchars(trim($_POST['erabiltzaile_izena'] ?? ''));
    $pasahitza = $_POST['pasahitza'] ?? '';

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        $hashedPassword = password_hash($pasahitza, PASSWORD_DEFAULT);
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
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erregistroa</title>
    <link rel="stylesheet" href="assets/styleHas.css">
    <script src="assets/validation.js" defer></script>
</head>
<body>

    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form id="register_form" method="POST" action="register.php">
        <h1>Erabiltzailearen erregistroa</h1>   

        <div>
            <label for="izen_abizen">Izen Abizenak</label>
            <input type="text" id="izen_abizen" name="izen_abizen" required placeholder="Juan Perez" value="<?php echo htmlspecialchars($_SESSION['form_data']['izen_abizen'] ?? ''); ?>">
            <span class="error" id="izen_abizen_errorea" style="color:red"></span>
        </div>

        <div>
            <label for="nan">NAN</label>
            <input type="text" id="nan" name="nan" required placeholder="12345678-Z" value="<?php echo htmlspecialchars($_SESSION['form_data']['nan'] ?? ''); ?>">
            <span class="error" id="nan_errorea" style="color:red"></span>
        </div>

        <div>
            <label for="telefonoa">Telefonoa</label>
            <input type="tel" id="telefonoa" name="telefonoa" required placeholder="999999999" value="<?php echo htmlspecialchars($_SESSION['form_data']['telefonoa'] ?? ''); ?>">
            <span class="error" id="telefonoa_errorea" style="color:red"></span>
        </div>

        <div>
            <label for="jaiotze_data">Jaiotze Data</label>
            <input type="text" id="jaiotze_data" name="jaiotze_data" required placeholder="YYYY-MM-DD" value="<?php echo htmlspecialchars($_SESSION['form_data']['jaiotze_data'] ?? ''); ?>">
            <span class="error" id="jaiotze_data_errorea" style="color:red"></span>
        </div>

        <div>
            <label for="email">Emaila</label>
            <input type="email" id="email" name="email" required placeholder="email@adibidea.com" value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>">
            <span class="error" id="email_errorea" style="color:red"></span>
        </div>

        <div>
            <label for="erabiltzaile_izena">Erabiltzaile Izena</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required placeholder="username123" value="<?php echo htmlspecialchars($_SESSION['form_data']['erabiltzaile_izena'] ?? ''); ?>">
        </div>

        <div>
            <label for="pasahitza">Pasahitza</label>
            <input type="password" id="pasahitza" name="pasahitza" required placeholder="******">
        </div>

        <button type="submit" id="register_submit">Erregistratu</button>
    </form>

    <p id="login_link">Baduzu jada kontu bat? <a href="login.php">Saioa hasi hemen</a></p>
</body>
</html>
