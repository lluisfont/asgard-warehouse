# Authentication Flows

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Technical language: English

## Candidate Flow: Customer Login With Email MFA

The customer login flow reaches `index_archivos/2fa.php` with a base64 encoded `u` parameter. The page decodes the payload, sends a six-digit code to the customer email, waits for the user to submit the code, and then calls `TwoFaClass::autenticar` to create the authenticated PHP session.

Evidence:

- `index_archivos/2fa.php:10-13`
- `index_archivos/2fa.php:61-90`
- `index_archivos/2fa/ajax/verificar-codigo.php:1-6`
- `index_archivos/2fa/ajax/autenticar.php:1-6`
- `index_archivos/2fa/TwoFaClass.php:10-19`
- `index_archivos/2fa/TwoFaClass.php:86-218`

## Observed Steps

1. `2fa.php` decodes the user payload from `u`.
2. `TwoFaClass::enviarCorreo` generates a random numeric code.
3. The code is stored in `dav_codigos_2fa` with email, IP, `tipo_usuario = CLIENTE`, and timestamps.
4. The email is sent with the generated MFA code.
5. The browser posts the code to `/2fa/ajax/verificar-codigo.php`.
6. `TwoFaClass::verificarCodigo` checks the code and expiration.
7. If valid, the code is soft-deleted and the browser posts to `/2fa/ajax/autenticar.php`.
8. `TwoFaClass::autenticar` starts the customer session, creates JWT values, resets failed login counters, records activity, and returns the next URL.

## Redirect Outcomes

- `principal.php` when authentication is successful and no password change is required.
- `cambiocontrasena.php` when `cambiarcontrasena == 1`.
- `login.php` when the code attempt sequence blocks the user.

## Validation Needed

- Confirm where the `u` payload is generated and whether it is signed or encrypted.
- Confirm whether `dav_clienteusuarios.2fa` enables this flow or only stores a configuration flag.
- Confirm whether additional login controls exist before `2fa.php`.
