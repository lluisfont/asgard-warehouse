# Legacy Dispatch Document Maintenance - Process Flow

## Flujo principal A - Editar ficha de despacho

1. El usuario abre `despachover.php?id=...`.
2. El sistema valida que exista `logis_despachos` para cliente `417` y `despacho = 1`.
3. Si no existe, redirige a `despachos.php`.
4. El usuario modifica los campos del despacho.
5. El usuario pulsa Guardar.
6. El sistema ejecuta `UPDATE logis_despachos`.
7. El sistema muestra mensaje de exito o error.

## Flujo principal B - Agregar o editar documento

1. El usuario abre la pestana Documentos.
2. El usuario despliega Agregar Documento.
3. Captura tipo, emisor, numero, formato, archivo, fecha, importe y divisa.
4. El formulario envia `POST` multipart a `despachoajax.php`.
5. El backend intenta mover el archivo a `FILES_PATH/logistica/...`.
6. Si `iddocumento > 0`, actualiza `logis_documentos`.
7. Si `iddocumento = 0`, intenta insertar en `logis_documentos`.

## Excepciones y brechas observadas

- No se incluye `config.php` ni inicializacion visible de `$db` en `despachoajax.php`.
- Se usan variables no definidas para ruta y nombre de archivo.
- La insercion usa `INSET INTO`, probable error tipografico.
- La salida imprime `$_POST` y `$_FILES`, lo que no parece comportamiento final de usuario.

