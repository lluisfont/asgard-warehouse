# Alicorp OCR Bulk Shipment Intake - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Automatizar la creacion masiva de embarques logisticos y solicitudes de gestion aduanera para Alicorp a partir de facturas comerciales leidas por OCR y listas de empaque asociadas.

## Alcance observado

- Modal de carga masiva en logistica con modalidad de transporte, linea, indicador `Todos OL`, factura comercial y lista de empaque.
- Carga de factura comercial como PDF individual o ZIP con PDFs.
- Carga de lista de empaque como ZIP con archivos XLSX.
- Lectura OCR Alicorp y persistencia de cabecera, detalle e importes internacionales.
- Creacion automatica de embarque logistico por factura leida.
- Creacion automatica de solicitud de Gestion Aduanera cuando los datos minimos OCR estan completos.
- Creacion/asociacion de intercambio documental desde JavaScript posterior a la respuesta.
- Asociacion de lista de empaque al caso previo cuando el nombre del archivo contiene el pedido.
- Determinacion candidata de servicios adicionales: certificado de origen, inocuidad y fitosanitario.

## Fuera de alcance observado

- Correccion manual de OCR antes de crear embarque.
- Validacion canonica de duplicados por factura, pedido u orden de compra.
- Reversion atomica cuando una parte del encadenamiento falla.
- Envio automatico final de servicios adicionales; se observa preparacion y llamada posterior desde UI.
- Validacion formal de permisos especificos para OCR masivo.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario logistico Alicorp | Carga facturas/listas y revisa resultado masivo. |
| OCR Alicorp | Extrae datos de factura comercial e items. |
| ASGARD Logistica | Crea embarques desde datos OCR. |
| ASGARD Gestion Aduanera | Crea solicitudes aduaneras asociadas al embarque. |
| Intercambio Documental | Recibe exchange logistico y aduanero desde llamadas JS. |

## Entradas

- Modalidad de transporte masiva.
- Linea de cliente masiva.
- Indicador `todosmasivo`.
- Archivo factura comercial: PDF o ZIP.
- Archivo lista de empaque: ZIP.
- Sesion de cliente/usuario.

## Salidas

- Registros `ocr_alicorp`, `ocr_alicorp_detalle`, `ocr_alicorp_internacional`.
- Registros de embarque logistico con `idocr_alicorp`.
- Solicitudes `dav_casosprevios` asociadas al embarque.
- Lista de empaque asociada a la solicitud cuando se identifica por pedido.
- Exchanges logisticos/aduaneros creados o asociados desde la UI.
- Resultado por archivo con errores, faltantes o ids creados.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/index.php:80-110` | Modal de carga masiva OCR con factura comercial y lista de empaque. |
| `index_archivos/logistica/js/datosEmbarques.js:1164` | Envia formulario a `get-ocr-alicorp-masivo.php`. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php:20-63` | Copia ZIP/PDF y extrae PDFs/XLSX en `/datadrive1/OCRAlicorp`. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php:83-214` | Ejecuta OCR y arma POST para crear embarque. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php:217-288` | Crea embarque y solicitud de Gestion Aduanera. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php:294-423` | Determina servicios adicionales segun linea, proveedor, producto y peso. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php:437-455` | Asocia lista de empaque por coincidencia del pedido en el nombre. |
| `index_archivos/logistica/js/datosEmbarques.js:1195-1300` | Crea/edita exchanges y actualiza `idExchange` de la solicitud. |
| `index_archivos/ocr/lectura_ocr.php:488-607` | Inserta lectura OCR Alicorp y devuelve datos persistidos. |
| `.data_base/asgard.sql:12797-12879` | DDL de tablas OCR Alicorp. |

## Criterios de aceptacion candidatos

- Cada factura PDF valida debe producir una lectura OCR persistida.
- Cada lectura con datos minimos debe crear un embarque logistico.
- Cada embarque con datos completos debe crear una solicitud de Gestion Aduanera.
- La solicitud debe quedar enlazada al exchange aduanero y al exchange logistico cuando la UI completa las llamadas.
- Las listas de empaque deben asociarse al pedido correspondiente cuando el nombre permite emparejar.
- El resultado masivo debe mostrar errores por archivo sin ocultar creaciones parciales.
