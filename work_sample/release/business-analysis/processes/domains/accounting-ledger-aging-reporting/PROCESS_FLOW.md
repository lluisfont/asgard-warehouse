# Accounting Ledger Aging Reporting - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Registro aging/ahorro

1. El usuario abre Registro Ahorro por Mes.
2. ASGARD crea una tabla temporal con meses y columnas de anios desde 2020 hasta el anio actual.
3. ASGARD carga montos desde `dav_aging`.
4. El usuario edita montos por mes/anio.
5. ASGARD actualiza el registro existente o inserta uno nuevo.

## Flujo B - Estado de cuentas/comision

1. El usuario selecciona linea y rango de fecha de pago DIM.
2. ASGARD consulta casos no anulados del cliente.
3. ASGARD arma temporales por concepto contable.
4. ASGARD devuelve filas separadas de factura y planilla.
5. El usuario revisa importes por concepto o exporta Excel.

## Flujo C - Libro de compras

1. El usuario selecciona ciudad/rango.
2. ASGARD consulta facturas/planillas activas y pagos IVA/DIM.
3. ASGARD genera correlativo y campos fiscales de compra.
4. ASGARD calcula importe base y credito fiscal.
5. El usuario exporta el libro.

