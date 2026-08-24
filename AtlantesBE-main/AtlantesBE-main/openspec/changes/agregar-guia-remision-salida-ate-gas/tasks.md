# Tasks

## 1. Método HTTP y multipart

- [x] 1.1 Verificar con el runtime real si Slim/PHP entrega multipart mediante PUT.
- [x] 1.2 Documentar el resultado en el cambio OpenSpec.
- [x] 1.3 Descartar PUT al no existir soporte multipart confiable en el runtime actual.
- [x] 1.4 Implementar POST si PUT no entrega el archivo de forma confiable.
- [x] 1.5 Coordinar el método final con `AlmacenesService` del frontend.
- [x] 1.6 No implementar un parser multipart manual en la ruta.

## 2. Autorización y validación

- [x] 2.1 Validar JWT, `idempresa` e `idalmacen`.
- [x] 2.2 Validar existencia y pertenencia de `idate_gas`.
- [x] 2.3 Validar estado compatible y `fecha_salida IS NULL`.
- [x] 2.4 Validar destino y transportista con `trim` y longitud máxima.
- [x] 2.5 Validar que exista exactamente un archivo.
- [x] 2.6 Limitar el archivo a 10 MB.
- [x] 2.7 Permitir JPG, JPEG, PNG, WebP, HEIC, HEIF, PDF, XLS, XLSX, DOC y DOCX.
- [x] 2.8 Detectar MIME real mediante `finfo`.
- [x] 2.9 Implementar una matriz explícita de extensión/MIME.
- [ ] 2.10 Probar MIME reales de archivos Office y HEIC/HEIF.

## 3. Nombre y Azure Storage

- [x] 3.1 Sanear y limitar `nombre_original_salida`.
- [x] 3.2 Generar `nombre_guardado_salida` con `random_bytes`.
- [x] 3.3 Construir `{idempresa}/almacen/ate_gas/salidas/{idate_gas}/{nombre_guardado}`.
- [x] 3.4 Reutilizar la lógica de `BlobStorageService` del endpoint de inventario.
- [x] 3.5 Confirmar que no se genere thumbnail.
- [x] 3.6 Eliminar siempre archivos temporales locales.
- [x] 3.7 Revisar o implementar eliminación de blobs para compensación.

## 4. Persistencia y consistencia

- [x] 4.1 Agregar los tres campos de guía al UPDATE de `t_ate_gas`.
- [x] 4.2 Condicionar el UPDATE al almacén y a `fecha_salida IS NULL`.
- [x] 4.3 Usar prepared statements para todos los parámetros.
- [x] 4.4 Verificar `rowCount()`.
- [x] 4.5 No actualizar DB si falla Azure.
- [x] 4.6 Ejecutar rollback si falla SQL.
- [x] 4.7 Intentar eliminar el blob si SQL falla después del upload.
- [x] 4.8 Registrar el resultado de la compensación.

## 5. Listado

- [x] 5.1 Agregar `nombre_original_salida` a `GET /ate-gas/salidas`.
- [x] 5.2 Agregar `nombre_guardado_salida`.
- [x] 5.3 Agregar `ubicacion_fisica_salida`.
- [x] 5.4 Mantener registros históricos y retornar campos nulos.

## 6. Visualización y descarga

- [x] 6.1 Identificar si existe un endpoint genérico seguro de archivos Azure.
- [x] 6.2 Reutilizarlo o crear `GET /ate-gas/salidas/{idate_gas}/guia-remision`.
- [x] 6.3 Validar autorización por almacén.
- [x] 6.4 Recuperar el blob desde `ubicacion_fisica_salida`.
- [x] 6.5 Determinar y enviar el MIME correcto.
- [x] 6.6 Usar `inline` para imágenes.
- [x] 6.7 Usar `inline` para PDF.
- [x] 6.8 Usar `attachment` para XLS, XLSX, DOC y DOCX.
- [x] 6.9 Conservar el nombre original saneado.
- [x] 6.10 Agregar `X-Content-Type-Options: nosniff`.
- [x] 6.11 Manejar archivo inexistente sin revelar rutas internas.

## 7. Errors and logs

- [x] 7.1 Mantener el contrato de respuesta usado por el proyecto.
- [x] 7.2 Agregar mensajes específicos para validaciones de archivo.
- [x] 7.3 Registrar upload, SQL y compensación con identificadores técnicos.
- [x] 7.4 No registrar JWT, contenido ni secretos.

## 8. Validation

- [ ] 8.1 Probar JPG, JPEG, PNG, WebP, HEIC y HEIF.
- [ ] 8.2 Probar PDF.
- [ ] 8.3 Probar XLS y XLSX.
- [ ] 8.4 Probar DOC y DOCX.
- [ ] 8.5 Probar archivo de exactamente 10 MB.
- [ ] 8.6 Probar archivo superior a 10 MB.
- [ ] 8.7 Probar extensión y MIME incompatibles.
- [ ] 8.8 Probar solicitud sin archivo y con múltiples archivos.
- [ ] 8.9 Simular error Azure.
- [ ] 8.10 Simular error SQL posterior al upload.
- [ ] 8.11 Probar doble despacho concurrente.
- [ ] 8.12 Probar acceso desde otro almacén.
- [ ] 8.13 Probar listado con registros antiguos y nuevos.
- [ ] 8.14 Probar inline de imagen y PDF.
- [ ] 8.15 Probar descarga de Word y Excel.
- [ ] 8.16 Probar nombres con espacios, acentos y caracteres especiales.
- [ ] 8.17 Ejecutar la validación OpenSpec disponible en el repositorio.
- [x] 8.18 Confirmar que la implementación coincide con el delta spec.
