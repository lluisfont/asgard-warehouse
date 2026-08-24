# Export MIC DEX Physical Reception Control - Business Rules

## Reglas inferidas

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-EMDPRC-001 | Solo se consultan registros con `fecha_verificacion_salida IS NOT NULL`. | `RecepcionFisicaMICs.php:16-36` | OBSERVED |
| BR-EMDPRC-002 | El estado documental se deriva por prioridad: concluido, enviado, recibido, pendiente. | `RecepcionFisicaMICs.php:23-24` | OBSERVED |
| BR-EMDPRC-003 | Solo se habilita seleccion masiva de registros del mismo estado. | `recepcion_fisica_mics.js:1-57` | OBSERVED |
| BR-EMDPRC-004 | Cada accept/reject inserta historial en `dex_suma_estado_historial`. | `ActualizarMICs.php:18-29`, `ActualizarMICs.php:52-60` | OBSERVED |
| BR-EMDPRC-005 | Tipo usuario `1` corresponde a proveedores y `2` a clientes. | `ActualizarMICs.php:17` | INFERRED |
| BR-EMDPRC-006 | Clientes marcan o revierten conclusion/envio; proveedores marcan o revierten recibido/enviado segun rama observada. | `ActualizarMICs.php:30-38`, `ActualizarMICs.php:62-70` | INFERRED |
| BR-EMDPRC-007 | El historial muestra usuario proveedor o cliente segun tipo usuario. | `RecepcionFisicaMICs.php:100-118` | OBSERVED |

## Riesgos y reglas pendientes

- En `accept`, tipo `enviado` para clientes actualiza `fecha_concluido`, lo que puede ser intencional o error.
- Los ids se interpolan directamente en SQL.
- La matriz exacta de transiciones por tipo usuario debe confirmarse.
- No se observa validacion server-side de que todos los ids seleccionados tengan el estado enviado por la UI.

