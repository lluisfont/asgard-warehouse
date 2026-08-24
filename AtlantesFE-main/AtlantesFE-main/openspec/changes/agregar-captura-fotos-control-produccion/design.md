# Design: Captura de fotos en Control de Producción

## Current Architecture Context
El cambio se concentra en el frontend Angular 17:

- `src/app/gestion-movimiento/gestion-movimiento.component.html`
  - Modal PrimeNG `p-dialog` llamado Inventario Visual.
  - Cada daño marcado crea una tarjeta con descripción y un `p-fileUpload` múltiple.
  - Observaciones Adicionales tiene otro `p-fileUpload` múltiple.
- `src/app/gestion-movimiento/gestion-movimiento.component.ts`
  - `uploadedFiles: File[][]` almacena archivos por índice de daño.
  - `uploadedFilesMain: File[]` almacena archivos generales.
  - `onSelectFiles()` convierte HEIC/HEIF a JPEG y acumula archivos.
  - `cerrarModal()` comprueba archivos pendientes.
- `src/app/services/almacenes.service.ts`
  - `guardargestionmovimientoinventario()` construye el `FormData`.
  - Usa `files[<iddanios_vehiculos>][]` y `filesMain[]`.
- `src/app/inventario-fisico-conteo/`
  - Ya contiene una implementación de referencia para `getUserMedia`, selector de cámaras, captura a canvas y linterna.

El backend Slim PHP 4 ya procesa los archivos multipart existentes. Como una fotografía capturada puede convertirse a `File`, no se requiere modificar el contrato ni el backend.

## Proposed UI Flow
En cada destino de imágenes se mostrarán dos tarjetas o botones grandes:

1. **Tomar foto**
2. **Elegir de galería**

El selector real de archivos se mantendrá oculto o integrado detrás de Elegir de galería y conservará `multiple`, tipos de imagen y límite de 20 MB.

Tomar foto abrirá un único diálogo secundario reutilizable, con:

- Selector de cámara.
- Vista previa `<video playsinline autoplay muted>`.
- `<canvas>` para previsualizar la captura.
- Botón iniciar cámara.
- Botón capturar.
- Botón de linterna condicional.
- Botón detener.
- Acciones Repetir/Cancelar y Agregar foto.
- Lista o contador de fotos agregadas al destino actual.

## Camera Destination Context
Mantener un estado explícito para evitar que una captura termine en otra sección:

```ts
type CameraTarget =
  | { kind: 'damage'; index: number }
  | { kind: 'main' };
```

Estado sugerido:

```ts
cameraVisible = false;
cameraTarget: CameraTarget | null = null;
videoDevices: MediaDeviceInfo[] = [];
selectedDeviceId = '';
stream: MediaStream | null = null;
videoTrack: MediaStreamTrack | null = null;
torchSupported = false;
torchOn = false;
capturedPreviewUrl: string | null = null;
```

`openCameraForDamage(index)` y `openCameraForMain()` deberán establecer el destino antes de iniciar o mostrar la cámara.

## Capture Pipeline
1. Solicitar el stream mediante `navigator.mediaDevices.getUserMedia()`.
2. Preferir `facingMode: { ideal: 'environment' }` si no existe una cámara elegida.
3. Asignar el stream al video y esperar `loadeddata`/`play()`.
4. Al capturar, dibujar el frame en canvas conservando relación de aspecto.
5. Convertir el canvas con `canvas.toBlob(..., 'image/jpeg', quality)` en lugar de mantener base64.
6. Crear un `File` con nombre único, por ejemplo:
   - `foto-inventario-<timestamp>-<secuencia>.jpg`
7. Al confirmar, agregar el archivo al arreglo correspondiente:

```ts
if (cameraTarget.kind === 'main') {
  uploadedFilesMain = [...uploadedFilesMain, file];
} else {
  uploadedFiles[cameraTarget.index] = [
    ...(uploadedFiles[cameraTarget.index] ?? []),
    file
  ];
}
```

8. Restablecer la previsualización para permitir otra captura sin cerrar la cámara.

Se recomienda una calidad JPEG aproximada entre `0.75` y `0.85` y limitar el canvas a una dimensión máxima razonable para controlar memoria y tamaño, manteniendo suficiente detalle visual.

## Reuse Strategy
Extraer del componente de inventario físico únicamente el comportamiento estable necesario:

