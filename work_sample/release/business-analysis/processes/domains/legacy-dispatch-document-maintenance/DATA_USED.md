# Legacy Dispatch Document Maintenance - Data Used

## Entidades observadas

| Entidad / Tabla | Uso observado | Estado schema |
| --- | --- | --- |
| `logis_despachos` | Consulta y actualizacion de ficha de despacho. | No encontrada en `.data_base/asgard.sql`. |
| `logis_documentos` | Actualizacion/insercion pretendida de documentos de despacho. | No encontrada en `.data_base/asgard.sql`. |
| `logis_operadores` | Consulta nombre de operador asociado. | Referenciada por codigo; DDL no validado en este bloque. |
| Catalogos logisticos | Incoterm, pais, tipo embarque, tipo carga, operadores. | Referenciados por codigo. |
| Filesystem `FILES_PATH/logistica/...` | Almacenamiento de adjuntos. | Ruta fisica dependiente de configuracion. |

## Datos de ficha

- Nombre.
- Tipo de embarque.
- Orden de compra.
- Descripcion de carga.
- Origen/destino.
- Peso, volumen, piezas.
- Tipo de carga.
- Incoterm.

## Datos documentales

- Tipo documento.
- Emisor.
- Numero.
- Formato.
- Archivo.
- Fecha de emision.
- Importe.
- Divisa.

