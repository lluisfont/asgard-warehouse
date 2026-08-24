# Design: Guía de remisión en ATE GAS Salidas

## Current Architecture

Archivos principales:

- `src/app/ate-gas-salidas/ate-gas-salidas.component.ts`
- `src/app/ate-gas-salidas/ate-gas-salidas.component.html`
- `src/app/ate-gas-salidas/ate-gas-salidas.component.css`
- `src/app/services/almacenes.service.ts`

El modal actual registra destino y transportista. La nueva funcionalidad agregará un archivo obligatorio y cambiará el envío a multipart.

## Proposed UI

Dentro del formulario se agregará una sección **Documento: Guía de remisión** con:

- Botón o tarjeta **Tomar foto**.
- Botón o tarjeta **Elegir archivo**, compatible con documentos e imágenes guardadas en la galería.
- Video en vivo dentro del modal.
- Selector de cámara cuando exista más de una disponible.
- Controles para iniciar/detener la cámara, capturar, repetir y usar la fotografía.
- Control de linterna cuando el track de video declare esa capacidad.
- Nombre del archivo seleccionado.
- Tamaño del archivo.
- Vista previa para imágenes compatibles.
- Acción para quitar el archivo.
- Mensajes de formato, tamaño y obligatoriedad.

El selector de archivos seguirá siendo un input nativo independiente:

```html
<input
  #fileInput
  type="file"
  accept="image/*,.jpg,.jpeg,.png,.webp,.heic,.heif,.pdf,.xls,.xlsx,.doc,.docx"
  hidden>
```

No se utilizará `multiple`.

`image/*` permite que Android y iOS presenten las imágenes guardadas mediante su selector nativo. La validación común por extensión mantiene la allowlist acordada y rechaza otros tipos de imagen, aunque el selector del sistema llegue a ofrecerlos.

## Component State

Agregar propiedades equivalentes a:

```typescript
archivo_salida: File | null = null;
nombre_archivo_salida = '';
preview_archivo_salida: string | null = null;
error_archivo_salida = '';
procesando_salida = false;
mostrar_camara_salida = false;
camara_salida_activa = false;
foto_camara_salida_capturada = false;
camaras_salida: MediaDeviceInfo[] = [];
id_camara_salida = '';
linterna_salida_soportada = false;
linterna_salida_encendida = false;
```

Agregar referencias al input, video y canvas mediante `ViewChild`. El `MediaStream` y su track de video se conservarán de forma privada solo durante la captura.

## Validation Constants

```typescript
readonly MAX_FILE_SIZE = 10 * 1024 * 1024;
readonly ALLOWED_EXTENSIONS = [
  'jpg', 'jpeg', 'png', 'webp', 'heic', 'heif',
  'pdf', 'xls', 'xlsx', 'doc', 'docx'
];
```

La validación frontend mejora la experiencia, pero no sustituye la validación del backend.

## File Selection

El input nativo y el resultado de la cámara usarán un asignador común:

```typescript
seleccionarArchivoSalida(event: Event): void
```

Responsabilidades:

1. Obtener únicamente el primer archivo.
2. Validar tamaño máximo de 10 MB.
3. Validar extensión permitida.
4. Reemplazar la selección anterior.
5. Revocar cualquier Object URL anterior.
6. Crear vista previa solo para imágenes renderizables.
7. Limpiar el input si la selección es inválida.
8. Permitir elegir nuevamente el mismo archivo después de quitarlo.

Se recomienda `URL.createObjectURL(file)` en lugar de Base64 para evitar consumo de memoria, especialmente en iPhone.

HEIC y HEIF pueden no ser renderizables por todos los navegadores. Deben aceptarse como archivo válido aunque no exista vista previa local.

## Integrated Camera

La opción **Tomar foto** MUST solicitar acceso a la cámara con `getUserMedia` únicamente después de una acción explícita del usuario. Se preferirá `facingMode: environment`; si no está disponible, se intentará una cámara alternativa. Después de obtener permiso se enumerarán los dispositivos de video para permitir el cambio de cámara.

El video usará `autoplay`, `muted` y `playsinline` para evitar la reproducción a pantalla completa en Safari iOS. La captura se dibujará en un canvas, limitando su dimensión máxima a 1920 px, y se convertirá con `canvas.toBlob(..., 'image/jpeg', 0.9)`. Al confirmar, se creará un `File` JPEG y se enviará por el flujo multipart existente.

La linterna se habilitará solamente cuando `MediaStreamTrack.getCapabilities()` indique soporte para `torch`. Un fallo al aplicar la restricción no debe cancelar la captura.

