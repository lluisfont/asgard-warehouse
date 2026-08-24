# Customs Guarantee Tax Control - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Seguimiento mensual de boleta

1. El usuario abre Seguimiento Boletas de Garantia.
2. El usuario selecciona gestion y tipo de declaracion.
3. ASGARD consulta casos no anulados del cliente de sesion con DAM enviada en la gestion seleccionada.
4. ASGARD agrupa por mes de envio DAM.
5. ASGARD calcula unidades con DAM, unidades extraidas, tributos pagados, monto de boleta en uso y porcentaje de extraccion.
6. ASGARD obtiene monto total de garantia desde documentos de certificacion tipo `4`.
7. La UI muestra monto total, monto disponible y unidades sin extraccion.

## Flujo B - Seguimiento operativo de uso

1. ASGARD clasifica unidades con DAM aceptada y todavia comprometidas.
2. ASGARD identifica unidades sin nacionalizar con documento/FRV y dias menores o iguales a 90.
3. ASGARD identifica unidades sin nacionalizar por vencer con dias mayores a 90.
4. ASGARD identifica unidades en transito sin DAM aceptada.
5. La UI muestra cantidad y valor por categoria.

## Flujo C - Exportacion desglosada

1. El usuario informa rango de fechas.
2. La UI valida que el rango no exceda 90 dias.
3. ASGARD consulta el detalle por chasis/pedido.
4. ASGARD exporta Excel con valor provisional, valor segun requerimiento y estado.

## Flujo D - Conciliacion de tributos

1. El usuario genera reporte de tributos.
2. ASGARD arma tablas temporales de tributos pagados, facturas comerciales, recibos y recibos de otros vehiculos.
3. ASGARD cruza requerimiento de fondos, anticipos, cobros, pagos y casos.
4. ASGARD calcula monto recibido, tributos pagados, devolucion/reposicion y diferencia.
5. ASGARD clasifica saldo a favor de cliente o agencia.

## Evidencia

- `boletasgarantia.php`.
- `boletasgarantiaajax.php`.
- `boletasgarantiareporte.php`.
- `ContabilidadClass.php`.
- `tributosquery.php`.

