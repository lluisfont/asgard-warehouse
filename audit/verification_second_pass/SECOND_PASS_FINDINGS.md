# Second Pass Findings

Estado: `PASS`

## Hallazgos nuevos

| ID | Severidad | Estado | Hallazgo | Accion aplicada |
| --- | --- | --- | --- | --- |
| V2-SCOPE-001 | Low | FIXED_IN_WORKTREE | La primera pasada incluia 3 artefactos locales ignorados (`*.orig`, `*-errors.txt`) en el inventario. | El generador fue ajustado para usar `git ls-files` y excluir ruido local. |
| V2-SCOPE-002 | Low | FIXED_IN_WORKTREE | `ASGARD_ANALYSIS_FRAMEWORK.md` estaba dentro del inventario del sistema aunque es marco de control. | El generador lo excluye del corpus de aplicacion. |

## Sin omisiones funcionales detectadas

La segunda pasada no detecto rutas backend, rutas frontend, servicios HTTP, tablas SQL ni referencias SQL omitidas por las correcciones anteriores. Los conteos funcionales permanecen estables:

| Medida | Valor |
| --- | --- |
| files | 518 |
| backend_routes | 339 |
| frontend_routes | 99 |
| frontend_service_calls | 328 |
| tables | 189 |
| columns | 1716 |
| sql_refs | 2567 |
| integrations | 1058 |
| documents | 4036 |
| findings | 10 |

## Pendientes que siguen siendo validacion humana

- Objetos DB runtime fuera de `almacen.sql`.
- Matriz formal rol/permiso/endpoint.
- Cron jobs o schedulers externos al repositorio.
- Contratos operativos de integraciones.
- Invariantes OVP/contabilidad.
- Politicas documentales de retencion, acceso y almacenamiento.
