# ASGARD-06 - Matriz PHP <-> SQL

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- Se detectaron `2567` referencias SQL por accion/tabla/archivo.
- La matriz agregada `audit/evidence/php_sql_matrix.csv` cruza tabla, archivo y conteos de lectura/escritura.
- Tablas con escritura observada: `158`.

## Tablas mas referenciadas

| Item | Count |
| --- | --- |
| t_cliente | 140 |
| t_tipocambio | 134 |
| t_embarque | 129 |
| t_usuario | 71 |
| t_factura | 58 |
| t_concepto | 56 |
| t_ciudad | 47 |
| t_cargo | 46 |
| t_salida | 46 |
| dav_pagosovp | 43 |
| t_ingreso | 42 |
| t_notadebito | 42 |

## Estado

`COMPLETED`: matriz tecnica generada; debe usarse como base para revisar transacciones y efectos laterales.
