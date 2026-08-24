# Customs Guarantee Tax Control - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-CGTC-001 | El seguimiento de boletas se limita al cliente de sesion. | `boletasgarantiaajax.php`, `ContabilidadClass.php` |
| BR-CGTC-002 | El seguimiento mensual se filtra por gestion y tipo de declaracion. | `boletasgarantia.php`, `getSeguimientoMensual` |
| BR-CGTC-003 | Solo casos no anulados participan en calculos de garantia y tributos. | `ContabilidadClass.php`, `tributosquery.php` |
| BR-CGTC-004 | El monto total de garantia se obtiene desde `cc_registro_documentos` con `tipo_documento_id=4` y `deleted_at IS NULL`. | `getSeguimientoTotal` |
| BR-CGTC-005 | Una unidad cuenta como con DAM cuando existe `dav_facturacomercial.fechaenviodam`. | `getSeguimientoMensual` |
| BR-CGTC-006 | Una unidad cuenta como extraida cuando tiene `fechapasesalida` o `fechaasignacioncanal`. | `getSeguimientoMensual` |
| BR-CGTC-007 | El porcentaje de extraccion es unidades extraidas dividido entre unidades con DAM. | `getSeguimientoMensual` |
| BR-CGTC-008 | El monto disponible de garantia es monto total de boleta menos monto de boleta en uso. | `boletasgarantiaajax.php` |
| BR-CGTC-009 | El uso operativo separa DAM aceptada, sin nacionalizar, sin nacionalizar por vencer y sin DAM aceptada. | `getSeguimientoOperativo` |
| BR-CGTC-010 | La categoria sin nacionalizar usa umbral de 90 dias desde documento observado. | `getSeguimientoOperativo`, `getSeguimientoOperativoDesglosado` |
| BR-CGTC-011 | El reporte desglosado no debe permitir rangos mayores a 90 dias desde la UI. | `boletasgarantia.php` |
| BR-CGTC-012 | El reporte de tributos calcula saldo a favor comparando monto recibido, tributos pagados y devolucion/reposicion. | `tributosquery.php` |

## Riesgos de regla pendientes

- Confirmar que `tipo_documento_id=4` siempre significa boleta de garantia.
- Confirmar si la condicion `fechapasesalida OR fechaasignacioncanal` es suficiente para considerar unidad extraida.
- Confirmar si el umbral de 90 dias es normativo, operativo o solo una alerta interna.
- Confirmar si la division fija `6.96` usada para USD es regla vigente o valor historico.

