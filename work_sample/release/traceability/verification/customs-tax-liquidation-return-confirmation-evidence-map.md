# Customs Tax Liquidation Return Confirmation - Evidence Map

| Artefacto | Evidencia |
| --- | --- |
| Process definition | `detalleitems.php`, `detalleitemsquery.php`, `EmbarqueClass.php` |
| Legacy payment confirmation | `versolicitud.php:39-124` |
| Process flow | `detalleitems.php:7-64`, `detalleitems.php:191-196` |
| Business rules | `fechaenvioliquidacion` / `fecharetornoliquidacion` checks and email update |
| Data used | `.data_base/asgard.sql:1603-1604`, `.data_base/asgard.sql:9707-9714`, `.data_base/asgard.sql:6904-6951` |
| State model | `detalleitems.php:67-71`, `detalleitems.php:191-196` |
| Use case | Observed confirm form and update path |
| OpenSpec | Derived from observed PHP and schema behavior |

## Limitaciones

- No se inspecciono el flujo completo que informa `fechaenvioliquidacion`.
- La semantica formal de "retorno liquidacion" requiere validacion de negocio.
- El envio depende de SendGrid y no se observo persistencia local detallada del resultado.
