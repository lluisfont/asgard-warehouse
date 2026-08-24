# Data Lifecycle

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

1. Captura por formulario, Excel, OCR, API, carga documental o proceso batch.
2. Validacion funcional/tecnica parcial.
3. Persistencia en MySQL y/o filesystem.
4. Enriquecimiento por catalogos, EDP, estados, reportes.
5. Salida por HTML/JSON/Excel/PDF/correo/notificacion/dashboard.
6. Soft delete o estado final cuando aplica.

Riesgo: DB y filesystem no siempre comparten transaccion atomica.
