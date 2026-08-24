# Permission Catalog

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Mecanismos observados

| Mecanismo | Uso | Evidencia |
| --- | --- | --- |
| `permisos.php` | Gate de sesion cliente para pantallas legacy. | multiples includes |
| `dav_clienteusuariospermisos` | Permisos por usuario cliente y reporte, con lectura/escritura. | `cambiocontrasena.php`, `servicios-adicionales.php`, `embarques_ver_gestion_aduanera.php` |
| `dav_clientereportescliente` | Reportes habilitados por cliente. | `GlobalClass.php`, menu/permisos |
| `dav_permisos` | Permisos para usuarios internos/modulos. | schema, dominios de identidad |
| `idreportescliente` hardcoded | Gate puntual por pantalla/accion. | ids observados `49`, `65`, `66` |
| `ASGARD_TYPE` | Separa contexto `CLIENTES`, `PROVEEDORES` e interno. | `servicioNotificaciones/*`, `pusherlibs.php` |

## Permisos hardcoded relevantes

| ID | Interpretacion candidata | Evidencia |
| --- | --- | --- |
| `49` | Servicios adicionales / asesoria gestion. | `asesoria-gestion/servicios-adicionales.php` |
| `65` | Gestion aduanera desde embarque. | `logistica/componentes/embarques_ver_gestion_aduanera.php`, `shipment-customs-request-management` |
| `66` | Nueva solicitud asesoria/gestion. | `asesoria-gestion/views/nueva-solicitud.php` |

## Riesgos candidatos

- No se observa un middleware central de autorizacion por recurso; las pantallas aplican checks locales.
- La escritura se valida en UI/PHP puntual, pero requiere comprobar endpoints AJAX equivalentes.
- La pertenencia al tenant se repite manualmente con filtros `idcliente`, lo que aumenta riesgo de omision.

## Pendiente

- Extraer matriz completa `idreportescliente -> pantalla -> accion -> lectura/escritura`.
- Validar que todo endpoint mutador compruebe sesion, permiso y pertenencia del recurso.
