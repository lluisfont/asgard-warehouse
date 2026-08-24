# Alicorp Operational Document Package Dispatch - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Estados candidatos

| Estado | Significado candidato | Evidencia |
| --- | --- | --- |
| Pendiente de envio | Caso tiene exchange, no esta anulado y `embarque_documentos_enviados IS NULL`. | `getCasosParaDocumentacion` |
| Parametrizado | Existe parametrizacion documental para cliente/linea/proveedor. | `getListaParametrizacionConcatenado`, `getParametrizacionConcatenado` |
| ZIP generado | Documentos requeridos fueron descargados y empaquetados. | `documentacionAlicorp.php:73-110` |
| ZIP guardado | Archivo ZIP se persistio en carpeta operativa. | `GlobalClass::guardarArchivo` |
| Correo enviado | `MailClass::sendMail` invocado con adjuntos. | `documentacionAlicorp.php:169` |
| Carpeta marcada enviada | `embarque_documentos_enviados` actualizado. | `actualizarCarpetasEnviadasAlicorp` |

## Transiciones observadas

| Desde | Hacia | Condicion |
| --- | --- | --- |
| Pendiente de envio | Parametrizado | Caso coincide con proveedor/linea. |
| Parametrizado | ZIP generado | Existen documentos requeridos en exchange. |
| ZIP generado | ZIP guardado | `GlobalClass::guardarArchivo` devuelve `200`. |
| ZIP guardado | Correo enviado | Hay al menos un ZIP para enviar. |
| Correo enviado | Carpeta marcada enviada | Se invoca actualizacion de carpetas. |
