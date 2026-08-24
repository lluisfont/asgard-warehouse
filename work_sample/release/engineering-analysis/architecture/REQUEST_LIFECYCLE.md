# Request Lifecycle

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Ciclo tipico protegido

1. Navegador solicita pantalla PHP.
2. PHP incluye `cnfdb105.php` y normalmente `permisos.php`.
3. `permisos.php` comprueba sesion `idcliente`.
4. La pantalla consulta permisos especificos cuando aplica.
5. PHP renderiza HTML/JS con datos iniciales.
6. JS llama endpoints AJAX o submit POST.
7. Endpoint ejecuta SQL, filesystem, correo, OCR o Pusher.
8. Respuesta HTML/JSON/archivo vuelve al navegador.

## Ciclo login

1. `login.php` genera formulario y CSRF.
2. `veriflogin.php` valida CSRF y credenciales.
3. Si no hay MFA, crea sesion/JWT.
4. Si hay MFA, redirige a `2fa.php?u=...`.
5. `2fa.php` envia codigo y endpoints AJAX verifican/autentican.

## Variantes

- Descargas: `download.php` lee fichero y lo sirve directamente.
- Excel/OCR: upload/archivo externo se parsea y alimenta SQL.
- Reportes: queries temporales/procedimientos generan grillas o Excel.
- Realtime: endpoints insertan notificacion y disparan Pusher.
