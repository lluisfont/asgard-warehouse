# Alicorp Operational Document Package Dispatch - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-AODPD-001 | El job solo corre la logica cuando `cron=1`. | `documentacionAlicorp.php:24-29` |
| BR-AODPD-002 | Los clientes procesados observados son `775` y `755`. | `documentacionAlicorp.php:27` |
| BR-AODPD-003 | Solo se consideran casos con exchange de embarque, no anulados y sin `embarque_documentos_enviados`. | `SolicitudesClass.php:160-218` |
| BR-AODPD-004 | La parametrizacion documental se filtra por cliente, linea y proveedor. | `EmbarqueClass.php:1552-1584` |
| BR-AODPD-005 | Solo documentos cuyo id esta parametrizado se agregan al ZIP. | `documentacionAlicorp.php:73-89` |
| BR-AODPD-006 | El ZIP se guarda como archivo operativo Alicorp por embarque. | `documentacionAlicorp.php:108-122` |
| BR-AODPD-007 | El asunto incluye proveedor, pais, agencia, linea y facturas. | `documentacionAlicorp.php:145-146` |
| BR-AODPD-008 | Despues del envio, las carpetas quedan marcadas con `embarque_documentos_enviados=CURRENT_TIMESTAMP()`. | `SolicitudesClass.php:1203-1206` |
