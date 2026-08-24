# Findings Register

| ID | Fase | Severidad | Estado | Hallazgo | Evidencia |
| --- | --- | --- | --- | --- | --- |
| FND-AUTH-001 | ASGARD-08 | Medium | OBSERVED_CANDIDATE | La autenticacion depende de JWT firmado con constante `jwt_key` externa al repo. | AtlantesBE-main/AtlantesBE-main/app/middleware/jwt.php:16 |
| FND-INT-001 | ASGARD-09 | Medium | OBSERVED_CANDIDATE | Existe integracion Azure Blob configurada por constantes de entorno. | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php:14745 |
| FND-INT-002 | ASGARD-09 | Medium | OBSERVED_CANDIDATE | Existe integracion SendGrid para correo transaccional. | AtlantesBE-main/AtlantesBE-main/app/functions/sendmail.php:11 |
| FND-DOC-001 | ASGARD-10 | Medium | OBSERVED_CANDIDATE | El sistema procesa cargas de archivos con `move_uploaded_file`. | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php:10479 |
| FND-ACC-001 | ASGARD-11 | High | OBSERVED_CANDIDATE | La logica OVP/contable centraliza reglas extensas en `ovp.php`. | AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php:2 |
| FND-SEC-001 | ASGARD-13 | High | OBSERVED_CANDIDATE | CORS permite cualquier origen en el bootstrap del API. | AtlantesBE-main/AtlantesBE-main/app/start.php:59 |
| FND-SEC-002 | ASGARD-13 | High | OBSERVED_CANDIDATE | El middleware de errores de Slim expone detalles en runtime. | AtlantesBE-main/AtlantesBE-main/app/start.php:38 |
| FND-SEC-003 | ASGARD-13 | High | OBSERVED_CANDIDATE | Se observan consultas SQL construidas por concatenacion/interpolacion. | AtlantesBE-main/AtlantesBE-main/app/functions/generarcodigocontrol.php:161 |
| FND-SEC-004 | ASGARD-13 | High | OBSERVED_LOCAL_NOT_COMMITTED | Existe un archivo local ignorado `.env.example.php` con valores que parecen secretos; no esta versionado, pero requiere saneamiento/rotacion antes de compartir entornos. | AtlantesBE-main/AtlantesBE-main/app/.env.example.php |
| FND-TIME-001 | ASGARD-14 | Medium | OBSERVED_CANDIDATE | La normalizacion horaria por ciudad esta parcialmente implementada en servicios dedicados. | AtlantesBE-main/AtlantesBE-main/app/middleware/jwt.php:17 |
