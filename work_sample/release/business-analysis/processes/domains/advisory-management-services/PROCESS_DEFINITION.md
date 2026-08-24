# Advisory Management Services - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Solicitudes de asesoria y gestion soporte.

Objetivo de negocio candidato: permitir registrar solicitudes de servicios adicionales, asociarlas a embarques o casos previos, definir tramites por entidad emisora y tipo, gestionar su ciclo operativo por estados, conectar intercambio documental y exponer reportes generales de seguimiento.

## Trigger

El proceso se activa desde:

- Modulo `asesoria-gestion/servicios-adicionales.php`.
- Vista de nueva solicitud o solicitud existente.
- Integraciones dentro de detalle de embarque, aduanas o solicitud previa.
- Carga masiva que crea gestion aduanera/soporte.
- Reporte operativo de asesoria y gestion.

Evidencia:

- `index_archivos/asesoria-gestion/servicios-adicionales.php:35-61`
- `index_archivos/asesoria-gestion/views/solicitud.php`
- `index_archivos/asesoria-gestion/components/tbl-estados.js:1-220`
- `index_archivos/asesoria-gestion/components/solicitud.js:1-260`
- `index_archivos/asesoria-gestion/components/tramite.js:1-130`
- `index_archivos/logistica/SolicitudesClass.php:714-850`
- `index_archivos/controllers/SolicitudClass.php:481-520`
- `index_archivos/operativos/asesoria-gestion.php:67-212`

## Actores

- Cliente usuario o solicitante: registra solicitud, contacto, ciudad, linea y notas.
- Usuario operativo/coordinador: asigna o gestiona tramites y seguimiento.
- Oficial asignado: figura como responsable operativo de tramite.
- ASGARD: persiste solicitud, carpetas GE, tramites, estados, intercambio documental y reportes.
- Intercambio documental: se crea o vincula para soportar documentos del servicio adicional.

## Resultado Esperado

- La solicitud queda registrada en `ages_solicitudes_asesoria_gestion`.
- Las carpetas asociadas quedan en `ages_asesoria_gestion_carpetas`.
- Los tramites quedan asociados mediante `dav_tramites`.
- La solicitud se muestra en tablero por estado: pendientes, enviados, recepcionados, asignados, en revision, en proceso y finalizados.
- El reporte general expone fechas clave, responsables, entidades, tramites, costos y enlaces con embarque/carpeta.

## Estado

Reconstruccion candidata. La revision humana se difiere hasta completar todos los dominios del baseline.
