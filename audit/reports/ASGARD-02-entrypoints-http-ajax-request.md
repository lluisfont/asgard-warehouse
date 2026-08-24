# ASGARD-02 - Entry points, HTTP, AJAX y ciclo request-response

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- Backend Slim inicializa en `AtlantesBE-main/AtlantesBE-main/public/index.php` y carga `app/start.php`.
- Se detectaron `339` rutas Slim en `audit/evidence/backend_routes.csv`.
- Se detectaron `99` rutas Angular en `audit/evidence/frontend_routes.csv`.
- Se detectaron `328` llamadas HTTP desde servicios Angular en `audit/evidence/frontend_service_calls.csv`.

## Archivos backend con mas rutas

| Item | Count |
| --- | --- |
| AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 129 |
| AtlantesBE-main/AtlantesBE-main/app/routes/datosmaestro.php | 74 |
| AtlantesBE-main/AtlantesBE-main/app/routes/contabilidad.php | 55 |
| AtlantesBE-main/AtlantesBE-main/app/routes/embarques.php | 26 |
| AtlantesBE-main/AtlantesBE-main/app/routes/entidades.php | 19 |
| AtlantesBE-main/AtlantesBE-main/app/routes/usuarios.php | 19 |
| AtlantesBE-main/AtlantesBE-main/app/routes/asgard.php | 13 |
| AtlantesBE-main/AtlantesBE-main/app/routes/empresa.php | 3 |
| AtlantesBE-main/AtlantesBE-main/app/routes/common.php | 1 |

## Ciclo observado

`public/index.php` requiere `app/start.php`; `start.php` configura Slim, CORS, parser de cuerpo, middleware de rutas/error, conexion PDO y carga rutas por dominio. El frontend consume endpoints mediante servicios Angular que adjuntan `Authorization` en multiples llamadas.

## Estado

`COMPLETED`: entry points principales y catalogos HTTP/AJAX trazados.
