# Proposal: Migrar imagenes ATE-GAS a Azure Blob Storage

## Intent
Migrar el almacenamiento y recuperacion de imagenes asociadas a endpoints con prefijo `/ate-gas` desde el disco local de la maquina virtual hacia Azure Blob Storage, manteniendo la compatibilidad del contrato actual del API para el frontend.

## Scope
- Migrar la carga de imagenes de `/ate-gas/gestion-movimiento/inventario/:idate_gas_etapa` hacia Azure Blob Storage.
- Migrar la lectura/descarga de imagenes ATE-GAS que actualmente usa `file_exists()`, `file_get_contents()` y `mime_content_type()` sobre `folder_files`.
- Mantener la estructura logica de rutas ATE-GAS en los nombres de blob para no perder trazabilidad por empresa, etapa e inventario.
- Guardar en base de datos la referencia logica del blob en los campos existentes `ubicacion_fisica` y `ubicacion_thumb`, o mediante columnas nuevas si se decide normalizar metadatos.
- Generar thumbs localmente en archivo temporal y subirlos a Blob Storage, o generar ambos blobs desde memoria si se refactoriza `Common::crearThumbGD`.
- Agregar configuracion por ambiente para cuenta, contenedor, credenciales, prefijo base y modo de autenticacion.

## Out of Scope
- Migrar archivos masivos Excel ATE-GAS de `/ate-gas/cargamasiva` o `/ate-gas/salidas/cargamasiva`, salvo que se decida ampliar alcance en otra propuesta.
- Cambiar el formato de respuesta del frontend, que debe seguir recibiendo `itemImageSrc` en base64 cuando el endpoint actual lo requiere.
- Migrar imagenes de otros dominios como inventario fisico, salidas, timbrado o pedidos.
- Redisenar la base de datos completa de archivos adjuntos.

## Approach
Introducir una abstraccion `BlobStorageService` para centralizar subida, lectura, existencia y borrado logico/fisico de blobs. Los endpoints ATE-GAS reemplazaran operaciones directas al filesystem por llamadas al servicio. El nombre del blob preservara el patron actual relativo a empresa y dominio, por ejemplo:

```text
{ idempresa }/almacen/ate_gas/gestion-movimiento/{ idate_gas_etapa }/{ iddanios_vehiculos }/{ nombre_guardado }
{ idempresa }/almacen/ate_gas/gestion-movimiento/{ idate_gas_etapa }/{ iddanios_vehiculos }/thumb/{ thumbName }
{ idempresa }/almacen/ate_gas/gestion-movimiento-main/{ idate_gas_etapa }/{ nombre_guardado }
{ idempresa }/almacen/ate_gas/gestion-movimiento-main/{ idate_gas_etapa }/thumb/{ thumbName }
```

Para PHP 8 se evaluaran dos alternativas: usar el paquete `microsoft/azure-storage-blob` si se acepta su estado de mantenimiento, o implementar llamadas REST propias con HTTPS, `Put Blob` y `Get Blob`. La decision recomendada para largo plazo es encapsular la integracion para poder cambiar la estrategia sin tocar rutas Slim.

## Risks and Open Questions
- El paquete PHP `microsoft/azure-storage-blob` esta publicado en Packagist pero su README contiene aviso de retiro; debe decidirse si se acepta como dependencia o se implementa REST directo.
- Definir si la VM/app tendra identidad administrada de Azure, service principal, connection string o SAS. La opcion preferida es identidad administrada/Entra ID cuando la infraestructura lo permita.
- Confirmar si se requiere migrar historico ya existente en disco o solo nuevas cargas.
- Confirmar si los blobs deben permanecer privados y ser servidos por backend como base64, o si se podran usar SAS URLs de corta duracion en una etapa posterior.
- Confirmar politica de borrado: hoy el codigo marca registros con `deleted_at`; debe decidirse si tambien se elimina el blob o solo se conserva sin exponer.
