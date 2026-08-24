# Proposal: Agregar captura de fotos en Control de Producción

## Intent
Permitir que, dentro del modal **Inventario Visual** del módulo **Control de producción**, el usuario pueda adjuntar imágenes mediante dos alternativas claramente diferenciadas: seleccionar varias imágenes desde la galería/dispositivo o tomar varias fotografías directamente con la cámara.

La mejora debe aplicarse tanto a cada tarjeta de daño detectado seleccionada como a la sección permanente de Observaciones Adicionales, conservando el flujo actual de guardado de inventario e imágenes.

## Scope
- Modificar `src/app/gestion-movimiento/` en el frontend Angular 17.
- Reemplazar la presentación actual de carga de imágenes por dos acciones visibles: **Tomar foto** y **Elegir de galería**.
- Mantener selección múltiple de imágenes desde galería.
- Permitir tomar y acumular varias fotografías desde la cámara antes de guardar el inventario.
- Aplicar el mismo comportamiento a:
  - Cada daño marcado que despliega una tarjeta con descripción e imágenes.
  - La sección de Observaciones Adicionales.
- Reutilizar y adaptar el patrón de cámara de `src/app/inventario-fisico-conteo/`, incluyendo:
  - Solicitud de permiso de cámara.
  - Selector de cámara disponible.
  - Vista previa de video.
  - Captura de foto.
  - Encendido/apagado de linterna cuando el dispositivo lo soporte.
  - Detención y liberación del stream.
- Incorporar las fotos tomadas a los arreglos `uploadedFiles[index]` o `uploadedFilesMain`, para enviarlas mediante el `FormData` existente.
- Conservar la carga y visualización de imágenes previamente guardadas.
- Conservar el soporte actual para HEIC/HEIF seleccionado desde galería y su conversión a JPEG.

## Out of Scope
- Modificar endpoints, consultas, tablas o almacenamiento del backend.
- Cambiar el contrato actual de `POST ate-gas/gestion-movimiento/inventario/{idate_gas_etapa}`.
- Eliminar imágenes ya persistidas desde este modal.
- Agregar edición, recorte, anotaciones o filtros de imagen.
- Garantizar linterna en navegadores o dispositivos que no expongan la capacidad `torch`.

## Approach
Agregar una interfaz reutilizable de selección de origen para cada contexto de imágenes. Al elegir galería, se abrirá el selector de archivos existente. Al elegir cámara, se abrirá una única interfaz de captura que conservará el contexto de destino activo: índice del daño o `-1` para Observaciones Adicionales.

Cada captura se convertirá a un archivo JPEG con nombre único y se agregará al mismo arreglo que actualmente consume `guardargestionmovimientoinventario()`. De esta manera, el backend seguirá recibiendo:

- `files[<iddanios_vehiculos>][]` para imágenes asociadas a daños.
- `filesMain[]` para imágenes generales de Observaciones Adicionales.

La cámara deberá detenerse al cerrar/cancelar su interfaz, al cerrar el modal de inventario, al cambiar de cámara y al destruir el componente.

## Assumptions
- Las fotografías tomadas se guardarán como JPEG y contarán dentro del límite actual de 20 MB por archivo.
- Se usará una sola interfaz/modal de cámara reutilizada para todos los destinos, evitando un stream por cada tarjeta de daño.
- Las imágenes tomadas y las elegidas desde galería se mostrarán juntas como archivos pendientes y se enviarán en una sola operación de guardado.
- Si la cámara, el permiso o la linterna no están disponibles, la carga desde galería seguirá funcionando.

## Risks and Open Questions
- iOS y algunos navegadores móviles pueden limitar la enumeración de cámaras hasta que el usuario otorgue permiso.
- La linterna depende de `MediaTrackCapabilities.torch` y no está disponible en todos los navegadores.
- Las fotografías de alta resolución pueden consumir memoria; la implementación debe usar una resolución y compresión razonables antes de crear el archivo JPEG.
