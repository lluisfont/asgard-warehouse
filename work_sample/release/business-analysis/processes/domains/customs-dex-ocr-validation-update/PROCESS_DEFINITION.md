# Customs DEX OCR Validation Update - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Leer una DEX mediante OCR desde intercambio documental, validar que corresponde a la carpeta operativa y actualizar datos aduaneros clave del caso cuando la lectura contiene declaracion, Sidunea y fecha de aceptacion.

## Alcance observado

- Lectura OCR de documento DEX asociado a `exchange_id`.
- Resolucion de la solicitud/carpeta desde `logis_embarques.idExchange` o `dav_casosprevios.idExchange`.
- Validacion de pertenencia por campo OCR `carpeta`.
- Actualizacion de `dav_casos.gestiondui`, `nodui`, `nosidunea` y `fechavalidaciondui`.
- Comparacion de campos OCR contra datos ASGARD de aduana, proveedor, lugar, incoterm, valores, pesos, subpartida, descripcion y embalaje.
- Devolucion de observaciones para revision operativa.

## Fuera de alcance observado

- Carga del documento al repositorio documental.
- Correccion manual posterior de diferencias.
- Aprobacion formal de DEX.
- Generacion de DAM/DUI.
- Auditoria o versionado de lecturas OCR.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario aduanero | Ejecuta la lectura OCR desde intercambio documental y revisa mensajes. |
| ASGARD | Obtiene la lectura OCR, resuelve caso/carpeta, actualiza campos aduaneros y compara datos. |
| Servicio OCR | Devuelve campos estructurados del modelo DEX. |
| Carpeta aduanera | Entidad operativa afectada por la actualizacion. |

## Entradas

- `path` y `name` del documento.
- `exchange_id`.
- `id` de solicitud/documento.
- Campos OCR: `carpeta`, `declaracion`, `sidunea`, `fecha_aceptacion` y campos DEX de contraste.

## Salidas

- Datos DUI/DEX actualizados en `dav_casos`.
- Mensaje de error o diferencias encontradas.
- Respuesta JSON con `idrequest`, `urlSource`, declaracion, Sidunea, `erroractualizacion`, `mensajeerroractualziacion` e `idcasosprevios`.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:1-33` | Recibe documento, `exchange_id` y ejecuta OCR con `MODELO_DEX`. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:40-62` | Resuelve `idcasosprevios` desde embarque o solicitud aduanera por `idExchange`. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:83-101` | Recupera datos ASGARD de caso, factura, partidas y catalogos para comparar. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:106-139` | Si la carpeta coincide, actualiza declaracion, Sidunea y fecha de validacion DUI. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:141-221` | Construye observaciones por diferencias entre OCR y datos ASGARD. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:230-243` | Devuelve respuesta JSON con datos OCR, errores y caso previo. |

## Criterios de aceptacion candidatos

- La lectura debe localizar una carpeta vigente por `exchange_id`.
- La DEX solo debe actualizar casos cuando el campo OCR `carpeta` coincide con la carpeta ASGARD.
- La declaracion debe separarse en gestion y numero antes de actualizar `dav_casos`.
- La fecha de aceptacion debe convertirse desde `dd/mm/yyyy` antes de persistirse.
- Las diferencias de campos relevantes deben quedar visibles para revision.
