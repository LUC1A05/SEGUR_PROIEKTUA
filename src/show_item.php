<?php
session_start();
include 'db_connect.php'; 

if (!isset($_SESSION['user_id'])) {
    redirect('/login.php');
}

$user_id = $_SESSION['user_id'];
$item_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$maskota = null;

if (!$item_id) {
    $_SESSION['error_message'] = "Ez da maskota ID baliozko bat jaso.";
    redirect('/items.php');
}

try {
    // Seguridad: Verificar que la mascota pertenece al usuario logueado
    $sql = "SELECT * FROM maskotak WHERE id = ? AND propietario_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_id, $user_id]);
    $maskota = $stmt->fetch();

    if (!$maskota) {
        $_SESSION['error_message'] = "Maskota ez da aurkitu edo ez duzu baimenik ikusteko.";
        redirect('/items.php');
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
    <link rel="stylesheet" href="assets/styleHas.css">
</head>
<body>
    <h1>Maskotaren Xehetasunak: <?php echo htmlspecialchars($maskota['maskotaren_izena']); ?></h1>
    
    <p>
        <a href="/items.php">Maskoten Zerrendara Itzuli</a> | 
        <a href="/modify_item.php?id=<?php echo $item_id; ?>">Aldatu Maskota</a>
    </p>

    <div class="item-details">
        <p><strong>Izena:</strong> <?php echo htmlspecialchars($maskota['maskotaren_izena']); ?></p>
        <p><strong>Espeziea:</strong> <?php echo htmlspecialchars($maskota['espeziea']); ?></p>
        <p><strong>Arraza:</strong> <?php echo htmlspecialchars($maskota['arraza']); ?></p>
        <p><strong>Adina:</strong> <?php echo htmlspecialchars($maskota['adina']); ?> urte</p>
        <p><strong>Sexua:</strong> <?php echo htmlspecialchars($maskota['sexua']); ?></p>
        <p><strong>Deskribapena:</strong> <?php echo nl2br(htmlspecialchars($maskota['deskribapena'])); ?></p>
    </div>
</body>
</html>