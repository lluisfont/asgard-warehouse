# Customs Case EDP Status Monitoring - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-CCESM-001 | El reporte se limita al cliente de sesion y excluye casos anulados. | `edpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-002 | Solo entran casos con `dav_casos.gestion > 2016`. | `edpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-003 | El ultimo EDP se determina por maxima fecha y maximo `idedp` para el caso. | `edpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-004 | La etapa mostrada se deriva desde `dav_estadoedp.idetapaedp`. | `edpquery.php`, `edpdetallequery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-005 | Las etapas `4`, `7` y `8` no aparecen marcadas por defecto en el selector. | `edp.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-006 | Casos sin EDP se tratan como etapa candidata `12`, pero se excluyen si ya tienen factura-planilla activa. | `edpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-007 | Etapas observadas `8` y `11` se excluyen de la bandeja principal. | `edpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-008 | El filtro `conparte` exige existencia de Parte de Recepcion tipo documento `71`. | `edpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CCESM-009 | El detalle historico ordena por fecha y estado EDP. | `edpdetallequery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