- `ensureVideoReady()`.
- Enumeración de `videoinput` después del permiso.
- Fallback de cámara posterior a frontal.
- Cambio de cámara deteniendo el stream anterior.
- Detección y alternancia de `torch`.
- Limpieza completa del stream.

No copiar estado o métodos no relacionados con Control de producción. Los nombres deberán ajustarse al contexto del modal Inventario Visual.

## Gallery Integration
El flujo de galería debe seguir usando `onSelectFiles()` para conservar:

- Acumulación de archivos.
- Conversión HEIC/HEIF a JPEG.
- Separación entre daño y archivos generales.

La implementación puede mantener `p-fileUpload` con un template de cabecera personalizado o usar un `<input type="file" hidden multiple>` por destino. Se recomienda conservar PrimeNG para reducir el cambio funcional, ocultando la interfaz predeterminada y activando `choose()` desde el botón Elegir de galería.

## Pending File Presentation
Mostrar una lista o miniaturas de archivos pendientes unificada, independientemente de su origen. Cada elemento SHOULD poder eliminarse antes de guardar.

Para evitar fugas de memoria si se crean previsualizaciones con `URL.createObjectURL(file)`, revocar cada URL al eliminar el archivo, cerrar el modal o destruir el componente.

Las imágenes ya persistidas (`inventarios.imagenes` e `imagenesMain`) se mantienen separadas y visibles como actualmente.

## Lifecycle and Cleanup
Crear un único método `stopCamera()` idempotente que:

- Apague la linterna si corresponde.
- Detenga todas las pistas del stream.
- Limpie `srcObject` del video.
- Restablezca `stream`, `videoTrack`, `torchOn` y flags de UI.

Invocarlo en:

- Cierre/cancelación del diálogo de cámara.
- Cambio de cámara.
- Cierre confirmado de Inventario Visual.
- Guardado exitoso.
- `ngOnDestroy()`.

## Error Handling
Mostrar mensajes mediante el mecanismo de toast existente para:

- Permiso denegado.
- Cámara no encontrada.
- Navegador sin `mediaDevices`.
- Fallo al iniciar el video.
- Fallo al generar el JPEG.
- Fallo al alternar la linterna.

Un error de cámara no debe deshabilitar Elegir de galería.

## Backend and API Changes
No se prevén cambios. Las fotos capturadas se convierten a `File` y entran en los arreglos que ya recibe:

```ts
guardargestionmovimientoinventario(
  token,
  idate_gas_etapa,
  observaciones_inventario,
  inventario,
  uploadedFiles,
  uploadedFilesMain
)
```

El servicio seguirá anexando los archivos al `FormData` con los nombres actuales.

## Security and Browser Constraints
- `getUserMedia` requiere contexto seguro HTTPS, salvo `localhost`.
- La captura debe iniciarse por una acción explícita del usuario.
- No persistir el stream ni fotografías fuera del flujo actual.
- No registrar base64, blobs o imágenes en consola.
- Tratar los nombres de archivo como metadatos no confiables.

## Testing and Validation
### Unit tests sugeridos
- Agregar archivo capturado a un daño específico.
- Agregar archivo capturado a Observaciones Adicionales.
- No agregar una captura cancelada.
- Acumular galería y cámara.
- `stopCamera()` detiene todas las pistas y limpia estado.
- El control de linterna solo se habilita con capacidad `torch`.
- El cierre detecta fotografías pendientes.

### Manual tests
- Chrome Android con cámara posterior y linterna.
- iPhone Safari con `playsinline`.
- Equipo de escritorio con una webcam.
- Dispositivo con varias cámaras.
- Permiso denegado.
- Dispositivo sin linterna.
- Selección múltiple de JPG/PNG/HEIC.
- Mezcla de fotos y galería en más de un daño y en Observaciones Adicionales.
- Guardado y posterior recarga de imágenes persistidas.

## Alternatives Considered
### Usar solamente `<input capture="environment">`
Descartado como solución principal porque el comportamiento varía entre navegadores, no ofrece selector de cámara ni control de linterna y puede limitar el flujo de múltiples capturas.

### Crear un componente Angular compartido de cámara
Es una opción arquitectónicamente más limpia y reutilizable. Para este cambio se recomienda crear un componente compartido solo si la extracción no incrementa significativamente el alcance; de lo contrario, adaptar el patrón dentro de `GestionMovimientoComponent` y dejar una refactorización posterior.

### Enviar imágenes en base64
Descartado porque aumenta el tamaño del payload y obligaría a cambiar el contrato. Convertir a `File` conserva el flujo multipart existente.
