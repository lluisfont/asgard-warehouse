# Billing Payments Receivables - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Entidad / Tabla | Uso candidato | Evidencia |
| --- | --- | --- |
| `dav_facturaplanilla` | Cabecera de factura/planilla: caso, cliente, fecha, numero, estados, envio, recepcion, anulacion, datos electronicos, contabilizacion y cobro. | `.data_base/asgard.sql:6177-6266`, `facplaquery.php:31-117`, `recepcionplanillas_ajax.php:1-352` |
| `dav_facturasdetalle` | Detalle de conceptos e importes de factura. | `.data_base/asgard.sql:6266-6282`, `facplaquery.php:86-89`, `generarfacturaplanillacliente.php:139-148` |
| `dav_pagos` | Cabecera de pagos usada para excluir anulados o validar devoluciones/tributos. | `.data_base/asgard.sql:7890-7932`, `facplaquery.php:91-94`, `pagosrecibidosquery.php:35-43` |
| `dav_pagosdetalle` | Detalle de pagos/planilla asociado a casos y conceptos. | `.data_base/asgard.sql:7932-7986`, `facplaquery.php:91-112`, `generarfacturaplanillacliente.php:126-136` |
| `dav_notasdebito` | Cabecera de nota de debito/cobranza: cliente, tipo, fecha, numero/gestion, glosa, estado pago/envio/recepcion/anulacion/contabilizacion. | `.data_base/asgard.sql:7473-7517`, `notasdebitoquery.php:10-91`, `recepcionplanillas_ajax.php:72-85` |
| `dav_notasdebitodetalle` | Conceptos e importes de notas de debito/cobranza. | `.data_base/asgard.sql:7518-7531`, `notasdebitoquery.php:64-91` |
| `dav_anticipos` | Pagos recibidos/anticipos por cliente, ciudad, fecha, recibo e importe. | `pagosrecibidosquery.php:7-22` |
| `dav_cobros` | Aplicacion de anticipos contra documentos cobrables. | `pagosrecibidosquery.php:23-33`, `estadocuentasquery.php:63` |
| `dav_anticiposdevueltos` | Devoluciones de anticipos que reducen saldo en cuenta. | `pagosrecibidosquery.php:35-43` |
| `dav_cliente` | Dias de credito para calculo de vencimiento y mora. | `estadocuentasquery.php:1-75` |
| `dav_casos` | Carpeta/caso, pedido, DIM, proveedor, cliente y relacion con factura/planilla. | `facplaquery.php:31-117`, `recepcionplanillas_ajax.php:1-241` |
| `dav_cite` | Documento formal adicional que comparte recepcion con documentos de cobro. | `recepcionplanillas_ajax.php:103-160`, `recepcionplanillas_ajax.php:330-349` |

## Datos Calculados

| Dato | Formula candidata | Evidencia |
| --- | --- | --- |
| `montofactura` | Suma de `dav_facturasdetalle.monto` por factura-planilla. | `facplaquery.php:86-89` |
| `montoplanilla` | Suma de `dav_pagosdetalle.monto` por caso, excluyendo pagos anulados. | `facplaquery.php:91-94` |
| `encuenta` | `anticipo.monto - montoaplicado - devuelto`. | `pagosrecibidosquery.php:7-22` |
| `mora` | Dias del documento menos dias de credito del cliente. | `estadocuentasquery.php:1-75` |
| `estado` | `EN MORA` si mora > 0; si no, `VIGENTE`. | `estadocuentasquery.php:1-75` |
