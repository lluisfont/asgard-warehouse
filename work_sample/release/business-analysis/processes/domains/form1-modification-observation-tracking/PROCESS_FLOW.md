# Form1 Modification Observation Tracking - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Consultar modificaciones Form1

1. El usuario abre el reporte de modificaciones.
2. ASGARD identifica Form1 activos asociados al cliente de sesion.
3. ASGARD vincula cada Form1 con caso aduanero o carpeta AGES segun el tipo de servicio.
4. ASGARD obtiene documento modificado, aduana, DIM, chasis y placa desde el caso/carpeta.
5. ASGARD consolida subcontravenciones visibles al cliente.
6. ASGARD arma el detalle de modificacion con "dice" y "debe decir".
7. ASGARD obtiene fechas de observacion, ingreso y conclusion desde estados Form1 EDP.
8. ASGARD calcula dias antes de ingreso y dias de tramite.
9. ASGARD obtiene el ultimo estado del tramite y el contador de llamadas.
10. La UI muestra la grilla y enlaces al historial de llamadas.

## Flujo B - Consultar carpetas observadas por documentos faltantes

1. El usuario abre el reporte de observadas.
2. ASGARD identifica casos del cliente con documentos faltantes pendientes.
3. Para casos nuevos, ASGARD usa `dav_faltadocumentos`.
4. Para casos legacy, ASGARD usa los campos historicos de falta de documento.
5. ASGARD muestra carpeta, pedido, proveedor, aduana, regimen, DIM, fecha de pago DIM y documento faltante.

## Flujo C - Revisar historial de llamadas

1. El usuario abre el historial desde un Form1.
2. ASGARD lista llamadas por fecha, hora, numero, comentario, estado y usuario.
3. ASGARD muestra adjuntos cuando existen.
4. El usuario puede exportar o revisar el historial como evidencia operativa.
