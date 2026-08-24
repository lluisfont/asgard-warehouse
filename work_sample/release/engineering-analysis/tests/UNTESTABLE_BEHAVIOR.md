# Untestable Behavior

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Comportamiento | Motivo |
| --- | --- |
| Power BI interno | Requiere tenant/dataset externo. |
| OCR real | Depende de servicio externo, modelos y documentos sensibles. |
| SFTP/SSH real | Credenciales/servidor externo; no usar en tests sin entorno seguro. |
| Correos reales | Riesgo de envio a clientes/proveedores. |
| Pusher real | Puede notificar usuarios reales. |
| Cron externo no incluido | No se conoce scheduler/productivo. |
| Tablas SQL-only | Sin codigo PHP consumidor observado. |

Usar mocks/fakes/fixtures para estos comportamientos.
