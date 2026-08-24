# Proposal: Agregar guía de remisión a la salida de vehículos ATE GAS

## Intent

Incorporar el registro obligatorio de una guía de remisión al momento de despachar un vehículo desde el módulo ATE GAS Salidas.

El usuario podrá tomar una fotografía con la cámara del dispositivo o seleccionar un archivo existente. El documento se enviará junto con destino y transportista, y luego estará disponible desde el listado de salidas.

## Scope

- Modificar el modal **Registrar Salida** en `src/app/ate-gas-salidas`.
- Permitir capturar una fotografía desde una cámara integrada en la aplicación o seleccionar un único archivo existente, incluidas imágenes guardadas en la galería del dispositivo.
- Permitir seleccionar la cámara disponible, tomar/repetir la fotografía y activar la linterna cuando el dispositivo lo soporte.
- Liberar la cámara y apagar la linterna al terminar o abandonar la captura.
- Mostrar vista previa cuando el archivo sea una imagen.
- Permitir reemplazar el archivo antes de confirmar el despacho.
- Limpiar el archivo temporal al cerrar o volver a abrir el modal.
- Hacer obligatorio el archivo para despachar.
- Limitar el archivo a 10 MB.
- Admitir JPG, JPEG, PNG, WebP, HEIC, HEIF, PDF, XLS, XLSX, DOC y DOCX.
- Enviar los datos mediante `multipart/form-data`.
- Ajustar el servicio Angular para usar `POST` si el backend determina que el servidor no procesa correctamente multipart mediante `PUT`.
- Agregar la columna **Guía de Remisión** al listado, incluso para registros históricos.
- Dejar la celda vacía para registros históricos sin archivo.
- Abrir imágenes y PDF en una pestaña nueva.
- Descargar directamente los demás formatos conservando su nombre original.
- Mantener compatibilidad con Safari en iPhone.

## Out of Scope

- Cargar más de un archivo por salida.
- Reemplazar una guía después de confirmar el despacho.
- Generar thumbnails.
- Migrar archivos para salidas históricas.
- Hacer público el almacenamiento de Azure.

## Approach

Se incorporará una cámara integrada mediante `navigator.mediaDevices.getUserMedia`, con video en vivo, selector de dispositivo, captura mediante canvas y control de linterna cuando la capacidad esté disponible. La cámara trasera será la preferida y se mantendrá un selector nativo independiente para documentos e imágenes existentes. Este selector incluirá `image/*` para que los dispositivos móviles puedan ofrecer su galería, además de las extensiones documentales permitidas.

La fotografía confirmada se convertirá a `Blob` y luego a un único `File` JPEG. Tanto la captura como el selector de archivos actualizarán la misma propiedad `File`; una nueva selección reemplazará a la anterior sin conservar una copia Base64 permanente.

Los tracks de video se detendrán y la linterna se apagará al aceptar la fotografía, cerrar el modal, completar el despacho, cambiar de cámara o destruir el componente.

El componente construirá un `FormData` con:

- `destino_salida`
- `transportista_salida`
- `file`

El servicio mantendrá inicialmente el endpoint existente. Si el backend necesita cambiar la operación multipart a POST, el frontend deberá cambiar de forma coordinada a:

`POST /ate-gas/salidas/{idate_gas}`

El listado usará `nombre_original_salida` como texto visible. La visualización o descarga se realizará mediante un endpoint autenticado, sin construir URLs públicas de Azure en el navegador.

## Success Criteria

- No se puede despachar sin destino, transportista y guía de remisión.
- No se aceptan archivos superiores a 10 MB.
- Solo se mantiene un archivo por operación.
- El archivo puede reemplazarse antes del despacho.
- Las imágenes muestran vista previa.
- Cerrar y volver a abrir el modal elimina la selección anterior.
- La selección funciona en escritorio, Android y Safari iOS.
- **Elegir archivo** permite seleccionar documentos o imágenes guardadas en el dispositivo.
- **Tomar foto** abre la cámara dentro de la aplicación y no el selector de galería.
- Se puede elegir entre las cámaras detectadas y usar la linterna cuando el dispositivo lo permite.
- La fotografía puede repetirse antes de usarla como guía.
- Ningún stream de cámara queda activo al cerrar o completar la operación.
- La columna Guía de Remisión siempre está visible.
- Los registros históricos sin archivo muestran la celda vacía.
- Imágenes y PDF se abren en una pestaña nueva.
- Excel y Word se descargan conservando el nombre original.
