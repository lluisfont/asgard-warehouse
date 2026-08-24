# Assumption Register

| ID | Estado | Supuesto | Evidencia / limite |
| --- | --- | --- | --- |
| ASM-001 | INFERRED_DRAFT_REVIEW_REQUIRED | Los dominios funcionales se agrupan por rutas backend y servicios/componentes Angular. | `backend_routes.csv`, `frontend_service_calls.csv`; requiere validacion de negocio. |
| ASM-002 | INFERRED_DRAFT_REVIEW_REQUIRED | `almacen.sql` representa el esquema base del sistema auditado. | No contiene inserts; falta comparacion contra base real. |
| ASM-003 | INFERRED_DRAFT_REVIEW_REQUIRED | Las operaciones batch principales son endpoints/manual imports y no jobs autonomos versionados. | No se detectan crons/colas; requiere confirmacion de infraestructura. |
| ASM-004 | INFERRED_DRAFT_REVIEW_REQUIRED | Las integraciones se configuran por constantes de entorno fuera del repo. | `app/start.php`, `BlobStorageService.php`, `sendmail.php`, `ovp.php`. |
