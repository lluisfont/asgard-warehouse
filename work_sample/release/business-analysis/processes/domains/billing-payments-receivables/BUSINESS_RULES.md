# Billing Payments Receivables - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BPR-BR-001 | La consulta de factura-planilla se limita al cliente de sesion y filtra por fecha de factura o fecha de validacion DUI segun seleccion. | `facplaquery.php:31-83` |
| BPR-BR-002 | El monto factura se calcula sumando `dav_facturasdetalle.monto` por `idfacturaplanilla`. | `facplaquery.php:86-89`, `.data_base/asgard.sql:6266-6282` |
| BPR-BR-003 | El monto planilla se calcula sumando `dav_pagosdetalle.monto` por caso, excluyendo pagos anulados (`idestadopago = 3`) y prepagados en algunos calculos. | `facplaquery.php:91-94`, `generarfacturaplanillacliente.php:126-136` |
| BPR-BR-004 | Las planillas agrupadas usan `dav_casos.idplanilla` para sumar pagos detalle contra una factura-planilla agrupadora. | `facplaquery.php:96-112` |
| BPR-BR-005 | Facturas/planillas activas se identifican con `idestadofactura = 1` en consultas y descargas. | `facplaquery.php:31-117`, `recepcionplanillas_ajax.php:1-241` |
| BPR-BR-006 | La descarga de factura y planilla construye rutas PDF distintas segun dosificacion antigua/en linea y tipo de factura. | `descargarfactura.php:12-26`, `descargarplanilla.php:11-29` |
| BPR-BR-007 | Las notas de debito/cobranza reportadas se filtran con `dav_notasdebito.idestadopago = 2`. | `notasdebitoquery.php:10-91`, `recepcionplanillas_ajax.php:72-85` |
| BPR-BR-008 | Los pagos recibidos muestran saldo en cuenta como importe menos cobrado menos devuelto. | `pagosrecibidosquery.php:7-43` |
| BPR-BR-009 | La recepcion de factura/planilla marca `recepcionplanilla = 1` y `fecharecepcionplanilla = CURRENT_TIMESTAMP()`. | `recepcionplanillas_ajax.php:321`, `recepcionplanillas_ajax.php:346` |
| BPR-BR-010 | La recepcion de nota de cobranza marca `estado_recepcionado = 1` y `fecha_recepcionado = CURRENT_TIMESTAMP()`. | `recepcionplanillas_ajax.php:325`, `recepcionplanillas_ajax.php:352` |
| BPR-BR-011 | El estado de cuentas clasifica mora comparando dias transcurridos contra `dav_cliente.diascredito`. | `estadocuentasquery.php:1-75` |
| BPR-BR-012 | El procedimiento `cobros2` es la fuente operativa para preparar saldos pendientes del estado de cuentas. | `estadocuentasquery.php:63` |
