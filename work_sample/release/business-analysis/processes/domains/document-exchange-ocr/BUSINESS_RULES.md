# Document Exchange OCR - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| DEO-BR-001 | El template documental depende del modulo y del tipo de operacion. | `iniciarIntercambio.php:15-116`, `IntercambioDocumentalClass.php:8-29` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-002 | Los participantes se componen desde cliente, proveedor, operador logistico, seguro, despachante y transporte segun template. | `iniciarIntercambio.php:118-253` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-003 | Los correos operativos del cliente se separan por `;` y se deduplican antes de agregarlos. | `iniciarIntercambio.php:155-188` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-004 | El exchange id puede vincularse a un embarque en `logis_embarques.idExchange`. | `IntercambioDocumentalClass.php:290-296` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-005 | El exchange id puede vincularse a un pedido en `logis_pedidos.exchange_id`. | `IntercambioDocumentalClass.php:299-305` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-006 | Para cliente 775 o 755, la factura de transporte requiere contrato cargado antes de validarse. | `lectura_documentos_iasa.php:19-39`, `lectura_documentos_iasa.php:133-145` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-007 | Factura de transporte se compara contra contrato por precio unitario, peso/cantidad y flete total. | `lectura_documentos_iasa.php:219-252` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-008 | Lista de empaque reemplaza registros previos coincidentes por pedido, entrega y placa antes de insertar nuevos detalles. | `lectura_documentos_iasa.php:373-430` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-009 | Reporte SCP requiere lista de empaque cargada antes de compararse. | `lectura_documentos_iasa.php:444-461` | INFERRED_DRAFT_REVIEW_REQUIRED |
| DEO-BR-010 | Reporte SCP compara placa contra lista de empaque por orden/pedido. | `lectura_documentos_iasa.php:488-560` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Pendiente de Confirmar

- Catalogo completo de document ids y su nombre de negocio.
- Significado funcional de clientes 775 y 755.
- Estados formales de documento en `intercambiodocumental.exchange_documents.status`.
