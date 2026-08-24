# ASGARD-08 - Autenticacion, sesion, roles y permisos

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- Autenticacion JWT implementada en `app/middleware/jwt.php`.
- El frontend conserva y decodifica token via `UsuarioService` y adjunta `Authorization` en servicios.
- Permisos se consultan en componentes Angular via `tokenDetalle.permisos` y en middleware backend con `verifyRole`.
- El secreto JWT proviene de constante externa `jwt_key`; no se versiona el archivo `.env.php`.

## Evidencias

- `AtlantesBE-main/AtlantesBE-main/app/middleware/jwt.php`
- `AtlantesBE-main/AtlantesBE-main/app/routes/usuarios.php`
- `AtlantesFE-main/AtlantesFE-main/src/app/services/usuario.service.ts`
- `audit/evidence/frontend_service_calls.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: modelo identificado; falta matriz formal endpoint-permiso-rol validada.
