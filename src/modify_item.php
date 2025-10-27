<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// 1. Verificar Sesión
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Saioa hasi behar duzu zure maskotak aldatzeko.";
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
$maskota_datuak = null;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errorea datu-basean: " . $e->getMessage());
}

// ---------------------------------------------
// 2. Obtener el ID de la mascota y Cargar Datos
// ---------------------------------------------
$item_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Si se ha enviado el formulario, el ID viene por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

// Verificar ID válido
if (!$item_id) {
    $_SESSION['error_message'] = "Ez da maskota ID baliozko bat jaso.";
    header('Location: /items.php');
    exit;
}

// Cargar datos actuales de la mascota y verificar propiedad
try {
    $sql_fetch = "SELECT maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena 
                  FROM maskotak 
                  WHERE id = ? AND propietario_id = ?";
    $stmt_fetch = $pdo->prepare($sql_fetch);
    $stmt_fetch->execute([$item_id, $user_id]);
    $maskota_datuak = $stmt_fetch->fetch();

    if (!$maskota_datuak) {
        $_SESSION['error_message'] = "Maskota ez da aurkitu edo ez duzu baimenik hura aldatzeko.";
        header('Location: /items.php');
        exit;
    }

} catch (PDOException $e) {
    $error_message = "Errorea datuak kargatzean: " . $e->getMessage();
}


// ---------------------------------------------
// 3. Procesar la Modificación (POST)
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $izena = htmlspecialchars(trim($_POST['maskotaren_izena'] ?? ''));
    $espeziea = htmlspecialchars(trim($_POST['espeziea'] ?? ''));
    $arraza = htmlspecialchars(trim($_POST['arraza'] ?? ''));
    $adina = (int)($_POST['adina'] ?? 0);
    $sexua = htmlspecialchars(trim($_POST['sexua'] ?? ''));
    $deskribapena = htmlspecialchars(trim($_POST['deskribapena'] ?? ''));

    if (empty($izena) || empty($espeziea) || empty($sexua)) {
        $error_message = "Mascota (izena, espeziea, sexua) eremuak bete behar dira.";
    } else {
        try {
            $sql_update = "UPDATE maskotak SET 
                           maskotaren_izena = ?, 
                           espeziea = ?, 
                           arraza = ?, 
                           adina = ?, 
                           sexua = ?, 
                           deskribapena = ? 
                           WHERE id = ? AND propietario_id = ?";
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                $izena, 
                $espeziea, 
                $arraza, 
                $adina, 
                $sexua, 
                $deskribapena,
                $item_id,
                $user_id
            ]);
            
            $success_message = "Maskota arrakastaz aldatu da!";
            // Actualizar los datos del formulario después de la modificación exitosa
            $maskota_datuak = [
                'maskotaren_izena' => $izena,
                'espeziea' => $espeziea,
                'arraza' => $arraza,
                'adina' => $adina,
                'sexua' => $sexua,
                'deskribapena' => $deskribapena
            ];

        } catch (PDOException $e) {
            $error_message = "Errorea maskota aldatzean.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maskota Aldatu</title>
    <link rel="stylesheet" href="assets/styleHas.css">
</head> 
<body>

    <h1>Maskota Aldatu: <?php echo htmlspecialchars($maskota_datuak['maskotaren_izena'] ?? 'Ezezaguna'); ?></h1>
    <p><a href="/items.php">Maskoten Zerrendara Itzuli</a></p>

    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p style="color: green; border: 1px solid green; padding: 10px;"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form id="modify_item_form" method="POST" action="/modify_item.php?id=<?php echo $item_id; ?>"> 
        
        <input type="hidden" name="id" value="<?php echo $item_id; ?>">

        <div>
            <label for="maskotaren_izena">Mascota Izena:</label>
            <input type="text" id="maskotaren_izena" name="maskotaren_izena" required 
                   value="<?php echo htmlspecialchars($maskota_datuak['maskotaren_izena'] ?? ''); ?>">
        </div>
        <div>
            <label for="espeziea">Espeziea:</label>
            <input type="text" id="espeziea" name="espeziea" required
                   value="<?php echo htmlspecialchars($maskota_datuak['espeziea'] ?? ''); ?>">
        </div>
        <div>
            <label for="arraza">Arraza (Opcional):</label>
            <input type="text" id="arraza" name="arraza"
                   value="<?php echo htmlspecialchars($maskota_datuak['arraza'] ?? ''); ?>">
        </div>
        <div>
            <label for="adina">Adina (Urteak):</label>
            <input type="number" id="adina" name="adina" min="0"
                   value="<?php echo htmlspecialchars($maskota_datuak['adina'] ?? 0); ?>">
        </div>
        <div>
            <label for="sexua">Sexua:</label>
            <select id="sexua" name="sexua" required>
                <option value="">Aukeratu...</option>
                <option value="Arra" <?php echo ($maskota_datuak['sexua'] ?? '') == 'Arra' ? 'selected' : ''; ?>>Arra (Macho)</option>
                <option value="Emea" <?php echo ($maskota_datuak['sexua'] ?? '') == 'Emea' ? 'selected' : ''; ?>>Emea (Hembra)</option>
            </select>
        </div>
        <div>
            <label for="deskribapena">Deskribapena:</label>
            <textarea id="deskribapena" name="deskribapena"><?php echo htmlspecialchars($maskota_datuak['deskribapena'] ?? ''); ?></textarea>
        </div>

        <button type="submit">Aldaketak Gorde</button>
    </form>

</body>
</html>