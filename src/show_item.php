<?php
session_start();
include 'config/db.php'; 

function redirect($url) {
    header("Location: $url");
    exit;
}

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

$item_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$maskota = null;

if (!$item_id) {
    $_SESSION['error_message'] = "Ez da maskota ID baliozko bat jaso.";
    redirect('/index.php');
}

try {
    $sql = "SELECT m.* FROM maskotak m JOIN erabiltzaileak e ON m.jabea_id = e.id WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_id]);
    $maskota = $stmt->fetch();

    if (!$maskota) {
        $_SESSION['error_message'] = "Maskota ez da aurkitu.";
        redirect('/index.php');
    }
    
} catch (PDOException $e) {
    die("Errorea datu-basean.");
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Maskotaren Xehetasunak</title>
    <link rel="icon" type="image/png" href="images/button.png">
    <link rel="stylesheet" href="assets/styleShow.css">
</head>
<body>
    <header class="header">
        <p><a href="/">Hasiera</a></p>
    </header>
    <div class="fitxa">
        <h1>Maskotaren Xehetasunak: <br><?php echo htmlspecialchars($maskota['maskotaren_izena']); ?></h1>
        <div class="item-details">
            <p><strong>Izena:</strong> <?php echo htmlspecialchars($maskota['maskotaren_izena']); ?></p>
            <p><strong>Espeziea:</strong> <?php echo htmlspecialchars($maskota['espeziea']); ?></p>
            <p><strong>Arraza:</strong> <?php echo htmlspecialchars($maskota['arraza']); ?></p>
            <p><strong>Adina:</strong> <?php echo htmlspecialchars($maskota['adina']); ?> urte</p>
            <p><strong>Sexua:</strong> <?php echo htmlspecialchars($maskota['sexua']); ?></p>
            <p id="deskribapena"><strong>Deskribapena:</strong> <?php echo nl2br(htmlspecialchars($maskota['deskribapena'])); ?></p>
        </div>
    </div>
</body>
</html>
