# Certification Expiry Control

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Controlar certificados, resoluciones, autorizaciones previas y documentos regulados asociados a clientes, vehiculos/modelos o mercancias, incluyendo fecha de emision, fecha de vencimiento, extensiones, archivos de respaldo, alertas y notificaciones de documentos por vencer o vencidos.

## Business Outcome

El cliente y el equipo operativo pueden identificar documentos regulatorios vigentes, por vencer o vencidos antes de que afecten importaciones, vehiculos, autorizaciones previas o tramites relacionados.

## Scope

Incluye:

- Registro y edicion de documentos de control de certificaciones.
- Carga de archivos adjuntos y mercancias desde formulario o Excel.
- Asociacion con modelo, AP madre, tipo de vehiculo, motor, clase, marca, cilindrada y partida arancelaria.
- Calculo de estado documental por fecha de vencimiento, extension y plazo de alerta.
- Conteo/listado de certificados vencidos por cliente.
- Notificaciones a correos configurados para documentos vencidos o por vencer.
- Consulta de autorizaciones previas por chasis con vencimiento a 180 dias.

Fuera de alcance:

- Flujo completo de solicitud aduanera que consume estos documentos.
- Definicion normativa oficial de cada tipo documental.

## Actors

- Usuario cliente autorizado: registra, edita y consulta certificados/documentos.
- Usuario interno ASGARD: monitorea cumplimiento y puede revisar parametros o reportes.
- Sistema ASGARD: calcula estado, guarda adjuntos, cruza modelos/AP y envia notificaciones.
- Destinatario de notificacion: recibe alertas por documentos por vencer o vencidos.

## Trigger

El proceso inicia cuando se registra un documento regulatorio, se edita su informacion, se consulta el tablero de control o se ejecuta una notificacion programada.

## Completion Criteria

El documento queda registrado con archivos/mercancias, estado calculable, trazabilidad de cliente/usuario y capacidad de ser consultado o notificado segun su vencimiento.

## Evidence

- `index_archivos/parametros/control_certificaciones/controladores/ControlCertificacionesController.php`
- `index_archivos/parametros/control_certificaciones/controladores/CommonController.php`
- `index_archivos/parametros/control_certificaciones/ajax/registrar.php`
- `index_archivos/parametros/control_certificaciones/ajax/editar.php`
- `index_archivos/parametros/control_certificaciones/ajax/control-certificado.php`
- `index_archivos/parametros/control_certificaciones/ajax/control-certificaciones.php`
- `index_archivos/parametros/control_certificaciones/ajax/cetificados-vencidos.php`
- `index_archivos/parametros/control_certificaciones/notificaciones/notificaciones.php`
- `index_archivos/parametros/control_certificaciones/notificaciones/notificaciones-boleta-garantia.php`
- `index_archivos/parametros/control_certificaciones/notificaciones/notificaciones_mensuales.php`
- `index_archivos/parametros/control_certificaciones/notificaciones/notificaciones_semanales.php`
- `index_archivos/controllers/ControlAps.php`
- `.data_base/asgard.sql:662-750`
- `.data_base/asgard.sql:1288-1300`
- `.data_base/asgard.sql:17722-17725`
- `.data_base/asgard.sql:39347-39399`
