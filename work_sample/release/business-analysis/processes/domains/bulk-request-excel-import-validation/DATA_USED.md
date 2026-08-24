# Bulk Request Excel Import Validation - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Uso de negocio | Evidencia |
| --- | --- | --- |
| Excel columnas A-Y | Fuente de datos de lote. | `uploadExcelSolicitud.php` |
| `dav_solicitudesprevias` | Staging y validacion por fila. | `SolicitudClass::guadarSolicitudConvertidas`, schema |
| `dav_casosprevios` | Solicitud previa final creada por fila valida. | `crearGestionAduanera`, schema |
| `dav_documentosprevios` | Documentos iniciales por modo de transporte. | `crearGestionAduanera` |
| `dav_tramites` | Tramite/servicio adicional inicial. | `crearGestionAduanera` |
| `dav_proveedor`, `dav_clienteproveedor` | Validacion de proveedor por cliente. | `buscarProveedor`, formato |
| `dav_transportista`, `dav_clientetransportista` | Validacion de operador/transporte por cliente. | `buscarTransportista`, formato |
| `dav_ciudad` | Validacion de ciudad. | `buscarCiudad` |
| `dav_usuario` | Coordinador activo por ciudad. | `buscarUsuario` |
| `dav_regimen`, `dav_tipodeclaracion`, `dav_aduana`, `dav_modotransporte` | Catalogos aduaneros de solicitud. | Validaciones en `SolicitudClass` |
| `dav_clientelineas` | Linea de negocio opcional/validada. | `buscarClienteLineas` |
| `dav_clientedeclarante` | Firmante/declarante principal. | `buscarDeclarate` |
| `dav_entidademisora`, `dav_entidademisoratramite`, `dav_tipotramite` | Servicio adicional de la solicitud. | `buscarServicioAdicional` |

## Columnas de entrada observadas

| Columna | Campo |
| --- | --- |
| A-Y | Tipo solicitud, proveedor, transportista, solicitante, email, ciudad, coordinador, regimen, tipo declaracion, aduana, modo transporte, pedido, orden compra, linea, fecha embarque, fecha llegada, descarga, carga consolidada, transporte SLG, control temperatura, estibadores, firmante, notas, entidad emisora, tipo tramite. |
