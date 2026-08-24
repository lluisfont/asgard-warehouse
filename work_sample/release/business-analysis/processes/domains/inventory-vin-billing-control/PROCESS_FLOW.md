# Inventory VIN Billing Control - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Cargar consolidado mensual

1. El usuario abre Facturacion por VIN.
2. ASGARD consulta `lista-facturacion-chasis`.
3. ASGARD muestra periodos mensuales con unidades, tarifa y totales.

## Flujo B - Generar precalculo

1. ASGARD calcula fecha inicio/fin por defecto.
2. El usuario ajusta fecha inicio si corresponde.
3. ASGARD normaliza fecha inicio al dia 21.
4. ASGARD calcula fecha fin como dia 20 del mes siguiente.
5. El usuario pulsa Generar Pre-Calculo.
6. ASGARD llama a `facturacion-chasis`.
7. ASGARD muestra KPIs y habilita Confirmar Facturacion.

## Flujo C - Confirmar facturacion

1. El usuario revisa KPIs.
2. El usuario confirma facturacion.
3. ASGARD llama a `confirmar-facturacion-chasis`.
4. El API confirma el periodo.
5. ASGARD informa exito/error.

## Flujo D - Exportar detalle

1. El usuario pulsa Excel en un periodo.
2. ASGARD llama a `info-facturacion-excel/{id}`.
3. ASGARD valida base64.
4. ASGARD descarga el archivo.
