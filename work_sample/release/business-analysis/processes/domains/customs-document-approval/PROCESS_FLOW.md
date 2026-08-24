# Customs Document Approval - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Flujo Principal Candidato

1. El usuario abre documentacion de un caso previo.
2. ASGARD lista documentos previos existentes y otros documentos.
3. El usuario agrega o actualiza un documento previo con tipo, emisor, formato, numero, fecha, importe y divisa.
4. Opcionalmente adjunta un archivo al documento.
5. El usuario agrega o actualiza otros documentos.
6. En flujo aprobado, ASGARD combina documentos previos pendientes con documentos intermedios del caso.
7. Si un documento intermedio se completa, ASGARD crea un `dav_documentosprevios` relacionado y oculta el intermedio.
8. El usuario puede eliminar documento, duplicarlo o quitar adjunto.
9. ASGARD marca documentos con `aceptar = 4` cuando deben enviarse como nuevos documentos registrados.
10. ASGARD envia correo con documentos y otros documentos pendientes.
11. ASGARD marca otros documentos como `enviado = 1` y `estado = 1`.

## Excepciones / Variantes

- Documentos de transporte pueden tener filtro `transportista`.
- Documento tipo 290 puede renombrar archivo usando carpeta/pedido.
- Carga masiva de documentos usa `tmp_documentosprevios`.
- Documentos con `aceptar = 1` quedan fuera de algunos listados de aprobacion.

## Evidencia

- `index_archivos/documentacion.php:86-171`
- `index_archivos/documentacion.php:234-323`
- `index_archivos/documentacion.php:330-357`
- `index_archivos/documentacionaprobado.php:196-270`
- `index_archivos/documentacionaprobado.php:281-316`
- `index_archivos/documentacionaprobado.php:421-476`
- `index_archivos/documentacionaprobado.php:970-1053`
