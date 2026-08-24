# Billing Payments Receivables - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Estados Candidatos de Factura/Planilla

| Estado / Marcador | Interpretacion candidata | Evidencia |
| --- | --- | --- |
| `idestadofactura = 1` | Factura/planilla activa o valida para consultas, descargas y recepcion. | `facplaquery.php:31-117`, `recepcionplanillas_ajax.php:1-241` |
| `idestadofactura = 2` | Factura/planilla anulada o con marca visual especial en generacion PDF. | `generarfacturaplanillacliente.php:168-170`, `.data_base/asgard.sql:6177-6266` |
| `fechaenvioplanilla IS NOT NULL` | Documento enviado al cliente. | `recepcionplanillas_ajax.php:1-85` |
| `recepcionplanilla = 0` | Documento enviado pendiente de recepcion. | `recepcionplanillas_ajax.php:1-85` |
| `recepcionplanilla = 1` | Documento recibido por cliente. | `recepcionplanillas_ajax.php:171-241`, `recepcionplanillas_ajax.php:321-346` |
| `fecharecepcionplanilla` | Fecha/hora de recepcion registrada. | `recepcionplanillas_ajax.php:321-346` |
| `contabilizar_factura`, `contabilizar_planilla` | Marcadores de contabilizacion pendientes de formalizar. | `.data_base/asgard.sql:6249-6255` |
| `factura_cobrada`, `planilla_cobrada` | Fechas de cobro de factura o planilla. | `.data_base/asgard.sql:6252-6255` |

## Estados Candidatos de Nota de Cobranza

| Estado / Marcador | Interpretacion candidata | Evidencia |
| --- | --- | --- |
| `idestadopago = 2` | Nota valida para reporte/recepcion como nota de cobranza. | `notasdebitoquery.php:10-91`, `recepcionplanillas_ajax.php:72-85` |
| `estado_enviado = 1` | Nota enviada al cliente. | `recepcionplanillas_ajax.php:72-85` |
| `estado_recepcionado = 0` | Nota enviada pendiente de recepcion. | `recepcionplanillas_ajax.php:72-85` |
| `estado_recepcionado = 1` | Nota recibida por cliente. | `recepcionplanillas_ajax.php:215-241`, `recepcionplanillas_ajax.php:325-352` |
| `contabilizar_nd` | Marcador de contabilizacion de nota de debito. | `.data_base/asgard.sql:7508-7515` |
| `notadebito_cobrada` | Fecha de cobro de nota de debito. | `.data_base/asgard.sql:7508-7515` |

## Estados Candidatos de Cuenta por Cobrar

| Estado | Condicion candidata | Evidencia |
| --- | --- | --- |
| `VIGENTE` | Dias transcurridos menos credito del cliente es menor o igual a cero. | `estadocuentasquery.php:1-75` |
| `EN MORA` | Dias transcurridos menos credito del cliente es mayor que cero. | `estadocuentasquery.php:1-75` |

## Observaciones

Los estados numericos de factura, planilla, pago y nota de debito se infieren desde filtros de codigo. Requieren catalogo formal antes de cerrar baseline.
