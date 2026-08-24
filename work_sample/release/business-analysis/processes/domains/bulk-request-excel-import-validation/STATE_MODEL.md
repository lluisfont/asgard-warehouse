# Bulk Request Excel Import Validation - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos del lote

| Estado | Significado | Evidencia |
| --- | --- | --- |
| Formato descargado | El usuario obtiene Excel con listas maestras. | `formatoSolicitudMasiva.php` |
| Archivo cargado | El archivo se guarda en servidor. | `guardarArchivo` |
| Staged | Filas insertadas en `dav_solicitudesprevias`. | `guadarSolicitudConvertidas` |
| Validado con errores | Una o mas filas tienen `error=1`. | `validarSolicitud` |
| Validado sin errores | Todas las filas tienen `error=0`. | `uploadExcelSolicitud.php` |
| Lote rechazado | No se crean solicitudes por errores. | `status=100` |
| Solicitudes creadas | Se crean solicitudes previas/documentos/tramites. | `status=200` |

## Transiciones candidatas

| Transicion | Desde | Hacia | Disparador |
| --- | --- | --- | --- |
| Descargar plantilla | - | Formato descargado | Link formato Excel |
| Subir archivo | Formato descargado | Archivo cargado | Boton Cargar Archivo |
| Insertar staging | Archivo cargado | Staged | Lectura PHPExcel |
| Validar staging | Staged | Validado con errores / sin errores | `validarSolicitud` |
| Rechazar lote | Validado con errores | Lote rechazado | Conteo de errores > 0 |
| Crear solicitudes | Validado sin errores | Solicitudes creadas | Loop `crearGestionAduanera` |

## Estados no observados

- Aprobado por supervisor.
- Carga parcial.
- Revertido.
- Duplicado detectado.
- Enviado automaticamente despues de importar.
