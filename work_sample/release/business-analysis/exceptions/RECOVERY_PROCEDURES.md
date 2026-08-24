# Recovery procedures

Estado: candidate_reconstruction  
Confianza: baja

Procedimientos candidatos ante fallos:

- Reintentar carga/descarga documental tras validar archivo y permisos.
- Corregir datos OCR manualmente y revalidar.
- Reprocesar reporte/exportacion con filtros equivalentes.
- Reenviar correo/notificacion/token si el primer envio falla.
- Regularizar catalogos o datos maestros antes de continuar flujo.
- Usar conciliacion manual si integracion externa no responde.
- Escalar a TI ante errores SQL, permisos o rutas de archivo.

## Pendiente

Confirmar runbooks reales, logs y responsables.
