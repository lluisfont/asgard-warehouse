# Customer Primary Login Session Audit - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

1. `veriflogin.php` configura parametros/cabeceras de sesion y seguridad.
2. ASGARD valida `csrf_token` contra la sesion.
3. ASGARD normaliza `username` y `contrasena`.
4. ASGARD busca master passwords y evalua si la contrasena coincide con alguna.
5. ASGARD consulta `dav_clienteusuarios` por username.
6. Si el usuario esta bloqueado por 5 intentos dentro de 24 horas, redirige con error de bloqueo.
7. Si la password bcrypt o master password coinciden, valida que el usuario este activo.
8. Si ya hay sesion de otro cliente en el navegador, redirige a `samesesion.php`.
9. Si el usuario no tiene 2FA, ASGARD regenera sesion, crea variables de sesion y JWT.
10. ASGARD reinicia intentos, limpia bloqueo, actualiza ultima actividad y visitas.
11. ASGARD incrementa visitas del cliente.
12. ASGARD registra login exitoso en `log_asgard_ecosistema`.
13. Si la contrasena vencio por 90 dias, redirige a `cambiocontrasena.php`; si no, a `principal.php` o `ultimoenlace`.
14. Si el usuario tiene 2FA, redirige a `2fa.php` con datos codificados.
15. Si la password falla, ASGARD registra login fallido, recalcula intentos y bloquea/incrementa contador.

## Excepciones observadas

- CSRF invalido: HTTP 403.
- Usuario inexistente: error `2`.
- Campos vacios: error `3`.
- Usuario inactivo: error `4`.
- Usuario bloqueado: error `5`.
- Password incorrecta: error `1` con numero de intentos.
