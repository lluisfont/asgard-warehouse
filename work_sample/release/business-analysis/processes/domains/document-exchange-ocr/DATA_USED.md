# Document Exchange OCR - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Tabla / Fuente | Uso candidato | Evidencia |
| --- | --- | --- |
| `intercambiodocumental.exchanges` | Cabecera del intercambio documental externo. | `lectura_documentos_iasa.php:45-58` |
| `intercambiodocumental.exchange_documents` | Documentos del intercambio, archivo, ruta, estado y document id. | `lectura_documentos_iasa.php:27-35`, `lectura_documentos_iasa.php:45-60`, `.data_base/asgard.sql:11121-11132` |
| `logis_embarques` | Embarque vinculado al exchange mediante `idExchange`; fuente de cliente, proveedor, transportista y correos. | `IntercambioDocumentalClass.php:68-88`, `IntercambioDocumentalClass.php:290-296`, `.data_base/asgard.sql:12210-12217` |
| `logis_pedidos` | Pedido vinculado al exchange mediante `exchange_id`. | `IntercambioDocumentalClass.php:299-305`, `.data_base/asgard.sql:410-418` |
| `dav_cliente` | Datos del cliente y correo. | `IntercambioDocumentalClass.php:184-195` |
| `dav_proveedor` / `dav_proveedorcontactos` | Proveedor y coordinadores de proveedor. | `IntercambioDocumentalClass.php:198-248` |
| `dav_usuario` | Coordinadores internos / despachante. | `IntercambioDocumentalClass.php:170-181` |
| `dav_reporte_transportistas_iasa` | Resultado OCR de contrato y factura de transporte. | `lectura_documentos_iasa.php:98-126`, `lectura_documentos_iasa.php:257-286` |
| `dav_reporte_detalles_transportistas_iasa` | Resultado OCR de lista de empaque y actualizacion SCP. | `lectura_documentos_iasa.php:373-430`, `lectura_documentos_iasa.php:513-545` |
| `ocr_lecturas` | Conteo de lecturas por modelo OCR. | `OCRClass.php:193-201`, `.data_base/asgard.sql:13034-13042` |

## Datos Sensibles o Criticos

- Correos de participantes.
- Rutas y nombres de archivos.
- IDs externos de intercambio y documentos.
- Datos contractuales, factura, flete, peso, placa, ruta, pedido y SCP.
