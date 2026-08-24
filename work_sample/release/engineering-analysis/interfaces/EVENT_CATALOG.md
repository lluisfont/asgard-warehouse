# Event catalog

Estado: inferred_from_static_evidence  
Confianza: media

La aplicacion no muestra un bus de eventos formal. Aun asi, existen eventos de negocio implicitos representados por escrituras en tablas, cambios de estado, correos, notificaciones push, documentos generados y logs.

| Evento implicito | Origen probable | Efectos observados/candidatos |
|---|---|---|
| Creacion/actualizacion de caso aduanero | Gestion aduanera y DAV | Cambios en tablas `dav_*`, documentos, notificaciones |
| Cambio de estado documental | OCR, aprobaciones, expedientes | Actualizacion de estados, evidencias y posibles avisos |
| Generacion de factura/planilla | Modulos de facturacion | Documentos descargables, registros contables, trazabilidad |
| Comparacion documental de exportacion | `ComparacionDocumentosIASA.php` | Resultado operativo y excepciones de conciliacion |
| Alta/uso de token de tercero | Gestor transporte/proveedores | Registro de contacto, acceso documental y auditoria |
| Notificacion realtime | `servicioNotificaciones/pusherlibs.php` | Push a usuarios o areas operativas |
| Login, 2FA e historial | Autenticacion/usuario | Sesion, historial y controles de acceso |
| Dashboard/reporte consultado | Reporteria y Power BI | Consulta agregada, posible exportacion |

## Observacion

Estos eventos deben tratarse como candidatos pendientes de validacion funcional. No hay evidencia suficiente para asumir garantias de orden, reintento, idempotencia o entrega exactamente una vez.
