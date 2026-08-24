# Alicorp Transit Deadline Control - Process Flow

## Flujo principal - Seguimiento y normalizacion de vencimiento

1. El usuario abre el control Alicorp.
2. El usuario informa rango de fechas y filtros opcionales.
3. ASGARD convierte el rango a formato de base de datos.
4. ASGARD actualiza los casos Alicorp con vencimiento nulo, calculando `fechavalidaciondui + 60 dias`.
5. ASGARD arma tablas temporales de productos/facturas y tramites.
6. ASGARD consulta casos Alicorp y facturas anuladas de cliente dentro del rango.
7. ASGARD calcula dias restantes al vencimiento e indicador de alerta.
8. La UI presenta la grilla de control.
9. El usuario revisa casos, cierres, CEDEIM, reemplazos, salidas y alertas.
10. El usuario puede exportar el resultado a Excel.

## Flujo relacionado - Cierre de transito desde OCR

1. Un proceso OCR/intercambio documental procesa un documento Alicorp.
2. El proceso identifica el caso relacionado.
3. Si se cumple la regla del lector OCR, ASGARD actualiza `dav_casos.alicorp_cierre_transito = 1`.
4. El control Alicorp muestra el cierre como `PAGADO`.

## Excepciones observadas

- Si `fechavalidaciondui` es nula, el calculo de vencimiento puede quedar nulo o no aportar una fecha util.
- El `UPDATE` de vencimiento se ejecuta como efecto lateral de una consulta/reporte.
- No se observa transaccion ni auditoria especifica para la asignacion automatica de vencimiento.
- El filtro de vencimiento usa `1` para alerta y `2` para no alerta; los significados del catalogo no estan declarados fuera de la expresion.
