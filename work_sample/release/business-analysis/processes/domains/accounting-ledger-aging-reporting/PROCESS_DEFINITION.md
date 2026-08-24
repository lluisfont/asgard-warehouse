# Accounting Ledger Aging Reporting - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Mantener y emitir reportes contables/fiscales de soporte: libro de compras, descomposicion contable de factura/planilla por conceptos y registro mensual de ahorro/aging.

## Alcance observado

- Registro editable de montos mensuales por anio desde 2020 hasta el anio actual.
- Insercion o actualizacion de `dav_aging` por mes/anio.
- Reporte de comision/estado de cuentas por linea y fecha de pago DIM.
- Separacion de importes por tipo `Factura` y `Planilla`.
- Consolidacion de conceptos: tributos GA, IVA, ICE, IEHD, DUI, multas, carpeta ANB, gases, varios planilla, GDE, comision, factura ANB, otros factura y multas nota debito.
- Libro de compras con correlativo, proveedor fijo observado, factura/planilla, DIM, autorizacion, importe, base y credito fiscal.
- Exportacion Excel generica para reportes.

## Fuera de alcance observado

- Cobro, pago y recepcion documental de facturas/planillas.
- Reporte ZDAM y costo por vehiculo, documentados en `vehicle-cost-accounting-reporting`.
- Cierre contable formal o envio a autoridad fiscal.
- Validacion fiscal de proveedor fijo y formulas de credito fiscal.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario contable | Consulta reportes y registra montos mensuales de ahorro/aging. |
| ASGARD | Calcula libros/reportes y persiste aging mensual. |
| Cliente | Limita el alcance de los reportes por sesion. |

## Entradas

- Linea de cliente.
- Ciudad.
- Rango de fechas.
- Cliente de sesion.
- Casos no anulados.
- Facturas/planillas activas.
- Detalles de factura y conceptos contables.
- Pagos de IVA/tributos para libro de compras.
- Mes, anio y monto para aging.

## Salidas

- Matriz aging/ahorro mensual por anio.
- Estado de cuentas/comision por factura y planilla.
- Libro de compras con credito fiscal calculado.
- Exportacion Excel de reportes.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/contables/agig.php` | UI editable de registro mensual por anios y meses. |
| `index_archivos/contables/agig_ajax.php` | Inserta/actualiza `dav_aging` por mes/anio. |
| `index_archivos/contables/comision.php` | UI de reporte por linea y fecha de pago DIM. |
| `index_archivos/contables/comisionquery.php` | Union de factura y planilla con conceptos contables. |
| `index_archivos/contables/librocompras.php` y `librocomprasquery.php` | Generacion de libro de compras fiscal. |
| `.data_base/asgard.sql` | Tablas `dav_aging`, `dav_meses`, `dav_facturaplanilla`, `dav_facturasdetalle`, `dav_pagosdetalle`, `dav_concepto`. |

## Criterios de aceptacion candidatos

- Aging debe presentar meses y columnas anuales desde 2020 hasta el anio actual.
- Al guardar aging, si existe registro por mes/anio se actualiza; si no existe se inserta.
- Reportes contables deben filtrar por cliente de sesion y excluir casos anulados.
- Comision/estado de cuentas debe separar filas tipo `Factura` y `Planilla`.
- Libro de compras debe calcular credito fiscal como 13% del importe/base observado.
- Reportes deben permitir exportacion Excel cuando aplica.

