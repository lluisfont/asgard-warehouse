# Customer Primary Login Session Audit - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-CPLSA-001 | El login exige `csrf_token` valido antes de consultar credenciales. | `veriflogin.php:13-18` |
| BR-CPLSA-002 | La password se valida contra hash bcrypt de usuario o contra master password. | `veriflogin.php:38-48`, `veriflogin.php:80` |
| BR-CPLSA-003 | Usuario con `activo=0` no puede iniciar sesion. | `veriflogin.php:81-84` |
| BR-CPLSA-004 | Cinco intentos fallidos generan estado bloqueado con `fechabloqueo`. | `veriflogin.php:58-60`, `veriflogin.php:216-237` |
| BR-CPLSA-005 | Si pasaron mas de 24 horas, intentos/bloqueo se reinician antes de incrementar nuevo fallo. | `veriflogin.php:220-224` |
| BR-CPLSA-006 | No se permite iniciar sesion de otro cliente si ya existe `$_SESSION['idcliente']` distinto. | `veriflogin.php:87-98` |
| BR-CPLSA-007 | Sin 2FA, ASGARD crea sesion, JWT y tokens Atlantes. | `veriflogin.php:100-113` |
| BR-CPLSA-008 | Login exitoso reinicia intentos, limpia bloqueo, actualiza ultima actividad y suma visitas. | `veriflogin.php:115-120` |
| BR-CPLSA-009 | Login exitoso y fallido se registran en `log_asgard_ecosistema`. | `veriflogin.php:139-160`, `veriflogin.php:183-202` |
| BR-CPLSA-010 | Si `fechacontrasena + 90 dias` vencio, el usuario se redirige a cambio de contrasena. | `veriflogin.php:59`, `veriflogin.php:166-173` |
| BR-CPLSA-011 | Si el usuario tiene `2fa`, el proceso primario redirige a `2fa.php`. | `veriflogin.php:178-180` |
