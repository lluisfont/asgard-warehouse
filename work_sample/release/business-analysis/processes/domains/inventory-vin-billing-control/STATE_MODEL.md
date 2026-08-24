# Inventory VIN Billing Control - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos

| Estado | Descripcion |
| --- | --- |
| Periodo calculado | Fecha inicio y fin derivadas por regla 21-20. |
| Precalculo generado | KPIs calculados y panel visible. |
| Facturacion confirmada | Periodo confirmado por API. |
| Periodo historico | Periodo listado en consolidado mensual. |
| Excel generado | Detalle exportado correctamente. |

## Estados de periodo observados en schema

| Campo | Descripcion candidata |
| --- | --- |
| `confirmado` | Periodo validado/confirmado para facturacion. |
| `historico` | Periodo marcado como historico. |
| `deleted_at` | Baja logica del periodo. |

## Pendiente de validacion

- Transiciones entre confirmado e historico.
- Reapertura/anulacion de periodos.
- Auditoria de usuario de confirmacion.
