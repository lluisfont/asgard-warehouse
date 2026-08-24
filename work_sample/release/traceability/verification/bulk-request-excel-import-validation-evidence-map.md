# Bulk Request Excel Import Validation - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Elemento | Evidencia | Observacion |
| --- | --- | --- |
| Acceso UI | `solicitud.php` | Carga masiva visible para clientes 560/755. |
| Plantilla | `ajax/formatoSolicitudMasiva.php` | Rellena listas y validaciones Excel. |
| Upload | `ajax/uploadExcelSolicitud.php` | Guarda archivo y lee columnas A-Y. |
| Staging | `SolicitudClass::guadarSolicitudConvertidas` | Borra e inserta `dav_solicitudesprevias` por cliente/usuario. |
| Validacion | `SolicitudClass::validarSolicitud` | Convierte maestros y marca errores por fila. |
| Commit | `SolicitudClass::crearGestionAduanera` | Inserta `dav_casosprevios`, `dav_documentosprevios` y `dav_tramites`. |
| Rechazo lote | `uploadExcelSolicitud.php` | Si `error>0`, `status=100` y no crea solicitudes. |
| Persistencia | `.data_base/asgard.sql` | Tablas `dav_solicitudesprevias`, `dav_casosprevios`, `dav_documentosprevios`, `dav_tramites`. |

## Cobertura

- Flujo principal reconstruido: si.
- Reglas de validacion reconstruidas: si.
- Modelo de lote reconstruido: si.
- Riesgos y preguntas registrados: si.