El acceso requiere contexto seguro (HTTPS o localhost). Si el navegador no soporta la API o el usuario rechaza el permiso, se mostrará un error dentro del modal y el selector de archivos seguirá disponible.

## State Reset

Crear un método centralizado:

```typescript
limpiarArchivoSalida(): void
```

Debe:

- Revocar el Object URL existente.
- Asignar `null` al archivo.
- Limpiar nombre y error.
- Vaciar el valor del input nativo.
- Apagar la linterna, detener todos los tracks y desconectar el video.

Invocarlo:

- Al inicio de `abrirDespachar(idate_gas)`.
- Al cerrar mediante el botón Cerrar.
- En el evento de cierre del `p-dialog`, incluyendo X o máscara.
- Después de un despacho exitoso.
- En `ngOnDestroy` para liberar recursos.

Cuando el backend retorne error, el archivo debe mantenerse para permitir reintento.

## Dispatch Request

Construcción del multipart:

```typescript
const formData = new FormData();
formData.append('destino_salida', this.destino_salida.trim());
formData.append('transportista_salida', this.transportista_salida.trim());
formData.append('file', this.archivo_salida, this.archivo_salida.name);
```

No establecer manualmente `Content-Type`; Angular debe generar el boundary.

Firma conceptual del servicio:

```typescript
sacarategas(
  token: string,
  idateGas: number,
  formData: FormData
): Observable<any>
```

Método HTTP:

- Usar `POST /ate-gas/salidas/{idate_gas}` porque el runtime no ofrece procesamiento multipart PUT confiable sin un parser manual.
- Mantener frontend y backend alineados en POST.

## Listing Changes

Agregar la columna **Guía de Remisión** siempre, aunque el registro no tenga archivo.

Campos consumidos:

- `nombre_original_salida`
- `nombre_guardado_salida`
- `ubicacion_fisica_salida`
- Un dato de tipo MIME si el backend decide retornarlo, o inferencia por extensión como fallback visual.

No usar `ubicacion_fisica_salida` para construir una URL pública.

## View and Download Flow

Se recomienda un método único:

```typescript
abrirGuiaRemision(registro: any): void
```

Flujo:

1. Solicitar el Blob al endpoint autenticado.
2. Determinar el MIME desde la respuesta HTTP; usar extensión solo como fallback.
3. Crear `URL.createObjectURL(blob)`.
4. Para imagen o PDF, abrir con `window.open(objectUrl, '_blank', 'noopener')`.
5. Para Word o Excel, crear un `<a download="nombre_original_salida">` y ejecutarlo.
6. Liberar el Object URL después de un tiempo prudente; no revocarlo antes de que la pestaña nueva pueda cargarlo.

Safari puede bloquear una pestaña creada después de una llamada asíncrona. Para evitarlo, la implementación puede abrir una pestaña vacía de forma inmediata en el evento del usuario, completar la descarga y luego asignar el Object URL. Si ocurre un error, debe cerrar esa pestaña.

## Styling

- Dos opciones en columnas en escritorio.
- Apiladas en pantallas angostas.
- Preview con `max-width: 100%` y altura limitada.
- Nombre largo con truncado visual, conservando tooltip.
- Botones accesibles mediante teclado.
- Estado deshabilitado durante la solicitud.

## Error Handling

- Rechazo local de tamaño o extensión: no invocar backend.
- Error backend: conservar archivo y campos para reintento.
- Éxito: limpiar, cerrar, recargar listado y mostrar toast.
- Error de descarga: mostrar toast y cerrar pestaña provisional si se abrió.

## Testing

Validar:

- JPG, PNG, WebP, HEIC/HEIF, PDF, XLS/XLSX y DOC/DOCX.
- Archivo exactamente de 10 MB y archivo superior a 10 MB.
- Reemplazo de archivo.
- Limpieza al cerrar y al cambiar de VIN.
- FormData correcto.
- Cambio coordinado a POST si corresponde.
- Columna vacía en históricos.
- Apertura de imagen/PDF en nueva pestaña.
- Descarga de Office.
- Safari iPhone, especialmente bloqueo de popups y captura de cámara.
- Permiso concedido y denegado para la cámara integrada.
- Preferencia de cámara trasera, selector de cámaras y cambio entre dispositivos.
- Captura, repetición y confirmación de una fotografía.
- Linterna en dispositivos con y sin soporte.
- Liberación del stream al cerrar, confirmar, cambiar de cámara y destruir el componente.
