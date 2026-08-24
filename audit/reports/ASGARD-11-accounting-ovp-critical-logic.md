# ASGARD-11 - Contabilidad, OVP y logica critica

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- `app/functions/ovp.php` concentra integracion y reglas OVP/contables extensas.
- `app/routes/contabilidad.php` concentra rutas de reportes y operaciones contables.
- La matriz SQL muestra escrituras/lecturas que deben revisarse como transacciones criticas antes de refactorizar.
- `dav_pagosovp`, `t_embarque`, `t_tipocambio`, `t_cliente` y tablas de ingreso/salida aparecen entre los objetos mas referenciados.

## Objetos criticos por referencias/escritura

| Tabla | Archivo | Insert | Update | Delete | Total |
| --- | --- | --- | --- | --- | --- |
| dav_pagosovp | AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php | 7 | 20 | 0 | 43 |
| dav_cobros | AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php | 0 | 18 | 0 | 18 |
| t_inventariofisicodetalle | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 5 | 7 | 2 | 27 |
| t_ingresodetalle | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 7 | 1 | 39 |
| t_pedidodetalle | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 2 | 5 | 36 |
| t_ubicacionitem | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 5 | 5 | 1 | 24 |
| t_pedidotienda | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 0 | 5 | 19 |
| t_pediodetalletienda | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 0 | 5 | 19 |
| t_cargo | AtlantesBE-main/AtlantesBE-main/app/routes/embarques.php | 4 | 2 | 2 | 11 |
| t_costo | AtlantesBE-main/AtlantesBE-main/app/routes/embarques.php | 4 | 2 | 2 | 11 |

## Invariantes pendientes de validacion

| Area | Estado | Validacion requerida |
| --- | --- | --- |
| OVP/pagos | INFERRED_DRAFT_REVIEW_REQUIRED | Estados permitidos, reintentos, conciliacion, duplicados y reversas. |
| Contabilidad/reportes | INFERRED_DRAFT_REVIEW_REQUIRED | Criterios de fecha, moneda, tipo de cambio, cierre y exportaciones oficiales. |
| Ingresos/salidas | INFERRED_DRAFT_REVIEW_REQUIRED | Transiciones de estado, stock, detalle, anulaciones y efectos contables. |

## Evidencias

- `AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php`
- `AtlantesBE-main/AtlantesBE-main/app/routes/contabilidad.php`
- `audit/evidence/php_sql_matrix.csv`
- `audit/evidence/integration_catalog.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: superficie critica identificada; invariantes contables deben validarse con negocio/QA.
