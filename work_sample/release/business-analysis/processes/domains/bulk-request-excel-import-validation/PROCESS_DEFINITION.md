# Bulk Request Excel Import Validation - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Permitir que clientes habilitados carguen solicitudes operativas en lote desde un formato Excel controlado, convirtiendo valores textuales a identificadores maestros, validando cada fila y creando solicitudes previas solo cuando todo el lote queda sin errores bloqueantes.

## Alcance observado

- Descarga de plantilla `FormatoSolicitudMasiva.xlsx` enriquecida con listas maestras.
- Acceso a carga masiva solo para clientes observados `560` y `755`.
- Carga de archivo Excel desde pantalla de solicitud.
- Lectura de columnas A-Y.
- Staging por usuario/cliente en `dav_solicitudesprevias`.
- Validacion de tipo solicitud, proveedor, transportista, ciudad, coordinador, regimen, tipo declaracion, aduana, modo transporte, linea, fechas, flags SI/NO, firmante y servicio adicional.
- Reporte HTML por fila con errores/observaciones.
- Creacion de `dav_casosprevios`, documentos previos y tramite adicional si todo el lote es valido.

## Fuera de alcance observado

- Envio/finalizacion posterior de la solicitud.
- Validacion de duplicados por pedido/orden de compra.
- Creacion de intercambio documental.
- Notificaciones posteriores de envio.
- Reproceso parcial de filas validas cuando alguna fila falla.

## Actores

| Actor | Rol observado |
| --- | --- |
| Cliente usuario | Descarga formato, completa Excel y carga lote. |
| ASGARD | Genera plantilla, guarda archivo, transforma fechas, valida y crea solicitudes. |
| Maestros ASGARD | Proveen catalogos para validacion y conversion. |
| Coordinador asignado | Queda registrado como usuario responsable de solicitud. |

## Entradas

- Archivo Excel `adjunto`.
- Cliente y usuario de sesion.
- Columnas A-Y del formato.
- Catalogos de proveedor, transportista, ciudad, usuario, regimen, declaracion, aduana, transporte, linea, declarante y servicio adicional.

## Salidas

- Archivo subido a `FILES_PATH/cargasolicitudes/{idcliente}`.
- Filas staged en `dav_solicitudesprevias`.
- Resultado HTML por fila.
- `dav_casosprevios` creados si el lote no tiene errores.
- `dav_documentosprevios` iniciales por modo de transporte.
- `dav_tramites` inicial si existe servicio adicional valido.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/solicitud.php` | Pestaña Carga Masiva visible para clientes 560/755. |
| `index_archivos/ajax/formatoSolicitudMasiva.php` | Genera plantilla con listas maestras y validaciones Excel. |
| `index_archivos/ajax/uploadExcelSolicitud.php` | Procesa archivo, arma resultado por fila y decide guardar o rechazar. |
| `index_archivos/controllers/SolicitudClass.php` | Guarda archivo, stagea, valida y crea solicitudes previas/documentos/tramites. |
| `.data_base/asgard.sql` | Tablas `dav_solicitudesprevias`, `dav_casosprevios`, `dav_documentosprevios`, `dav_tramites`. |

## Criterios de aceptacion candidatos

- La plantilla debe incluir listas validas de proveedores y operadores del cliente.
- El archivo cargado debe mapear columnas A-Y al staging esperado.
- Cada fila debe mostrar mensaje de validacion.
- Si una fila tiene error bloqueante, no se crean solicitudes del lote.
- Si no hay errores bloqueantes, se crea una solicitud previa por fila.
- Transportista no encontrado se trata como observacion opcional con `idtransportista=0`.
