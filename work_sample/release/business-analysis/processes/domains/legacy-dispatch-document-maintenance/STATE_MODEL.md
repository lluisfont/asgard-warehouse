# Legacy Dispatch Document Maintenance - State Model

## Estados candidatos de despacho

| Estado | Condicion | Significado inferido |
| --- | --- | --- |
| No accesible | No existe fila para cliente `417`, `iddespacho` y `despacho = 1`. | Redireccion a listado. |
| Editable | Existe fila de despacho. | Permite editar ficha y agregar documentos. |

## Estados candidatos de documento

| Estado | Condicion | Significado inferido |
| --- | --- | --- |
| Nuevo | `iddocumento = 0`. | Se intenta crear documento. |
| Existente | `iddocumento > 0`. | Se actualiza documento. |
| Con adjunto | `$_FILES['archivo']['error'] == 0`. | Debe registrar/actualizar nombre de archivo. |

## Transiciones observadas

| Transicion | Persistencia | Evidencia |
| --- | --- | --- |
| Editable -> Editable actualizado | `UPDATE logis_despachos`. | `despachover.php` |
| Nuevo -> Existente | `INSERT` pretendido en `logis_documentos`. | `despachoajax.php` |
| Existente -> Existente actualizado | `UPDATE logis_documentos`. | `despachoajax.php` |

