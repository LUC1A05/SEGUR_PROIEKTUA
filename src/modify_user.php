<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_only_cookies', 1);
error_reporting(E_ALL);
session_start();
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");

if (isset($_COOKIE[session_name()])) {
    setcookie(
        session_name(),
        session_id(),
        0,
        '/; samesite=Lax',
        '',    // dominio
        false, // secure
        true   // httponly
    );
}

// ---------------------------------------------
// 1. Saioa hasi dela egiaztatu
// ---------------------------------------------
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Saioa hasi behar duzu zure profila aldatzeko.";
    header('Location: /login.php');
    exit;
}

// --- datu basearen konfigurazioa ---
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

$error_message = '';
$success_message = '';
$user_id = $_SESSION['user_id'];
$user_data = null;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errorea datu-basean: " . $e->getMessage());
}

// ---------------------------------------------
// 2. Erabiltzailearen Datuak Kargatu
// ---------------------------------------------
try {
    $sql_fetch = "SELECT izen_abizen, nan, telefonoa, jaiotze_data, email, erabiltzaile_izena 
                  FROM erabiltzaileak
                  WHERE id = ?";
    $stmt_fetch = $pdo->prepare($sql_fetch);
    $stmt_fetch->execute([$user_id]);
    $user_data = $stmt_fetch->fetch();

    if (!$user_data) {
        session_destroy();
        header('Location: /login.php');
        exit;
    }

} catch (PDOException $e) {
    $error_message = "Errorea datuak kargatzerakoan.";
}

// ---------------------------------------------
// 3. Prozesatu Aldaketa 
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $izen_abizen = htmlspecialchars(trim($_POST['izen_abizen'] ?? ''));
    $nan = htmlspecialchars(trim($_POST['nan'] ?? ''));
    $telefonoa = htmlspecialchars(trim($_POST['telefonoa'] ?? ''));
    $jaiotze_data = htmlspecialchars(trim($_POST['jaiotze_data'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $erabiltzaile_izena = htmlspecialchars(trim($_POST['erabiltzaile_izena'] ?? ''));
    $pasahitza_berria = $_POST['pasahitza_berria'] ?? '';
    $pasahitza_konfirmatu = $_POST['pasahitza_konfirmatu'] ?? '';

    if (empty($izen_abizen) || empty($nan) || empty($email) || empty($erabiltzaile_izena)) {
        $error_message = "Izen-abizenak, NANa, e-maila eta erabiltzaile izena derrigorrezkoak dira.";
    } elseif ($pasahitza_berria != $pasahitza_konfirmatu) {
        $error_message = "Pasahitz berriak ez datoz bat.";
    } else {
        $set_parts = [];
        $params = [];

        // --- 3.1. Balidatu (Email eta Username) ---
        $sql_check = "SELECT id FROM erabiltzaileak WHERE (email = ? OR erabiltzaile_izena = ?) AND id != ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$email, $erabiltzaile_izena, $user_id]);
        
        if ($stmt_check->fetch()) {
            $error_message = "E-mail edo erabiltzaile izen hori dagoeneko erabilita dago.";
        } else {
            // --- 3.2. UPDATE kontsulta ---
            $set_parts[] = "izen_abizen = ?"; $params[] = $izen_abizen;
            $set_parts[] = "nan = ?"; $params[] = $nan;
            $set_parts[] = "telefonoa = ?"; $params[] = $telefonoa;
            $set_parts[] = "jaiotze_data = ?"; $params[] = $jaiotze_data;
            $set_parts[] = "email = ?"; $params[] = $email;
            $set_parts[] = "erabiltzaile_izena = ?"; $params[] = $erabiltzaile_izena;

            // Pasahitza prozesatu, ematen bada
            if (!empty($pasahitza_berria)) {
                $hashed_password = MD5($pasahitza_berria);
                $set_parts[] = "pasahitza = ?"; $params[] = $hashed_password;
            }

            // --- 3.3. Update-a exekutatu ---
            $sql_update = "UPDATE erabiltzaileak SET " . implode(', ', $set_parts) . " WHERE id = ?";
            $params[] = $user_id; // Añadir el ID al final

            try {
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->execute($params);
                
                $success_message = "Profila arrakastaz eguneratu da!";
                
                $_SESSION['username'] = $erabiltzaile_izena;
                $_SESSION['user_name'] = $izen_abizen;
                
                
                $user_data = [
                    'izen_abizen' => $izen_abizen, 'nan' => $nan, 'telefonoa' => $telefonoa, 
                    'jaiotze_data' => $jaiotze_data, 'email' => $email, 
                    'erabiltzaile_izena' => $erabiltzaile_izena
                ];

            } catch (PDOException $e) {
                $error_message = "Errorea profila eguneratzean. Saiatu berriro.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Profila Aldatu</title>
    <link rel="icon" type="image/png" href="images/button.png">
    <link rel="stylesheet" href="assets/styleModifikazioa.css">
</head> 
<body>

    <header class="header">
        <p><a href="/">Hasiera</a></p>
        <p><a href="/items.php">Nire Maskotak</a></p>
    </header>
    
    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <p style="color: green; border: 1px solid green; padding: 10px;"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            
        <form id="modify_user_form" method="POST" action="/modify_user.php"> 
        <h1>Nire Profila Aldatu</h1>
        <div>
            <label for="izen_abizen">Izen-Abizenak:</label>
            <input type="text" id="izen_abizen" name="izen_abizen" required 
                   value="<?php echo htmlspecialchars($user_data['izen_abizen'] ?? ''); ?>">
        </div>
        <div>
            <label for="nan">NAN:</label>
            <input type="text" id="nan" name="nan" required 
                   value="<?php echo htmlspecialchars($user_data['nan'] ?? ''); ?>">
        </div>
        <div>
            <label for="telefonoa">Telefonoa:</label>
            <input type="text" id="telefonoa" name="telefonoa" 
                   value="<?php echo htmlspecialchars($user_data['telefonoa'] ?? ''); ?>">
        </div>
        <div>
            <label for="jaiotze_data">Jaiotze Data:</label>
            <input type="date" id="jaiotze_data" name="jaiotze_data" 
                   value="<?php echo htmlspecialchars($user_data['jaiotze_data'] ?? ''); ?>">
        </div>
        <div>
            <label for="email">E-maila:</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>">
        </div>
        <div>
            <label for="erabiltzaile_izena">Erabiltzaile Izena:</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required 
                   value="<?php echo htmlspecialchars($user_data['erabiltzaile_izena'] ?? ''); ?>">
        </div>
        <hr>
        <h3>Pasahitza aldatu (aukerakoa)</h3>
        <div>
            <label for="pasahitza_berria">Pasahitz Berria:</label>
            <input type="password" id="pasahitza_berria" name="pasahitza_berria" placeholder="****">
        </div>
        <div>
            <label for="pasahitza_konfirmatu">Pasahitz Berria Konfirmatu: </label>
            <input type="password" id="pasahitza_konfirmatu" name="pasahitza_konfirmatu" placeholder="****">
        </div>

        <button type="submit">Aldaketak Gorde</button>
    </form>

</body>
</html>
