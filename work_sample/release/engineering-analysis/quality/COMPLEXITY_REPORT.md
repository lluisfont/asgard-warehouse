# Complexity Report

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Fuentes de complejidad

- 6649 ficheros inventariados.
- 70 dominios candidatos con 420 artefactos.
- 838 tablas `CREATE TABLE` detectadas en dump principal usado para auditoria.
- Graphify: 32896 nodos y 53235 aristas.
- Mezcla de UI, negocio, SQL, filesystem e integraciones en un mismo runtime.

## Hotspots candidatos

- `logistica/*Class.php`, `controllers/SolicitudClass.php`, `controllers/GlobalClass.php`.
- `operativos/*query.php`, `contables/*query.php`.
- `intercambioDocumental/ajax/*OCR*`.
- `documentacion.php`, `documentacionaprobado.php`.
- `vehiculosexcel/*`.
