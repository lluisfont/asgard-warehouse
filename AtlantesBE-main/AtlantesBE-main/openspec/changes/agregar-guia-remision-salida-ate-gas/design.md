# Design: Persistencia de guía de remisión en salidas ATE GAS

## Current Architecture

Archivos principales:

- `app/routes/almacenes.php`
- `app/services/BlobStorageService.php`

Referencia de implementación existente:

`POST /ate-gas/gestion-movimiento/inventario/{idate_gas_etapa}`

La tabla `t_ate_gas` ya tiene:

- `nombre_original_salida`
- `nombre_guardado_salida`
- `ubicacion_fisica_salida`

## HTTP Method Decision

Se validó la configuración real de Slim 4 y PHP del proyecto:

```php
$parsedBody = $request->getParsedBody();
$uploadedFiles = $request->getUploadedFiles();
```

Resultado: se implementa `POST /ate-gas/salidas/{idate_gas}`. PHP entrega de forma nativa los archivos multipart mediante POST y el proyecto no tiene un parser multipart PUT. Los demás flujos multipart de ATE GAS también usan POST. Angular se ajusta de forma coordinada y no se implementa un parser manual.

## Request Fields

| Campo | Tipo | Obligatorio |
|---|---|---:|
| `destino_salida` | string | Sí |
| `transportista_salida` | string | Sí |
| `file` | uploaded file | Sí |

Solo se admite un archivo.

## Authorization and State

Antes del upload, validar:

- JWT válido.
- `idempresa` e `idalmacen` del usuario.
- Existencia de `idate_gas`.
- Pertenencia al almacén autenticado.
- Registro no eliminado.
- Estado compatible con despacho.
- `fecha_salida IS NULL`.

Usar prepared statements y convertir el parámetro de ruta a entero.

## Text Validation

Para destino y transportista:

- Deben existir.
- Aplicar `trim`.
- Rechazar cadena vacía.
- Validar longitud máxima según el esquema real de la tabla.

## File Validation

Tamaño máximo:

```text
10 MB = 10 * 1024 * 1024 bytes
```

Allowlist:

| Extensión | MIME aceptado principal |
|---|---|
| jpg, jpeg | image/jpeg |
| png | image/png |
| webp | image/webp |
| heic | image/heic, image/heic-sequence y variantes detectadas por el servidor |
| heif | image/heif, image/heif-sequence y variantes detectadas por el servidor |
| pdf | application/pdf |
| xls | application/vnd.ms-excel y MIME compatibles detectados por finfo |
| xlsx | application/vnd.openxmlformats-officedocument.spreadsheetml.sheet |
| doc | application/msword y MIME compatibles detectados por finfo |
| docx | application/vnd.openxmlformats-officedocument.wordprocessingml.document |

Validar:

- Estado de upload correcto.
- Tamaño mayor a cero.
- Tamaño menor o igual a 10 MB.
- Extensión normalizada en allowlist.
- MIME real mediante `finfo`.
- Combinación extensión/MIME aceptada.

Los archivos Office pueden ser reportados por algunos entornos como ZIP o CDF. La matriz de MIME debe implementarse explícitamente y probarse con archivos reales del proyecto, sin aceptar cualquier ZIP genérico.

## Original and Stored Names

Nombre guardado:

```php
$savedName = bin2hex(random_bytes(16)) . '.' . $extension;
```

Nombre original:

- Eliminar caracteres de control.
- Aplicar `basename`.
- Evitar path traversal.
- Limitar a la longitud de la columna.
- Preservar una versión legible para `Content-Disposition`.

## Azure Blob Name

Prefijo:

```php
$blobPrefix =
    $idempresa .
    '/almacen/ate_gas/salidas/' .
    $idate_gas;

$blobName = $blobPrefix . '/' . $savedName;
```

La referencia conceptual con `folder_files` puede conservarse para alinearse con el proyecto, pero el blob name debe usar `/` y no `DIRECTORY_SEPARATOR`.

`ubicacion_fisica_salida` almacenará el blob name, no una URL pública.

No crear carpeta ni archivo `thumb`.

## Upload and Persistence Flow

Flujo recomendado:

