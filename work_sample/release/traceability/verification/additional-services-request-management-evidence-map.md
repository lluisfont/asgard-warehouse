# Additional Services Request Management - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Elemento | Evidencia | Observacion |
| --- | --- | --- |
| Pestaña de embarque | `logistica/frames/servicios-adicionales.php` | Inserta `tbl-estados` con `embarque_id`, solicitante y permiso. |
| Bloqueo por embarque finalizado | `servicios-adicionales.php` | Consulta `logis_embarques.fecha_finalizacion`. |
| Permiso de escritura | `servicios-adicionales.php` | Consulta `dav_clienteusuariospermisos.idreportescliente=66`. |
| Bandejas por estado | `asesoria-gestion/components/tbl-estados.js` | Renderiza Pendientes, Enviados, Recepcionados, Asignados, En Revision, En Proceso y Finalizado. |
| Formulario solicitud | `asesoria-gestion/components/solicitud.js` | Captura solicitante, email, ciudad, linea, notas y tramites. |
| Catalogo entidad/tramite/tipo | `servicios-adicionales.js`, `tramite.js` | Usa endpoints `/entidades-emisoras`, `/tramites/{id}`, `/tipos-tramites/{id}`. |
| Guardado solicitud | `solicitud.js`, `tbl-estados.js` | Llama `/nueva-solicitud`. |
| Envio solicitud | `solicitud.js` | Llama `/enviar-solicitud/{id}`. |
| Intercambio documental | `solicitud.js`, `servicios-adicionales.js`, `iniciarIntercambio.php` | Usa modulo/template `servicio_adicional`. |
| Alta automatica | `logistica/ajax/actualizaridexchange.php` | Crea servicios certificado origen, fitosanitario e inocuidad. |
| Estado y reportes | `.data_base/asgard.sql` | Vistas `v_solicitudes_asesoria_gestion`, `v_reporte_general_asesoria_gestion`. |

## Cobertura

- Flujo principal reconstruido: si.
- Reglas de permiso y bloqueo reconstruidas: si.
- Modelo de estado candidato reconstruido: si.
- Integracion documental reconstruida: si.
- Validacion humana requerida: si.
