# Additional Services Request Management - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-ASRM-001 | El boton Agregar/Nuevo Servicio depende de permiso de escritura. | `tbl-estados.js`, `servicios-adicionales.php` |
| BR-ASRM-002 | En embarques finalizados no se muestra boton para agregar servicios adicionales. | `fecha_finalizacion` en `logis_embarques` |
| BR-ASRM-003 | La escritura se consulta en `dav_clienteusuariospermisos` con `idreportescliente=66`. | `servicios-adicionales.php` |
| BR-ASRM-004 | Una solicitud nueva requiere solicitante, email y ciudad. | Validaciones `required` en `solicitud.js` |
| BR-ASRM-005 | La seleccion de entidad emisora controla los tramites disponibles. | Endpoint `/tramites/{entidad}` |
| BR-ASRM-006 | La seleccion de tramite controla los tipos disponibles. | Endpoint `/tipos-tramites/{tramite}` |
| BR-ASRM-007 | Los estados visuales observados son Pendientes, Enviados, Recepcionados, Asignados, En Revision, En Proceso y Finalizado. | `tbl-estados.js` |
| BR-ASRM-008 | La edicion/agregado de tramites se bloquea desde estados recepcionado, asignado, en revision, en proceso o finalizado. | `validaEstado` en `servicios-adicionales.js` |
| BR-ASRM-009 | Cuando una solicitud se guarda con exchange existente, ASGARD persiste el id de intercambio documental. | `guardar-intercambio-documental/{id}` |
| BR-ASRM-010 | Cuando no hay exchange, ASGARD crea uno usando modulo/template `servicio_adicional`. | `getExchangeData(..., 'servicio_adicional')`, `iniciarIntercambio.php` |
| BR-ASRM-011 | Los documentos agregados al exchange usan `hash_tramite` como `document_id`. | `getDatosIntercambio` |
| BR-ASRM-012 | Servicios automaticos observados: certificado de origen, fitosanitario e inocuidad. | `actualizaridexchange.php` |

## Riesgos de regla pendientes

- Confirmar catalogo oficial de estados y transiciones.
- Confirmar matriz de roles para enviar, recibir, asignar, revisar, procesar, finalizar, cerrar y facturar.
- Confirmar si se permite modificar tramites despues de enviado pero antes de recepcionado.
- Confirmar si `hash_tramite` es estable y obligatorio para documentos.