1. Autenticar.
2. Validar registro, ownership y estado.
3. Validar campos.
4. Validar archivo.
5. Preparar archivo temporal si el servicio lo requiere.
6. Cargar mediante `BlobStorageService::uploadBlobFromFile()` o método equivalente existente.
7. Iniciar o confirmar la transacción SQL.
8. Actualizar datos de salida y metadatos.
9. Confirmar la transacción.
10. Eliminar temporal local en `finally`.

## Database Update

Actualización conceptual:

```sql
UPDATE t_ate_gas
SET
    fecha_salida = CURRENT_TIMESTAMP(),
    destino_salida = :destino_salida,
    transportista_salida = :transportista_salida,
    nombre_original_salida = :nombre_original_salida,
    nombre_guardado_salida = :nombre_guardado_salida,
    ubicacion_fisica_salida = :ubicacion_fisica_salida
WHERE idate_gas = :idate_gas
  AND idalmacen = :idalmacen
  AND fecha_salida IS NULL
  AND deleted_at IS NULL
```

Ajustar nombres de campos de borrado al esquema real. Verificar `rowCount()` para detectar carreras o doble despacho.

## Transaction and Compensation

Azure no forma parte de la transacción SQL.

- No actualizar DB si falla el upload.
- Si falla SQL después del upload, ejecutar rollback.
- Intentar eliminar el blob cargado.
- Registrar si la compensación falla.
- Revisar si `BlobStorageService` ya posee delete; agregarlo si falta.

## GET Salidas

Agregar al SELECT y respuesta:

```sql
t_ate_gas.nombre_original_salida,
t_ate_gas.nombre_guardado_salida,
t_ate_gas.ubicacion_fisica_salida
```

Los registros históricos deben retornar `null` sin ser excluidos.

Puede agregarse `mime_salida` solo si el esquema ya lo contempla o si el equipo decide migrarlo; no es obligatorio para este cambio, porque el endpoint puede detectar MIME al recuperar el blob o inferirlo de forma controlada.

## File Endpoint

Reutilizar un endpoint genérico seguro si ya existe. Si no existe, crear uno equivalente a:

`GET /ate-gas/salidas/{idate_gas}/guia-remision`

Responsabilidades:

1. Autenticar.
2. Validar pertenencia por almacén.
3. Leer metadatos desde DB.
4. Recuperar el blob mediante `BlobStorageService`.
5. Determinar MIME real o confiable.
6. Sanear el nombre original para headers.
7. Responder:
   - `inline` para imágenes y PDF.
   - `attachment` para Excel y Word.
8. Evitar exponer credenciales o rutas internas.

Headers conceptuales:

```text
Content-Type: <mime>
Content-Disposition: inline; filename*=UTF-8''<encoded-name>
```

O:

```text
Content-Disposition: attachment; filename*=UTF-8''<encoded-name>
```

Agregar también `X-Content-Type-Options: nosniff`.

## Error Contract

Mantener el formato funcional usado por el proyecto, distinguiendo:

- Destino obligatorio.
- Transportista obligatorio.
- Archivo obligatorio.
- Más de un archivo.
- Formato inválido.
- Archivo superior a 10 MB.
- Unidad inexistente.
- Unidad ya despachada.
- Acceso a otro almacén.
- Error de Azure.
- Error de persistencia.
- Archivo no encontrado.

No devolver excepciones, secretos ni blob names sensibles.

## Observability

Registrar:

- `idate_gas`.
- `idempresa` e `idalmacen`.
- Método HTTP utilizado.
- Resultado de validación.
- Blob name generado.
- Resultado de upload.
- Resultado SQL.
- Resultado de compensación.

No registrar contenido del archivo, JWT ni secretos.

## Testing

Cubrir:

- Multipart PUT real y decisión documentada.
- POST alternativo si corresponde.
- Cada formato permitido.
- 10 MB exactos y más de 10 MB.
- MIME inconsistente.
- Múltiples archivos.
- Error Azure.
- Error SQL posterior a upload.
- Doble despacho.
- Acceso de otro almacén.
- Listado histórico con campos nulos.
- Inline para imagen y PDF.
- Attachment para Office.
- Nombre con espacios, acentos y caracteres especiales.
