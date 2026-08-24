# Customs Guarantee Tax Control - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Controlar el uso de boletas de garantia asociadas a operaciones aduaneras, comparando monto total disponible, monto comprometido por unidades con DAM, avance de extraccion/nacionalizacion y diferencias entre requerimientos de fondos, tributos pagados y devoluciones/reposiciones.

## Alcance observado

- Seguimiento mensual por gestion, cliente y tipo de declaracion.
- Calculo de unidades con DAM, unidades extraidas y porcentaje de extraccion.
- Calculo de tributos pagados y monto de boleta en uso.
- Calculo de monto total de garantia desde documentos de certificacion tipo `4`.
- Calculo de monto disponible como total de boleta menos monto en uso.
- Resumen operativo por categorias: DAM aceptada, unidades sin nacionalizar, unidades sin nacionalizar por vencer y unidades en transito sin DAM aceptada.
- Reporte desglosado de unidades para exportacion Excel.
- Reporte de tributos que compara requerimiento de fondos, monto recibido, tributos pagados, devolucion/reposicion, diferencia y saldo a favor.
- Reporte de planillas legalizadas Imcruz para vehiculos nacionalizados.

## Fuera de alcance observado

- Alta formal de boletas de garantia, que parece vivir en control de certificaciones/documentos.
- Aprobacion bancaria o legal de boletas.
- Definicion oficial del catalogo de documentos `tipo_documento_id=4`.
- Regla formal de los 90 dias para clasificar unidades sin nacionalizar por vencer.
- Validacion contable final de devoluciones, reposiciones o saldos a favor.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario contable | Consulta uso de boletas, tributos y reportes de soporte. |
| Usuario operativo | Genera datos de DAM, extraccion, pase de salida y nacionalizacion que alimentan el control. |
| ASGARD | Cruza casos, partidas, facturas comerciales, liquidaciones, pagos, requerimientos y documentos. |
| Cliente | Es propietario del alcance de consulta por sesion. |

## Entradas

- Gestion y tipo de declaracion.
- Fechas para reporte operativo desglosado.
- Cliente de sesion.
- Casos no anulados.
- Fecha de envio DAM, fecha de pase de salida, fecha de asignacion de canal y fecha de verificacion FRV.
- Documentos asociados a DAM/parte y documentos de garantia.
- Valores aduaneros: FOB, fletes, seguro, gastos, tipo de cambio, GA, IVA, ICE, formulario DUI y AP.
- Requerimientos de fondos, anticipos, cobros, pagos de tributos y devoluciones.

## Salidas

- Seguimiento mensual con unidades con DAM, unidades extraidas, tributos pagados, monto de boleta en uso y porcentaje de extraccion.
- Resumen de monto total garantia, monto disponible y unidades sin extraccion.
- Resumen operativo de uso de boleta por categoria.
- Detalle de unidades en transito por marca sin DAM aceptada.
- Excel de seguimiento operativo desglosado por chasis, pedido, valor provisional, valor segun requerimiento y estado.
- Reporte de tributos con diferencias y saldo a favor de cliente o agencia.
- Reporte de planillas legalizadas de vehiculos.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/contables/boletasgarantia.php` | UI de seguimiento de boletas de garantia por gestion y tipo declaracion. |
| `index_archivos/contables/boletasgarantiaajax.php` | Construye tablas de resumen mensual, total, operativo y acciones. |
| `index_archivos/contables/boletasgarantiareporte.php` | Exporta seguimiento operativo desglosado a Excel. |
| `index_archivos/contables/controllers/ContabilidadClass.php` | Implementa consultas de seguimiento mensual, total, operativo y desglosado. |
| `index_archivos/contables/tributos.php` | Pantalla de reporte de requerimientos, recibos, tributos pagados, diferencia y saldo a favor. |
| `index_archivos/contables/tributosquery.php` | Query temporal del reporte de tributos. |
| `index_archivos/contables/planillaslegalizadas.php` y `planillaslegalizadasquery.php` | Reporte de planillas legalizadas Imcruz por nacionalizacion. |
| `.data_base/asgard.sql` | Tablas `cc_registro_documentos`, `dav_casos`, `dav_facturacomercial`, `dav_partidas`, `dav_liquidacion`, `dav_pagosdetalle`, `dav_cobros`, `dav_anticipos`, `dav_facturaplanilla`. |

## Criterios de aceptacion candidatos

- El seguimiento se calcula por cliente de sesion, gestion y tipo de declaracion.
- Solo casos no anulados se incluyen.
- El monto total de garantia se obtiene de registros de documentos activos con `tipo_documento_id=4`.
- Las unidades con DAM usan `dav_facturacomercial.fechaenviodam`.
- Las unidades extraidas se cuentan cuando existe pase de salida o asignacion de canal.
- El monto disponible de garantia es monto total de boleta menos monto de boleta en uso.
- Las unidades sin nacionalizar se separan por diferencia de dias respecto al documento observado: hasta 90 dias y mayor a 90 dias.
- El reporte de tributos calcula diferencia entre monto recibido/pagado y clasifica saldo a favor de cliente o agencia.
