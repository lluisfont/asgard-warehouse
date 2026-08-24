# Job and worker catalog

Estado: inferred_from_static_evidence  
Confianza: baja-media

No se ha observado un sistema de colas/workers formal con contratos explicitos. Si existen procesos diferidos, parecen implementarse mediante scripts PHP, `cron`, procedimientos SQL, polling, integraciones externas o procesamiento dentro del request web.

| Candidato | Evidencia | Riesgo |
|---|---|---|
| Tareas programadas | Referencias `cron` y procesos batch | Scheduler real pendiente de confirmar |
| OCR/procesamiento documental | Modulos documentales y OCR | Estados asincronos y reintentos no caracterizados |
| Notificaciones | `servicioNotificaciones/pusherlibs.php`, correos | Fallos/reintentos no uniformes |
| Reportes pesados | Dashboards, Excel, PDF | Posible bloqueo de request o timeouts |
| Conciliaciones/exportaciones | Comparacion documental y reportes operativos | Idempotencia y compensacion pendientes |
| Procedimientos SQL | Schema y referencias a stored logic | Side effects fuera del codigo PHP |

## Criterio para completar

Antes de refactorizar, levantar inventario de scheduler real, comandos ejecutados, frecuencia, usuario del sistema, variables de entorno, rutas de salida y tablas modificadas.
