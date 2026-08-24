# Billing Invoice Planilla Document Generation - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Generar y entregar documentos PDF de factura y planilla asociados a una factura-planilla, componiendo datos del caso, importes facturables, gastos por cuenta del cliente, datos fiscales de dosificacion, codigo de control, QR y plantillas membretadas.

## Alcance observado

- Generacion de PDF combinado Planilla de Despacho + Factura.
- Descarga individual de Factura PDF con membretado.
- Descarga individual de Planilla PDF con membretado.
- Composicion de importes de planilla desde pagos detalle.
- Composicion de importes de factura desde factura detalle.
- Inclusion de datos de cliente, NIT, pedido, carpeta, DIM/DEX, regimen, proveedor y factura proveedor.
- Inclusion de dosificacion, autorizacion, fecha limite, codigo de control y QR.
- Soporte diferenciado para dosificacion antigua (`iddosificacion <= 39`) y documento en linea.

## Fuera de alcance observado

- Creacion inicial de la cabecera `dav_facturaplanilla`.
- Numeracion oficial y autorizacion fiscal externa.
- Envio electronico al SIN u otro ente fiscal.
- Validacion de existencia previa de archivos origen en almacenamiento externo.
- Gestion de anulacion fiscal completa.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario contable/operativo | Genera o descarga factura/planilla PDF. |
| Cliente | Destinatario del documento de cobro y soporte. |
| ASGARD | Compone HTML/PDF, calcula totales, genera QR/codigo de control y sirve descarga. |
| Dosificacion fiscal | Fuente de autorizacion, llave, fecha limite, actividad y leyenda. |
| Almacenamiento `/datadrive1` | Fuente/destino de PDFs de factura y planilla con o sin membretado. |

## Entradas

- `idfacturaplanilla`.
- Datos de `dav_facturaplanilla`.
- Detalle `dav_facturasdetalle`.
- Detalle de planilla desde `dav_pagosdetalle`.
- Datos de caso/carpeta y cliente.
- Dosificacion fiscal y llave de codigo de control.
- Imagenes de fondo/membrete.

## Salidas

- PDF combinado `carpeta-PF-nro.pdf`.
- PDF de factura membretado.
- PDF de planilla membretada.
- QR temporal embebido en factura.
- Codigo de control mostrado en pie de pagina.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/contables/generarfacturaplanillacliente.php` | Genera PDF combinado planilla + factura usando mPDF, QR y codigo de control. |
| `index_archivos/operativos/generarfacturaplanillacliente1.php` | Variante operativa de generacion/descarga PDF y ZIP para factura-planilla, con actualizacion de motivo cuando no existe dosificacion aplicable. |
| `index_archivos/operativos/generadorcodigocontrol.php` | Helper legacy de calculo de codigo de control fiscal mediante Verhoeff, Base64 y Alleged RC4. |
| `index_archivos/contables/descargarfactura.php` | Sirve factura PDF y crea version membretada con FPDI si aplica. |
| `index_archivos/contables/descargarplanilla.php` | Sirve planilla PDF y crea version membretada con FPDI. |
| `index_archivos/contables/facplaquery.php` | Reporte enlaza descarga de factura, planilla y documentos asociados. |
| `.data_base/asgard.sql` | Tablas `dav_facturaplanilla`, `dav_facturasdetalle`, `dav_dosificacion`, `dav_estadofactura`. |

## Criterios de aceptacion candidatos

- Dado un `idfacturaplanilla`, ASGARD debe recuperar datos de cabecera, caso, cliente y dosificacion.
- El total de factura debe sumar los detalles de factura.
- El total de planilla debe sumar gastos no prepagados y no anulados del caso.
- El PDF combinado debe incluir planilla y factura en el mismo documento.
- La factura debe incluir codigo de control y QR construidos con datos fiscales.
- Las descargas individuales deben devolver PDF si existe el archivo fuente o membretado esperado.
