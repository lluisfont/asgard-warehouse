# Design: Migrar imagenes ATE-GAS a Azure Blob Storage

## Context
El backend es PHP 8 con Slim 2 y concentra rutas en `app/routes/almacenes.php`. Las imagenes ATE-GAS relevantes estan en endpoints bajo `/ate-gas`.

Puntos de codigo identificados:

- `POST /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa`
  - Lee `$_FILES['files']` y `$_FILES['filesMain']`.
  - Genera rutas locales con `folder_files.$idempresa.DIRECTORY_SEPARATOR.'almacen/ate_gas/...'`.
  - Guarda archivos con `move_uploaded_file()`.
  - Genera thumbnails con `Common::crearThumbGD()`.
  - Inserta rutas en `t_ate_gas_etapa_imagen` y `t_ate_gas_etapa_inventario_imagen`.

- `GET /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa`
  - Lee `ubicacion_thumb` de `t_ate_gas_etapa_inventario_imagen`.
  - Construye `folder_files.$rowimagenes['ubicacion_thumb']`.
  - Usa `file_exists()`, `file_get_contents()`, `mime_content_type()` y devuelve `itemImageSrc` base64.

- `GET /ate-gas/gestion-movimiento/:idate_gas_etapa/imagenes`
  - Lee `ubicacion_thumb` de `t_ate_gas_etapa_imagen`.
  - Usa el mismo patron de lectura desde disco y devolucion base64.

- Listados posteriores de gestion de movimiento tambien leen `ubicacion_thumb` y `ubicacion_fisica` para armar arreglos `imagenes` e `inventario`.

Azure Blob Storage es adecuado para este caso porque esta optimizado para datos no estructurados y para servir imagenes o documentos, y los objetos se pueden acceder por HTTPS, REST API o bibliotecas cliente.

## Proposed Approach

### 1. Crear capa de almacenamiento
Agregar un servicio, por ejemplo:

```text
app/services/BlobStorageService.php
```

Responsabilidades minimas:

```php
class BlobStorageService
{
    public function uploadBlob(string $blobName, string $localPath, string $contentType): BlobUploadResult;
    public function uploadContent(string $blobName, string $content, string $contentType): BlobUploadResult;
    public function getBlob(string $blobName): BlobDownloadResult;
    public function exists(string $blobName): bool;
    public function deleteBlob(string $blobName): bool;
    public function buildAteGasBlobName(int $idempresa, string $relativePath): string;
}
```

El servicio debe aislar la dependencia elegida:

- Opcion A: `microsoft/azure-storage-blob`, usando `BlobRestProxy::createBlobService($connectionString)`.
- Opcion B: cliente REST interno con `Put Blob` y `Get Blob` mediante HTTPS.

La opcion B reduce dependencia en un paquete PHP retirado, pero requiere implementar correctamente autenticacion y firma. La opcion A acelera la migracion, pero debe encapsularse para poder reemplazarla.

### 2. Configuracion
Agregar variables a `app/.env.example.php` y al mecanismo actual de configuracion:

```php
define('azure_blob_enabled', true);
define('azure_blob_connection_string', '');
define('azure_blob_account_name', '');
define('azure_blob_container', 'warehouse');
define('azure_blob_base_prefix', '');
define('azure_blob_auth_mode', 'connection_string'); // connection_string | sas | managed_identity_future
```

No versionar secretos reales. Para despliegue en Azure, la preferencia de seguridad debe ser identidad administrada/Entra ID cuando la infraestructura lo permita.

### 3. Modelo de nombres de blob
Usar rutas relativas similares a las actuales, sin `folder_files` y con separador `/`:

```text
{idempresa}/almacen/ate_gas/gestion-movimiento/{idate_gas_etapa}/{iddanios_vehiculos}/{nombre_guardado}
{idempresa}/almacen/ate_gas/gestion-movimiento/{idate_gas_etapa}/{iddanios_vehiculos}/thumb/{thumbName}
{idempresa}/almacen/ate_gas/gestion-movimiento-main/{idate_gas_etapa}/{nombre_guardado}
{idempresa}/almacen/ate_gas/gestion-movimiento-main/{idate_gas_etapa}/thumb/{thumbName}
```

Guardar esos nombres de blob en `ubicacion_fisica` y `ubicacion_thumb`. Evitar prefijarlos con `folder_files`.

### 4. Flujo de subida
Para cada archivo valido:

