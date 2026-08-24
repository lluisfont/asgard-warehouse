# Alicorp Albo OCR Payment Reconciliation - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Leer facturas ALBO/FALBO mediante OCR desde intercambio documental y usar el resultado para reconciliar pagos de concepto observado `272`, actualizar notas de debito y marcar cierre de transito Alicorp cuando el DIM coincide.

## Alcance observado

- Lectura OCR de PDF o paquete ZIP/RAR con facturas.
- Descompresion remota para paquetes y recorrido de documentos PDF.
- Resolucion de contexto por `exchange_id` desde embarque logistico, solicitud aduanera o solicitud de asesoria gestion.
- Busqueda de pago pendiente por concepto `272`, monto OCR y ausencia de numero.
- Actualizacion de `dav_pagosdetalle.nro`, `fecha_numero`, metadata OCR y JSON de lectura en la rama de paquete.
- Actualizacion de `dav_notasdebitodetalle.nro` y `fecha_numero`.
- Marcado de `dav_casos.alicorp_cierre_transito=1` si el DIM OCR coincide.
- Vinculacion `dav_facturacomercial.ages_id` cuando el contexto proviene de asesoria gestion.

## Fuera de alcance observado

- Registro inicial del pago o nota de debito.
- Validacion contable posterior.
- Correccion manual de importes.
- Gobierno de credenciales, servidor remoto o limpieza de archivos.
- Auditoria formal de aprobacion de OCR.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario operativo | Lanza OCR de factura desde intercambio documental. |
| ASGARD | Extrae datos, localiza caso/pago y aplica reconciliacion. |
| Servicio OCR | Devuelve total, DIM, numero de factura y fecha. |
| Operacion Alicorp | Consume cierre de transito y pagos reconciliados. |

## Entradas

- `path`, `name`, `exchange_id`, `id`.
- Archivo PDF, ZIP o RAR.
- Campos OCR: `total`, `dim`, `num_fact`, `fecha`.
- Concepto fijo observado `272`.

## Salidas

- Pago actualizado con numero y fecha de factura.
- Nota de debito actualizada con numero y fecha.
- Cierre de transito marcado como pagado en casos con DIM coincidente.
- JSON OCR almacenado en algunas ramas de procesamiento.
- Respuesta JSON con monto OCR, mensaje, consulta usada y comando remoto si aplica.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:1-45` | Inicializa OCR ALBO/FALBO y concepto `272`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:51-82` | Para ZIP/RAR usa SSH remoto, descomprime y recorre PDFs. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:91-162` | Resuelve contexto por embarque, solicitud aduanera o AGES y arma consulta de pago pendiente. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:171-177` | Marca cierre de transito y vincula factura comercial con AGES cuando corresponde. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:183-195` | Actualiza pago, nota de debito y metadata OCR para paquete. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:225-347` | Rama PDF directa con resolucion, cierre y actualizacion de pago/nota. |

## Criterios de aceptacion candidatos

- La lectura debe extraer monto, DIM, numero y fecha de factura.
- El pago candidato debe estar pendiente de numero, pertenecer al contexto resuelto y coincidir por monto.
- La fecha OCR debe tener formato `dd/mm/yyyy` para actualizar pago y nota.
- El cierre Alicorp solo debe marcarse cuando el DIM OCR coincide con el DIM construido desde ASGARD.
- La respuesta debe indicar si se actualizo el pago, falta pago/concepto o la fecha es incorrecta.
