# Advisory Management Services - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Flujo Principal

1. El usuario abre el tablero de servicios adicionales.
2. El sistema muestra pestañas por estado operativo.
3. El usuario crea una nueva solicitud o abre una existente.
4. El usuario informa solicitante, correo, ciudad, linea y notas.
5. El usuario agrega uno o mas tramites seleccionando entidad emisora, tramite y tipo de tramite.
6. El sistema registra la solicitud y los tramites.
7. Si existe intercambio documental, el sistema lo vincula; si no, puede crear uno nuevo.
8. El sistema actualiza o consulta la solicitud por estados del ciclo.
9. El reporte general permite filtrar y exportar solicitudes con fechas, responsables, costos y referencias.

## Integraciones Observadas

- Con logistica: solicitudes pueden asociarse a embarque (`embarque_id`) y mostrarse dentro de frames/componentes logisticos.
- Con solicitud previa: solicitudes pueden asociarse a `casos_previos_id`.
- Con document exchange: se usa `exchange_id` o se crea intercambio documental de tipo servicio adicional.
- Con facturacion/pagos: los reportes y notas de cobranza pueden enlazar `idages` y `ages_caso_id`.

## Evidencia

- `tbl-estados.js:1-220`
- `solicitud.js:1-260`
- `tramite.js:1-130`
- `logistica/SolicitudesClass.php:714-850`
- `.data_base/asgard.sql:285-505`
- `operativos/asesoria-gestion.php:67-212`
