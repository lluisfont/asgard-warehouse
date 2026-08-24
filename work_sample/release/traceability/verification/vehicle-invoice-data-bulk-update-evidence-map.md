# Vehicle Invoice Data Bulk Update - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia fuente

| Artefacto | Lineas / patron | Uso |
| --- | --- | --- |
| `index_archivos/logistica/componentes/embarques_ver_gestion_aduanera.php` | `Fechas Facturas`, iframe a `fechasfacturas.php` | Entrada UI del proceso. |
| `index_archivos/logistica/componentes/formatoActualizarFechas.php` | Query UNION y escritura de columnas A-F | Generacion de plantilla Excel. |
| `index_archivos/logistica/componentes/fechasfacturas.php` | Query UNION de datos actuales | Determina universo de vehiculos por embarque. |
| `index_archivos/logistica/componentes/fechasfacturas.php` | `idsuma` y observacion de bloqueo | Regla de no actualizacion con SUMA/DAM. |
| `index_archivos/logistica/componentes/fechasfacturas.php` | Carga `actfechas` y lectura PHPExcel | Entrada masiva por Excel. |
| `index_archivos/logistica/componentes/fechasfacturas.php` | Updates a tablas `dav_*` | Persistencia y recalculo. |

## Trazabilidad a artefactos

| Artefacto generado | Evidencia principal |
| --- | --- |
| `PROCESS_DEFINITION.md` | UI, plantilla, carga y updates por chasis. |
| `PROCESS_FLOW.md` | Secuencia observada en `fechasfacturas.php`. |
| `BUSINESS_RULES.md` | Bloqueo por SUMA/DAM, monto positivo, propagacion de datos. |
| `DATA_USED.md` | Tablas y columnas usadas por consultas/update. |
| `STATE_MODEL.md` | Estados inferidos de `idsuma`, match por chasis y monto. |
| `UC-001.md` | Caso de uso de correccion masiva desde Excel. |
| `openspec/spec.md` | Requisitos AS-IS candidatos. |

## Limitaciones

- No se ejecuto el flujo contra base de datos productiva.
- Las reglas de negocio estan inferidas desde codigo fuente y deben validarse con usuarios responsables.
- No se localizaron permisos especificos del endpoint durante esta pasada.
