# logistics-bl-policy-ocr-capture - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 12
- Tablas con mutacion observada: 1
- Riesgos candidatos: documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `logis_lecturablpoliza` | CREATE_AND_UPDATE | Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor. | aplicacionps, cantidadbl, cantidadps, emisorbl, fechabl, fechaps, idembarque, numerobl, numerops, ubicacionbl, ubicacionps, valorps | transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia | index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:10-18 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:35-44 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:52-141 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:143-235 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:240-250 \| lectura-ocr-bl.php:10-18 \| lectura-ocr-bl.php:35-44 \| lectura-ocr-bl.php:52-141 \| lectura-ocr-bl.php:143-235 \| lectura-ocr-bl.php:240-250 \| EV-SQL_QUE |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `logis_lecturablpoliza` | `aplicacionps` | Campo de soporte funcional mencionado en datos/reglas del flujo. | PERSONAL_OR_CONTACT_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `cantidadbl` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `cantidadps` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `emisorbl` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `fechabl` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | BR-LBPOC-005 \| Las comparaciones se devuelven cuando `DATEDIFF(fechabl, fechaps)` es negativo. \| \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `fechaps` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | BR-LBPOC-005 \| Las comparaciones se devuelven cuando `DATEDIFF(fechabl, fechaps)` es negativo. \| \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `idembarque` | Referencia funcional que vincula el flujo con otra entidad/catalogo. | BUSINESS_DATA | \| `idExchange`, `idembarquelogis` \| \| \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `numerobl` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `numerops` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `ubicacionbl` | Campo de soporte funcional mencionado en datos/reglas del flujo. | PERSONAL_OR_CONTACT_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `ubicacionps` | Campo de soporte funcional mencionado en datos/reglas del flujo. | PERSONAL_OR_CONTACT_DATA | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
| `logis_lecturablpoliza` | `valorps` | Valor economico usado en calculos, liquidaciones, conciliacion o reporteria. | FINANCIAL_OR_COMMERCIAL | \| `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` \| ## Mutaciones observadas |
