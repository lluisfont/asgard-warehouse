# Alicorp Transit Deadline Control - State Model

## Estados inferidos del vencimiento

| Estado candidato | Condicion observada | Significado funcional |
| --- | --- | --- |
| SIN_VENCIMIENTO | `alicorp_vencimiento IS NULL` antes de normalizar | Caso sin fecha de control Alicorp persistida. |
| VIGENTE | `DATEDIFF(alicorp_vencimiento, CURRENT_DATE()) > 5` o ya existe pase de salida | Caso sin alerta inmediata de vencimiento. |
| POR_VENCER | `DATEDIFF(alicorp_vencimiento, CURRENT_DATE()) <= 5` y `fechapasesalida IS NULL` | Caso requiere seguimiento por proximidad o vencimiento. |
| CON_SALIDA_DEX | `fechapasesalida IS NOT NULL` | Caso con pase de salida registrado. |

## Estados inferidos de cierre de transito

| Estado candidato | Condicion observada | Presentacion |
| --- | --- | --- |
| SIN_PAGAR | `alicorp_cierre_transito` distinto de `1` o nulo | `SIN PAGAR` |
| PAGADO | `alicorp_cierre_transito = 1` | `PAGADO` |

## Transiciones observadas

| Transicion | Disparador | Cambio persistido |
| --- | --- | --- |
| Asignar vencimiento | Ejecucion del control Alicorp para facturas en rango | `alicorp_vencimiento = fechavalidaciondui + 60 dias` |
| Marcar cierre pagado | Lector OCR/intercambio documental identifica documento aplicable | `alicorp_cierre_transito = 1` |

## Pendiente de validar

- Si existe estado formal vencido separado de por vencer.
- Si CEDEIM/reemplazo forman subestados operativos o atributos informativos.
- Si el cierre de transito admite reversa o correccion.
