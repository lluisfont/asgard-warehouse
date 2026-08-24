# Export MIC DEX Physical Reception Control - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Controlar la recepcion fisica, envio, conclusion y reversa de documentos MIC/DEX de exportacion, permitiendo marcar registros en lote y conservar historial de cambios por usuario/tipo de usuario.

## Alcance observado

- Consulta filtrada de registros `dex_suma` con fecha de verificacion de salida.
- Filtros por fecha de salida, factura comercial, empresa transporte, DEX, placa y estado documental.
- Control Alicorp de transito por factura, DEX, manifiesto, transporte, placa y cruce con reporte fiscal.
- Clasificacion de unidades pendientes de cruzar frontera o ya cruzadas mediante comparacion entre reporte fiscal y `dex_suma`.
- Estados documentales derivados: PENDIENTE, RECIBIDO, ENVIADO, CONCLUIDO.
- Seleccion multiple de registros, restringida a un mismo estado.
- Marcado masivo de avance de estado.
- Reversion masiva de estado.
- Historial por documento con usuario, fecha y estado.
- Diferenciacion de tipo usuario segun `ASGARD_TYPE`: proveedor o cliente.

## Fuera de alcance observado

- Creacion inicial de registros `dex_suma`.
- Generacion del DEX/MIC.
- Validacion documental/fiscal completa.
- Reportes de exportacion generales.
- Tracking de viaje, ubicaciones o eventos de transporte.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario proveedor | Marca recepcion/envio fisico segun ramas `ASGARD_TYPE = PROVEEDORES`. |
| Usuario cliente | Marca envio/conclusion segun ramas `ASGARD_TYPE = CLIENTES`. |
| ASGARD | Consulta, actualiza fechas y registra historial. |

## Entradas

- Filtros de busqueda.
- Lista de ids seleccionados.
- Accion `accept` o `reject`.
- Estado actual `q`.
- Sesion de usuario cliente o proveedor.

## Salidas

- Actualizacion de fechas en `dex_suma`: `fecha_recibido`, `fecha_enviado`, `fecha_concluido`.
- Insercion en `dex_suma_estado_historial`.
- Tabla con estado documental actualizado.
- Modal de historial por registro.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/recepcion_fisica_mics.php:48-115` | UI de filtros, botones Revertir/Marcar y tabla MIC/DEX. |
| `index_archivos/operativos/exportaciones/ajax/RecepcionFisicaMICs.php:16-89` | Consulta filtrada y derivacion de estado documental. |
| `index_archivos/operativos/exportaciones/ajax/RecepcionFisicaMICs.php:93-118` | Detalle e historial DEX. |
| `index_archivos/operativos/reporte_control_transito_alicorp.php` | Pantalla de control de transito Alicorp sobre MIC/DEX. |
| `index_archivos/operativos/exportaciones/ajax/ControlTransitoAlicorp.php` | Consulta `dex_suma`, filtra DEX/factura/transporte y cruza con reporte fiscal para pendiente/cruzado frontera. |
| `index_archivos/operativos/exportaciones/js/recepcion_fisica_mics.js:1-57` | Restringe seleccion masiva a registros del mismo estado. |
| `index_archivos/operativos/exportaciones/js/recepcion_fisica_mics.js:59-128` | Ejecuta consulta y renderiza tabla/exportacion Excel. |
| `index_archivos/operativos/exportaciones/js/recepcion_fisica_mics.js:131-248` | Marca o revierte registros seleccionados. |
| `index_archivos/operativos/exportaciones/js/recepcion_fisica_mics.js:250-270` | Abre historial. |
| `index_archivos/operativos/exportaciones/ajax/ActualizarMICs.php:13-82` | Actualiza estados y registra historial. |
| `index_archivos/operativos/exportaciones/ajax/ActualizarMICs.php:83-120` | Devuelve historial HTML. |
| `.data_base/asgard.sql:11038-11071` | Esquema `dex_suma` y `dex_suma_estado_historial`. |

## Criterios de aceptacion candidatos

- Solo registros con `fecha_verificacion_salida` deben entrar en la consulta.
- La seleccion masiva debe limitarse a registros del mismo estado documental.
- Cada avance o reversa debe insertar historial.
- Los cambios deben actualizar la fecha documental correspondiente segun estado y tipo de usuario.
- El historial debe mostrar fecha de modificacion, estado y usuario.
