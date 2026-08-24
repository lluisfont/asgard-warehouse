# Logistics Quotation Costing - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Cotizacion

| Estado candidato | Descripcion | Evidencia |
| --- | --- | --- |
| Creada | Registro en `logis_embarques` con `cotizacion = 1`. | `embarquesController.php:129` |
| En revision | Estado visual cuando no hay aceptacion ni confirmacion. | `embarquesController.php:48-63` |
| Enviada a operador | `enviocot = 1` y fecha de envio. | `embarquesController.php:238-274`, `CotizacionClass.php:861-868` |
| Contestada por operador | `llenadocot = 1`, `llenadocotfecha` y token anulado. | `CostosClass.php:467-480` |
| En evaluacion | Pantalla `evaluarcosto.php` muestra operadores, grupos y costos. | `evaluarcosto.php:16-180` |
| Confirmada | `confirmado = 1`. | `embarquesController.php:299-313` |
| Aprobada / operador aceptado | `aceptado = 1`; puede pasar a embarque. | `embarquesController.php:276-294` |
| Reajustada | Se ejecuta reajuste si existe cotizacion. | `embarquesController.php:218-226` |

## Operador

| Estado candidato | Descripcion | Evidencia |
| --- | --- | --- |
| Candidato | Asociado a embarque/cotizacion. | `CotizacionClass.php:605-627` |
| Solicitado | Recibe correo de cotizacion. | `embarquesController.php:238-274` |
| Respondido | Carga costos y opcionalmente documento. | `costosController.php:9-24` |
| En revision | Cliente solicita revision de costos. | `embarquesController.php:315-335` |
| Aceptado | Seleccionado como operador. | `embarquesController.php:276-294` |
| Confirmado | Confirmacion enviada al operador. | `embarquesController.php:299-313` |
