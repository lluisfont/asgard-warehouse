# Continuous Improvement Nonconformity

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Gestionar registros de mejora continua, oportunidades de mejora y no conformidades desde el hallazgo inicial hasta asignacion, analisis causal, plan de acciones correctivas, verificacion, cierre o reapertura.

## Business Outcome

El cliente y el equipo responsable pueden registrar hallazgos, evaluar impacto/riesgo, asignar analistas, ejecutar acciones correctivas y dejar evidencia de cierre para trazabilidad de calidad, auditoria y mejora continua.

## Scope

Incluye:

- Registro o guardado de hallazgos/NC/SNC.
- Envio de hallazgos pendientes para gestion.
- Calculo de plazos de asignacion y atencion segun impacto.
- Asignacion de analista de causa y posible derivacion a equipo.
- Registro de normativa/procedimiento/requisito incumplido.
- Analisis de causa y plan de acciones correctivas.
- Archivos de evidencia de hallazgo, analisis y verificacion.
- Verificacion de acciones correctivas.
- Cierre, reapertura y postergacion.
- Parametrizacion de areas, procesos, origenes, hallazgos, consecuencias, impactos, controles, responsables, riesgos, normativas, requisitos, proveedores y roles.

Fuera de alcance:

- Definicion formal de la API externa consumida por `api_url`; el front demuestra endpoints y payloads, pero no se inspecciono implementacion backend.
- Reporteria BI historica derivada en `pbi_rnc_*`, salvo como consumidor de datos.

## Actors

- Usuario regular: registra hallazgo y accion inmediata.
- Administrador: abre casos, asigna analista, verifica, cierra o reabre.
- Analista: realiza analisis causal y plan de accion.
- Responsable de accion correctiva: ejecuta acciones asignadas.
- Responsable de control: participa en controles existentes.
- Sistema ASGARD/API RNC: persiste datos, calcula/lista estados, expone catalogos y guarda evidencias.

## Trigger

El proceso inicia cuando un usuario detecta un hallazgo, incumplimiento, NC/SNC u oportunidad de mejora y lo registra en el modulo de mejora continua.

## Completion Criteria

El caso queda cerrado con resultado de verificacion registrado, o reabierto con trazabilidad hacia un nuevo caso relacionado.

## Evidence

- `index_archivos/mejora-continua/js/constantes.js`
- `index_archivos/mejora-continua/js/config.js`
- `index_archivos/mejora-continua/views/formulario-caso.php`
- `index_archivos/mejora-continua/views/asignacion-analista.php`
- `index_archivos/mejora-continua/views/analisis.php`
- `index_archivos/mejora-continua/views/verificacion.php`
- `index_archivos/mejora-continua/views/cerrar-caso.php`
- `index_archivos/mejora-continua/views/re-apertura.php`
- `index_archivos/mejora-continua/components/commons/tbl-hallazgos.js`
- `index_archivos/mejora-continua/components/commons/tbl-analisis.js`
- `index_archivos/mejora-continua/components/commons/tbl-verificar.js`
- `index_archivos/mejora-continua/components/commons/tbl-cerrar.js`
- `.data_base/asgard.sql:15248-15882`
