<?php
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //TODO
//}
// POST ez bada edo errorerik badaude, HTML-a erakuzten da
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

    <form id="erregistro_formularioa" method="POST" action="/register">

        <div>
            <label for="izen_abizen">Izen Abizenak:</label>
            <input type="text" id="izen_abizen" required>
            <span class="error" id="izen_abizen_errorea"></span>
        </div>

        <div>
            <label for="nan">NAN:</label>
            <input type="text" id="nan" required>
            <span class="error" id="nan_errorea"></span>
        </div>

        <div>
            <label for="telefonoa">Telefonoa:</label>
            <input type="tel" id="telefonoa" required>
            <span class="error" id="telefonoa_errorea"></span>
        </div>

        <div>
            <label for="jaiotze_data">Jaiotze Data (YYYY-MM-DD):</label>
            <input type="text" id="jaiotze_data" required>
            <span class="error" id="jaiotze_data_errorea"></span>
        </div>

        <div>
            <label for="email">Emaila:</label>
            <input type="email" id="email" required>
            <span class="error" id="email_errorea"></span>
        </div>

        <div>
            <label for="erabiltzaile_izena">Erabiltzaile Izena:</label>
            <input type="text" id="erabiltzaile_izena" required>
            <span class="error" id="erabiltzaile_izena_errorea"></span>
        </div>

        <div>
            <label for="pasahitza">Pasahitza:</label>
            <input type="password" id="pasahitza" required>
        </div>

        <button type="submit" id="erregistro_botoia">Erregistratu</button>
    </form>

    <script src="assets/validation.js"></script>
</body>
</html>