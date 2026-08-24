# Delta for ATE-GAS Images

## ADDED Requirements

### Requirement: Subida de imagenes ATE-GAS a Azure Blob Storage
El sistema SHALL almacenar en Azure Blob Storage toda nueva imagen cargada por endpoints con prefijo `/ate-gas` que pertenezca al flujo de gestion de movimiento e inventario ATE-GAS.

#### Scenario: Subir imagen principal de etapa
- GIVEN un usuario autenticado envia archivos en `filesMain` al endpoint `/ate-gas/gestion-movimiento/inventario/:idate_gas_etapa`
- WHEN cada archivo cumple extension permitida y validacion MIME
- THEN el sistema SHALL subir el archivo original a Azure Blob Storage bajo el prefijo logico de `almacen/ate_gas/gestion-movimiento-main`
- AND el sistema SHALL crear y subir un thumbnail asociado bajo subcarpeta `thumb`
- AND el sistema SHALL registrar en `t_ate_gas_etapa_imagen` las rutas logicas del blob original y del thumbnail.

#### Scenario: Subir imagen de danio/inventario
- GIVEN un usuario autenticado envia archivos agrupados en `files[iddanios_vehiculos][idx]`
- WHEN cada archivo cumple extension permitida y validacion MIME
- THEN el sistema SHALL subir el archivo original a Azure Blob Storage bajo el prefijo logico de `almacen/ate_gas/gestion-movimiento/{idate_gas_etapa}/{iddanios_vehiculos}`
- AND el sistema SHALL subir el thumbnail bajo `thumb`
- AND el sistema SHALL registrar en `t_ate_gas_etapa_inventario_imagen` las rutas logicas del blob original y del thumbnail.

### Requirement: Lectura de imagenes ATE-GAS desde Azure Blob Storage
El sistema SHALL recuperar desde Azure Blob Storage las imagenes y thumbnails ATE-GAS guardadas para construir las respuestas JSON existentes.

#### Scenario: Consultar imagenes principales de etapa
- GIVEN existen registros activos en `t_ate_gas_etapa_imagen` para una etapa
- WHEN se llama `GET /ate-gas/gestion-movimiento/:idate_gas_etapa/imagenes`
- THEN el sistema SHALL descargar cada `ubicacion_thumb` desde Azure Blob Storage
- AND SHALL devolver cada imagen en `imagenes[].itemImageSrc` como data URI base64 compatible con el contrato actual.

#### Scenario: Consultar inventario de etapa con imagenes por danio
- GIVEN existen registros activos en `t_ate_gas_etapa_inventario_imagen` para una etapa y danio
- WHEN se llama `GET /ate-gas/gestion-movimiento/inventario/:idate_gas_etapa`
- THEN el sistema SHALL descargar cada `ubicacion_thumb` desde Azure Blob Storage
- AND SHALL incluir cada imagen en el arreglo `inventario[].imagenes` como data URI base64.

### Requirement: Compatibilidad del contrato API
El sistema SHALL preservar los nombres de endpoints, estructura de respuesta, codigos de exito/error y campos usados por el frontend, salvo cambios documentados en una nueva version del API.

#### Scenario: Frontend consume imagenes sin cambios
- GIVEN el frontend espera `itemImageSrc` con formato `data:{mime};base64,{data}`
- WHEN consulta imagenes ATE-GAS despues de la migracion
- THEN el backend SHALL responder el mismo formato aunque el origen fisico sea Azure Blob Storage.

### Requirement: Configuracion segura de Azure Storage
El sistema SHALL obtener la configuracion de Azure Storage desde variables de ambiente o archivo de configuracion no versionado.

#### Scenario: Ambiente sin credenciales configuradas
- GIVEN la aplicacion no tiene credenciales o contenedor configurado
- WHEN un endpoint ATE-GAS intenta subir o leer una imagen
- THEN el sistema SHALL responder error controlado
- AND SHALL registrar el error sin exponer secretos.

## MODIFIED Requirements

### Requirement: Persistencia de rutas de imagen ATE-GAS
El sistema SHALL tratar `ubicacion_fisica` y `ubicacion_thumb` como claves logicas de blob, no como rutas del disco local de la VM.
(Previously: estos campos eran rutas relativas que se concatenaban con `folder_files` para acceder al filesystem local.)

#### Scenario: Registro nuevo despues de migracion
- GIVEN se carga una nueva imagen ATE-GAS
- WHEN la imagen y su thumbnail se suben correctamente a Blob Storage
- THEN los campos de ubicacion SHALL almacenar nombres de blob o rutas relativas dentro del contenedor
- AND no SHALL depender de que exista un archivo persistente en la VM.

### Requirement: Borrado logico de imagenes ATE-GAS
El sistema SHALL mantener el comportamiento de borrado logico mediante `deleted_at` para registros de imagenes ATE-GAS.
(Previously: el borrado logico solo afectaba la base de datos y los archivos quedaban en disco.)

#### Scenario: Inventario elimina danio con imagenes
- GIVEN un danio ya no esta presente en el inventario recibido
- WHEN el sistema marca `deleted_at` en imagenes relacionadas
- THEN esas imagenes SHALL dejar de exponerse en endpoints de consulta
- AND el sistema MAY eliminar fisicamente los blobs si se habilita una politica explicita de limpieza.
