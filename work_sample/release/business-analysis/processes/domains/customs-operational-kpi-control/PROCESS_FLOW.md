# Customs Operational KPI Control - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - KPI AP/DAM/REQ

1. El usuario abre KPIs.
2. El usuario informa rango de fecha de asignacion/solicitud.
3. ASGARD arma temporales de casos previos, fechas por pedido y min/max de pedidos.
4. ASGARD calcula cantidad de unidades por requerimiento.
5. ASGARD calcula dias AP, dias DAM y dias REQ.
6. ASGARD clasifica cada indicador como `CUMPLE` o `NO CUMPLE`.
7. La UI muestra grilla y permite Excel.

## Flujo B - Control AD

1. El usuario selecciona filtros de control aduanero.
2. ASGARD obtiene hitos EDP y datos de costos/tributos.
3. ASGARD calcula prevision de tributos y compara contra costos reales.
4. ASGARD calcula dias e indicadores de nacionalizacion y planillaje.
5. El reporte expone resultados por embarque/caso.

## Flujo C - Control OL

1. El usuario selecciona filtros logisticos.
2. ASGARD deriva estado actual del embarque desde la etapa maxima EDP.
3. ASGARD cruza operador, proveedor, agente, inhouse y viajes.
4. ASGARD devuelve fechas EDP relevantes y cantidad de asignaciones.

## Flujo D - Seguimiento aduanero

1. El usuario selecciona filtros de fecha y dimensiones operativas.
2. ASGARD cruza caso, documentos, costos, planilla, tributos y fechas.
3. ASGARD calcula tiempos de pago DIM, servicio, validacion y planillaje.
4. ASGARD clasifica KPI como correcto, atrasado, en tiempo o reintegro.

