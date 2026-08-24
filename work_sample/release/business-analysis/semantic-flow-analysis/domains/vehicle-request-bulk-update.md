# vehicle-request-bulk-update - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 3
- Campos cruzados: 9
- Tablas con mutacion observada: 0
- Riesgos candidatos: SQL dinamico; permisos/autorizacion; documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_historialmodificacionvehiculos` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | actualizado, created_at, created_by, idcargado, mensaje | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; SQL construido dinamicamente; seguridad/autorizacion sensible | EV-SQL_QUERY-8BA64888006E61 index_archivos/controllers/VehiculosClass.php:57 READS access to dav_historialmodificacionvehiculos \| EV-SQL_QUERY-77D5418911B816 index_archivos/controllers/VehiculosClass.php:78 READS access to dav_historialmodificacionvehiculos \| EV-SQL_QUERY-C37B34EE779341 index_archivos/controllers/VehiculosClass.php:113 READS access to dav_historialmodificacionvehiculos \| EV-SQL_QUERY-12EAA46E4AF7C0 index_archivos/logistica/ajax/vehiculos/historialModificaciones.php:16 READS acce |
| `soat_loteitems` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | fecha, idcaso | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; SQL construido dinamicamente; seguridad/autorizacion sensible | EV-SQL_QUERY-F6D07BC602FE45 .data_base/asgard.sql:17887 READS access to soat_loteitems \| EV-SQL_QUERY-8A4DB5E6374FDC .data_base/asgard.sql:31233 READS access to soat_loteitems \| EV-SQL_QUERY-9DFCDB1E775C41 .data_base/asgard.sql:31277 READS access to soat_loteitems \| EV-SQL_QUERY-48BA0A68D8DC3B .data_base/asgard.sql:31319 READS access to soat_loteitems \| EV-SQL_QUERY-D52835AF9D65E3 .data_base/asgard.sql:31373 READS access to soat_loteitems \| EV-SQL_QUERY-4F92E92FC90AD6 index_archivos/controllers/ |
| `soat_lotes` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | archivo, fecha | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; SQL construido dinamicamente; seguridad/autorizacion sensible |  |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_historialmodificacionvehiculos` | `actualizado` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | Reportes de costos/vehiculos \| Consumidores de algunos campos actualizados. \| - Tras confirmar, las filas del `idcargado` se marcan `actualizado=1`. \| ASGARD marca registros como actualizados. \| ASGARD consulta cambios actualizados del usuario actual. \| BR-VRBU-012 \| Tras aplicar cambios, `dav_historialmodificacionvehiculos.actualizado` pasa a `1`. |
| `dav_historialmodificacionvehiculos` | `created_at` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | `created_at` \| Fecha de carga/historial. |
| `dav_historialmodificacionvehiculos` | `created_by` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | `created_by` \| Usuario que cargo/aplico la modificacion. |
| `dav_historialmodificacionvehiculos` | `idcargado` | Referencia funcional que vincula el flujo con otra entidad/catalogo. | BUSINESS_DATA | - Registro temporal/historico por `idcargado` en `dav_historialmodificacionvehiculos`. \| `index_archivos/logistica/ajax/vehiculos/actualizarDatosVehiculos.php` \| Confirma actualizacion por `idcargado`. \| - Tras confirmar, las filas del `idcargado` se marcan `actualizado=1`. \| ASGARD crea un nuevo `idcargado`. \| ASGARD lee registros del `idcargado` y usuario. |
| `dav_historialmodificacionvehiculos` | `mensaje` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | - Mensajes de observacion por fila. \| - No se actualizan filas con mensaje de error. \| BR-VRBU-004 \| Filas con mensaje de error no se actualizan al confirmar. \| `mensaje` \| Observacion/error de validacion por fila. \| Carga con observaciones \| Al menos una fila tiene `mensaje`. |
| `soat_loteitems` | `fecha` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | BR-VRBU-011 \| Si el chasis ya tiene informacion completa AP o fecha de recepcion completa, se generan bloqueos/observaciones para cambio de solicitud. \| \| `buscarChasisFechaInfoCarpeta`, `validarNumeroSolicitud` \| \| `created_at` \| Fecha de carga/historial. |
| `soat_loteitems` | `idcaso` | Referencia funcional que vincula el flujo con otra entidad/catalogo. | BUSINESS_DATA | `dav_vehiculosprevios.idcasosprevios` \| Solicitud asociada al chasis cuando se cambia numero de solicitud. \| `dav_casos.idcasosprevios` \| Solicitud asociada a carpeta/caso cuando se cambia numero de solicitud. |
| `soat_lotes` | `archivo` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | DOCUMENT_OR_FILE_REFERENCE | # Vehicle Request Bulk Update - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Actualizar masivamente datos de vehiculos asociados a solicitudes/casos mediante un archivo Excel, permitiendo corregir valores FOB, flete maritimo, pedido, posicion, clave de validacion y numero de solicitud, con prevalidacion por chasis y c... \| Archivo Excel de modificacion. \| `index_archivos/logistica/updateSolicitudVehiculos.php` \| Pantalla, opciones de campo y acceso a historial. \| # Vehicle Request Bulk Update - Process Flow ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Flujo |
| `soat_lotes` | `fecha` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | BR-VRBU-011 \| Si el chasis ya tiene informacion completa AP o fecha de recepcion completa, se generan bloqueos/observaciones para cambio de solicitud. \| \| `buscarChasisFechaInfoCarpeta`, `validarNumeroSolicitud` \| \| `created_at` \| Fecha de carga/historial. |
