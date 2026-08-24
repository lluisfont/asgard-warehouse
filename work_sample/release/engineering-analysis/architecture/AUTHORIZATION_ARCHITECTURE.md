# Authorization Architecture

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Modelo observado

- Gate de sesion: `permisos.php`.
- Permisos cliente: `dav_clienteusuariospermisos` por `idreportescliente`, lectura/escritura.
- Reportes cliente habilitados: `dav_clientereportescliente`.
- Permisos internos: `dav_permisos`.
- Reglas locales por pantalla: consultas puntuales a permisos y condicion de cliente/estado.

## Ejemplos

- Gestion aduanera desde embarque usa `idreportescliente = 65`.
- Servicios adicionales usan ids `49` y `66`.
- Dashboards/reportes dependen de menu/reportes habilitados.

## Riesgo

La autorizacion esta distribuida. No se observa middleware unico que fuerce permiso + pertenencia del recurso en todos los endpoints AJAX y descargas.
