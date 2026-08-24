# Logistics Order Item Detail Maintenance - Business Rules

| ID | Regla | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-LOIDM-001 | Cliente `417` muestra origen/destino en lugar de almacen. | `items_pedido.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOIDM-002 | Cliente `802` muestra cantidad en la grilla de items. | `items_pedido.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOIDM-003 | Guardar aplica cada campo POST como una actualizacion independiente sobre la posicion. | `saveDescripcionItems.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOIDM-004 | El id de posicion y nombre de columna se derivan del nombre del input. | `explode("_", $key)` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Riesgos / validaciones pendientes

- Definir lista permitida de columnas editables.
- Confirmar permisos y bloqueo por estado/finalizacion.
- Confirmar si el cambio requiere auditoria.
- Validar que la posicion pertenece al pedido/cliente en sesion.
