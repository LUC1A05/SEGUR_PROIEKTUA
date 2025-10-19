<?php
// Variables de estado inicial
$global_error = null;
$form_data = []; // Para rellenar campos después de un error, si lo deseas

// ------------------------------------------------------------------
// I. LÓGICA DE PROCESAMIENTO (Controlador)
// ------------------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger los datos del formulario
    $form_data = $_POST;
    
    // Aquí iría la validación backend (PHP) de todos los datos
    // *************************************************************
    // EJEMPLO de validación backend (AÑADIR MÁS VALIDACIONES AQUÍ):
    // if (strlen($form_data['pasahitza']) < 6) {
    //     $global_error = "Pasahitza sei karaktere baino gehiago izan behar du.";
    // }
    // *************************************************************
    
    // Si NO se detectan errores:
    if ($global_error === null) {
        
        // 1. Conectar a MariaDB
        // 2. Hash del password (OBLIGATORIO: password_hash())
        // 3. Insertar el nuevo usuario en la BBDD
        
        // 4. Redirigir al usuario (ejemplo: al login)
        // header("Location: /login");
        // exit();
        
    }
}

// ------------------------------------------------------------------
// II. CARGA DE LA VISTA (Si es GET o si hay errores en POST)
// ------------------------------------------------------------------

// Definir la ruta correcta de la plantilla (IMPORTANTE)
// __DIR__ es el directorio actual (src/), subimos un nivel (a /) y entramos en /templates
$template_path = __DIR__ . '/../templates/register_form.html';

// El include hace que PHP lea y procese el archivo HTML.
// Las variables PHP ($global_error, $form_data) están disponibles dentro del HTML.
if (file_exists($template_path)) {
    include $template_path;
} else {
    // Error si no se encuentra la plantilla
    http_response_code(500); 
    echo "Errorea: Ezin izan da plantilla aurkitu.";
}

?>