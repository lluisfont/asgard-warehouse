# Billing Invoice Planilla Document Generation - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Generar PDF combinado factura-planilla

1. El usuario abre el enlace Factura & Planilla para un `idfacturaplanilla`.
2. ASGARD consulta cabecera de factura-planilla y caso asociado.
3. ASGARD obtiene datos de cliente, NIT, pedido, carpeta, DIM/DEX, regimen, proveedor y dosificacion.
4. ASGARD calcula total de planilla desde pagos detalle no prepagados y no anulados.
5. ASGARD calcula total de factura desde `dav_facturasdetalle`.
6. ASGARD compone la pagina de Planilla de Despacho.
7. ASGARD compone la pagina de Factura.
8. ASGARD genera codigo de control y QR.
9. ASGARD descarga el PDF combinado.
10. ASGARD elimina el archivo QR temporal.

## Flujo B - Descargar factura individual

1. El usuario pulsa Ver Factura.
2. ASGARD identifica si la factura es antigua o en linea segun `iddosificacion`.
3. Si es antigua y existe PDF fuente, ASGARD aplica imagen de factura membretada con FPDI.
4. Si es en linea, ASGARD espera el PDF membretado derivado del XML/documento en linea.
5. ASGARD devuelve el PDF en navegador.
6. Si no encuentra archivo, informa error.

## Flujo C - Descargar planilla individual

1. El usuario pulsa Ver Planilla.
2. ASGARD busca PDF fuente de planilla en `/datadrive1/planillas`.
3. ASGARD aplica imagen de planilla membretada con FPDI.
4. ASGARD guarda la version membretada.
5. ASGARD devuelve el PDF en navegador.
6. Si no existe planilla, muestra alerta y cierra ventana.

## Flujo D - Consultar reporte de facturacion y planillaje

1. El usuario filtra por empresa, pedido, proveedor, DIM, fecha y linea.
2. ASGARD calcula montos de factura y planilla.
3. ASGARD muestra estado y enlaces a Factura, Planilla y documentos.
4. El usuario puede exportar Excel del reporte.
