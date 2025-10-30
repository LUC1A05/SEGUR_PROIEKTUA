<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['user_name'] : '';

$host='db';
$db='database';
$user='admin';
$pass='test';
$charset='utf8mb4';

$dsn="mysql:host=$host;dbname=$db;charset=$charset";
$options=[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$error_message = '';
$maskota_guztiak = [];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sql = "SELECT m.id, m.maskotaren_izena, m.espeziea, m.arraza, m.adina, m.sexua, m.deskribapena, m.irudia, e.izen_abizen, e.id AS jabea_id
            FROM maskotak m 
            JOIN erabiltzaileak e ON m.jabea_id = e.id";

    $stmt_guztiak = $pdo->query($sql);
    $maskota_guztiak = $stmt_guztiak->fetchAll();
} catch (PDOException $e) {
    $error_message = "Errorea datu-basean: Ezin izan dira maskotak kargatu.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/styleHome.css">
</head>
<body>
    <header class="header">
        <input type="checkbox" class="header_checkbox" id="open-menu">
        <label for="open-menu" class="header_open-nav-button" role="button">=</label>
        <div class="header_logo-container">
            <img src="images/button.png" alt="Logo" class="header_logo">
        </div>
        <nav class="header_nav">
            <ul class="header_nav-list">
                <li class="header_nav-item"><a href="/"> Hasiera</a></li>

                <?php if ($is_logged_in): ?>
                    <li class="header_nav-item"><a href="items.php"> Nire maskotak</a></li>
                    <li class="header_nav-item"><a href="add_item.php"> Maskota bat adoptatu</a></li>
                    <li class="header_nav-item"><a href="modify_user.php"> Nire datuak aldatu</a></li>
                <?php else: ?>
                    <li class="header_nav-item"><a href="login.php"> Saioa hasi</a></li>
                    <li class="header_nav-item"><a href="register.php"> Erregistratu</a></li>
                <?php endif; ?>

            </ul>
        </nav>
    </header>
    <main class="profile">
        <div class="profile_wrapper">
            <div class="image_container">
                <h1 id="welcome">Ongi Etorri Web Sistemara</h1>
                <?php if ($is_logged_in): ?>
                    <p>
                        Kaixo, <b><?php echo htmlspecialchars($username); ?></b>! Hasi zure maskotak kudeatzen goiko menua erabiliz.
                    </p>
                <?php else: ?>
                    <p>
                        Web Sistema hau erabiltzen hasteko, <b><a href="login.php">Saioa hasi</a></b> edo <b><a href="register.php">Erregistratu</a></b> mesedez.
                    </p>
                <?php endif; ?>
            </div>
            <?php if (!empty($error_message)): ?>
        <div class="maskota-zerrenda">
            <p style="color: red; border: 1px solid red; padding: 10px;"><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    <?php endif; ?>
        
    <div class="maskota-zerrenda">
        <h2>Komunitatearen Maskotak</h2>
        
        <?php if (empty($maskota_guztiak)): ?>
            <p>Oraindik ez dago maskotarik erregistratuta komunitatean.</p>
        <?php else: ?>
            <?php foreach ($maskota_guztiak as $maskota): ?>
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
                        <h3><a href="show_item.php?id=<?php echo $maskota['id']; ?>"><?php echo htmlspecialchars($maskota['maskotaren_izena']); ?></a></h3>
                        
                        <p style="margin-top: 10px; padding-top: 5px; border-top: 1px solid #eee;">
                            Jabea: <a href="show_user.php?id=<?php echo $maskota['jabea_id']; ?>"><b><?php echo htmlspecialchars($maskota['izen_abizen']); ?></b></a>
                        </p>
                    </div> 

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    </main>
</body>
</html>
