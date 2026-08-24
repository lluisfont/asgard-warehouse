# ASGARD-04 - Arquitectura PHP, clases, includes y dependencias

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- `app/start.php` es el bootstrap central: carga entorno, PDO, CORS, middleware JWT, funciones, servicios y archivos de rutas.
- La arquitectura es modular por archivos de rutas grandes (`almacenes.php`, `contabilidad.php`, `entidades.php`, etc.) mas servicios puntuales (`DateTimeService`, `BlobStorageService`).
- Se observan librerias embebidas (`phpqrcode`, fuentes Tahoma, `piramide-uploader`) y dependencia Composer declarada en `composer.json`.

## Evidencias

- `AtlantesBE-main/AtlantesBE-main/app/start.php`
- `AtlantesBE-main/AtlantesBE-main/app/routes/*.php`
- `AtlantesBE-main/AtlantesBE-main/app/services/*.php`
- `AtlantesBE-main/AtlantesBE-main/composer.json`
- `audit/evidence/source_inventory.csv`

## Estado

`COMPLETED`: dependencias principales y puntos de acoplamiento identificados.
