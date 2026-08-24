# Document Exchange OCR - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Intercambio documental con participantes externos y lectura OCR.

Objetivo de negocio candidato: crear un intercambio documental asociado a operaciones de aduana, logistica, pedido o servicio adicional; asignar empresas y coordinadores participantes; recibir documentos; y, para ciertos clientes/documentos, leerlos con OCR para validar consistencia operativa.

## Trigger

El proceso se inicia desde `intercambioDocumental/ajax/iniciarIntercambio.php`, recibiendo modulo (`m`), tramite (`t`), cliente, embarque/pedido y datos operativos por `POST`.

Evidencia:

- `index_archivos/intercambioDocumental/ajax/iniciarIntercambio.php:3-13`
- `index_archivos/intercambioDocumental/ajax/iniciarIntercambio.php:15-116`
- `index_archivos/intercambioDocumental/IntercambioDocumentalClass.php:8-29`

## Actores

- Cliente importador: participa como empresa cliente y aporta correos operativos.
- Proveedor de mercancia: participa si el template lo requiere.
- Operador logistico / transportista: participa en flujos logisticos y de embarque.
- Agente de seguro: participa cuando el template incluye compania de seguro.
- Despachante / agente de aduana: participa como coordinador o compania externa.
- Sistema ASGARD: arma participantes, vincula exchange id y ejecuta validaciones OCR.
- Servicio externo OCR: Form Recognizer / Document Intelligence configurado con `URL_OCR`, `VERSION_OCR` y modelos por documento.

## Resultado Esperado

- Se selecciona un template documental segun modulo y atributos de tramite.
- Se construye la lista de companias y coordinadores.
- Se evita duplicar correos dentro del participante cliente.
- El exchange id queda vinculado al embarque o pedido cuando corresponde.
- Los documentos cargados pueden ser leidos por OCR y comparados entre si.

## Alcance Observado

- Modulos observados: `aduana`, `aduanaEmbarque`, `logistica`, `logistica_cotizacion_embarque`, `logistica_duplicado_embarque`, `servicio_adicional`, `pedido`.
- OCR especializado observado para cliente `775` o `755` en documentos IASA.
- Documentos IASA observados: contrato, factura de transporte, lista de empaque y reporte SCP.

## Estado de Validacion

Reconstruccion candidata desde codigo, SQL y Graphify. La revision humana queda diferida hasta completar todos los dominios del baseline.

## Variantes de consulta/historial relacionadas

- `index_archivos/intercambioDocumental/ajax/exchange-history-excel.php` descarga historial Excel de versiones por intercambio.
- `index_archivos/intercambioDocumental/ajax/document-history-excel.php` descarga historial Excel de versiones por documento.
- `index_archivos/intercambioDocumental/lista_intercambios_documentales.php` consulta lista de intercambios desde la API documental.
- `index_archivos/size-intercambio-documental.php` calcula tamano acumulado de documentos de intercambios logisticos para cliente observado, consultando la API documental y cabeceras de ficheros.
- `index_archivos/intercambioDocumentalV2/index.php` muestra vista v2/familiar de intercambio documental desde API `intercambio-documental-v2/lista/{exchangeId}`.
