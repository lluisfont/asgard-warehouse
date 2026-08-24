# Alicorp Transit Deadline Control - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Controlar casos de exportacion/importacion del cliente Alicorp con foco en fecha de factura, validacion DEX, vencimiento de transito, cierre de transito y alertas de proximidad a vencimiento.

## Alcance observado

- Consulta operativa de control Alicorp filtrada por rango de fecha de factura, proveedor, vencimiento, producto, pedido, factura, lugar de entrega y pais.
- Normalizacion automatica de `dav_casos.alicorp_vencimiento` cuando el caso Alicorp no tiene vencimiento informado.
- Calculo de vencimiento como `fechavalidaciondui + 60 dias`.
- Visualizacion de datos de carpeta, linea, pedido, aduana, consignatario, factura, producto, fecha factura, recepcion/envio, observacion, servicios adicionales, estado GE, incoterm, destino, DEX, vencimiento, pesos, salida DEX, canal, cierre de transito, CEDEIM, reemplazo y oficial.
- Integracion de facturas anuladas de cliente para mantener visibilidad de documentos anulados dentro del mismo reporte.
- Indicador de vencimiento cuando faltan cinco dias o menos y no existe pase de salida.
- Exportacion a Excel del resultado.
- Relacion con OCR/document exchange cuando lecturas OCR marcan `alicorp_cierre_transito`.

## Fuera de alcance observado

- Definicion oficial del SLA de 60 dias.
- Flujo manual que informa `alicorp_hora_recepcion`, `alicorp_hora_envio`, `alicorp_observacion`, `alicorp_cedeim` o `alicorp_reemplazo`.
- Pago real del cierre de transito fuera del flag observado.
- Alta de casos Alicorp y carga OCR completa.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario operativo | Consulta casos Alicorp, revisa vencimientos y exporta seguimiento. |
| ASGARD | Completa vencimiento Alicorp faltante y consolida datos operativos. |
| OCR / intercambio documental | Puede marcar cierre de transito Alicorp desde documentos reconocidos. |
| Cliente Alicorp | Ambito de negocio observado mediante `idcliente = 775`. |

## Entradas

- Rango de fechas de factura.
- Filtros de vencimiento, proveedor, producto, pedido, factura, lugar de entrega y pais.
- Datos de caso, factura comercial, partidas, tramite, canal, aduana, proveedor y pais.
- Fechas `fechavalidaciondui` y `fechapasesalida`.

## Salidas

- `dav_casos.alicorp_vencimiento` informado para casos Alicorp que no lo tengan.
- Grilla de control de transito Alicorp.
- Indicador de riesgo de vencimiento.
- Excel operativo de seguimiento.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/control_alicorpquery.php:1-18` | Normaliza rango de fechas y filtros de vencimiento/proveedor. |
| `index_archivos/operativos/control_alicorpquery.php:29-139` | Consulta consolidada de casos Alicorp y facturas anuladas. |
| `index_archivos/operativos/control_alicorpquery.php:145-154` | Actualiza `alicorp_vencimiento` con `fechavalidaciondui + 60 dias` para cliente `775`. |
| `index_archivos/operativos/control_alicorpquery.php:55-58` | Calcula dias de vencimiento e indicador de error cuando faltan cinco dias o menos sin pase de salida. |
| `.data_base/asgard.sql:1945-1951` | Campos Alicorp persistidos en `dav_casos`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-*.php` | Lecturas OCR marcan `alicorp_cierre_transito=1` en casos relacionados. |

## Criterios de aceptacion candidatos

- La consulta debe limitarse al cliente Alicorp observado (`idcliente = 775`).
- Si un caso Alicorp tiene `fechavalidaciondui` y `alicorp_vencimiento` nulo, el sistema debe asignar vencimiento a 60 dias desde la validacion.
- El indicador de vencimiento debe activarse cuando quedan cinco dias o menos y no existe `fechapasesalida`.
- El resultado debe incluir casos activos y anulados por cliente cuando la anulacion cliente esta marcada.
- Las facturas anuladas deben aparecer como registros visibles, diferenciadas de los casos operativos.
- El cierre de transito debe mostrarse como `PAGADO` cuando `alicorp_cierre_transito = 1`; en otro caso como `SIN PAGAR`.
