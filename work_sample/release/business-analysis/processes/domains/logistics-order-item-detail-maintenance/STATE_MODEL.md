# Logistics Order Item Detail Maintenance - State Model

## Estados inferidos

| Estado candidato | Condicion observada | Significado |
| --- | --- | --- |
| ITEM_CARGADO | Posicion retornada por API | Visible para revision. |
| ITEM_EDITADO | Usuario modifica campo en formulario | Cambio pendiente de guardar. |
| ITEM_ACTUALIZADO | `UPDATE` ejecutado para la posicion | Cambio persistido. |

## Pendiente de validar

- Si existen estados formales de posicion bloqueada, agrupada o embarcada.
- Si una posicion eliminada logicamente puede ser editada por este endpoint.
