# Document Exchange OCR - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Intercambio

| Estado candidato | Descripcion | Evidencia |
| --- | --- | --- |
| Preparado | ASGARD selecciona template y participantes. | `iniciarIntercambio.php:15-253` |
| Vinculado | El exchange id queda asociado a embarque o pedido. | `IntercambioDocumentalClass.php:290-305` |
| Con documentos | Existen documentos en `exchange_documents`. | `lectura_documentos_iasa.php:27-60` |
| Evaluado por OCR | ASGARD ejecuta modelo OCR y obtiene campos. | `OCRClass.php:108-206` |
| Con diferencias | Se detectan diferencias entre documentos relacionados. | `lectura_documentos_iasa.php:219-252`, `lectura_documentos_iasa.php:488-560` |
| Registrado / actualizado | Datos OCR se insertan o actualizan en tablas ASGARD. | `lectura_documentos_iasa.php:98-126`, `lectura_documentos_iasa.php:257-286`, `lectura_documentos_iasa.php:396-430` |

## Dependencias Documentales Observadas

- Factura de transporte depende de contrato cargado.
- Reporte SCP depende de lista de empaque cargada.
- Lista de empaque puede reemplazar detalle previo por coincidencia de pedido, entrega y placa.
