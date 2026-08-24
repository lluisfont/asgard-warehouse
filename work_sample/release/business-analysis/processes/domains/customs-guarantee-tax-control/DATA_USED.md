# Customs Guarantee Tax Control - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Descripcion candidata | Fuente |
| --- | --- | --- |
| `cc_registro_documentos.monto_boleta` | Monto registrado de boleta/garantia. | `getSeguimientoTotal` |
| `cc_registro_documentos.tipo_documento_id` | Tipo de documento usado para identificar garantia, observado como `4`. | `getSeguimientoTotal` |
| `dav_casos.idcliente` | Cliente propietario del caso. | `ContabilidadClass.php`, `tributosquery.php` |
| `dav_casos.idtipodeclaracion` | Tipo de declaracion para filtrar seguimiento. | `ContabilidadClass.php` |
| `dav_casos.anulado` | Exclusion de casos anulados. | `ContabilidadClass.php`, `tributosquery.php` |
| `dav_facturacomercial.fechaenviodam` | Fecha base para unidad con DAM y agrupacion mensual. | `getSeguimientoMensual` |
| `dav_casos.fechapasesalida` | Indicador de extraccion/salida. | `getSeguimientoMensual` |
| `dav_casos.fechaasignacioncanal` | Indicador alternativo de extraccion/canal. | `getSeguimientoMensual` |
| `dav_casos.fechaverificacionfrv` | Indicador usado para clasificar avance operativo. | `getSeguimientoOperativo` |
| `dav_documentos.fecha` | Fecha usada para calcular dias sin nacionalizar. | `getSeguimientoOperativo` |
| `dav_liquidacion.GA/IVA/ICE` | Tributos liquidados pagados/observados. | `getSeguimientoMensual` |
| `dav_casos.valorGA/valorIVA/valorICE/valorDUI` | Valores de requerimiento/estimacion tributaria. | `ContabilidadClass.php`, `tributosquery.php` |
| `dav_pagosdetalle.monto` | Pagos aplicados, incluyendo conceptos de tributos/AP. | `ContabilidadClass.php`, `tributosquery.php` |
| `dav_requerimientofondos.fecha` | Fecha base del requerimiento de fondos. | `tributosquery.php` |
| `dav_anticipos.recibo` | Recibo asociado a fondos recibidos. | `tributosquery.php` |
| `dav_cobros.tributos` | Marca de cobros asociados a tributos. | `tributosquery.php` |

## Observaciones

- La formula tributaria se repite con alta complejidad y usa FOB, flete, seguro, gastos, deducciones, tipo de cambio, GA, IVA, ICE, DUI y AP.
- Algunos reportes presentan valores en USD dividiendo por `6.96`; el origen formal del tipo de cambio queda pendiente.
- El reporte de planillas legalizadas esta observado como Imcruz por filtro fijo de cliente `417`.

