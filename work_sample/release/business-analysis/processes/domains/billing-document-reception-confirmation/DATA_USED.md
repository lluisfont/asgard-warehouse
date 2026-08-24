# Billing Document Reception Confirmation - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Uso de negocio | Evidencia |
| --- | --- | --- |
| `dav_facturaplanilla.idfacturaplanilla` | Identifica planilla/factura a recibir. | `recepcionplanillas_ajax.php` |
| `dav_facturaplanilla.fechaenvioplanilla` | Marca que la planilla/factura fue enviada. | `recepcionplanillas_ajax.php`, schema |
| `dav_facturaplanilla.recepcionplanilla` | Bandera de recepcion de planilla/factura. | `recepcionplanillas_ajax.php`, schema |
| `dav_facturaplanilla.fecharecepcionplanilla` | Fecha/hora de recepcion de planilla/factura. | `recepcionplanillas_ajax.php`, schema |
| `dav_notasdebito.idnotasdebito` | Identifica nota de cobranza a recibir. | `recepcionplanillas_ajax.php` |
| `dav_notasdebito.estado_enviado` | Marca que la nota de cobranza fue enviada. | `recepcionplanillas_ajax.php`, schema |
| `dav_notasdebito.estado_recepcionado` | Bandera de recepcion de nota de cobranza. | `recepcionplanillas_ajax.php`, schema |
| `dav_notasdebito.fecha_recepcionado` | Fecha/hora de recepcion de nota de cobranza. | `recepcionplanillas_ajax.php`, schema |
| `dav_cite.idcite` | Identifica cite a recibir. | `recepcionplanillas_ajax.php` |
| `dav_cite.fechasalida` | Fecha de salida/envio del cite. | `recepcionplanillas_ajax.php` |
| `dav_cite.fecharecepcion` | Fecha/hora de recepcion del cite. | `recepcionplanillas_ajax.php`, schema |
| `dav_casos`, `dav_embarque`, `dav_pedidos` | Contexto operacional mostrado: carpeta, pedido, DIM, proveedor, cliente. | Joins en `recepcionplanillas_ajax.php` |

## Observaciones de calidad de datos

- El mismo tablero consolida tres familias documentales con tablas y campos de estado distintos.
- El total mostrado para Planilla/Factura se calcula con funciones `valorplanilladoid` y `valorfacturadoid`.
- El filtro por cliente de sesion reduce el alcance de bandeja, pero la actualizacion directa por id/tipo debe revisarse para autorizacion por documento.
