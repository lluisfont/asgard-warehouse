# Customs Document Approval - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Carga, mantenimiento, aprobacion y envio de documentos previos de aduana.

Objetivo de negocio candidato: permitir gestionar documentos requeridos para un caso previo, adjuntar archivos, registrar documentos adicionales, convertir documentos intermedios en documentos previos, marcar documentos para envio/aprobacion y notificar pendientes.

## Trigger

El proceso se ejecuta desde `documentacion.php` y `documentacionaprobado.php`, y se integra con el envio/finalizacion de solicitud en `finsolicitud.php`.

Evidencia:

- `index_archivos/documentacion.php:86-171`
- `index_archivos/documentacion.php:234-323`
- `index_archivos/documentacionaprobado.php:74-171`
- `index_archivos/documentacionaprobado.php:196-270`
- `index_archivos/documentacionaprobado.php:316-476`
- `index_archivos/finsolicitud.php:196-220`
- `index_archivos/finsolicitud.php:375`

## Actores

- Cliente usuario: carga documentos y adjuntos.
- Usuario operativo/aprobador: revisa documentos, completa datos y marca documentos para envio.
- ASGARD: persiste documentos, adjuntos, estados y notificaciones.
- Destinatarios de correo: reciben detalle de documentos registrados o pendientes.

## Resultado Esperado

- Documentos previos se registran en `dav_documentosprevios`.
- Otros documentos se registran en `dav_otrosdocumentosprevios`.
- Archivos se almacenan bajo carpetas por documento/caso.
- Documentos intermedios pueden convertirse en documentos previos y ocultarse.
- Documentos aprobados/pendientes se marcan mediante `aceptar` y `estado`.
- Al enviar/finalizar, se notifican documentos y se marcan como enviados.

## Estado

Reconstruccion candidata. La revision humana se difiere hasta completar todos los dominios del baseline.
