<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// logeatuta ez badago, login-era bideratu
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Saioa hasi behar duzu zure maskotak ikusteko.";
    header('Location: /login.php');
    exit;
}

// --- datu basearen konfigurazioa (PDO) ---
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
$user_id = $_SESSION['user_id'];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Maskoten lista atzitu
    $sql_list = "SELECT id, maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena, irudia 
                 FROM maskotak 
                 WHERE jabea_id = ?";
    
    $stmt_list = $pdo->prepare($sql_list);
    $stmt_list->execute([$user_id]);
    $maskotak = $stmt_list->fetchAll();
    
} catch (PDOException $e) {
    $error_message = "Errorea datu-basean: Ezin izan dira maskotak kargatu.";
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nire Maskotak</title>
    <link rel="stylesheet" href="assets/styleItems.css">
    
</head> 
<body>
    <header class="header">

        <p><a href="/">Hasiera</a> | <a href="/logout.php">Saioa itxi</a></p>
        
        <p><a href="/add_item.php" style="font-weight: bold;">+ Maskota Berria Erregistratu</a></p>
    
    </header>
    
    <?php if (!empty($error_message)): ?>
        <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        
    <div class="maskota-zerrenda">
        <h1>Nire Maskotak</h1>
        <?php if (empty($maskotak)): ?>
            <p>Oraindik ez duzu maskotarik erregistratu. Gehitu bat goiko estekatik.</p>
        <?php else: ?>
            <?php foreach ($maskotak as $maskota): ?>
                <div class="maskota-txartela">
                    <?php if (!empty($maskota['irudia'])): ?>
                        <div class="maskota-irudia">
                            <?php
                                $imgData = base64_encode($maskota['irudia']);
                                $src = 'data:image/jpeg;base64,' . $imgData;
                            ?>
                            <img src="<?php echo $src; ?>" alt="Irudia: <?php echo htmlspecialchars($maskota['maskotaren_izena']); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="maskota-info"> 
                        <h3><?php echo htmlspecialchars($maskota['maskotaren_izena']); ?></h3>
                        <p>
                            Espeziea: <b><?php echo htmlspecialchars($maskota['espeziea']); ?></b> | 
                            Arraza: <?php echo htmlspecialchars($maskota['arraza'] ?? 'Ez dago') ; ?> | 
                            Adina: <?php echo htmlspecialchars($maskota['adina'] ?? 'N/A'); ?>
                        </p>
                        <p style="font-size: 0.9em; color: #555;"><?php echo htmlspecialchars($maskota['deskribapena']); ?></p>

                        <p>
                            <a href="/modify_item.php?id=<?php echo $maskota['id']; ?>" style="color: blue; text-decoration: none;">
                            Aldatu
                            </a>
                            
                            <a href="/delete_item.php?id=<?php echo $maskota['id']; ?>" 
                            onclick="return confirm('Ziur zaude <?php echo htmlspecialchars($maskota['maskotaren_izena']); ?> ezabatu nahi duzula?');">
                            | Ezabatu
                            </a>
                        </p>
                    </div> 
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>