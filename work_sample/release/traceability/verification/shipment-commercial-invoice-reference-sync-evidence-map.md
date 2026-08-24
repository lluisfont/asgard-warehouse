# Shipment Commercial Invoice Reference Sync - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Artefacto | Evidencia |
| --- | --- |
| `PROCESS_DEFINITION.md` | `embarquereferencia.php` recibe documentos, filtra factura comercial y actualiza embarque. |
| `PROCESS_FLOW.md` | Secuencia observada del endpoint. |
| `BUSINESS_RULES.md` | Cliente 429, nombre documental y concatenacion de referencias. |
| `DATA_USED.md` | POST documental y `logis_embarques.facturacomercial`. |
| `STATE_MODEL.md` | Aplicable/no aplicable/sin referencia/sincronizado. |
| `UC-001.md` | Caso de uso de sincronizacion. |
| `openspec/spec.md` | Requisitos AS-IS candidatos. |

## Limitaciones

- No se localizo en esta pasada el disparador exacto que invoca el endpoint.
- No se ejecuto contra intercambio documental real.
