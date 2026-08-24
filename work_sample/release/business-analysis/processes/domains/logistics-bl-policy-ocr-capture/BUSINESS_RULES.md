# Logistics BL Policy OCR Capture - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-LBPOC-001 | El UUID de BL usa `MODELO_BL`; el UUID de poliza usa `MODELO_SEGURO`. | Switch `tipodoc` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LBPOC-002 | El embarque se resuelve primero por `logis_embarques.idExchange` y luego por `dav_casosprevios.idExchange`. | Consultas iniciales | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LBPOC-003 | Una lectura existente puede emparejarse por ubicacion o por cantidad complementaria. | Busquedas en `logis_lecturablpoliza` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LBPOC-004 | Cuando hay multiples coincidencias por cantidad, se inserta una nueva lectura. | Condicion `varioscasos>1` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LBPOC-005 | Las comparaciones se devuelven cuando `DATEDIFF(fechabl, fechaps)` es negativo. | Consulta de comparacion | INFERRED_DRAFT_REVIEW_REQUIRED |