1. Validar `UPLOAD_ERR_OK`.
2. Validar extension permitida: `jpg`, `jpeg`, `png`, `webp`.
3. Validar MIME real con `finfo_file()`.
4. Generar nombre seguro con `bin2hex(random_bytes(16))`.
5. Mover temporalmente el archivo a un directorio transitorio solo si `Common::crearThumbGD()` requiere path local.
6. Crear thumbnail en temporal.
7. Subir original a Azure Blob Storage con content type real.
8. Subir thumbnail como `image/jpeg`.
9. Insertar DB solo despues de confirmar subida de ambos blobs.
10. Eliminar temporales en `finally`.

### 5. Flujo de lectura
Reemplazar:

```php
$filePath = folder_files.$rowimagenes['ubicacion_thumb'];
if (file_exists($filePath)) {
    $imageData = file_get_contents($filePath);
    $mimeType = mime_content_type($filePath);
}
```

por:

```php
$blob = $blobStorage->getBlob($rowimagenes['ubicacion_thumb']);
$fileBase64 = 'data:' . $blob->contentType . ';base64,' . base64_encode($blob->content);
```

Si el blob no existe o hay error de lectura, el endpoint debe omitir la imagen o registrar error controlado segun el comportamiento actual esperado.

### 6. Compatibilidad historica
Si hay imagenes antiguas en disco, implementar fallback temporal:

1. Intentar leer desde Blob Storage si `ubicacion_thumb` parece nombre de blob.
2. Si no existe y la ruta local existe en `folder_files`, leer desde disco.
3. Opcional: migrar on-read subiendo a Blob Storage y actualizando DB.

Este fallback debe ser temporal y removible despues de una migracion batch.

## Data Model / API Changes
No se requiere cambiar el contrato publico de endpoints.

Opciones de persistencia:

- Minimo cambio: reutilizar `ubicacion_fisica` y `ubicacion_thumb` como nombres de blob.
- Cambio mas explicito: agregar columnas `storage_provider`, `blob_container`, `blob_name`, `thumb_blob_name`, `content_type`, `size_bytes`.

Recomendacion brownfield: usar minimo cambio para reducir riesgo, pero documentar que esos campos dejan de representar rutas locales.

## Rollout and Migration

1. Agregar configuracion Azure deshabilitada por defecto.
2. Implementar `BlobStorageService` y pruebas unitarias del servicio con mocks.
3. Cambiar endpoints ATE-GAS con feature flag `azure_blob_enabled`.
4. Desplegar en ambiente de pruebas con contenedor privado.
5. Probar carga y lectura de imagenes nuevas.
6. Activar fallback local para historico.
7. Ejecutar migracion batch del historico si aplica.
8. Desactivar escrituras al disco persistente para imagenes ATE-GAS.
9. Remover fallback cuando el historico este migrado.

## Testing and Observability

Pruebas recomendadas:

- Subir `filesMain` con imagen JPEG/PNG/WEBP valida.
- Subir `files[iddanios_vehiculos]` con multiples imagenes.
- Rechazar extension no permitida.
- Rechazar MIME incompatible aunque la extension parezca valida.
- Confirmar que DB guarda blob name y thumb blob name.
- Confirmar que los endpoints GET devuelven `data:image/...;base64,...`.
- Simular fallo de Azure durante subida y verificar rollback DB.
- Simular blob inexistente durante lectura y verificar respuesta controlada.

Logs minimos:

- `idate_gas_etapa`, `iddanios_vehiculos`, blob name, content type, tamano, request id de Azure si esta disponible.
- Nunca registrar connection string, account key, SAS completo ni Authorization header.

## Security and Failure Modes

- Usar contenedor privado.
- Preferir Entra ID/identidad administrada sobre Shared Key cuando sea posible.
- Si se usa SAS, debe ser de permisos minimos y vida corta.
- Sanitizar nombres y nunca usar el nombre original como blob name final.
- Mantener transacciones DB: si la subida falla, no insertar registro; si la DB falla despues de subir, considerar eliminar blobs subidos o registrar compensacion.
- Limitar tamano maximo de imagen para evitar abuso de memoria al convertir a base64.

## Alternatives Considered

### Mantener disco local y sincronizar con Azure
No recomendado. Mantiene dependencia de la VM y duplica fuentes de verdad.

### Montar Blob Storage como filesystem
No recomendado para este cambio. Reduce refactor inicial, pero oculta errores de red y mantiene acoplamiento a rutas locales.

### Devolver SAS URL en vez de base64
Bueno para performance, pero cambia contrato frontend. Puede ser una segunda fase.

### Usar solo REST API propio
Mas sostenible si se implementa bien, especialmente por el retiro del paquete PHP, pero toma mas tiempo inicial.
