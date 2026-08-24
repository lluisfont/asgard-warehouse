# Customer Password Recovery - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-CPR-001 | Solo usuarios cliente activos pueden iniciar recuperacion. | `ResetPassword::verificausurio` |
| BR-CPR-002 | El codigo de recuperacion tiene 6 caracteres y combina numeros/letras convertidas a mayusculas. | `ResetPassword::generateCode` |
| BR-CPR-003 | El codigo se registra con correo, usuario cliente, nombre y apellido del solicitante. | `ResetPassword::generateToken` |
| BR-CPR-004 | El correo de recuperacion se envia mediante SendGrid y requiere respuesta `202`. | `ResetPassword::sendEmailVerification` |
| BR-CPR-005 | El token se valida por correo o usuario cliente, valor de token y ausencia de `deleted_at`. | `ResetPassword::verifyToken` |
| BR-CPR-006 | El token observado expira cuando su fecha de creacion es anterior al dia actual. | `ResetPassword::verifyToken` |
| BR-CPR-007 | Un token validado queda invalidado inmediatamente con `deleted_at`. | `ResetPassword::verifyToken` |
| BR-CPR-008 | La nueva contrasena se guarda con `password_hash(..., PASSWORD_BCRYPT)`. | `ResetPassword::resetPasswordClient` |
| BR-CPR-009 | El reset limpia bloqueo, reinicia intentos y actualiza fecha de contrasena. | `ResetPassword::resetPasswordClient` |
| BR-CPR-010 | El correo incluye copia oculta a una cuenta tecnica observada. | `ResetPassword::sendEmailVerification` |
