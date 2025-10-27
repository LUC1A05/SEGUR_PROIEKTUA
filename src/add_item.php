<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// --- CONFIGURACIÓN DE BASE DE DATOS (PDO) ---
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

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
catch (PDOException $e) {
    $error_message = "Errorea maskota erregistratzean: " . $e->getMessage();
}


// ---------------------------------------------
// Procesar Añadir Nueva Mascota (POST)
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $izena = htmlspecialchars(trim($_POST['maskotaren_izena'] ?? ''));
    $espeziea = htmlspecialchars(trim($_POST['espeziea'] ?? ''));
    $arraza = htmlspecialchars(trim($_POST['arraza'] ?? ''));
    $adina = (int)($_POST['adina'] ?? 0);
    $sexua = htmlspecialchars(trim($_POST['sexua'] ?? ''));
    $deskribapena = htmlspecialchars(trim($_POST['deskribapena'] ?? ''));

    if (empty($izena) || empty($espeziea) || empty($sexua)) {
        $error_message = "Maskota (izena, espeziea, sexua) eremuak bete behar dira.";
    } else {
        try {
            $sql = "INSERT INTO maskotak (maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena, jabe_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $izena, 
                $espeziea, 
                $arraza, 
                $adina, 
                $sexua, 
                $deskribapena,
                $user_id 
            ]);
            
            $success_message = "Maskota berria arrakastaz erregistratu da!";
            // Redirigir a la lista de items después del éxito
            header('Location: /items.php');
            exit;

        } catch (PDOException $e) {
            $error_message = "Errorea maskota erregistratzean.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maskota Berria</title>
    <link rel="stylesheet" href="assets/styleHas.css">
</head> 
<body>

    <h1>Maskota Berria Erregistratu</h1>
    <p><a href="/items.php">Maskoten Zerrendara Itzuli</a></p>

    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form id="add_item_form" method="POST" action="/add_item.php"> 
        <div>
            <label for="maskotaren_izena">Maskota Izena:</label>
            <input type="text" id="maskotaren_izena" name="maskotaren_izena" required>
        </div>
        <div>
            <label for="espeziea">Espeziea:</label>
            <input type="text" id="espeziea" name="espeziea" required>
        </div>
        <div>
            <label for="arraza">Arraza (Opcional):</label>
            <input type="text" id="arraza" name="arraza">
        </div>
        <div>
            <label for="adina">Adina (Urteak):</label>
            <input type="number" id="adina" name="adina" min="0">
        </div>
        <div>
            <label for="sexua">Sexua:</label>
            <select id="sexua" name="sexua" required>
                <option value="">Aukeratu...</option>
                <option value="Arra">Arra (Macho)</option>
                <option value="Emea">Emea (Hembra)</option>
            </select>
        </div>
        <div>
            <label for="deskribapena">Deskribapena:</label>
            <textarea id="deskribapena" name="deskribapena"></textarea>
        </div>

        <button type="submit">Gorde Maskota</button>
    </form>

</body>
</html>
