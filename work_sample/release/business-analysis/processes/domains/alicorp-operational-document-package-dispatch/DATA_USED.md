# Alicorp Operational Document Package Dispatch - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Entidades y tablas observadas

| Tabla/Fuente | Uso candidato | Campos observados |
| --- | --- | --- |
| `dav_casos` | Casos/carpeta pendientes y marca de envio. | `idcasos`, `carpeta`, `idcliente`, `idproveedor`, `idclientelineas`, `nodui`, `embarque_documentos_enviados`, `anulado` |
| `dav_casosprevios` | Relacion con embarque, fechas y solicitud. | `idembarquelogis`, `fechafin`, `fechaaprobacion`, `ordencompra`, `fechaembarque` |
| `logis_embarques` | Exchange del embarque y factura comercial. | `idExchange`, `facturacomercial` |
| `dav_facturacomercial` | Factura y fecha de factura. | `nofactura`, `fechafactura` |
| `logis_parametrizacionconcatenado` | Parametrizacion por cliente/linea/proveedor. | `idcliente`, `idclientelineas`, `idproveedor`, `idincoterms`, `idmodotransporte`, `idpaisdestino` |
| `logis_parametrizaciondocumentos` | Documentos requeridos por parametrizacion. | `iddocuments` |
| Document Exchange API | Documentos y archivos del exchange. | `document.id`, `document.name`, `file_path`, `file_name` |
| Contactos | Destinatarios de correo. | Consignatario tipo contacto `1`, operador tipo contacto `1` |

## Mutaciones observadas

| Operacion | Destino | Evidencia |
| --- | --- | --- |
| Guardar ZIP generado | Filesystem / `GlobalClass::guardarArchivo` | `documentacionAlicorp.php:108-122` |
| Marcar carpetas enviadas | `dav_casos.embarque_documentos_enviados` | `SolicitudesClass.php:1203-1206` |

## Integraciones observadas

- `DOCUMENTS_API` para listar documentos del exchange.
- `DOCUMENTS_FILES` para descargar archivos.
- `MailClass::sendMail` para envio con adjuntos.
