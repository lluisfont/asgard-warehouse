# Customs Document Approval - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Documento Previo

| Estado candidato | Descripcion | Evidencia |
| --- | --- | --- |
| Creado | Registro en `dav_documentosprevios`. | `documentacion.php:86-89` |
| Con adjunto | Campo `adjunto` actualizado tras subida. | `documentacion.php:94-109` |
| Actualizado | Datos documentales modificados. | `documentacion.php:292-323` |
| Convertido desde intermedio | Nace desde `dav_intermediodocumento`. | `documentacionaprobado.php:196-241` |
| Aceptado/omitido | `aceptar = 1` excluye de pendientes en aprobacion. | `documentacionaprobado.php:970-999` |
| Marcado para envio | `aceptar = 4`. | `documentacionaprobado.php:316`, `documentacionaprobado.php:421-426` |
| Eliminado | Registro eliminado por accion. | `documentacion.php:330`, `documentacionaprobado.php:281` |
| Sin adjunto | Adjunto eliminado y campo limpiado. | `documentacion.php:339-343`, `documentacionaprobado.php:308-312` |

## Otro Documento

| Estado candidato | Descripcion | Evidencia |
| --- | --- | --- |
| Creado | Registro en `dav_otrosdocumentosprevios`. | `documentacion.php:118-120` |
| Con adjunto | Archivo subido y campo `adjunto` actualizado. | `documentacion.php:125-140` |
| Pendiente de envio | `enviado` no marcado o estado 0/3. | `finsolicitud.php:216-220`, `documentacionaprobado.php:442-476` |
| Enviado | `enviado = 1`, `estado = 1`. | `finsolicitud.php:375`, `documentacionaprobado.php:476` |
