# Authorization rules

Estado: candidate_reconstruction  
Confianza: media

- Toda accion sensible debe depender de sesion valida.
- El usuario solo debe ver datos de clientes habilitados.
- Los permisos por rol controlan pantallas, reportes y acciones.
- Las acciones documentales dependen de rol, cliente, estado y tipo documental.
- Los terceros con token deben tener acceso limitado por alcance y vigencia.
- Los reportes deben aplicar filtros de cliente/usuario antes de agregar datos.

## Riesgo AS-IS

La autorizacion aparece distribuida entre PHP, SQL, menus, parametros y filtros manuales. Esto aumenta riesgo de bypass en endpoints AJAX o descargas.
