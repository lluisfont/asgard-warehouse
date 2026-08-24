# Secret Management Findings

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| ID | Secreto / Credencial | Evidencia | Riesgo |
| --- | --- | --- | --- |
| SEC-SECRET-001 | Token SendGrid global en codigo/configuracion PHP. | `cnfdb105.php:8` | Alto |
| SEC-SECRET-002 | JWT key `@QEGTUI` hardcoded. | `2fa/TwoFaClass.php:165` | Alto |
| SEC-SECRET-003 | JWT Atlantes key hardcoded. | `2fa/TwoFaClass.php:180` | Alto |
| SEC-SECRET-004 | Credencial SFTP/SSH hardcoded para OCR/descompresion. | `intercambioDocumental/ajax/lectura-ocr-ft.php:24` y variantes OCR | Critico |
| SEC-SECRET-005 | OCR subscription keys se inyectan como constantes globales; confirmar si `.env.php` esta fuera de control de versiones y rotacion. | `OCRClass.php`, `OcrUtil.php`, `cnfdb105.php` | Alto |
| SEC-SECRET-006 | Credenciales SMTP legacy aparecen configuradas en scripts de correo. | `email.php`, `documentacionaprobado.php` | Medio |

## Acciones candidatas

- Rotar inmediatamente credenciales expuestas en repo o historico.
- Mover secretos a vault/secret manager con permisos por entorno.
- Eliminar secretos de logs, evidencias y artefactos exportables.
- Usar claves JWT por entorno con `kid`, expiracion y rotacion.
- Sustituir SFTP con credenciales estaticas por identidad gestionada/clave rotada.
