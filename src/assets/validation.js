document.addEventListener('DOMContentLoaded', function() {
    const inprimakia = document.getElementById('register_form');

    inprimakia.addEventListener('submit', async function(event) {
        event.preventDefault();
        let baliozko = true;

        // Aurreko erroreak garbitu
        document.querySelectorAll('.error').forEach(span => span.textContent = '');

        if (!balioztatuIzenAbizenak()) baliozko = false;
        if (!balioztatuNAN()) baliozko = false;
        if (!balioztatuTelefonoa()) baliozko = false;
        if (!balioztatuJaiotzeData()) baliozko = false;
        if (!balioztatuEmaila()) baliozko = false;

        const erabilgarri = await egiaztatuErabiltzaileIzena();
        if (!erabilgarri) baliozko = false;
        const nanErabilgarri = await egiaztatuNAN();
        if (!nanErabilgarri) baliozko = false;
        
        if (!baliozko) {
            event.preventDefault();
            console.log('Formularioa ez da bidali. Balioztatze erroreak.');
        } else {
            console.log('Formularioa baliozkoa. Datuak bidaltzen...');
            inprimakia.submit();
        }
    });

    // 1. Izen abizenak: letra eta espazio bakarrik
    function balioztatuIzenAbizenak() {
        const sarrera = document.getElementById('izen_abizen');
        const balioa = sarrera.value.trim();
        const regex = /^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/;

        if (!regex.test(balioa)) {
            erakutsiErrorea('izen_abizen_errorea', 'Letra eta espazio bakarrik onartzen dira.');
            return false;
        }
        return true;
    }

    // 2. NAN: 12345678-Z formatoa eta letra zuzena
    function balioztatuNAN() {
        const sarrera = document.getElementById('nan');
        const balioa = sarrera.value.toUpperCase().trim();

        const formatuaRegex = /^\d{8}-[A-Z]$/;
        if (!formatuaRegex.test(balioa)) {
            erakutsiErrorea('nan_errorea', 'Formatoa okerra (adib: 12345678-Z).');
            return false;
        }

        const [zenbakiaStr, letra] = balioa.split('-');
        const zenbakia = parseInt(zenbakiaStr, 10);
        const letrakBaliozkoak = "TRWAGMYFPDXBNJZSQVHLCKE";
        const letraEspero = letrakBaliozkoak[zenbakia % 23];

        if (letra !== letraEspero) {
            erakutsiErrorea('nan_errorea', 'NAN-aren letra ez da zuzena.');
            return false;
        }

        return true;
    }

    // 3. Telefonoa
    function balioztatuTelefonoa() {
        const sarrera = document.getElementById('telefonoa');
        const balioa = sarrera.value.trim();
        const regex = /^\d{9}$/;

        if (!regex.test(balioa)) {
            erakutsiErrorea('telefonoa_errorea', 'Telefonoak 9 digitu izan behar ditu.');
            return false;
        }
        return true;
    }

    // 4. Jaiotze data
    function balioztatuJaiotzeData() {
        const sarrera = document.getElementById('jaiotze_data');
        const balioa = sarrera.value.trim();
        const regex = /^\d{4}-\d{2}-\d{2}$/;

        if (!regex.test(balioa)) {
            erakutsiErrorea('jaiotze_data_errorea', 'Egitura ez da egokia (YYYY-MM-DD).');
            return false;
        }

        const [urtea, hila, eguna] = balioa.split('-').map(Number);
        const data = new Date(urtea, hila - 1, eguna);

        if (data.getFullYear() !== urtea || data.getMonth() + 1 !== hila || data.getDate() !== eguna) {
            erakutsiErrorea('jaiotze_data_errorea', 'Ez da baliozkoa.');
            return false;
        }
        return true;
    }

    // 5. Emaila
    function balioztatuEmaila() {
        const sarrera = document.getElementById('email');
        const balioa = sarrera.value.trim();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regex.test(balioa)) {
            erakutsiErrorea('email_errorea', 'Email ez da baliozkoa.');
            return false;
        }
        return true;
    }

    async function egiaztatuErabiltzaileIzena() {
        const sarrera = document.getElementById('erabiltzaile_izena');
        const izena = sarrera.value.trim();

        if (izena === '') {
            erakutsiErrorea('erabiltzaile_izena_errorea', 'Erabiltzaile izena ezin da hutsik egon.');
            return false;
        }

        try {
            const erantzuna = await fetch(`assets/checkErabiltzailea.php?erabiltzaile_izena=${encodeURIComponent(izena)}`);
            const data = await erantzuna.json();

            if (!data.available){
                erakutsiErrorea('erabiltzaile_izena_errorea', 'Erabiltzaile izena ez dago erabilgarri.');
                return false;
            }

            errorea.textContent = '';
            return true;
        } catch (e) {
            erakutsiErrorea('erabiltzaile_izena_errorea', 'Errorea zerbitzariarekin konektatzean.');
            return false;
        }
    }

    async function egiaztatuNAN() {
        const sarrera = document.getElementById('nan');
        const nan = sarrera.value.trim();

        if (nan === '') {
            erakutsiErrorea('nan_errorea', 'NAN ezin da hutsik egon.');
            return false;
        }

        try {
            const erantzuna = await fetch(`assets/checkNAN.php?nan=${encodeURIComponent(nan)}`);
            const data = await erantzuna.json();

            if (!data.available){
                erakutsiErrorea('nan_errorea', 'NAN erregistratuta dago.');
                return false;
            }

            errorea.textContent = '';
            return true;
        } catch (e) {
            erakutsiErrorea('nan_errorea', 'Errorea zerbitzariarekin konektatzean.');
            return false;
        }
    }

    function erakutsiErrorea(id, mezua) {
        const span = document.getElementById(id);
        if (span) span.textContent = mezua;
    }
});
