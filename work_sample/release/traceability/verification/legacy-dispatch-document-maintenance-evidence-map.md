# Legacy Dispatch Document Maintenance - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia por capacidad

| Capacidad | Evidencia | Inferencia |
| --- | --- | --- |
| Abrir despacho legacy | `index_archivos/logistica/despachover.php:1-9` | El despacho se filtra por cliente hardcoded y bandera. |
| Actualizar ficha | `index_archivos/logistica/despachover.php:11-28` | La ficha se actualiza en `logis_despachos`. |
| Capturar documento | `index_archivos/logistica/despachover.php:220-296` | El formulario captura documento y archivo. |
| Procesar archivo | `index_archivos/logistica/despachoajax.php:3-43` | El endpoint intenta guardar adjunto en filesystem. |
| Persistir documento | `index_archivos/logistica/despachoajax.php:57-65` | Existe intencion de update/insert en `logis_documentos`. |
| Brecha DDL | `.data_base/asgard.sql` | No se encontro DDL de tablas legacy referenciadas. |

## Riesgos

- Alta documental posiblemente rota por `INSET`.
- Variables no definidas en carga de archivo.
- Salida debug con POST/FILES.
- Cliente hardcoded `417`.

