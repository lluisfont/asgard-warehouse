# ASGARD-05 - Modelo completo de base de datos

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- `almacen.sql` contiene `189` tablas y `1716` columnas detectadas.
- No se detectaron `INSERT INTO`, por lo que el SQL versionado representa estructura y no datos de usuarios.
- Indices/constraints parseados: `191`.

## Tablas con mas columnas

| Tabla | Columnas | Linea |
| --- | --- | --- |
| pbi_ingresos | 67 | 81 |
| t_timbradodetalle | 63 | 2748 |
| t_timbradodetalle_bk | 63 | 2819 |
| t_embarque | 56 | 1340 |
| t_ingresodetalle | 53 | 1702 |
| pbi_salidas | 52 | 215 |
| t_salida | 51 | 2501 |
| t_ingreso | 50 | 1643 |
| t_cliente | 47 | 759 |
| t_factura | 36 | 1507 |
| t_ate_gas | 31 | 449 |
| pbi_logistico | 25 | 156 |

## Evidencias

- `audit/evidence/database_tables.csv`
- `audit/evidence/database_columns.csv`
- `audit/evidence/database_indexes_constraints.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: diccionario fisico generado; relaciones semanticas y ownership de datos requieren validacion funcional.
