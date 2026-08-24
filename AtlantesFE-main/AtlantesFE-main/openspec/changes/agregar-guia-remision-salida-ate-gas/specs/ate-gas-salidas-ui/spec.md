# Delta for ATE GAS Salidas UI

## ADDED Requirements

### Requirement: Selección de una única guía de remisión

El sistema MUST permitir seleccionar una única guía de remisión desde el modal **Registrar Salida**, ya sea mediante cámara o mediante el selector de archivos del dispositivo.

La opción **Tomar foto** MUST abrir una cámara integrada en la aplicación y MUST NOT invocar el selector de galería o archivos.

La opción **Elegir archivo** MUST permitir seleccionar tanto documentos como imágenes guardadas en el dispositivo. En dispositivos móviles, el input MUST incluir `image/*` para permitir el acceso a la galería, sin eliminar la validación de extensiones permitidas.

#### Scenario: Seleccionar un archivo

- GIVEN que el usuario abrió el modal de despacho
- WHEN selecciona un archivo permitido
- THEN el sistema conserva ese archivo como guía de remisión
- AND muestra su nombre original
- AND no conserva más de un archivo

#### Scenario: Seleccionar una imagen guardada

- GIVEN que el usuario abrió el modal de despacho en un dispositivo móvil
- WHEN selecciona **Elegir archivo**
- THEN el selector nativo permite acceder a las imágenes guardadas en el dispositivo
- AND una imagen con extensión permitida se procesa mediante el mismo flujo de validación

#### Scenario: Tomar una fotografía

- GIVEN que el usuario utiliza un dispositivo con cámara
- WHEN selecciona la opción **Tomar foto** y confirma la captura
- THEN el sistema conserva la fotografía como guía de remisión
- AND muestra una vista previa
- AND la fotografía reemplaza cualquier archivo seleccionado anteriormente

### Requirement: Cámara integrada

El sistema MUST solicitar acceso a la cámara mediante `getUserMedia` solo después de que el usuario seleccione **Tomar foto**.

El sistema MUST preferir la cámara trasera, MUST enumerar las cámaras disponibles después de obtener permiso y MUST permitir cambiar el dispositivo de video.

El sistema MUST permitir capturar, revisar, repetir y confirmar la fotografía dentro del modal.

La captura confirmada MUST convertirse a un único `File` JPEG sin mantener una copia Base64 permanente.

El sistema MUST detener los tracks y apagar la linterna al aceptar la fotografía, cerrar el modal, completar el despacho, cambiar de cámara o destruir el componente.

#### Scenario: Capturar y confirmar una fotografía

- GIVEN que el usuario concedió permiso de cámara
- WHEN captura una fotografía y selecciona **Usar foto**
- THEN el sistema convierte la captura en un archivo JPEG
- AND lo procesa mediante el mismo flujo de validación y multipart que un archivo seleccionado
- AND detiene el stream de cámara

#### Scenario: Repetir una fotografía

- GIVEN que el usuario capturó una fotografía
- WHEN selecciona **Repetir**
- THEN el video vuelve a mostrarse
- AND la fotografía provisional no se usa como guía

#### Scenario: Cambiar de cámara

- GIVEN que el navegador detectó más de una cámara
- WHEN el usuario selecciona otro dispositivo
- THEN el sistema detiene el stream anterior
- AND inicia el dispositivo seleccionado

#### Scenario: Permiso denegado o API no disponible

- GIVEN que no se puede acceder a `getUserMedia`
- WHEN el usuario selecciona **Tomar foto**
- THEN el sistema muestra un mensaje de error
- AND mantiene disponible la opción **Elegir archivo**

### Requirement: Linterna de la cámara

El sistema SHOULD permitir encender y apagar la linterna únicamente cuando el track de video anuncie soporte para `torch`.

Un error al cambiar el estado de la linterna MUST NOT invalidar la captura ni cerrar la cámara.

#### Scenario: Cámara con linterna

- GIVEN que la cámara activa soporta `torch`
- WHEN el usuario activa o desactiva la linterna
- THEN el sistema aplica la restricción al track activo

#### Scenario: Cámara sin linterna

- GIVEN que la cámara activa no soporta `torch`
- WHEN se muestra la interfaz de captura
- THEN el sistema no ofrece un control de linterna activo

#### Scenario: Reemplazar el archivo

- GIVEN que ya existe un archivo seleccionado
- AND todavía no se confirmó el despacho
- WHEN el usuario selecciona o captura otro archivo
- THEN el sistema reemplaza el archivo anterior
- AND conserva únicamente el último archivo

### Requirement: Tipos y tamaño permitidos

El sistema MUST aceptar archivos JPG, JPEG, PNG, WebP, HEIC, HEIF, PDF, XLS, XLSX, DOC y DOCX.

El sistema MUST rechazar archivos cuyo tamaño sea superior a 10 MB.

#### Scenario: Archivo permitido

- GIVEN que el archivo tiene una extensión permitida
- AND su tamaño es menor o igual a 10 MB
- WHEN el usuario lo selecciona
- THEN el sistema lo acepta provisionalmente

#### Scenario: Archivo demasiado grande

- GIVEN que el archivo supera 10 MB
- WHEN el usuario lo selecciona
- THEN el sistema rechaza la selección
- AND muestra un mensaje indicando el límite de 10 MB
- AND limpia el input utilizado

#### Scenario: Formato no permitido

- GIVEN que el archivo no pertenece a la lista permitida
- WHEN el usuario lo selecciona
- THEN el sistema rechaza la selección
- AND muestra un mensaje de formato inválido

