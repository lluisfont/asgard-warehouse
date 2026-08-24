# Alicorp Operational Document Package Dispatch - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

1. El cron recibe `cron=1`.
2. ASGARD recorre clientes `775` y `755`.
3. Para cada cliente, obtiene parametrizacion concatenada con proveedor informado.
4. Para cada proveedor/linea parametrizados, busca casos pendientes de documentacion.
5. ASGARD obtiene contactos de consignatario y operador logistico.
6. ASGARD calcula filtros documentales y obtiene ids de documentos requeridos.
7. Para cada embarque con `idExchange`, consulta documentos de Document Exchange.
8. ASGARD descarga archivos cuyo documento esta parametrizado.
9. ASGARD crea ZIP "Documentos Operativos - Embarque #...".
10. ASGARD guarda el ZIP en carpeta `documentosOperativosAlicorp`.
11. ASGARD arma tabla de correo con carpetas, facturas y datos operativos.
12. Si hay documentos para enviar, envia correo con adjuntos.
13. ASGARD actualiza `dav_casos.embarque_documentos_enviados` para las carpetas enviadas.

## Excepciones observadas

- Si no hay contactos o documentos parametrizados, no se observa envio.
- Si un archivo no descarga, el script imprime error y continua.
- El token de Document Exchange usado por el cron esta hardcodeado.
- El contexto HTTP desactiva verificacion SSL.
