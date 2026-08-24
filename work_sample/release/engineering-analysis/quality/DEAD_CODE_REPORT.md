# Dead Code Report

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

No se confirma codigo muerto sin ejecucion ni logs reales. Candidatos a revisar:

- APIs Android legacy (`android/*`).
- Despachos legacy (`logistica/despachos.php`, `despachover.php`).
- Scripts `email.php`, pruebas o helpers hardcoded.
- Librerias duplicadas (`PHPExcel` en varias rutas, PDF libs).
- Familias SQL-only sin consumidor PHP (`con_*`, `serv_*`, `cn_*`, `bot_*`) si no hay codigo externo.

Regla: no eliminar sin prueba de rutas, logs, menu/permisos y busqueda de enlaces.
