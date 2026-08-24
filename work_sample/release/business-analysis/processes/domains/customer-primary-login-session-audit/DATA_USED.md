# Customer Primary Login Session Audit - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Entidades y tablas observadas

| Tabla | Uso candidato | Campos observados |
| --- | --- | --- |
| `dav_clienteusuarios` | Credenciales, estado de usuario, bloqueo, visitas y flags de sesion. | `username`, `password`, `activo`, `intentos`, `fechabloqueo`, `fechacontrasena`, `ultimaactividad`, `visitas`, `2fa`, `correo` |
| `master_pass` | Hashes de master password. | `master_pass` |
| `dav_cliente` | Conteo de visitas por cliente. | `visitas` |
| `log_asgard_ecosistema` | Auditoria de inicio de sesion exitoso/fallido. | `date_start_log`, `user_ip`, `user_id`, `client_id`, `ecosystem_id`, `sistema_operativo`, `navegador`, `plataforma`, `latitud`, `longitud`, `estado_inicio`, `identificador` |

## Mutaciones observadas

| Operacion | Tabla | Evidencia |
| --- | --- | --- |
| Reiniciar intentos, bloqueo, actividad y visitas de usuario | `dav_clienteusuarios` | `veriflogin.php:115-117` |
| Incrementar visitas del cliente | `dav_cliente` | `veriflogin.php:119-120` |
| Registrar login exitoso | `log_asgard_ecosistema` | `veriflogin.php:139-160` |
| Registrar login fallido | `log_asgard_ecosistema` | `veriflogin.php:183-202` |
| Reiniciar bloqueo vencido | `dav_clienteusuarios` | `veriflogin.php:220-224` |
| Incrementar intentos y fijar bloqueo | `dav_clienteusuarios` | `veriflogin.php:227-228` |

## Entradas observadas

- `username`
- `contrasena`
- `csrf_token`
- `ultimoenlace`
- cookies `latitud` y `longitud`
- `HTTP_USER_AGENT` e IP de request
