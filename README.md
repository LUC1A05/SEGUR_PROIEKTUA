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
| **Ahultasuna** | **[EJ: Inyección SQL (SQLi) / Cross-Site Scripting Persistente (XSS) / File Inclusion (LFI)]** |
| **Non ematen da** | `[EJ: El formulario de login / El campo de comentarios / El parámetro 'id' en la URL]` |
| **Zergatik ematen da** | Ausencia de **[EJ: sentencias preparadas / sanitización de entrada HTML / validación de rutas de archivo]**. La entrada del usuario se procesa directamente sin un control de seguridad adecuado. |
| **Zer lortu dugu erasoarekin** | Se logró **[EJ: extraer la contraseña del administrador / ejecutar código JavaScript en la sesión de otro usuario / leer el archivo /etc/passwd del servidor]**. |

---

## Erasoa berregiteko azalpena

### Erabilitako tresnak


### Prosezua

1.  **:** 
