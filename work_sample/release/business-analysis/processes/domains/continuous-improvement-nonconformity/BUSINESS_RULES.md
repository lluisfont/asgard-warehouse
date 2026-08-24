# Business Rules

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Rule | Evidence |
| --- | --- | --- |
| BR-CIN-001 | El modulo usa estados numericos: eliminado 0, guardado 1, enviado 2, analisis 3, verificar 4, verificado 5, cerrado 6, reabierto 7 y postergado 8. | `mejora-continua/js/constantes.js:1-11` |
| BR-CIN-002 | Los roles visibles del modulo son administrador 1, analista 2 y regular 3. | `mejora-continua/js/constantes.js:13-15` |
| BR-CIN-003 | La comunicacion con API se autentica con cabecera `X-CREDENTIALS` que contiene usuario, cliente y bandera cliente. | `mejora-continua/js/config.js:1-14` |
| BR-CIN-004 | Un nuevo caso puede guardarse o enviarse usando el mismo payload base y distinto estado destino. | `views/formulario-caso.php:130-218`, `views/re-apertura.php:120-199` |
| BR-CIN-005 | El payload del hallazgo incluye area, consecuencia, impacto, nivel, origen, proceso afectado, responsable de accion inmediata, tipo de hallazgo, tipo de registro, descripcion y plazos. | `views/formulario-caso.php:153-196` |
| BR-CIN-006 | Los plazos de asignacion y atencion se calculan desde el impacto y su unidad de tiempo. | `components/casos/detalle-hallazgo.js:547-571`, `components/parametros/impactos.js` |
| BR-CIN-007 | Solo administrador ve accion para abrir/asignar casos enviados. | `components/commons/tbl-hallazgos.js:45-55` |
| BR-CIN-008 | La asignacion exige responsable de analisis y calcula fecha/plazo si existe impacto. | `components/casos/asignacion-analista.js:80-103`, `asignacion-analista.js:241-251` |
| BR-CIN-009 | Si no hay atencion inmediata se solicita plazo de postergacion y justificacion. | `components/casos/asignacion-analista.js:54-77` |
| BR-CIN-010 | Durante analisis se registran hasta cinco bloques de causa, accion correctiva, resultado esperado, responsable y fechas. | `components/casos/analisis.js:1-180`, `views/analisis.php:100-260` |
| BR-CIN-011 | El analista puede adjuntar archivos y descripcion de evidencia de analisis. | `components/casos/analisis.js:120-155`, `views/analisis.php:220-250` |
| BR-CIN-012 | La verificacion registra verificacion por accion, archivo de evidencia y bandera `verificado`. | `components/casos/verificacion.js:1-151`, `views/verificacion.php:180-244` |
| BR-CIN-013 | El cierre exige resultado de verificacion y ejecuta endpoint de cierre. | `views/cerrar-caso.php:160-229` |
| BR-CIN-014 | El estado de atencion en tableros se muestra como pendiente, por vencer o vencido segun comparacion de fechas. | `tbl-hallazgos.js:85-103`, `tbl-analisis.js:76-93`, `tbl-verificar.js:95-113` |
| BR-CIN-015 | El esquema mantiene relaciones con caso anterior/nuevo para reapertura. | `.data_base/asgard.sql:15516-15518` |
