# External Agency Procedure Tracking - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-EAPT-001 | Reporte SENASAG usa `identidademisora=2`. | `senasag.php` |
| BR-EAPT-002 | El reporte se filtra por cliente desde caso o caso previo. | `senasagquery.php` |
| BR-EAPT-003 | Factura comercial del tramite se obtiene de documentos tipo `19`. | `senasagquery.php` |
| BR-EAPT-004 | Columnas de etapa se generan desde `dav_etapasentidademisora` ordenadas por `orden`. | `senasagquery.php` |
| BR-EAPT-005 | Si `tieneestado=1`, se agrega columna de estado para la etapa. | `senasagquery.php` |
| BR-EAPT-006 | Filtro de etapa exige fecha en la etapa seleccionada y siguiente etapa nula si no es final. | `senasagquery.php` |
| BR-EAPT-007 | Alta de tramite requiere entidad emisora, tramite de entidad y tipo de tramite. | `tramites.php` |
| BR-EAPT-008 | Listas de tramite/tipo tramite dependen de la entidad seleccionada. | `tramites_json.php` |

## Riesgos de regla pendientes

- Confirmar si `identidademisora=2` siempre es SENASAG.
- Confirmar si la regla de etapa actual por `idetapa+1` es valida cuando el orden no coincide con ids.
- Confirmar permisos para alta/edicion/eliminacion de tramites.
- Confirmar auditoria de cambios de etapa/estado.