### Requirement: Vista previa

El sistema MUST mostrar una vista previa cuando el archivo seleccionado sea una imagen que el navegador pueda renderizar.

Para los demás formatos, MUST mostrar al menos el nombre original y el tipo de documento.

#### Scenario: Vista previa de imagen

- GIVEN que el usuario seleccionó una imagen compatible
- WHEN finaliza la selección
- THEN el modal muestra una vista previa del último archivo seleccionado

#### Scenario: Documento sin vista previa visual

- GIVEN que el usuario seleccionó PDF, Excel o Word
- WHEN finaliza la selección
- THEN el sistema muestra el nombre del archivo
- AND no intenta renderizarlo como imagen

### Requirement: Limpieza del estado temporal

El sistema MUST limpiar archivo, preview, valores de inputs y errores al iniciar o cerrar una operación de despacho.

#### Scenario: Cerrar sin despachar

- GIVEN que existe un archivo seleccionado
- WHEN el usuario cierra el modal sin confirmar el despacho
- THEN el sistema descarta el archivo
- AND elimina la vista previa
- AND limpia el input nativo
- AND apaga la linterna y detiene cualquier stream de cámara activo

#### Scenario: Abrir el mismo VIN nuevamente

- GIVEN que el usuario cerró el modal sin despachar
- WHEN ejecuta nuevamente `abrirDespachar(idate_gas)` para el mismo VIN
- THEN el sistema no restaura el archivo anterior

#### Scenario: Abrir otro VIN

- GIVEN que el modal fue utilizado para un VIN anterior
- WHEN se abre para otro VIN
- THEN el estado del archivo se encuentra limpio

### Requirement: Guía obligatoria para despachar

El sistema MUST impedir el despacho cuando no exista un archivo válido.

#### Scenario: Intentar despachar sin guía

- GIVEN que destino y transportista son válidos
- AND no existe archivo seleccionado
- WHEN el usuario intenta despachar
- THEN el frontend no invoca el endpoint
- AND muestra que la guía de remisión es obligatoria

#### Scenario: Despacho válido

- GIVEN que destino y transportista son válidos
- AND existe un archivo válido
- WHEN el usuario confirma el despacho
- THEN el frontend envía la solicitud multipart
- AND deshabilita temporalmente las acciones para evitar duplicados

### Requirement: Compatibilidad con Safari iOS

El video de la cámara integrada MUST usar reproducción inline y la captura MUST evitar mantener una copia Base64 permanente en memoria.

La cámara integrada MUST ejecutarse en un contexto seguro compatible con `getUserMedia`. El selector nativo de archivos MUST permanecer disponible como alternativa.

#### Scenario: Captura desde iPhone

- GIVEN que el usuario utiliza Safari en un iPhone
- WHEN selecciona la opción de cámara y concede permiso
- THEN el sistema muestra el video dentro del modal sin forzar pantalla completa
- AND procesa la fotografía confirmada como un único archivo
- AND evita almacenar una copia Base64 permanente en memoria

### Requirement: Envío multipart coordinado con el backend

El sistema MUST enviar `destino_salida`, `transportista_salida` y `file` mediante `FormData`.

El servicio MUST usar POST si el backend confirma que multipart PUT no es compatible con el servidor actual.

#### Scenario: Construcción de la solicitud

- GIVEN que todos los campos son válidos
- WHEN se ejecuta el despacho
- THEN el `FormData` contiene `destino_salida`
- AND contiene `transportista_salida`
- AND contiene un único `file`
- AND el servicio no establece manualmente el header `Content-Type`

#### Scenario: Backend requiere POST

- GIVEN que la validación técnica del backend determina que multipart PUT no funciona correctamente
- WHEN se implementa el cambio
- THEN el servicio Angular usa `POST /ate-gas/salidas/{idate_gas}`
- AND el comportamiento funcional permanece sin cambios

### Requirement: Columna Guía de Remisión

El sistema MUST mostrar la columna **Guía de Remisión** para todos los registros del listado.

#### Scenario: Registro con guía

- GIVEN que el registro tiene `nombre_original_salida`
- WHEN se muestra la tabla
- THEN la celda muestra el nombre original como acción disponible

#### Scenario: Registro histórico sin guía

- GIVEN que el registro no tiene archivo asociado
- WHEN se muestra la tabla
- THEN la columna permanece visible
- AND la celda queda vacía
- AND no se genera un enlace inválido

### Requirement: Apertura y descarga según tipo

El sistema MUST abrir imágenes y PDF en una pestaña nueva.

El sistema MUST descargar XLS, XLSX, DOC y DOCX conservando el nombre original.

#### Scenario: Abrir imagen

- GIVEN que la guía es una imagen
- WHEN el usuario hace clic en su nombre
- THEN el frontend obtiene el Blob mediante el endpoint autenticado
- AND abre un Object URL en una pestaña nueva

#### Scenario: Abrir PDF

- GIVEN que la guía es un PDF
- WHEN el usuario hace clic en su nombre
- THEN el frontend abre el contenido en una pestaña nueva

#### Scenario: Descargar documento de oficina

- GIVEN que la guía es un archivo Excel o Word
- WHEN el usuario hace clic en su nombre
- THEN el navegador inicia la descarga
- AND usa `nombre_original_salida`

#### Scenario: Error al obtener el archivo

- GIVEN que el backend no puede recuperar la guía
- WHEN el usuario intenta abrirla o descargarla
- THEN el sistema muestra un mensaje de error
- AND no abre una pestaña vacía ni una URL inválida
