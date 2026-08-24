# Refactoring Risk Register

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| ID | Riesgo de refactor | Mitigacion |
| --- | --- | --- |
| RR-001 | Cambiar SQL rompe reportes/estados. | Golden masters por query. |
| RR-002 | Cambiar ficheros rompe documentos historicos. | Servicio de ficheros compatible. |
| RR-003 | Cambiar permisos abre/cierra acceso indebidamente. | Matriz autorizacion + tests IDOR. |
| RR-004 | Actualizar librerias PDF/Excel cambia salidas. | Render/hash/campos clave. |
| RR-005 | Extraer OCR cambia parsing. | Fixtures OCR congelados. |
| RR-006 | Normalizar magic values rompe clientes especiales. | Catalogo variantes + tests por cliente. |
