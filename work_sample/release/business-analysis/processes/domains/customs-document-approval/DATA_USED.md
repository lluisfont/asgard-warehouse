# Customs Document Approval - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Tabla / Fuente | Uso candidato | Evidencia |
| --- | --- | --- |
| `dav_documentosprevios` | Documento requerido/asociado a caso previo, datos fiscales/documentales y adjunto. | `documentacion.php:86-109`, `.data_base/asgard.sql:5484-5502` |
| `dav_otrosdocumentosprevios` | Documentos adicionales no catalogados o complementarios. | `documentacion.php:118-165`, `.data_base/asgard.sql:7839-7848` |
| `dav_intermediodocumento` | Documentos intermedios del caso que pueden convertirse en previos y ocultarse. | `documentacionaprobado.php:196-241`, `.data_base/asgard.sql:6893-6898` |
| `dav_tipodocumento` | Catalogo de tipos de documento, plazos, orden y atributos. | `documentacion.php:1034-1055`, `.data_base/asgard.sql:10237-10256` |
| `dav_formatodocumento` | Catalogo de formato documental. | `documentacionaprobado.php:970-996`, `.data_base/asgard.sql:6524-6530` |
| `tmp_documentosprevios` | Staging para carga masiva de documentos. | `documentacion.php:234-271` |
| Sistema de archivos `casosprevios` / `casospreviosotro` | Almacenamiento fisico de adjuntos. | `documentacion.php:94-109`, `documentacion.php:125-140` |

## Datos Criticos

- Tipo documento, emisor, numero, fecha, importe, divisa, adjunto, estado de aceptacion y observaciones.
