# Proposal: Unificar manejo de fecha/hora por ciudad en servidor único

## Intent
Fusionar los servidores de Bolivia (UTC-04) y Perú (UTC-05) en una sola instalación backend, evitando que la zona horaria del sistema operativo determine las fechas guardadas o consultadas. El backend debe resolver la zona horaria desde el usuario autenticado por JWT y su ciudad asociada.

## Scope
- Agregar configuración de zona horaria en `t_ciudad`.
- Incluir la zona horaria de la ciudad del usuario dentro del JWT y/o resolverla en middleware al procesar requests autenticados.
- Centralizar utilidades PHP para fecha/hora local, UTC y conversión.
- Ajustar escrituras que usan `date()`, `DateTime()`, `CURRENT_TIMESTAMP()`, `NOW()` o fechas generadas por servidor.
- Definir migración SQL para mantener compatibilidad con columnas `datetime` existentes.
- Estandarizar la serialización de fechas para el frontend.

## Out of Scope
- Reescritura completa del modelo de datos.
- Cambio obligatorio de todos los campos `datetime` a `timestamp` en una sola entrega.
- Cambio de reglas tributarias, OVP, SIAT o integraciones externas salvo el uso correcto de fecha/hora.

## Approach
Se propone mantener las columnas `datetime` existentes como valores locales de negocio cuando correspondan a documentos, operaciones o reportes por ciudad, y agregar metadatos de zona horaria por ciudad. Para auditoría y trazabilidad nueva se recomienda guardar también UTC en campos nuevos cuando el evento represente un instante real.

La ciudad del usuario se obtiene desde `t_usuario.idciudad`. La tabla `t_ciudad` debe tener `utc_offset_minutos` y preferentemente `timezone_name` con valores IANA como `America/La_Paz` y `America/Lima`. El login debe emitir estos datos en el JWT para que el backend y frontend no dependan del timezone del servidor.

## Risks and Open Questions
- Hay SQL embebido en rutas grandes (`almacenes.php`, `contabilidad.php`, `usuarios.php`, `datosmaestro.php`) que debe revisarse por etapas.
- `CURRENT_TIMESTAMP()` y `NOW()` dependen de la zona horaria de MySQL si no se reemplazan o normalizan.
- Algunos campos `fecha` pueden representar fecha calendario y no instante; esos campos no deben convertirse a UTC.
- Confirmar si la base de datos final usará una sola instancia o varias bases con datos fusionados.
