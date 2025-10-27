<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['user_name'] : '';
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
                <li class="header_nav-item"><a href="register.php"> Hasiera</a></li>

                <?php if ($is_logged_in): ?>
                    <li class="header_nav-item"><a href="items.php"> Nire animaliak</a></li>
                    <li class="header_nav-item"><a href="add_item.php"> Animali bat adoptatu</a></li>
                    <li class="header_nav-item"><a href="modify_user"> Nire Datuak aldatu</a></li>
                <?php else: ?>
                    <li class="header_nav-item"><a href="items.php"> Nire animaliak</a></li>
                    <li class="header_nav-item"><a href="add_item.php"> Animali bat adoptatu</a></li>
                    <li class="header_nav-item"><a href="modify_user"> Nire Datuak aldatu</a></li>
                    <li class="header_nav-item"><a href="login.php"> Saioa hasi</a></li>
                    <li class="header_nav-item"><a href="register.php"> Erregistratu</a></li>
                <?php endif; ?>

            </ul>
        </nav>
    </header>
    <main class="profile">
        <div class="profile_wrapper">
            <div class="image_container">
                <!--emen nahi duguna jarri-->
                <h1>Ongi Etorri Web Sistemara</h1>
                <?php if ($is_logged_in): ?>
                    <p>
                        Kaixo, **<?php echo htmlspecialchars($username); ?>**! Hasi zure animaliak kudeatzen goiko menua erabiliz.
                    </p>
                <?php else: ?>
                    <p>
                        Web Sistema hau erabiltzen hasteko, **Saioa hasi** edo **Erregistratu** mesedez.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>