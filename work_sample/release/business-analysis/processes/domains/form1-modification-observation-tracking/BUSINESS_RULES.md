# Form1 Modification Observation Tracking - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-FMOT-001 | Una modificacion se considera visible en el reporte cuando la subcontravencion tiene `permisoclientes=1`. | `modificacionesquery.php`, `dav_casossubcontravencion` |
| BR-FMOT-002 | El detalle de modificacion se compone de subcontravencion, valor actual y valor corregido con la semantica "dice/debe decir". | `modificacionesquery.php` |
| BR-FMOT-003 | Form1 puede estar vinculado a caso aduanero o a carpeta AGES; el reporte trata ambos como origenes operativos. | `modificacionesquery.php` |
| BR-FMOT-004 | La fecha de primera observacion se toma del maximo estado Form1 EDP observado como `idestadoform1edp=1`. | `modificacionesquery.php` |
| BR-FMOT-005 | La fecha de ingreso se toma del maximo estado Form1 EDP observado como `idestadoform1edp=3`. | `modificacionesquery.php` |
| BR-FMOT-006 | La fecha de conclusion se toma del maximo estado Form1 EDP observado como `idestadoform1edp=7`. | `modificacionesquery.php` |
| BR-FMOT-007 | Los dias antes de ingreso se calculan desde observacion hasta ingreso o fecha actual si aun no ingreso. | `modificacionesquery.php` |
| BR-FMOT-008 | Los dias de tramite se calculan desde ingreso hasta conclusion o fecha actual si aun no concluyo. | `modificacionesquery.php` |
| BR-FMOT-009 | El historial de llamadas se agrupa por `idform1` y puede incluir adjuntos. | `historial_llamadas.php`, `dav_form1llamadas` |
| BR-FMOT-010 | Documentos faltantes nuevos se reportan cuando `responsabilidad=0` y `resuelto=0`. | `observadasquery.php`, `dav_faltadocumentos` |
| BR-FMOT-011 | Los casos anteriores a la regla nueva mantienen logica legacy desde campos de `dav_casos`. | `observadasquery.php` |

## Riesgos de regla pendientes

- Confirmar los nombres oficiales de estados `1`, `3` y `7`.
- Confirmar si `permisoclientes=1` equivale siempre a visibilidad cliente.
- Confirmar si la fecha de corte `2018-12-11` sigue vigente como cambio funcional formal.
