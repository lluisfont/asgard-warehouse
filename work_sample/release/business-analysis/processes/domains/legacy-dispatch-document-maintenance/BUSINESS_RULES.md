# Legacy Dispatch Document Maintenance - Business Rules

## Reglas inferidas

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-LDDM-001 | El modulo solo permite acceder a despachos del cliente `417`. | `despachover.php:5-9` | OBSERVED |
| BR-LDDM-002 | El registro debe tener `despacho = 1` para abrirse como despacho. | `despachover.php:8-9` | OBSERVED |
| BR-LDDM-003 | Guardar ficha actualiza campos basicos de embarque/despacho. | `despachover.php:20-21` | OBSERVED |
| BR-LDDM-004 | El documento requiere tipo, emisor, numero y formato en el formulario. | `despachover.php:235-258` | OBSERVED |
| BR-LDDM-005 | Si hay archivo, debe moverse a un directorio logistico bajo `FILES_PATH`. | `despachoajax.php:12-41` | INFERRED |
| BR-LDDM-006 | `iddocumento > 0` implica edicion documental; `iddocumento = 0` implica alta. | `despachoajax.php:57-65` | OBSERVED |

## Riesgos y reglas pendientes

- `logis_despachos` y `logis_documentos` no aparecen en el DDL inspeccionado.
- `idcliente = 417` esta hardcodeado.
- El alta documental contiene `INSET INTO`, probable defecto que impediria insertar.
- Variables `$ciudad`, `$despacho` y `$filename` aparentan no estar inicializadas correctamente.
- El endpoint imprime datos POST/FILES y podria exponer informacion sensible.

