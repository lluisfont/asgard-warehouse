# document-service-plan-ocr-capture - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 2
- Tablas con mutacion observada: 1
- Riesgos candidatos: documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_planillasdp` | CREATE_AND_UPDATE | Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor. | document_id, exchange_id | transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; seguridad/autorizacion sensible | index_archivos/intercambioDocumental/ajax/lectura-ocr.php:13-35 \| index_archivos/intercambioDocumental/ajax/lectura-ocr.php:73-142 \| index_archivos/intercambioDocumental/ajax/lectura-ocr.php:144-156 \| lectura-ocr.php:13-35 \| lectura-ocr.php:73-142 \| lectura-ocr.php:144-156 \| index_archivos/intercambioDocumental/ajax/lectura-ocr.php:144-156 \| lectura-ocr.php:144-156 |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_planillasdp` | `document_id` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | - Soft delete de lecturas anteriores para el mismo `exchange_id` y `document_id`. \| BR-DSPC-004 \| Se reemplaza la lectura vigente por `exchange_id` y `document_id` usando `deleted_at`. \| \| `exchange_id`, `document_id`, `ubicacion`, `archivo`, `numero`, `numerobl`, `montocotizado`, `fechaimpresion`, `fecharegistro`, `fechavalidacion`, `deleted_at` \| ## Mutaciones observadas \| AND document_id=...`. |
| `dav_planillasdp` | `exchange_id` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | - Soft delete de lecturas anteriores para el mismo `exchange_id` y `document_id`. \| BR-DSPC-004 \| Se reemplaza la lectura vigente por `exchange_id` y `document_id` usando `deleted_at`. \| \| `exchange_id`, `document_id`, `ubicacion`, `archivo`, `numero`, `numerobl`, `montocotizado`, `fechaimpresion`, `fecharegistro`, `fechavalidacion`, `deleted_at` \| ## Mutaciones observadas \| `UPDATE dav_planillasdp SET deleted_at=CURRENT_TIMESTAMP() WHERE exchange_id=... |
