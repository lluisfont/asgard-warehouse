# Input Validation Analysis

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Patron general

El repo mezcla validacion moderna puntual con SQL legacy interpolado. En login primario se observa sanitizacion superficial y PDO preparado, pero gran parte de los modulos operativos usan `mysql_query` con concatenacion directa de `$_GET`, `$_POST`, `$_REQUEST` y valores de sesion.

## Evidencia representativa

| Caso | Observacion | Riesgo |
| --- | --- | --- |
| `veriflogin.php` | Usa PDO prepared para `username`, pero redireccion `ultimoenlace` proviene de POST y la IP confia en headers. | Medio |
| `2fa/TwoFaClass.php` | Inserta/verifica codigos MFA con interpolacion directa. | Alto |
| `documentacion.php` | `idcasosprevios`, `accion`, POST documentales y nombres de archivo alimentan SQL/rutas. | Alto |
| `download.php` | Construye ruta con `FILES_PATH.$_GET['p']."/".basename($_GET['f'])`; `p` no se normaliza con allowlist. | Alto |
| `android/consulta.php` | Login Android legacy compara `username`/`contrasena` directamente en SQL. | Alto |
| `ajax/uploadExcelSolicitud.php` | Excel de usuario se transforma en solicitudes; hay validacion funcional, pero la carga es superficie de datos no confiables. | Medio |

## Reglas candidatas de hardening

- Migrar endpoints legacy de `mysql_query` a prepared statements.
- Validar todos los IDs con cast estricto y pertenencia al tenant antes de consulta/mutacion.
- Usar allowlists para acciones (`accion`) y rutas descargables/subibles.
- Normalizar y renombrar archivos al subir; no persistir el nombre original como ruta ejecutable.
- Separar validacion funcional de validacion de seguridad para Excel/OCR/PDF.
- Auditar redirecciones con destino controlado; evitar open redirect por `ultimoenlace`.
