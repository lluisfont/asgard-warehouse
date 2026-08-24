# Logistics Shipment Finalization Control - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia por capacidad

| Capacidad | Evidencia | Inferencia |
| --- | --- | --- |
| Mostrar accion de cierre | `index_archivos/logistica/embarquever.php:300-302`, `index_archivos/logistica/js/finalizar-embarque.js:1-3` | El cierre es una accion de usuario sobre embarques abiertos y con escritura. |
| Confirmar irreversibilidad | `index_archivos/logistica/js/finalizar-embarque.js:43-67` | El negocio presenta el cierre como no re-habilitable. |
| Validar prerequisitos cliente 429 | `index_archivos/logistica/ajax/finalizar-embarque.php:17-67` | Cliente 429 requiere EDP, costos y GA completos antes del cierre. |
| Informar faltantes | `index_archivos/logistica/ajax/finalizar-embarque.php:67-106` | El bloqueo devuelve detalle agrupado por area. |
| Persistir cierre | `index_archivos/logistica/ajax/finalizar-embarque.php:110-114` | Cierre actualiza embarque e inserta EDP. |
| Bloquear acciones posteriores | `index_archivos/logistica/frames/costos.php:48-59`, `index_archivos/logistica/frames/estado-pedidos.php:65-71` | Las pantallas usan `fecha_finalizacion` como candado operativo. |
| Campos de base de datos | `.data_base/asgard.sql:12090-12110`, `.data_base/asgard.sql:12174-12217` | El schema soporta EDP y finalizacion de embarque. |

## Riesgos

- Posible sobrescritura del estado EDP final por estructura de bloque en `finalizar-embarque.php:7-13`.
- SQL interpolado y ausencia de transaccion explicita.
- Reapertura no observada pese al mensaje de irreversibilidad.

