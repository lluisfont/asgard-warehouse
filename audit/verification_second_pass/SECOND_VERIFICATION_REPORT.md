# Second Verification Report

Estado: `PASS`

Fecha UTC: `2026-08-25T10:05:02.395284+00:00`

## Veredicto

La segunda auditoria no detecta omisiones funcionales en el paquete AS-IS. Si detecto y corrigio ruido de alcance: artefactos locales ignorados y el propio marco ASGARD ya no forman parte del corpus de aplicacion.

## Checks

| Area | Check | Estado | Detalle |
| --- | --- | --- | --- |
| scope | tracked_source_vs_inventory | PASS | tracked_scope=518 inventory=518 missing=0 unexpected=0 |
| scope | excluded_control_and_local_noise | PASS | Framework, work_sample, audit outputs, graphify-out, env files, .orig, pyc and error logs are outside source evidence. |
| evidence | expected_evidence_files | PASS | missing=none extra=none |
| evidence | summary_counts_match_artifacts | PASS | {"mismatches": 0} |
| phases | asgards_01_to_15_reports_exist | PASS | reports=15 missing=none |
| phases | phase_reports_have_status_and_evidence | PASS | weak_reports=none |
| security | audit_outputs_do_not_embed_known_secret_patterns | PASS | hits=none |
| graphify | graphify_cross_check_available | PASS | nodes=2932 edges=6227 built_at_commit=7a4639ec0c93be207400b75dbfd7af01b9d4c722 |
| graphify | graphify_hubs_are_reflected_in_audit | PASS | top_hubs=['DatoMaestroService', 'UsuarioService', 'AlmacenesService', 'app.module.ts', 'app.routing.ts', 'almacen.sql', 'ContabilidadService', '.getTokenDetalle()', 'usuario.service.ts', '.getToken()']; missing_expected=none |

## Contraste Graphify

Graphify disponible: `True`. Nodos: `2932`. Edges: `6227`. Commit del grafo: `7a4639ec0c93be207400b75dbfd7af01b9d4c722`.

| God node | Degree |
| --- | --- |
| DatoMaestroService | 237 |
| UsuarioService | 230 |
| AlmacenesService | 223 |
| app.module.ts | 215 |
| app.routing.ts | 198 |
| almacen.sql | 189 |
| ContabilidadService | 106 |
| .getTokenDetalle() | 103 |
| usuario.service.ts | 101 |
| .getToken() | 100 |
| QRcode | 99 |
| EmbarquesDetalleComponent | 93 |
| ExcelModel | 92 |
| ExportExcelService | 90 |
| EntidadesService | 89 |

## Conclusiones

- `ASGARD-01` a `ASGARD-15` siguen cubiertas.
- La auditoria primaria queda mas precisa tras limpiar el alcance a `518` archivos versionados del sistema.
- No se encontro evidencia de secretos literales en los artefactos de auditoria.
- Los pendientes restantes son de validacion externa/humana, no de omision documental detectada por esta segunda pasada.
