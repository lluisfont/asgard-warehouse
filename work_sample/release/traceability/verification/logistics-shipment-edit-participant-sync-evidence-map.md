# Logistics Shipment Edit Participant Sync - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Artefacto | Evidencia |
| --- | --- |
| `PROCESS_DEFINITION.md` | `cotizacionEditarController.php`, `CotizacionClass::editarCotizacionCliente`, `IntercambioDocumentalEditClass`. |
| `PROCESS_FLOW.md` | Edicion local, reemplazo de hijos y sync documental. |
| `BUSINESS_RULES.md` | Reglas de update, delete/insert, operador y notificaciones. |
| `DATA_USED.md` | Tablas logistica y API documental. |
| `STATE_MODEL.md` | Cotizacion/embarque, asignacion y sync documental. |
| `UC-001.md` | Caso de uso de edicion de embarque. |
| `openspec/spec.md` | Requisitos AS-IS candidatos. |

## Limitaciones

- No se ejecuto edicion real ni llamada al API documental.
- Las reglas de bloqueo por finalizacion o permisos deben validarse en el flujo completo.
