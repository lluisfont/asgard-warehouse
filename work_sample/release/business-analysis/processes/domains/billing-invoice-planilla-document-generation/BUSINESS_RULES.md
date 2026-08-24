# Billing Invoice Planilla Document Generation - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-BIPD-001 | La generacion parte de un `idfacturaplanilla`. | `generarfacturaplanillacliente.php` |
| BR-BIPD-002 | Si `idcasos` de factura-planilla es cero, se usa `idcasos_rel` como caso generador. | `generarfacturaplanillacliente.php` |
| BR-BIPD-003 | El total de planilla excluye pagos prepagados y detalles marcados como nota de debito. | Consulta `dav_pagosdetalle` con `prepagado=0` y `nd=0` |
| BR-BIPD-004 | Los pagos directos/prepagados del cliente se muestran aparte cuando existen. | Consulta `prepagado=1` o `mostrarprepagado=1` |
| BR-BIPD-005 | El total de factura se calcula sumando `dav_facturasdetalle.monto`. | `generarfacturaplanillacliente.php` |
| BR-BIPD-006 | La factura incluye codigo de control generado desde llave, autorizacion, numero, NIT, fecha y total. | `generaFactura(...)` |
| BR-BIPD-007 | El QR fiscal concatena NIT empresa, numero, autorizacion, fecha, total, codigo de control y NIT cliente. | `$codeContents` |
| BR-BIPD-008 | Para facturas antiguas, `iddosificacion <= 39` define rutas y aplicacion de membretado sobre PDF existente. | `descargarfactura.php` |
| BR-BIPD-009 | Para facturas en linea, el nombre de documento se deriva de `NombreDocumentoXML`. | `descargarfactura.php`, `descargarplanilla.php` |
| BR-BIPD-010 | La planilla individual se descarga solo si existe el PDF fuente de planilla. | `descargarplanilla.php` |
| BR-BIPD-011 | El PDF combinado usa fondos `Planilla.png` y `Factura.png`. | Estilos `@page` en `generarfacturaplanillacliente.php` |

## Riesgos de regla pendientes

- Confirmar vigencia legal del codigo de control para fechas posteriores a factura electronica/en linea.
- Confirmar si el comentario `UPDATE codigocontrol` comentado indica persistencia pendiente o reemplazada.
- Confirmar si los archivos PDF fuente en `/datadrive1` son fuente oficial o cache operativo.
- Confirmar politica de seguridad para descarga por `idfacturaplanilla`.
