# Customs DAM Document Send Date Control - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-CDDSDC-001 | `exchange_id` puede pertenecer a `dav_casosprevios` o a `logis_embarques`. | Query de resolucion | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDDSDC-002 | La fecha DAM solo se actualiza si existe `fechaenvioap` distinta de `0000-00-00`. | Conteo de facturas AP | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDDSDC-003 | La actualizacion DAM usa `CURRENT_DATE()` para todos los casos de la solicitud. | `UPDATE dav_facturacomercial` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDDSDC-004 | Si no existe AP, el sistema envia correo a destinatario operativo fijo observado. | `MailClass::sendMail` | INFERRED_DRAFT_REVIEW_REQUIRED |
