# Billing Payments Receivables - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Facturacion, planillaje, notas de debito, pagos recibidos y estado de cuentas.

Objetivo de negocio candidato: permitir consultar y generar documentos de cobro asociados a carpetas/casos, calcular importes de factura y planilla, emitir o descargar respaldos PDF, gestionar recepcion por cliente, registrar pagos/anticipos aplicados y presentar saldos vigentes o en mora.

## Trigger

El proceso se activa por busquedas o acciones dentro del modulo `contables`:

- Consulta de factura-planilla por fechas, cliente, pedido, DIM, linea, proveedor o empresa.
- Generacion/descarga de factura y planilla PDF.
- Consulta de notas de debito/cobranza emitidas.
- Consulta de pagos recibidos, anticipos aplicados, devoluciones y saldo en cuenta.
- Recepcion individual o masiva de facturas, planillas, notas de cobranza y cites enviados.
- Consulta de estado de cuentas a una fecha de corte.

Evidencia:

- `index_archivos/contables/facplaquery.php:31-117`
- `index_archivos/contables/facpla.php`
- `index_archivos/contables/facplaquery2.php`
- `index_archivos/contables/generarfacturaplanillacliente.php:12-170`
- `index_archivos/contables/descargarfactura.php:12-26`
- `index_archivos/contables/descargarplanilla.php:11-29`
- `index_archivos/contables/notasdebitoquery.php:10-91`
- `index_archivos/contables/pagosrecibidosquery.php:7-43`
- `index_archivos/contables/recepcionplanillas_ajax.php:1-352`
- `index_archivos/contables/estadocuentasquery.php:1-75`
- `index_archivos/contables/librocomprasxls.php`

## Actores

- Cliente usuario: consulta facturas, planillas, notas, recepcion y saldos.
- Usuario contable/operativo: genera documentos, registra envio/recepcion y mantiene informacion de cobro.
- ASGARD: calcula importes, estados, mora, saldos y documentos descargables.
- Mensajero/destino de envio: aparece como dato de entrega/recepcion cuando se envia documentacion fisica o formal.

## Resultado Esperado

- Facturas/planillas se consultan desde `dav_facturaplanilla` y sus importes desde `dav_facturasdetalle` y `dav_pagosdetalle`.
- Notas de debito/cobranza se consultan desde `dav_notasdebito` y `dav_notasdebitodetalle`.
- Pagos recibidos se consultan desde anticipos, cobros y devoluciones.
- La recepcion cambia marcadores y fechas de recepcion en facturas/planillas, notas de cobranza o cites.
- El estado de cuentas muestra documentos pendientes, saldo, dias de mora y clasificacion vigente/en mora.
- El libro de compras se exporta desde plantilla Excel y registra incremento de contador de exportacion.

## Estado

Reconstruccion candidata. La revision humana se difiere hasta completar todos los dominios del baseline.
