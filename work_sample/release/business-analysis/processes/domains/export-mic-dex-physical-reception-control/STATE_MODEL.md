# Export MIC DEX Physical Reception Control - State Model

## Estados documentales candidatos

| Estado | Condicion | Valor historial observado |
| --- | --- | --- |
| PENDIENTE | Sin fechas documentales. | `0` o estado inicial inferido |
| RECIBIDO | `fecha_recibido` informada. | `1` |
| ENVIADO | `fecha_enviado` informada. | `2` |
| CONCLUIDO | `fecha_concluido` informada. | `3` |

## Transiciones observadas

| Accion | Actor/tipo observado | Transicion candidata | Persistencia |
| --- | --- | --- | --- |
| Accept pendiente | Proveedor | PENDIENTE -> RECIBIDO | `fecha_recibido = CURRENT_TIMESTAMP()` |
| Accept recibido | Proveedor | RECIBIDO -> ENVIADO | `fecha_enviado = CURRENT_TIMESTAMP()` |
| Accept enviado/concluido | Cliente | ENVIADO -> CONCLUIDO | `fecha_concluido = CURRENT_TIMESTAMP()` |
| Reject concluido | Cliente | CONCLUIDO -> ENVIADO | `fecha_concluido = NULL` |
| Reject enviado | Cliente | ENVIADO -> RECIBIDO | `fecha_enviado = NULL` |
| Reject recibido/pendiente | Proveedor | RECIBIDO -> PENDIENTE | `fecha_recibido = NULL` |

## Pendiente

La matriz anterior es inferida desde ramas de codigo y debe revisarse con negocio, especialmente la rama `accept q=enviado` para clientes.

