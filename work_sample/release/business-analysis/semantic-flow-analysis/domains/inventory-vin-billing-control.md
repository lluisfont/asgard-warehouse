# inventory-vin-billing-control - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 2
- Campos cruzados: 4
- Tablas con mutacion observada: 0
- Riesgos candidatos: documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `inventario_facturacion_chasis` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | deleted_at | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; notificacion o acceso externo; variante cliente pendiente de confirmar; seguridad/autorizacion sensible |  |
| `inventario_facturacion_periodo` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | confirmado, deleted_at, historico | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; notificacion o acceso externo; variante cliente pendiente de confirmar; seguridad/autorizacion sensible |  |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `inventario_facturacion_chasis` | `deleted_at` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | `deleted_at` \| Baja logica del periodo. |
| `inventario_facturacion_periodo` | `confirmado` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | - Exportacion Excel por periodo confirmado/listado. \| `inventario_facturacion_periodo` \| Periodo de facturacion, cliente, fechas, confirmado/historico y auditoria. \| Facturacion confirmada \| Periodo confirmado por API. \| `confirmado` \| Periodo validado/confirmado para facturacion. \| Transiciones entre confirmado e historico. |
| `inventario_facturacion_periodo` | `deleted_at` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | `deleted_at` \| Baja logica del periodo. |
| `inventario_facturacion_periodo` | `historico` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | `inventario_facturacion_periodo` \| Periodo de facturacion, cliente, fechas, confirmado/historico y auditoria. \| Periodo historico \| Periodo listado en consolidado mensual. \| `historico` \| Periodo marcado como historico. \| Transiciones entre confirmado e historico. |
