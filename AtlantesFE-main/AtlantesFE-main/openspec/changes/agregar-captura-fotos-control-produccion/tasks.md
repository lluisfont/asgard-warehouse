# Tasks

## 1. Preparar el flujo de destinos de imágenes
- [x] 1.1 Identificar en `gestion-movimiento.component.html` las dos clases de destino: tarjeta de daño y Observaciones Adicionales.
- [x] 1.2 Agregar un tipo/estado de destino de cámara que diferencie daño por índice y destino general.
- [x] 1.3 Asegurar que `uploadedFiles` se inicialice para todos los daños y que `uploadedFilesMain` se reinicie al abrir un nuevo inventario.

## 2. Actualizar la interfaz de carga
- [x] 2.1 Reemplazar la presentación predeterminada de cada `p-fileUpload` por acciones visibles Tomar foto y Elegir de galería.
- [x] 2.2 Mantener selección múltiple, `accept="image/*,.heic,.heif,image/heic,image/heif"` y límite de 20 MB para galería.
- [x] 2.3 Conectar Elegir de galería con el cargador correspondiente sin alterar `onSelectFiles()`.
- [x] 2.4 Aplicar la misma interfaz en cada tarjeta de daño y en Observaciones Adicionales.
- [x] 2.5 Mostrar juntos los archivos pendientes elegidos y capturados, con una acción para eliminar cada uno.
- [x] 2.6 Mantener visibles las imágenes ya persistidas en `inventarios.imagenes` e `imagenesMain`.
- [x] 2.7 Agregar estilos responsivos para que las dos acciones funcionen en móvil y escritorio.

## 3. Implementar la cámara reutilizable
- [x] 3.1 Agregar un diálogo/interfaz única de cámara reutilizable desde cualquier destino de imágenes.
- [x] 3.2 Adaptar desde `inventario-fisico-conteo` la solicitud de permiso y enumeración de cámaras.
- [x] 3.3 Preferir cámara posterior y agregar fallbacks compatibles.
- [x] 3.4 Implementar selector de cámara y reiniciar el stream al cambiarla.
- [x] 3.5 Implementar vista previa de video con `autoplay`, `muted` y `playsinline`.
- [x] 3.6 Implementar captura a canvas conservando la relación de aspecto.
- [x] 3.7 Convertir la captura a `Blob` JPEG y luego a `File` con nombre único.
- [x] 3.8 Implementar Confirmar/Agregar foto y Repetir/Cancelar captura.
- [x] 3.9 Permitir confirmar varias fotografías sucesivas sin abandonar el destino activo.

## 4. Implementar selector de cámara y linterna
- [x] 4.1 Mostrar las cámaras disponibles después de otorgar permiso.
- [x] 4.2 Detectar `MediaTrackCapabilities.torch` en la pista activa.
- [x] 4.3 Habilitar el botón de linterna solo cuando exista soporte.
- [x] 4.4 Alternar la linterna con `applyConstraints()` y manejar fallos sin bloquear la captura.
- [x] 4.5 Apagar la linterna antes de detener la pista.

## 5. Integrar fotos con los arreglos existentes
- [x] 5.1 Agregar fotos de un daño a `uploadedFiles[index]`.
- [x] 5.2 Agregar fotos generales a `uploadedFilesMain`.
- [x] 5.3 Confirmar que la mezcla de archivos de galería y cámara se acumula sin reemplazos.
- [x] 5.4 Confirmar que eliminar un archivo actualiza el arreglo correcto.
- [x] 5.5 Confirmar que `cerrarModal()` detecta fotos tomadas como cambios no guardados.

## 6. Liberar recursos y manejar errores
- [x] 6.1 Crear un método idempotente para detener cámara, pistas y linterna, y limpiar referencias.
- [x] 6.2 Invocar la limpieza al cerrar/cancelar la cámara.
- [x] 6.3 Invocar la limpieza al cambiar de cámara.
- [x] 6.4 Invocar la limpieza al cerrar el Inventario Visual y después de guardar exitosamente.
- [x] 6.5 Implementar `OnDestroy` y liberar el stream si el componente se destruye.
- [x] 6.6 Mostrar mensajes para permiso denegado, cámara no encontrada, API no soportada y fallo de captura.
- [x] 6.7 Mantener Elegir de galería operativo aunque falle la cámara.

## 7. Verificar compatibilidad con el backend
- [x] 7.1 Confirmar que no se modifica la firma de `guardargestionmovimientoinventario()`.
- [x] 7.2 Confirmar que las fotos de daños se anexan como `files[<iddanios_vehiculos>][]`.
- [x] 7.3 Confirmar que las fotos generales se anexan como `filesMain[]`.
- [x] 7.4 Confirmar que el backend no requiere cambios para recibir los nuevos archivos JPEG.

## 8. Pruebas automatizadas
- [x] 8.1 Agregar pruebas para asignar una captura al daño correcto.
- [x] 8.2 Agregar pruebas para asignar una captura al destino general.
- [x] 8.3 Agregar pruebas para descartar una captura cancelada.
- [x] 8.4 Agregar pruebas para acumular archivos de galería y cámara.
- [x] 8.5 Agregar pruebas para detener todas las pistas y limpiar el estado.
- [x] 8.6 Agregar pruebas para habilitar/deshabilitar linterna según capacidades.
- [x] 8.7 Agregar pruebas para la advertencia de cierre con fotos pendientes.

## 9. Validación manual
- [x] 9.1 Ejecutar el build del frontend Angular 17.
- [ ] 9.2 Probar selección múltiple de JPG y PNG.
- [ ] 9.3 Probar selección y conversión de HEIC/HEIF.
- [ ] 9.4 Probar varias fotos para un mismo daño.
- [ ] 9.5 Probar fotos para varios daños distintos sin mezclar destinos.
- [ ] 9.6 Probar fotos en Observaciones Adicionales.
- [ ] 9.7 Probar mezcla de galería y cámara antes de guardar.
- [ ] 9.8 Probar selector de cámara en un dispositivo con varias cámaras.
- [ ] 9.9 Probar linterna soportada y no soportada.
- [ ] 9.10 Probar permiso denegado y verificar que galería siga disponible.
- [ ] 9.11 Probar cierre con fotos pendientes y confirmación de descarte.
- [ ] 9.12 Guardar, reabrir el inventario y confirmar que las imágenes persistidas se cargan correctamente.

## 10. OpenSpec
- [x] 10.1 Revisar que `proposal.md`, `design.md`, `tasks.md` y el delta spec coincidan con la implementación final.
- [ ] 10.2 Ejecutar `openspec validate agregar-captura-fotos-control-produccion` si el CLI está disponible.
