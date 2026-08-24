# Logistics Order Status Milestones - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Entidades principales

| Entidad / tabla | Uso funcional observado | Campos destacados |
| --- | --- | --- |
| `logis_edp` | Historial de hitos/estados de pedido por embarque. | `caso_id`, `embarque_id`, `cliente_id`, `proveedor_id`, `estado_edp_id`, `pedido_id`, `tipo_proveedor`, `fecha`, `edp`, `valor`, `mail`, `created_by`, `created_type`, `created_at`, `deleted_at`. |
| `logis_estados_edp` | Catalogo de estados seleccionables. | `cliente_id`, `estado_edp`, `orden_etapa`, `etapa`, `etapa_cbn_id`, `idtipoproveedor`, `deleted_at`. |
| `logis_embarques` | Embarque afectado por el hito y posible finalizacion. | `id`, `fecha_finalizacion`, `fecha_finalizacion_usuario`, `gestor_transporte_id`, `idagentesaduana`. |
| `dav_clienteusuariospermisos` | Permiso de escritura para estado de pedidos. | `idclienteusuarios`, `idreportescliente`, `escritura`. |
| `dav_clienteusuarios`, `dav_usuario`, `prov_usuarioaccesoproveedor` | Resolucion del usuario que creo el hito. | Nombre/username segun `created_type`. |
| `dav_transportista`, `dav_clientetransportista`, `logis_embarquesoperador` | Destinatario transportista para notificacion. | `idtransportista`, vinculo operador/embarque. |
| `push_notificacion`, `push_notificacionusuarios` | Notificacion persistida por integracion con servicio de notificaciones. | Se usa via `ServicioNotificacionesClass`. |

## Campos funcionales clave

| Campo | Descripcion candidata | Validacion pendiente |
| --- | --- | --- |
| `estado_edp_id` | Estado/hito de pedido seleccionado. | Confirmar catalogo oficial y valores finales. |
| `orden_etapa` | Orden/etapa de presentacion; para cliente 429 se muestra junto al estado. | Confirmar uso por cliente. |
| `edp` | Comentario o descripcion del hito. | Confirmar etiqueta funcional. |
| `valor` | Cantidad opcional, visible para estado id `11`. | Confirmar estado y unidad. |
| `created_type` | Origen del creador: `CLIENTE`, `INTERNO`, `OPERADOR`. | Confirmar todos los flujos que escriben. |

## Integraciones

| Integracion | Uso |
| --- | --- |
| MailClass | Envio de correo para actualizacion pick up. |
| ServicioNotificacionesClass | Persistencia de notificacion. |
| Pusher | Evento realtime en canal `logistica`, evento `crearSolicitud`. |
| Vue JSON Excel | Exportacion del historial mostrado. |

