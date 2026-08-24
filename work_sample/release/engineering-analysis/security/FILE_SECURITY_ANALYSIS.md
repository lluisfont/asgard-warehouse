# File Security Analysis

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Superficie de ficheros

- Descargas genericas: `index_archivos/download.php`.
- Documentacion de solicitudes/casos: `documentacion.php`, `documentacionaprobado.php`, `cargardocumentos_v.php`.
- OCR/intercambio documental: `intercambioDocumental/ajax/*`, `OCRClass.php`, `ocr/lectura_ocr.php`.
- Excel: solicitudes masivas, vehiculos, DAM, packing list, reportes.
- ZIP/RAR/PDF: lectura OCR, SOAT, parte recepcion, documentacion Alicorp.

## Hallazgos candidatos

| ID | Hallazgo | Evidencia | Prioridad |
| --- | --- | --- | --- |
| FILE-001 | Descarga generica permite controlar subruta `p`; `basename` solo protege el nombre `f`, no la carpeta. | `download.php:5-8` | Alta |
| FILE-002 | Upload documental usa nombre original y lo mueve bajo `FILES_PATH` sin allowlist MIME/tamano visible en el bloque observado. | `documentacion.php:96-110` | Alta |
| FILE-003 | Borrado usa parametros GET para localizar adjuntos despues de consultar DB; requiere validar pertenencia del recurso al tenant. | `documentacion.php:341-355` | Alta |
| FILE-004 | Procesamiento ZIP/RAR via SSH ejecuta comandos remotos con rutas/nombres derivados del archivo. | `lectura-ocr-ft.php:21-35` | Alta |
| FILE-005 | OCR puede leer contenido desde URLs/rutas derivadas del documento; requiere controles anti-SSRF y allowlist. | `OCRClass.php`, `lectura-ocr-pr.php` | Alta |
| FILE-006 | Directorios creados con `0777` en cargas vehiculares/OCR. | `vehiculosexcel/*`, `lectura-ocr-pr.php` | Media |

## Controles recomendados

- Centralizar descarga/subida con verificacion de tenant, recurso, extension, MIME, tamano y ruta canonica.
- Guardar ficheros con nombres generados, no nombres originales.
- Deshabilitar ejecucion en carpetas de upload.
- Validar ZIP/RAR contra zip-slip, tamano maximo, profundidad y numero de archivos.
- Sustituir comandos shell remotos por librerias seguras o colas controladas.
