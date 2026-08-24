# Export MIC DEX Physical Reception Control - Data Used

## Entidades principales

| Entidad / Tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `dex_suma` | Registro documental MIC/DEX controlado. | `id`, `numero_dex`, `factura`, `numero_manifiesto`, `fecha_registro`, `fecha_verificacion_salida`, `empresa_transporte`, `placa_transporte`, `cantidad_bultos`, `peso_bruto`, `fecha_recibido`, `fecha_enviado`, `fecha_concluido` |
| `dex_suma_estado_historial` | Historial de cambios de estado. | `idsuma`, `idusuario`, `tipousuario`, `fecha`, `estado`, `created_at` |
| `prov_usuarioaccesoproveedor` | Nombre de usuario proveedor en historial. | `idusuarioaccesoproveedor`, `nombre` |
| `dav_clienteusuarios` | Nombre de usuario cliente en historial. | `idclienteusuarios`, `username` |

## Estados derivados

- `PENDIENTE`: sin fechas recibido/enviado/concluido.
- `RECIBIDO`: `fecha_recibido` informada y sin enviados/concluido.
- `ENVIADO`: `fecha_enviado` informada y sin concluido.
- `CONCLUIDO`: `fecha_concluido` informada.

## Persistencia observada

- `UPDATE dex_suma SET fecha_recibido = CURRENT_TIMESTAMP()` o `NULL`.
- `UPDATE dex_suma SET fecha_enviado = CURRENT_TIMESTAMP()` o `NULL`.
- `UPDATE dex_suma SET fecha_concluido = CURRENT_TIMESTAMP()` o `NULL`.
- `INSERT INTO dex_suma_estado_historial`.

