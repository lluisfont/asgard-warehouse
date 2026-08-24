# Delta for ATE GAS Salidas API

## ADDED Requirements

### Requirement: Solicitud multipart de despacho

El sistema MUST recibir destino, transportista y un único archivo mediante `multipart/form-data`.

El sistema MAY conservar PUT únicamente si el entorno procesa correctamente multipart PUT. En caso contrario, MUST exponer `POST /ate-gas/salidas/{idate_gas}` y el frontend MUST ajustarse de forma coordinada.

#### Scenario: Multipart PUT compatible

- GIVEN que el runtime entrega campos y uploaded files en una solicitud PUT multipart
- WHEN se implementa el despacho
- THEN el endpoint existente puede conservar el método PUT

#### Scenario: Multipart PUT incompatible

- GIVEN que el runtime no entrega de forma confiable los uploaded files mediante PUT
- WHEN se valida la integración
- THEN el backend implementa POST para la misma operación
- AND no mantiene un parser multipart manual dentro de la ruta

### Requirement: Archivo obligatorio y único

El endpoint MUST exigir exactamente un archivo de guía de remisión.

#### Scenario: Solicitud sin archivo

- GIVEN que destino y transportista fueron enviados
- WHEN no existe `file`
- THEN el endpoint responde con error de validación
- AND no actualiza `fecha_salida`
- AND no carga ningún blob

#### Scenario: Solicitud con más de un archivo

- GIVEN que la solicitud contiene múltiples archivos
- WHEN se procesa
- THEN el endpoint la rechaza
- AND no registra el despacho

### Requirement: Allowlist y límite de 10 MB

El sistema MUST aceptar JPG, JPEG, PNG, WebP, HEIC, HEIF, PDF, XLS, XLSX, DOC y DOCX.

El sistema MUST rechazar archivos superiores a 10 MB.

#### Scenario: Archivo válido

- GIVEN que el archivo pertenece a la allowlist
- AND su tamaño es menor o igual a 10 MB
- AND su MIME real es compatible
- WHEN se procesa
- THEN continúa el flujo de carga

#### Scenario: Archivo superior a 10 MB

- GIVEN que el archivo supera 10 MB
- WHEN se procesa
- THEN el endpoint rechaza la operación
- AND no carga el archivo

#### Scenario: MIME incompatible

- GIVEN que la extensión aparenta ser permitida
- AND el MIME detectado en servidor no corresponde a un tipo permitido
- WHEN se procesa
- THEN el endpoint rechaza la operación

### Requirement: Nombre seguro y ubicación

El sistema MUST generar un nombre no predecible, conservar el nombre original por separado y almacenar el blob bajo:

`{idempresa}/almacen/ate_gas/salidas/{idate_gas}/{nombre_guardado}`

#### Scenario: Generación del nombre

- GIVEN que el archivo es válido
- WHEN se prepara la carga
- THEN `nombre_guardado_salida` usa aleatoriedad criptográficamente segura
- AND conserva una extensión normalizada
- AND no usa directamente el nombre original como blob name

#### Scenario: Construcción de ubicación

- GIVEN que `idempresa` e `idate_gas` son válidos
- WHEN se crea el blob name
- THEN utiliza `/` como separador lógico
- AND queda bajo el prefijo definido para salidas

### Requirement: Almacenamiento sin thumbnail

El sistema MUST guardar únicamente el archivo original.

#### Scenario: Archivo de imagen

- GIVEN que la guía es una imagen válida
- WHEN se carga en Azure
- THEN no se genera thumbnail
- AND no se persiste una ruta de thumbnail

### Requirement: Persistencia de metadatos

El sistema MUST actualizar:

- `nombre_original_salida`
- `nombre_guardado_salida`
- `ubicacion_fisica_salida`

#### Scenario: Despacho exitoso

- GIVEN que el archivo se cargó correctamente
- WHEN la actualización SQL se confirma
- THEN se guardan los tres metadatos
- AND se guardan destino y transportista
- AND se registra `fecha_salida`

### Requirement: Consistencia ante fallos

El sistema MUST evitar que la unidad quede despachada si el archivo no fue almacenado.

#### Scenario: Error de Azure

- GIVEN que la solicitud es válida
- WHEN falla la carga a Azure
- THEN no se confirma la actualización SQL
- AND `fecha_salida` permanece sin cambios

#### Scenario: Error SQL después del upload

- GIVEN que el blob fue cargado
- WHEN falla la actualización SQL
- THEN la transacción se revierte
- AND el sistema intenta eliminar el blob recién creado
- AND registra el resultado de la compensación

### Requirement: Protección contra doble despacho

El sistema MUST rechazar un nuevo despacho cuando `fecha_salida` ya tenga valor.

#### Scenario: Unidad ya despachada

- GIVEN que la unidad ya tiene fecha de salida
- WHEN se intenta despachar nuevamente
- THEN el endpoint rechaza la operación
- AND no carga un nuevo archivo
- AND no sobrescribe los metadatos existentes

### Requirement: Metadatos en el listado

`GET /ate-gas/salidas` MUST retornar los tres campos de archivo.

#### Scenario: Registro nuevo con guía

- GIVEN que una salida tiene archivo
- WHEN se consulta el listado
- THEN la respuesta incluye los tres metadatos

#### Scenario: Registro histórico

- GIVEN que una salida histórica no tiene archivo
- WHEN se consulta el listado
- THEN los campos se retornan como `null`
- AND el registro continúa apareciendo

### Requirement: Obtención autenticada del archivo

El sistema MUST permitir obtener la guía solo a usuarios autorizados para el almacén correspondiente.

Para imágenes y PDF, SHOULD responder con `Content-Disposition: inline`.

Para XLS, XLSX, DOC y DOCX, SHOULD responder con `Content-Disposition: attachment`.

#### Scenario: Visualizar imagen

- GIVEN que el usuario está autorizado
- AND la guía es una imagen
- WHEN solicita el archivo
- THEN el backend responde con el MIME correcto
- AND usa disposición inline

#### Scenario: Visualizar PDF

- GIVEN que el usuario está autorizado
- AND la guía es PDF
- WHEN solicita el archivo
- THEN el backend responde con `application/pdf`
- AND usa disposición inline

#### Scenario: Descargar documento Office

- GIVEN que el usuario está autorizado
- AND la guía es Excel o Word
- WHEN solicita el archivo
- THEN el backend usa disposición attachment
- AND conserva el nombre original saneado

#### Scenario: Acceso desde otro almacén

- GIVEN que la salida pertenece a otro almacén
- WHEN el usuario solicita el archivo
- THEN el backend rechaza el acceso
- AND no revela el blob name

#### Scenario: Blob inexistente

- GIVEN que existen metadatos en base de datos
- AND el blob no existe
- WHEN se solicita el archivo
- THEN el backend responde como archivo no encontrado
- AND registra la inconsistencia
