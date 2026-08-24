# Document Exchange OCR - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Flujo Principal Candidato

1. El usuario solicita iniciar un intercambio documental.
2. ASGARD identifica el modulo y el tramite.
3. ASGARD obtiene datos del cliente y contexto operativo.
4. ASGARD selecciona un template documental segun modulo y atributos.
5. ASGARD arma companias participantes: proveedor, logistico, seguro, cliente, despachante y transporte, segun template.
6. ASGARD obtiene coordinadores y correos desde tablas maestras.
7. ASGARD agrega correos operativos del cliente evitando repetidos.
8. ASGARD crea o actualiza el intercambio documental y participantes.
9. ASGARD vincula el exchange id al embarque o pedido si aplica.
10. El usuario carga documentos del intercambio.
11. Para documentos configurados, ASGARD llama al OCR externo.
12. ASGARD compara datos extraidos entre documentos relacionados.
13. ASGARD registra resultados o diferencias.

## Flujos OCR Observados

- Contrato IASA: lectura OCR e insercion de datos base en `dav_reporte_transportistas_iasa`.
- Factura de Transporte: requiere contrato cargado; compara precio, peso y flete contra contrato.
- Lista de Empaque: lee detalle por item y reemplaza registros previos equivalentes en `dav_reporte_detalles_transportistas_iasa`.
- Reporte SCP: requiere lista de empaque cargada; compara placa por orden y actualiza datos SCP si no hay diferencias.

## Excepciones Observadas

- Factura sin contrato previo: devuelve error "Debe cargar el documento: CONTRATO para continuar".
- SCP sin lista de empaque previa: devuelve error "Debe cargar el documento: LISTA EMPAQUE para continuar".
- Diferencias contrato/factura: devuelve estado de rechazo y detalle de diferencias.
- Diferencias lista de empaque/SCP: devuelve estado `diferencias`.
- Error OCR: devuelve mensaje de error de lectura.

## Evidencia

- `index_archivos/intercambioDocumental/ajax/iniciarIntercambio.php:15-188`
- `index_archivos/intercambioDocumental/IntercambioDocumentalClass.php:68-300`
- `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:19-65`
- `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:67-132`
- `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:133-334`
- `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:335-560`
- `index_archivos/intercambioDocumental/ajax/OCRClass.php:108-206`
