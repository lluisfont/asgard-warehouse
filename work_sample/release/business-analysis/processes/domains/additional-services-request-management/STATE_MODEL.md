# Additional Services Request Management - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos

| Estado ID | Estado | Significado observado | Evidencia |
| --- | --- | --- | --- |
| 0 | Pendiente | Solicitud creada/no enviada. | Schema default y bandeja Pendientes |
| 1 | Enviado | Solicitud enviada para gestion. | `enviarSolicitud`, views |
| 2 | Recepcionado | Solicitud recibida por gestion. | `tbl-estados.js`, views |
| 3 | Asignado | Solicitud asignada a oficial. | `tbl-estados.js`, `showOficial` |
| 4 | En Revision | Solicitud en revision previa. | `tbl-estados.js`, views |
| 5 | En Proceso | Servicio en ejecucion. | `tbl-estados.js`, views |
| 6 | Finalizado | Gestion operativa finalizada. | `tbl-estados.js`, views |
| 7 | Cerrado | Cierre posterior observado en vistas. | `v_reporte_general_asesoria_gestion` |
| 8 | Facturado | Facturacion posterior observada en vistas. | `v_reporte_general_asesoria_gestion` |

## Transiciones candidatas

| Transicion | Desde | Hacia | Disparador | Evidencia |
| --- | --- | --- | --- | --- |
| Crear solicitud | - | Pendiente | Guardar nueva solicitud | `/nueva-solicitud` |
| Enviar solicitud | Pendiente | Enviado | Boton Enviar Solicitud | `/enviar-solicitud/{id}` |
| Recepcionar | Enviado | Recepcionado | Flujo API/operativo no visible completo | Bandeja Recepcionados |
| Asignar | Recepcionado | Asignado | Asignacion de oficial | Bandeja Asignados |
| Revisar | Asignado | En Revision | Inicio revision | `fecha_inicio_revision` |
| Procesar | En Revision | En Proceso | Inicio proceso | `fecha_inicio_proceso` |
| Finalizar | En Proceso | Finalizado | Finalizacion | `fecha_finalizacion` |
| Cerrar | Finalizado | Cerrado | Cierre responsable | `fecha_cierre`, `responsable_cierre_id` |
| Facturar | Cerrado/Finalizado | Facturado | Facturacion posterior | Estado `8` en vistas |

## Estados no observados en UI principal

- Anulado.
- Reabierto con razon.
- Observado/gestion observada.
- Facturacion en preparacion.
