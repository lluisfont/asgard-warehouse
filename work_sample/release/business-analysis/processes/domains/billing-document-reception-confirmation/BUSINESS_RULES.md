# Billing Document Reception Confirmation - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-BDRC-001 | Una Planilla/Factura esta pendiente si `fechaenvioplanilla IS NOT NULL`, `idestadofactura=1` y `recepcionplanilla=0`. | `recepcionplanillas_ajax.php` |
| BR-BDRC-002 | Una Planilla/Factura esta recepcionada si `recepcionplanilla=1` y se informa `fecharecepcionplanilla`. | `recepcionplanillas_ajax.php`, schema |
| BR-BDRC-003 | Una Nota de Cobranza esta pendiente si `estado_enviado=1` y `estado_recepcionado=0`. | `recepcionplanillas_ajax.php` |
| BR-BDRC-004 | Una Nota de Cobranza esta recepcionada si `estado_recepcionado=1` y se informa `fecha_recepcionado`. | `recepcionplanillas_ajax.php`, schema |
| BR-BDRC-005 | Un Cite esta pendiente si tiene `fechasalida` y no tiene `fecharecepcion`. | `recepcionplanillas_ajax.php` |
| BR-BDRC-006 | Un Cite esta recepcionado si `fecharecepcion IS NOT NULL`. | `recepcionplanillas_ajax.php`, schema |
| BR-BDRC-007 | La recepcion individual y masiva aplican la misma actualizacion por tipo documental. | `recibirunico`, `recibirvarios` |
| BR-BDRC-008 | La recepcion guarda `CURRENT_TIMESTAMP()` como fecha/hora de recepcion. | Updates en `recepcionplanillas_ajax.php` |
| BR-BDRC-009 | El listado observado filtra documentos desde `2021-08-02`. | Condiciones SQL en `recepcionplanillas_ajax.php` |
| BR-BDRC-010 | Las Planillas Legalizadas Entregadas se reportan con base en la primera `fechaenvioplanilla` asociada. | `planillaslegalizadasquery.php` |

## Riesgos de regla pendientes

- Confirmar si el receptor debe quedar identificado nominalmente.
- Confirmar si la recepcion requiere soporte fisico, firma, comentario o adjunto.
- Confirmar si existe un flujo oficial de anulacion/reversion de recepcion.
- Confirmar si el corte `2021-08-02` es temporal, migratorio o permanente.
