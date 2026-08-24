# Alicorp Supplier OCR Payment Reconciliation - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Reconciliar pagos Alicorp desde facturas OCR de proveedores/documentos SENAVEX, FDAB y Jennefer, actualizando pagos, notas de debito y cierre de transito cuando los datos OCR coinciden con contexto, concepto, monto y DIM.

## Alcance observado

- SENAVEX: conceptos por UUID de documento (`208`, `270`, `256`, `271`) y modelo `MODELO_SENAVEX`.
- FDAB: concepto `273`, modelo `MODELO_FACTUTA_DAB`, soporte PDF y ZIP/RAR.
- Jennefer: concepto `274`, modelo `MODELO_FACTUTA_JENNEFER`.
- Resolucion por `exchange_id` desde embarque, solicitud aduanera o AGES.
- Busqueda de pago pendiente por concepto, monto y `nro` vacio.
- Actualizacion de `dav_pagosdetalle`, `dav_notasdebitodetalle`, metadata OCR y cierre `alicorp_cierre_transito`.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-senavex.php:9-23` | Mapea UUID documental a conceptos. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-senavex.php:51-144` | Resuelve contexto, busca pago y actualiza pago/nota/cierre. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-fdab.php:7-13` | Concepto FDAB fijo `273`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-fdab.php:24-53` | Soporta ZIP/RAR con descompresion remota. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-fdab.php:88-192` | Rama paquete: contexto, DIM, pago y nota. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-fdab.php:230-347` | Rama PDF directa equivalente. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-jennefer.php:7-14` | Concepto Jennefer fijo `274`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-jennefer.php:32-155` | Extrae DIM de campo DEX, busca pago y actualiza pago/nota/cierre. |

## Criterios de aceptacion candidatos

- El concepto de pago debe derivarse del documento/proveedor.
- El pago candidato debe estar pendiente, tener monto igual al OCR y pertenecer al contexto resuelto.
- La fecha OCR debe poder convertirse antes de actualizar pago y nota.
- El cierre de transito debe marcarse cuando el DIM OCR coincide con ASGARD.
