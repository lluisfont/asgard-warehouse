# State transition rules

Estado: candidate_reconstruction  
Confianza: media

| Entidad | Transiciones candidatas |
|---|---|
| Caso | abierto -> en gestion -> pendiente -> finalizado/anulado |
| Documento | requerido -> cargado -> validado/observado -> aprobado/enviado |
| Solicitud | creada -> validada -> asignada -> atendida/rechazada |
| Embarque | registrado -> coordinado -> en transito -> entregado/finalizado |
| Factura/planilla | generada -> enviada -> recibida -> pagada/conciliada |
| Token | emitido -> activo -> usado -> vencido/revocado |

## Pendiente

Mapear codigos reales por tabla y endpoint antes de automatizar cambios de estado.
