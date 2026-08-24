# Scheduled tasks catalog

Estado: inferred_from_static_evidence  
Confianza: baja-media

## Tareas candidatas

| Categoria | Evidencia | Confirmacion pendiente |
|---|---|---|
| Cron PHP | Carpetas/referencias `cron` | Crontab/Task Scheduler real, frecuencia y usuario |
| Notificaciones | Pusher/correos | Disparador exacto y politica de reintentos |
| OCR/documentos | Procesamiento documental | Cola, polling o servicio externo usado |
| Conciliaciones | Comparaciones/reportes operativos | Periodicidad y tablas afectadas |
| Limpieza/temporales | Uso de tablas `tmp_*` | Caducidad, truncado y dependencia por flujo |
| Sincronizaciones externas | Integraciones SFTP/API/correo | Credenciales, ventanas horarias y fallback |

## Riesgo

La ausencia de scheduler confirmado impide declarar estos procesos como canonicos. Para migracion, el entorno productivo debe aportar crontabs, tareas Windows, comandos deployados y logs de ejecucion.
