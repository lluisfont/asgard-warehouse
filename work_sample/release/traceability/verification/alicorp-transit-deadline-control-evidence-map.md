# Alicorp Transit Deadline Control - Evidence Map

## Cobertura

| Artefacto | Evidencia |
| --- | --- |
| Process definition | `index_archivos/operativos/control_alicorpquery.php:1-154`, `.data_base/asgard.sql:1945-1951` |
| Process flow | `control_alicorpquery.php`, `data.php`, export setup in same query file |
| Business rules | `control_alicorpquery.php:11`, `control_alicorpquery.php:55-58`, `control_alicorpquery.php:145-154` |
| Data used | `.data_base/asgard.sql:1520-1951`, `control_alicorpquery.php:29-139` |
| State model | `control_alicorpquery.php:55-68`, OCR update files |
| Use case | `control_alicorpquery.php`, report/export pattern |
| OpenSpec | Derived from observed SQL and UI query behavior |

## Evidencia tecnica directa

| Ruta | Observacion |
| --- | --- |
| `index_archivos/operativos/control_alicorpquery.php` | Query principal, side-effect de vencimiento, tablas temporales y salida Excel. |
| `.data_base/asgard.sql` | DDL de campos Alicorp en `dav_casos`; tambien contiene copia de query/estructura analitica relacionada. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-senavex.php` | Marca `alicorp_cierre_transito=1`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-jennefer.php` | Marca `alicorp_cierre_transito=1`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-fdab.php` | Marca `alicorp_cierre_transito=1`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php` | Marca `alicorp_cierre_transito=1`. |

## Limitaciones

- No se encontro una pantalla especifica para modificar manualmente todos los campos Alicorp.
- La consulta combina seguimiento y actualizacion, por lo que el limite entre reporte y proceso operativo debe validarse con negocio.
- Los lectores OCR relacionados no se han reanalizado completos dentro de este dominio; se usan como evidencia de cierre de transito.
