# customs-dex-ocr-validation-update - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 5
- Tablas con mutacion observada: 1
- Riesgos candidatos: documentos/OCR; catalogos/semantica

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_casos` | UPDATE | Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor. | carpeta, fechavalidaciondui, idcasosprevios, nodui, nosidunea | transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; catalogo/semantica pendiente | index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:1-33 \| index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:40-62 \| index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:83-101 \| index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:106-139 \| index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:141-221 \| index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:230-243 \| index_archivos/intercambioDocumental/ajax/documento-ocr-dex.ph |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_casos` | `carpeta` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | # Customs DEX OCR Validation Update - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Leer una DEX mediante OCR desde intercambio documental, validar que corresponde a la carpeta operativa y actualizar datos aduaneros clave del caso cuando la lectura contiene declaracion, Sidunea y fecha de aceptacion. \| - Resolucion de la solicitud/carpeta desde `logis_embarques.idExchange` o `dav_casosprevios.idExchange`. \| - Validacion de pertenencia por campo OCR `carpeta`. \| ASGARD resuelve la carpeta relacionada usando `exchange_id`. \| Si existe solicitud, compara la ca |
| `dav_casos` | `fechavalidaciondui` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | PERSONAL_OR_CONTACT_DATA | - Actualizacion de `dav_casos.gestiondui`, `nodui`, `nosidunea` y `fechavalidaciondui`. \| BR-CDOVU-006 \| `fecha_aceptacion` OCR se convierte de `dd/mm/yyyy` a `yyyy-mm-dd` y actualiza `fechavalidaciondui`. \| \| `idcasos`, `idcasosprevios`, `carpeta`, `gestiondui`, `nodui`, `nosidunea`, `fechavalidaciondui`, `idaduana`, `idproveedor`, `idlugarembarque`, `idaduanasalida`, `idpaisdestino`, `idincoterm`, `idlugarentrega` \| \| - `UPDATE dav_casos SET fechavalidaciondui=... |
| `dav_casos` | `idcasosprevios` | Referencia funcional que vincula el flujo con otra entidad/catalogo. | BUSINESS_DATA | - Respuesta JSON con `idrequest`, `urlSource`, declaracion, Sidunea, `erroractualizacion`, `mensajeerroractualziacion` e `idcasosprevios`. \| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:40-62` \| Resuelve `idcasosprevios` desde embarque o solicitud aduanera por `idExchange`. \| \| `idcasosprevios`, `idExchange`, `idembarquelogis` \| \| \| `idcasos`, `idcasosprevios`, `carpeta`, `gestiondui`, `nodui`, `nosidunea`, `fechavalidaciondui`, `idaduana`, `idproveedor`, `idlugarembarque`, `idaduanasalida`, `idpaisdestino`, `idincoterm`, `idlugarentrega` \| \| WHERE idcasosprevios IN (...)`. |
| `dav_casos` | `nodui` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | - Actualizacion de `dav_casos.gestiondui`, `nodui`, `nosidunea` y `fechavalidaciondui`. \| ## Diagrama ```mermaid flowchart TD A["Documento DEX en intercambio"] --> B["Ejecutar OCR MODELO_DEX"] B --> C["Resolver carpeta por exchange_id"] C -->\|No encontrada\| Z["Responder sin solicitud"] C -->\|Encontrada\| D["Comparar carpeta OCR vs ASGARD"] D -->\|No coincide\| E["Informar DEX no pertenece"] D -->\|Coincide\| F["Actualizar gestion/nodui"] F --> G["A... \| BR-CDOVU-004 \| El campo OCR `declaracion` se interpreta como compuesto y actualiza `gestiondui` y `nodui`. \| \| `idcasos`, `idcasosprevios`, `carpet |
| `dav_casos` | `nosidunea` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | - Actualizacion de `dav_casos.gestiondui`, `nodui`, `nosidunea` y `fechavalidaciondui`. \| BR-CDOVU-005 \| `sidunea` OCR actualiza `dav_casos.nosidunea` cuando viene informado. \| \| `UPDATE dav_casos SET nosidunea` \| INFERRED_DRAFT_REVIEW_REQUIRED \| \| \| `idcasos`, `idcasosprevios`, `carpeta`, `gestiondui`, `nodui`, `nosidunea`, `fechavalidaciondui`, `idaduana`, `idproveedor`, `idlugarembarque`, `idaduanasalida`, `idpaisdestino`, `idincoterm`, `idlugarentrega` \| \| - `UPDATE dav_casos SET nosidunea=... |
