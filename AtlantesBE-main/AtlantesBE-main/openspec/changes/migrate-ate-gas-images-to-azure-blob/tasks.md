# Tasks

## 1. Inventario de endpoints y comportamiento actual
- [x] 1.1 Confirmar todos los endpoints `/ate-gas` que leen o escriben imagenes en `app/routes/almacenes.php`.
  - Confirmados: GET /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa (línea 11833), GET /ate-gas/gestion-movimiento/:idate_gas_etapa/imagenes (línea 11895), POST /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa (línea 11938).
- [ ] 1.2 Confirmar si los endpoints PDF `/ate-gas/inventario/:idate_gas` y `/ate-gas/etapa/inventario/:idate_gas_etapa` deben migrar PDFs a Blob Storage o quedan fuera del alcance.
  - Pendiente de decisión. PDFs quedan fuera del alcance actual según proposal.md.
- [ ] 1.3 Confirmar si imagenes historicas en disco deben migrarse o solo se almacenaran nuevas cargas en Azure.
  - Se implementó fallback a disco local para imágenes anteriores. Migración batch es tarea 6 (opcional).

## 2. Configuracion Azure
- [x] 2.1 Agregar variables Azure Blob en `app/.env.example.php` sin secretos reales.
  - Agregadas: azure_blob_enabled, azure_blob_auth_mode, azure_blob_connection_string, azure_blob_account_name, azure_blob_sas_token, azure_blob_container.
- [ ] 2.2 Agregar lectura de configuracion en el bootstrap/configuracion actual.
  - Las constantes se leen desde `app/.env.php` (mismo mecanismo que todo el resto). Copiar bloque Azure de `.env.example.php` a `.env.php` local.
- [ ] 2.3 Definir nombre del contenedor y prefijo base por ambiente.
  - El contenedor se configura con `azure_blob_container`. Prefijo base es `{idempresa}/almacen/ate_gas/`.
- [ ] 2.4 Configurar contenedor privado en Azure Storage.
  - Requiere credenciales reales en Azure Portal. No automatizable sin acceso.

## 3. Servicio de almacenamiento
- [x] 3.1 Crear `app/services/BlobStorageService.php`.
- [x] 3.2 Implementar subida de blob original con content type (`uploadBlob`, `uploadBlobFromFile`).
- [x] 3.3 Implementar descarga de blob devolviendo contenido binario y MIME (`getBlob`).
- [x] 3.4 Implementar existencia/borrado (`exists`, `deleteBlob`).
- [x] 3.5 Encapsular via REST API directa (sin SDK deprecado). Soporta `connection_string` y `sas`.

## 4. Refactor de subida ATE-GAS
- [x] 4.1 Refactorizar `POST /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa` para subir `filesMain` a Azure Blob Storage.
- [x] 4.2 Refactorizar el mismo endpoint para subir `files[iddanios_vehiculos][idx]` a Azure Blob Storage.
- [x] 4.3 Generar thumbnails en temporal y subirlos a Blob Storage con `image/jpeg`.
- [x] 4.4 Guardar blob names en `ubicacion_fisica` y `ubicacion_thumb`.
- [x] 4.5 Limpiar archivos temporales en bloque `finally`.
- [x] 4.6 Rollback de base de datos cuando falle la subida (no se inserta registro si `uploadBlobFromFile` falla — `continue`).

## 5. Refactor de lectura ATE-GAS
- [x] 5.1 Refactorizar `GET /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa` para leer thumbnails desde Blob Storage.
- [x] 5.2 Refactorizar `GET /ate-gas/gestion-movimiento/:idate_gas_etapa/imagenes` para leer thumbnails desde Blob Storage.
- [ ] 5.3 Refactorizar listados de gestion de movimiento que construyen `imagenes` e `inventario` desde `ubicacion_thumb`.
  - El endpoint GET /ate-gas/gestion-movimiento (listado principal) no devuelve imágenes en base64; solo devuelve metadata. No requiere migración.
- [x] 5.4 Mantener respuesta `itemImageSrc` en formato data URI base64.
- [x] 5.5 Implementar fallback temporal a disco local si se requiere compatibilidad historica.
  - Ambos endpoints GET intentan Azure primero; si falla o devuelve null, leen desde `folder_files` como antes.

## 6. Migracion historica opcional
- [ ] 6.1 Crear script CLI para listar registros ATE-GAS con `ubicacion_fisica`/`ubicacion_thumb` locales.
- [ ] 6.2 Subir originales y thumbnails existentes a Azure Blob Storage.
- [ ] 6.3 Actualizar DB con blob names migrados.
- [ ] 6.4 Generar reporte de registros migrados, omitidos y fallidos.

## 7. Validacion
- [ ] 7.1 Probar subida exitosa de imagen principal y de imagen por danio.
  - Requiere activar `azure_blob_enabled=true` y configurar credenciales reales.
- [ ] 7.2 Probar rechazo de archivos invalidos por extension y MIME.
- [ ] 7.3 Probar consulta de imagenes y confirmar data URI base64.
- [ ] 7.4 Probar falla simulada de Azure y confirmar rollback DB (no se inserta registro).
- [ ] 7.5 Probar comportamiento con blob inexistente (fallback a disco o imagen omitida).
- [ ] 7.6 Ejecutar pruebas del backend disponibles.
- [ ] 7.7 Ejecutar `openspec validate migrate-ate-gas-images-to-azure-blob` si OpenSpec CLI esta instalado.

## 8. Documentacion y despliegue
- [x] 8.1 Documentar variables de entorno requeridas para Azure Blob Storage (en `app/.env.example.php`).
- [ ] 8.2 Documentar comandos de migracion historica si se implementa.
- [ ] 8.3 Documentar rollback: desactivar `azure_blob_enabled` en `.env.php` para volver a disco local.
  - El fallback a disco está implementado en los endpoints GET. Para el POST, basta con `azure_blob_enabled=false`.
