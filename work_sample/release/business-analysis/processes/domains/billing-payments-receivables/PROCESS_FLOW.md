# Billing Payments Receivables - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Flujo A - Consulta y Descarga de Factura/Planilla

1. El usuario filtra por rango de fechas y criterios operativos.
2. El sistema crea tablas temporales de monto de factura, monto de planilla y planillas agrupadas.
3. El sistema lista documentos con fecha, carpeta, pedido, DIM, numero, montos, estado y enlaces.
4. El usuario abre la factura o planilla.
5. El sistema recupera cabecera, caso, dosificacion, importes y documentos comerciales.
6. El sistema genera o entrega PDF de factura/planilla segun ruta y tipo.

## Flujo B - Notas de Debito / Cobranza

1. El usuario filtra por fecha, cliente, empresa y ciudad.
2. El sistema lista notas pagadas/emitidas (`idestadopago = 2`) con ciudad, cliente, numero/gestion, tipo, carpeta, pedido, glosa, monto y conceptos.
3. El sistema relaciona notas con casos, carpetas de asesoria gestion o detalles de pagos cuando hay varias carpetas.

## Flujo C - Pagos Recibidos y Anticipos

1. El usuario consulta pagos recibidos por ciudad y fechas.
2. El sistema calcula monto aplicado desde `dav_cobros`.
3. El sistema calcula monto devuelto desde `dav_anticiposdevueltos` y pagos en estado valido.
4. El sistema presenta importe, aplicado, devuelto y saldo en cuenta.

## Flujo D - Recepcion de Documentos de Cobro

1. El usuario consulta documentos enviados pendientes de recepcion.
2. El sistema une facturas/planillas, notas de cobranza y cites.
3. El usuario marca recepcion individual o multiple.
4. El sistema actualiza el marcador y fecha de recepcion correspondiente al tipo de documento.

## Flujo E - Estado de Cuenta

1. El usuario consulta a una fecha de corte.
2. El sistema ejecuta el procedimiento `cobros2` para preparar documentos pendientes.
3. El sistema calcula vencimiento segun dias de credito del cliente.
4. El sistema clasifica cada saldo como `VIGENTE` o `EN MORA`.

## Evidencia

- `index_archivos/contables/facplaquery.php:31-117`
- `index_archivos/contables/generarfacturaplanillacliente.php:12-170`
- `index_archivos/contables/notasdebitoquery.php:10-91`
- `index_archivos/contables/pagosrecibidosquery.php:7-43`
- `index_archivos/contables/recepcionplanillas_ajax.php:1-352`
- `index_archivos/contables/estadocuentasquery.php:1-75`
