# Logistics Quotation Costing - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| LQC-BR-001 | `guardarCotizacion` crea registro con `cotizacion = 1`; `guardarEmbarque` crea con `cotizacion = 0`. | `embarquesController.php:129` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-002 | La cotizacion almacena cabecera, magnitudes, tramos y operadores candidatos. | `embarquesController.php:94-178` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-003 | Enviar cotizacion recorre operadores y envia correo cuando existe email. | `embarquesController.php:238-274` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-004 | El acceso de operador a costos se resuelve por token en `logis_embarquesoperador`. | `CostosClass.php:14-24`, `costosController.php:9-14` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-005 | La estructura de costos depende de incoterm y tipo de embarque. | `CostosClass.php:412-427` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-006 | Si existen ETD y ETA en el mismo grupo, el concepto `TT` se calcula como diferencia en dias. | `CostosClass.php:431-463` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-007 | Al enviar costos, se insertan detalles y se marca `llenadocot = 1`; el token queda en `NULL`. | `CostosClass.php:467-480` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-008 | Aceptar costos marca operador aceptado y el proceso pasa a embarque. | `embarquesController.php:276-294` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-009 | Confirmar costos marca operador confirmado y envia correo de confirmacion. | `embarquesController.php:299-313` | INFERRED_DRAFT_REVIEW_REQUIRED |
| LQC-BR-010 | Para cliente 802, el bloqueo de evaluacion considera aceptado o confirmado; para otros, considera aceptado. | `evaluarcosto.php:58-66` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Pendiente de Confirmar

- Diferencia de negocio entre "aceptar" y "confirmar" costos.
- Politica de vigencia y expiracion del token.
- Reglas completas de comparacion de costos y desempate.
