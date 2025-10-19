document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('erregistro_formularioa');

    form.addEventListener('submit', function(event) {
        let isValid = true;

        document.querySelectorAll('.error').forEach(span => span.textContent = '');

        if (!validateNombreApellidos()) isValid = false;
        if (!validateNAN()) isValid = false;
        if (!validateTelefono()) isValid = false;
        if (!validateFecha()) isValid = false;
        if (!validateEmail()) isValid = false;
        
        if (!isValid) {
            event.preventDefault();
            console.log('Formulario no enviado. Errores de validación.');
        } else {
            console.log('Formulario válido. Enviando datos (a la espera del backend PHP)...');
        }
    });


    // 1. Izen abizenak: Solo texto
    function validateNombreApellidos() {
        const input = document.getElementById('izen_abizen');
        const value = input.value.trim();

        const regex = /^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/;

        if (!regex.test(value)) {
            document.getElementById('izen_abizen_errorea').textContent = 'Soilik testua bahimentzen da.';
            return false;
        }
        return true;
    }

    // 2. NAN: 11111111-Z formatua ETA letra zuzena
    function validateNAN() {
        const input = document.getElementById('nan');
        const value = input.value.toUpperCase().trim();
    
        const formatRegex = /^\d{8}-[A-Z]$/;
        
        if (!formatRegex.test(value)) {
            document.getElementById('nan_errorea').textContent = 'Formatu ez zuzena (ej: 11111111-Z).';
            return false;
        }
        
        const parts = value.split('-');
        const number = parseInt(parts[0], 10);
        const letter = parts[1];

        const validLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        const expectedLetter = validLetters.charAt(number % 23);

        if (expectedLetter !== letter) {
            document.getElementById('nan_errorea').textContent = 'NAN ez zuzena, letra ez da espro dena.';
            return false;
        }
        
        return true;
    }

    // 3. Telefonoa: 9 zenbaki soilik
    function validateTelefono() {
        const input = document.getElementById('telefonoa');
        const value = input.value.trim();

        const regex = /^\d{9}$/;

        if (!regex.test(value)) {
            document.getElementById('telefonoa_errorea').textContent = '9 zenbaki eduki behar ditu telefonoa.';
            return false;
        }
        return true;
    }

    // 4. Jaiotze data: uuuu-hh-ee formatuan
    function validateFecha() {
        const input = document.getElementById('jaiotze_data');
        const value = input.value.trim();

        const regex = /^\d{4}-\d{2}-\d{2}$/;

        if (!regex.test(value)) {
            document.getElementById('jaiotze_data_errorea').textContent = 'Formatu ez zuzena (YYYY-MM-DD).';
            return false;
        }
        
        const zatiak = value.split("-");
        const urtea = parseInt(zatiak[0], 10);
        const hilabetea = parseInt(zatiak[1], 10);
        const eguna = parseInt(zatiak[2], 10);

        if(hilabetea > 12 || eguna > 31){
            document.getElementById('jaiotze_data_errorea').textContent = 'Data ez zuzena. Ez da existitzen.'
        }
        // *Opcional: Se podrían añadir validaciones más complejas aquí (ej. si es una fecha real, o si no es futuro)
        return true;
    }

    // 5. Email: Formato estándar
    function validateEmail() {
        const input = document.getElementById('email');
        const value = input.value.trim();

        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regex.test(value)) {
            document.getElementById('email_errorea').textContent = 'Email zuzen bat sartu.';
            return false;
        }
        return true;
    }
});
