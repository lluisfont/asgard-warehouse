# Export MIC DEX Physical Reception Control - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia por capacidad

| Capacidad | Evidencia | Inferencia |
| --- | --- | --- |
| Consultar documentos MIC/DEX | `recepcion_fisica_mics.php`, `RecepcionFisicaMICs.php:16-89` | Reporte filtra y deriva estados de `dex_suma`. |
| Seleccion por mismo estado | `recepcion_fisica_mics.js:1-57` | La UI evita lote mixto de estados. |
| Marcar registros | `recepcion_fisica_mics.js:131-184`, `ActualizarMICs.php:20-50` | Accept inserta historial y actualiza fecha. |
| Revertir registros | `recepcion_fisica_mics.js:186-248`, `ActualizarMICs.php:52-82` | Reject inserta historial y limpia fecha. |
| Historial | `recepcion_fisica_mics.js:250-270`, `ActualizarMICs.php:83-120`, `RecepcionFisicaMICs.php:100-118` | Historial une usuarios proveedor/cliente. |
| Schema | `.data_base/asgard.sql:11038-11071` | DDL soporta estados por fechas e historial. |

## Brechas

- Confirmar matriz de transiciones por actor.
- Revisar interpolacion SQL de ids.
- Revisar rama `accept q=enviado` para clientes.

