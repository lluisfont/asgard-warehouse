# Exception rules

Estado: candidate_reconstruction  
Confianza: media

| Excepcion | Tratamiento candidato |
|---|---|
| Documento faltante | Mantener pendiente, observar o bloquear avance |
| OCR inconsistente | Requiere correccion/revision manual |
| Permiso insuficiente | Bloquear accion o redirigir |
| Datos de catalogo faltantes | No permitir guardado o dejar pendiente operativo |
| Integracion fallida | Registrar error y operar por fallback/manual |
| Duplicidad | Rechazar, observar o solicitar confirmacion segun flujo |
| Estado invalido | Bloquear transicion |
