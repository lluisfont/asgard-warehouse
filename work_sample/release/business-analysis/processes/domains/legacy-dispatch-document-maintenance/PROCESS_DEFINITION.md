# Legacy Dispatch Document Maintenance - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Mantener datos basicos de un despacho logistico legacy y adjuntar documentos asociados al despacho, incluyendo metadatos de emisor, numero, formato, importe, divisa y fecha de emision.

## Alcance observado

- Acceso a despacho por `iddespacho`.
- Listado legacy de despachos por referencia/orden de compra.
- Validacion de existencia del despacho para cliente hardcoded `417` y bandera `despacho = 1`.
- Edicion de datos de embarque/despacho: nombre, tipo de embarque, orden de compra, descripcion, origen, destino, peso, volumen, piezas, tipo de carga e incoterm.
- Consulta de operador asociado.
- Pestaña de documentos con formulario de adjunto.
- Captura de tipo de documento, emisor, numero, formato, archivo, fecha de emision, importe y divisa.
- Subida fisica del archivo a ruta bajo `FILES_PATH/logistica/...`.
- Intencion de actualizar o insertar registro en `logis_documentos`.

## Fuera de alcance observado

- Creacion inicial del despacho.
- Listado completo de despachos.
- Persistencia verificable contra schema, porque `logis_despachos` y `logis_documentos` no aparecen en `.data_base/asgard.sql`.
- Integracion con intercambio documental moderno/OCR.
- Flujo de rutas/productos/eventos dentro del mismo archivo, no reconstruido por falta de escritura observada suficiente.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario logistico legacy | Edita ficha de despacho y agrega documentos. |
| ASGARD legacy | Consulta/actualiza despacho y documentos. |
| Operador logistico | Aparece como operador asociado de solo lectura. |
| Filesystem | Almacena adjuntos cargados. |

## Entradas

- `GET id` / `iddespacho`.
- Campos de ficha de embarque/despacho.
- Metadatos de documento.
- Archivo adjunto.

## Salidas

- `UPDATE logis_despachos ... WHERE iddespacho`.
- `UPDATE logis_documentos ... WHERE iddocumento` cuando se edita documento.
- Insercion pretendida en `logis_documentos` cuando `iddocumento = 0`.
- Archivo copiado a directorio logistico legacy si la carga no falla.
- Mensajes de exito/error en pantalla o salida directa.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/despachos.php` | Lista despachos legacy del cliente `417`, filtra por orden de compra y pagina resultados. |
| `index_archivos/logistica/despachover.php:1-9` | Acceso a despacho con cliente `417` y `despacho = 1`. |
| `index_archivos/logistica/despachover.php:11-28` | Actualizacion de datos basicos en `logis_despachos`. |
| `index_archivos/logistica/despachover.php:35-39` | Consulta despacho, operador y catalogos. |
| `index_archivos/logistica/despachover.php:220-296` | Formulario de alta documental con metadatos y archivo. |
| `index_archivos/logistica/despachoajax.php:3-43` | Procesa POST, archivo y ruta de almacenamiento. |
| `index_archivos/logistica/despachoajax.php:57-65` | Actualiza documento o intenta insertar nuevo documento. |
| `.data_base/asgard.sql` | No contiene DDL observado para `logis_despachos` ni `logis_documentos`. |

## Criterios de aceptacion candidatos

- Solo debe abrirse un despacho existente del cliente y marcado como despacho.
- Guardar ficha debe actualizar los campos basicos del despacho.
- Agregar documento debe guardar metadatos y archivo.
- Editar documento debe actualizar metadatos y opcionalmente reemplazar archivo.
- El schema requerido debe existir en el entorno real antes de considerar este flujo operativo.
