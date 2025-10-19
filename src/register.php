<?php
// register.php: Procesamiento y carga de la vista

$global_error = null;
$form_data = [];

// Si el formulario se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_data = $_POST;

    // TODO: Validación backend de los campos
    // EJEMPLO:
    // if (strlen($form_data['pasahitza']) < 6) {
    //     $global_error = "Pasahitza sei karaktere baino gehiago izan behar du.";
    // }

    // Si no hay errores, se podría insertar en DB y redirigir al login
    if ($global_error === null) {
        // 1. Conectar a MariaDB
        // 2. Hashear password: password_hash($form_data['pasahitza'], PASSWORD_DEFAULT)
        // 3. Insertar en DB
        // 4. Redirigir al login: header("Location: /login"); exit();
    }
}

// Si hay errores o no es POST, se muestra el HTML del formulario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="assets/style.css">
</head> 
<body>
    <h1>Erabiltzailearen erregistroa</h1>   

    <?php if ($global_error): ?>
        <p class="error"><?= htmlspecialchars($global_error) ?></p>
    <?php endif; ?>

    <form id="erregistro_formularioa" method="POST" action="/register">

        <div>
            <label for="izen_abizen">Izen Abizenak:</label>
            <input type="text" id="izen_abizen" name="izen_abizen" required>
            <span class="error" id="izen_abizen_errorea"></span>
        </div>

        <div>
            <label for="nan">NAN:</label>
            <input type="text" id="nan" name="nan" required>
            <span class="error" id="nan_errorea"></span>
        </div>

        <div>
            <label for="telefonoa">Telefonoa:</label>
            <input type="tel" id="telefonoa" name="telefonoa" required>
            <span class="error" id="telefonoa_errorea"></span>
        </div>

        <div>
            <label for="jaiotze_data">Jaiotze Data (YYYY-MM-DD):</label>
            <input type="text" id="jaiotze_data" name="jaiotze_data" required>
            <span class="error" id="jaiotze_data_errorea"></span>
        </div>

        <div>
            <label for="email">Emaila:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email_errorea"></span>
        </div>

        <div>
            <label for="erabiltzaile_izena">Erabiltzaile Izena:</label>
            <input type="text" id="erabiltzaile_izena" name="erabiltzaile_izena" required>
            <span class="error" id="erabiltzaile_izena_errorea"></span>
        </div>

        <div>
            <label for="pasahitza">Pasahitza:</label>
            <input type="password" id="pasahitza" name="pasahitza" required>
        </div>

        <button type="submit" id="erregistro_botoia">Erregistratu</button>
    </form>

    <script src="assets/validation.js"></script>
</body>
</html>
