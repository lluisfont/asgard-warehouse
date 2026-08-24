# vehicle-sales-invoice-ocr-ledger-capture - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 7
- Tablas con mutacion observada: 1
- Riesgos candidatos: SQL dinamico; atomicidad/concurrencia; permisos/autorizacion; documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `logis_libroventas` | CREATE | Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor. | chasis, fecha, importe, nit, noautorizacion, nofactura, preciounitario | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; SQL construido dinamicamente; atomicidad/concurrencia pendiente; seguridad/autorizacion sensible | index_archivos/intercambioDocumental/ajax/lectura-ocr-ft.php:16-55 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-ft.php:64-101 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-ft.php:137-166 \| index_archivos/intercambioDocumental/ajax/lectura-ocr-ft.php:90-99 \| lectura-ocr-ft.php:16-55 \| lectura-ocr-ft.php:64-101 \| lectura-ocr-ft.php:137-166 \| EV-SQL_QUERY-1B0D61F6A40D6C .data_base/asgard.sql:29229 READS access to logis_libroventas \| EV-SQL_QUERY-FB47BA0CFC30F6 index_archivos/in |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `logis_libroventas` | `chasis` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | - Extraccion de factura, fecha/hora, chasis, precio unitario e importe. \| La factura OCR debe tener numero, fecha, chasis e importe. \| ASGARD normaliza fecha, chasis y montos. \| ```mermaid flowchart TD A["Factura PDF/ZIP/RAR"] --> B["OCR MODELO_IMCRUZ"] B --> C["Extraer factura, fecha, chasis, importes"] C --> D["Buscar duplicado"] D -->\|Existe\| E["No insertar"] D -->\|No existe e importe > 0\| F["Insertar logis_libroventas"] ``` \| BR-VSILC-004 \| El caracter OCR `Ø` en chasis se normaliza a `0`. |
| `logis_libroventas` | `fecha` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | # Vehicle Sales Invoice OCR Ledger Capture - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Leer facturas Imcruz por OCR desde documentos PDF o paquetes y registrar facturas de venta de vehiculos en `logis_libroventas` evitando duplicados por fecha y numero. \| - Extraccion de factura, fecha/hora, chasis, precio unitario e importe. \| - Insercion en `logis_libroventas` si no existe factura para la misma fecha y numero, y el importe es positivo. \| ASGARD normaliza fecha, chasis y montos. \| ASGARD busca duplicado por fecha y numero de factura. |
| `logis_libroventas` | `importe` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | FINANCIAL_OR_COMMERCIAL | - Extraccion de factura, fecha/hora, chasis, precio unitario e importe. \| - Insercion en `logis_libroventas` si no existe factura para la misma fecha y numero, y el importe es positivo. \| `index_archivos/intercambioDocumental/ajax/lectura-ocr-ft.php:90-99` \| Valida duplicado por fecha/factura e importe positivo. \| Si no hay duplicado y el importe es positivo, inserta en `logis_libroventas`. \| ```mermaid flowchart TD A["Factura PDF/ZIP/RAR"] --> B["OCR MODELO_IMCRUZ"] B --> C["Extraer factura, fecha, chasis, importes"] C --> D["Buscar duplicado"] D -->\|Existe\| E["No insertar"] D -->\|No existe e |
| `logis_libroventas` | `nit` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | PERSONAL_OR_CONTACT_DATA | # Vehicle Sales Invoice OCR Ledger Capture - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Leer facturas Imcruz por OCR desde documentos PDF o paquetes y registrar facturas de venta de vehiculos en `logis_libroventas` evitando duplicados por fecha y numero. \| - Extraccion de factura, fecha/hora, chasis, precio unitario e importe. \| \| `noautorizacion`, `nofactura`, `fecha`, `preciounitario`, `importe`, `chasis` \| ## Mutaciones observadas |
| `logis_libroventas` | `noautorizacion` | Campo de soporte funcional mencionado en datos/reglas del flujo. | PERSONAL_OR_CONTACT_DATA | \| `noautorizacion`, `nofactura`, `fecha`, `preciounitario`, `importe`, `chasis` \| ## Mutaciones observadas |
| `logis_libroventas` | `nofactura` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `noautorizacion`, `nofactura`, `fecha`, `preciounitario`, `importe`, `chasis` \| ## Mutaciones observadas |
| `logis_libroventas` | `preciounitario` | Valor economico usado en calculos, liquidaciones, conciliacion o reporteria. | PERSONAL_OR_CONTACT_DATA | \| `noautorizacion`, `nofactura`, `fecha`, `preciounitario`, `importe`, `chasis` \| ## Mutaciones observadas |
