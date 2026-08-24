# Deployment Architecture AS-IS

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Inferencia candidata

- Servidor web PHP sirve `index_archivos`.
- Configuracion runtime mediante `.env.php`, constantes y `cnfdb105.php`.
- Base MySQL accesible desde PHP via extension `mysql_*` legacy y PDO en login.
- Filesystem compartido para `FILES_PATH` y documentos.
- Dependencias vendorizadas dentro del repo (`vendor`, `PHPExcel`, `MPDF57`, etc.).

## Pendiente

- Confirmar web server, version PHP, OS, vhosts, TLS termination, rutas fisicas y cron real.
- Confirmar si `FILES_PATH` esta en disco local, compartido o red.
- Confirmar segregacion de entornos y secretos.
