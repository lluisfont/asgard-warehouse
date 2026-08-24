# Billing Document Reception Confirmation - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Consultar documentos enviados pendientes

1. El usuario abre Recepcion de Planillas.
2. ASGARD carga la pestana Enviadas.
3. ASGARD consulta planillas/facturas, notas de cobranza y cites con evidencia de envio.
4. ASGARD excluye documentos ya recepcionados.
5. ASGARD muestra datos de carpeta, pedido, DIM, documento, total, tipo y selector.

## Flujo B - Confirmar recepcion individual

1. El usuario identifica un documento pendiente.
2. El usuario pulsa Recibido en la fila.
3. ASGARD envia id, tipo y numero documental.
4. ASGARD actualiza la marca de recepcion segun tipo documental.
5. ASGARD recarga las bandejas.

## Flujo C - Confirmar recepcion masiva

1. El usuario selecciona uno o varios documentos.
2. Opcionalmente usa seleccionar todo.
3. El usuario pulsa Recibir Marcadas.
4. ASGARD envia la lista de documentos seleccionados.
5. ASGARD aplica las mismas reglas de actualizacion que en recepcion individual.
6. ASGARD recarga enviados y recepcionados.

## Flujo D - Consultar documentos recepcionados

1. El usuario abre la pestana Recepcionadas.
2. ASGARD consulta documentos con marca o fecha de recepcion.
3. ASGARD muestra los documentos ya recibidos para control y seguimiento.

## Flujo E - Consultar planillas legalizadas entregadas

1. El usuario abre el reporte de Planillas Legalizadas Entregadas.
2. El usuario filtra por Fecha Pago DIM.
3. ASGARD lista vehiculo, factura, DUI/DIM, nacionalizacion, partida y fecha de entrega de planilla original.
