# Proposal: Persistir guía de remisión en salidas ATE GAS

## Intent

Extender el despacho de vehículos ATE GAS para exigir y almacenar una guía de remisión asociada a cada salida.

El archivo se guardará en Azure Blob Storage usando la infraestructura existente. Sus metadatos se persistirán en `t_ate_gas` y estarán disponibles para listado, visualización y descarga.

## Scope

- Ajustar el endpoint de despacho para recibir `multipart/form-data`.
- Mantener PUT solo si el servidor procesa correctamente multipart PUT.
- Cambiar a `POST /ate-gas/salidas/{idate_gas}` si es necesario, coordinando el cambio con Angular.
- Validar destino, transportista y un único archivo obligatorio.
- Limitar el archivo a 10 MB.
- Admitir JPG, JPEG, PNG, WebP, HEIC, HEIF, PDF, XLS, XLSX, DOC y DOCX.
- Guardar el archivo bajo `{idempresa}/almacen/ate_gas/salidas/{idate_gas}`.
- No generar thumbnail.
- Persistir `nombre_original_salida`, `nombre_guardado_salida` y `ubicacion_fisica_salida`.
- Mantener consistencia entre Azure y base de datos ante errores.
- Extender `GET /ate-gas/salidas` con los metadatos.
- Proveer o reutilizar un endpoint autenticado para obtener la guía.
- Responder con disposición inline para imágenes y PDF, y attachment para Office.

## Out of Scope

- Múltiples archivos por salida.
- Reemplazo después de confirmar el despacho.
- Thumbnails.
- Migración de archivos históricos.
- Contenedor público o URL de Azure expuesta directamente.

## Approach

El endpoint recibirá:

- `destino_salida`
- `transportista_salida`
- `file`

La validación técnica decidirá el método final:

- PUT si Slim/PHP/servidor entrega correctamente los uploaded files multipart.
- POST en caso contrario.

El archivo se almacenará con un nombre aleatorio seguro y extensión normalizada. La ruta lógica será:

`{idempresa}/almacen/ate_gas/salidas/{idate_gas}/{nombre_guardado}`

La descarga o visualización se realizará mediante un endpoint autenticado. Para imágenes y PDF se responderá con `Content-Disposition: inline`; para Word y Excel con `attachment`.

## Success Criteria

- No se registra despacho sin archivo.
- No se aceptan archivos mayores a 10 MB.
- No se acepta más de un archivo.
- Solo se aceptan los formatos acordados.
- El blob y los metadatos quedan consistentes.
- No se genera thumbnail.
- Los registros históricos continúan apareciendo con campos nulos.
- Imágenes y PDF pueden abrirse en otra pestaña.
- Word y Excel se descargan conservando el nombre original.
