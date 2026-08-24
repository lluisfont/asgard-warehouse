# Tasks

## 1. Estado y selección

- [x] 1.1 Agregar estado para archivo, nombre, preview, error y procesamiento.
- [x] 1.2 Agregar cámara integrada y un selector nativo de archivos independiente, sin `multiple`.
- [x] 1.3 Implementar un asignador común para archivos seleccionados y fotografías capturadas.
- [x] 1.4 Limitar el archivo a 10 MB.
- [x] 1.5 Permitir JPG, JPEG, PNG, WebP, HEIC, HEIF, PDF, XLS, XLSX, DOC y DOCX.
- [x] 1.6 Permitir reemplazar el archivo antes del despacho.
- [x] 1.7 Crear y revocar Object URLs de forma segura.
- [x] 1.8 Aceptar HEIC/HEIF aunque el navegador no pueda mostrar preview.

## 2. Limpieza del modal

- [x] 2.1 Implementar `limpiarArchivoSalida()` o método equivalente.
- [x] 2.2 Invocarlo al inicio de `abrirDespachar(idate_gas)`.
- [x] 2.3 Invocarlo al cerrar con el botón Cerrar.
- [x] 2.4 Invocarlo en el evento de cierre del diálogo.
- [x] 2.5 Invocarlo después de un despacho exitoso.
- [x] 2.6 Liberar recursos en `ngOnDestroy`.
- [x] 2.7 Confirmar que no se restaura el archivo al reabrir el mismo VIN ni otro VIN.
- [x] 2.8 Detener tracks y apagar la linterna al aceptar, cerrar, completar, cambiar de cámara o destruir el componente.

## 3. Interfaz

- [x] 3.1 Agregar la sección **Documento: Guía de remisión**.
- [x] 3.2 Agregar opción **Tomar foto** mediante `getUserMedia`, sin abrir la galería.
- [x] 3.3 Agregar opción **Elegir archivo** con `image/*` para la galería y la allowlist acordada para documentos.
- [x] 3.4 Mostrar nombre y tamaño del archivo.
- [x] 3.5 Mostrar preview para imágenes compatibles.
- [x] 3.6 Mostrar representación no visual para PDF y Office.
- [x] 3.7 Agregar acción para quitar el archivo.
- [x] 3.8 Aplicar diseño responsive y accesible.
- [x] 3.9 Preferir la cámara trasera y permitir seleccionar otro dispositivo de video.
- [x] 3.10 Mostrar el video en vivo con reproducción inline.
- [x] 3.11 Permitir capturar, repetir y confirmar la fotografía.
- [x] 3.12 Habilitar el control de linterna solo cuando el dispositivo lo soporte.
- [x] 3.13 Convertir el canvas a `Blob` y luego a un único `File` JPEG sin Base64 permanente.
- [x] 3.14 Mostrar errores de API no disponible o permiso denegado sin bloquear el selector de archivos.

## 4. Validación y despacho

- [x] 4.1 Hacer obligatorio el archivo en `despacharVehiculo()`.
- [x] 4.2 Mostrar mensajes específicos de obligatoriedad, formato y tamaño.
- [x] 4.3 Construir `FormData` con destino, transportista y un archivo.
- [x] 4.4 Ajustar `AlmacenesService.sacarategas()` para recibir `FormData`.
- [x] 4.5 No establecer manualmente `Content-Type`.
- [x] 4.6 Descartar PUT al confirmar el backend que se requiere POST multipart.
- [x] 4.7 Cambiar el servicio a POST si el backend adopta `POST /ate-gas/salidas/{idate_gas}`.
- [x] 4.8 Deshabilitar acciones durante la solicitud.
- [x] 4.9 Conservar archivo y campos cuando el backend responda con error.
- [x] 4.10 Limpiar y cerrar solo después del éxito.

## 5. Listado y archivo

- [x] 5.1 Agregar siempre la columna **Guía de Remisión**.
- [x] 5.2 Mostrar `nombre_original_salida` para registros con archivo.
- [x] 5.3 Mantener la celda vacía para registros históricos sin archivo.
- [x] 5.4 No generar enlaces inválidos para valores nulos.
- [x] 5.5 Agregar al servicio el método autenticado para obtener el archivo como Blob.
- [x] 5.6 Abrir imágenes en una pestaña nueva.
- [x] 5.7 Abrir PDF en una pestaña nueva.
- [x] 5.8 Descargar XLS, XLSX, DOC y DOCX con el nombre original.
- [x] 5.9 Manejar el bloqueo de popups de Safari iOS.
- [x] 5.10 Liberar Object URLs después de su uso.

## 6. Validation

- [ ] 6.1 Probar todos los formatos permitidos.
- [ ] 6.2 Probar un archivo de 10 MB.
- [ ] 6.3 Probar un archivo mayor a 10 MB.
- [ ] 6.4 Probar formato no permitido.
- [ ] 6.5 Probar reemplazo del archivo.
- [ ] 6.6 Probar cierre con botón, X y máscara.
- [ ] 6.7 Probar mismo VIN y VIN distinto.
- [ ] 6.8 Probar validación sin archivo.
- [ ] 6.9 Probar error backend y reintento.
- [ ] 6.10 Probar columna vacía en registros históricos.
- [ ] 6.11 Probar apertura de imagen y PDF.
- [ ] 6.12 Probar descarga de Word y Excel.
- [ ] 6.13 Probar Chrome escritorio, Android y Safari iPhone.
- [ ] 6.14 Ejecutar la validación OpenSpec disponible en el repositorio.
- [x] 6.15 Confirmar que la implementación coincide con el delta spec.
- [ ] 6.16 Probar permiso de cámara concedido y denegado.
- [ ] 6.17 Probar preferencia de cámara trasera y cambio entre cámaras.
- [ ] 6.18 Probar captura, repetición y confirmación de la fotografía.
- [ ] 6.19 Probar linterna en dispositivos con y sin soporte.
- [ ] 6.20 Verificar que el stream se detiene en todos los caminos de cierre y éxito.
- [ ] 6.21 Probar la cámara en HTTPS o localhost.
- [ ] 6.22 Probar reproducción inline y captura en Safari iPhone.
- [ ] 6.23 Probar selección de una imagen guardada mediante **Elegir archivo** en Android y Safari iPhone.
