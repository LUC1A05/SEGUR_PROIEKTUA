<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// ---------------------------------------------
// 1. Sesioa egiaztatu
// ---------------------------------------------

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Saioa hasi behar duzu zure maskoten infomrazioa aldatzeko.";
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
$maskota_datuak = null;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errorea datu-basean: " . $e->getMessage());
}

// ---------------------------------------------
// 2. Kargatu Maskotaren Datuak
// ---------------------------------------------
$item_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// POST bidezko eskaerarekin, id-a lortu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

// 1. id baliozko bat jaso dela egiaztatu
if (!$item_id) {
    $_SESSION['error_message'] = "Ez da maskota ID baliozko bat jaso.";
    header('Location: /items.php');
    exit;
}

// Maskotaren datuak kargatu eta jabea egiaztatu
try {
    $sql_fetch = "SELECT maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena, irudia 
                  FROM maskotak 
                  WHERE id = ? AND jabea_id = ?";
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
// 3. Prozesatu Aldaketa (POST)
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $izena = htmlspecialchars(trim($_POST['maskotaren_izena'] ?? ''));
    $espeziea = htmlspecialchars(trim($_POST['espeziea'] ?? ''));
    $arraza = htmlspecialchars(trim($_POST['arraza'] ?? ''));
    $adina = (int)($_POST['adina'] ?? 0);
    $sexua = htmlspecialchars(trim($_POST['sexua'] ?? ''));
    $deskribapena = htmlspecialchars(trim($_POST['deskribapena'] ?? ''));

    $irudia_datuak = null;
    $irudia_kudeatu = false;

    if (isset($_POST['ezabatu_irudia']) && $_POST['ezabatu_irudia'] == '1') {
        $irudia_datuak = null;
        $irudia_kudeatu = true;
        
    } elseif (isset($_FILES['irudia_berria']) && $_FILES['irudia_berria']['error'] === UPLOAD_ERR_OK) {   
        $irudia_datuak = file_get_contents($_FILES['irudia_berria']['tmp_name']);
        $irudia_kudeatu = true;
    }

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
                           WHERE id = ? AND jabea_id = ?";
            
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

            if ($irudia_kudeatu) {
                
                $sql_img_update = "UPDATE maskotak SET irudia = ? WHERE id = ? AND jabea_id = ?";
                $stmt_img = $pdo->prepare($sql_img_update);
                
                $stmt_img->bindParam(1, $irudia_datuak, PDO::PARAM_LOB); 
                $stmt_img->bindParam(2, $item_id, PDO::PARAM_INT);
                $stmt_img->bindParam(3, $user_id, PDO::PARAM_INT);
                $stmt_img->execute();
            }
            
            $success_message = "Maskota arrakastaz aldatu da!";
            // Berriro kargatu datuak eguneratuta
            $maskota_datuak = [
                'maskotaren_izena' => $izena,
                'espeziea' => $espeziea,
                'arraza' => $arraza,
                'adina' => $adina,
                'sexua' => $sexua,
                'deskribapena' => $deskribapena
            ];
            header('Location: items.php'); //berbideratu animalien zerrendara
            exit;

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
    <link rel="stylesheet" href="assets/styleModifikazioa.css">
</head> 
<body>
    <header class="header">
        <p><a href="/items.php">Maskoten Zerrendara Itzuli</a></p>
    </header>

    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <p style="color: green; border: 1px solid green; padding: 10px;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
            
    <form id="modify_item_form" method="POST" action="/modify_item.php?id=<?php echo $item_id; ?>" enctype="multipart/form-data"> 
        <h1>Maskota Aldatu: <?php echo htmlspecialchars($maskota_datuak['maskotaren_izena'] ?? 'Ezezaguna'); ?></h1>
        
        <input type="hidden" name="id" value="<?php echo $item_id; ?>">

        <div>
            <label for="maskotaren_izena">Maskota Izena:</label>
            <input type="text" id="maskotaren_izena" name="maskotaren_izena" required 
                   value="<?php echo htmlspecialchars($maskota_datuak['maskotaren_izena'] ?? ''); ?>">
        </div>
        <div>
            <label for="espeziea">Espeziea:</label>
            <input type="text" id="espeziea" name="espeziea" required
                   value="<?php echo htmlspecialchars($maskota_datuak['espeziea'] ?? ''); ?>">
        </div>
        <div>
            <label for="arraza">Arraza (Aukerazkoa):</label>
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
                <option value="Arra" <?php echo ($maskota_datuak['sexua'] ?? '') == 'Arra' ? 'selected' : ''; ?>>Arra</option>
                <option value="Emea" <?php echo ($maskota_datuak['sexua'] ?? '') == 'Emea' ? 'selected' : ''; ?>>Emea</option>
            </select>
        </div>
        <div>
            <label for="deskribapena">Deskribapena:</label>
            <textarea id="deskribapena" name="deskribapena"><?php echo htmlspecialchars($maskota_datuak['deskribapena'] ?? ''); ?></textarea>
        </div>
        
    <fieldset>
        <?php if (!empty($maskota_datuak['irudia'])): ?>
            <div class="maskota-irudia">
                <?php
                    $imgData = base64_encode($maskota_datuak['irudia']);
                    $src = 'data:image/jpeg;base64,' . $imgData;
                ?>
                <img src="<?php echo $src; ?>" alt="Maskotaren uneko irudia" style="max-width: 100%; height: auto; display: block; margin: 10px auto; border-radius: 4px;">
            </div>
        <?php endif; ?>
            <legend>Irudiaren kudeaketa</legend>
            
            <?php if (isset($maskota_datuak['irudia']) && !empty($maskota_datuak['irudia'])): ?>
                <div>
                    <input type="checkbox" id="ezabatu_irudia" name="ezabatu_irudia" value="1">
                    <label for="ezabatu_irudia">Uneko irudia ezabatu</label>
                </div>
            <?php endif; ?>

            <div>
                <label for="irudia_berria">Irudi berria kargatu (.jpg):</label>
                <input type="file" id="irudia_berria" name="irudia_berria" accept="image/*">
                <p>Kargatu irudi berria edo markatu "Ezabatu" aurrekoa kentzeko.</p>
            </div>
        </fieldset>

        <button type="submit">Aldaketak Gorde</button>
    </form>

</body>
</html>
