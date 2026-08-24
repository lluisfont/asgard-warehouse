# Delta for Control de Producción - Inventario Visual

## ADDED Requirements

### Requirement: Selección de origen de imágenes
El sistema SHALL mostrar las acciones **Tomar foto** y **Elegir de galería** en toda sección del modal Inventario Visual que permita adjuntar imágenes.

#### Scenario: Acciones en una tarjeta de daño
- GIVEN que el usuario abrió Inventario Visual
- AND marcó un tipo de daño
- WHEN se muestra la tarjeta correspondiente al daño
- THEN la tarjeta muestra las acciones Tomar foto y Elegir de galería

#### Scenario: Acciones en Observaciones Adicionales
- GIVEN que el usuario abrió Inventario Visual
- WHEN visualiza la sección Observaciones Adicionales
- THEN la sección muestra las acciones Tomar foto y Elegir de galería

### Requirement: Selección múltiple desde galería
El sistema SHALL permitir seleccionar y acumular varias imágenes desde el dispositivo para cada destino de imágenes de forma independiente.

#### Scenario: Seleccionar varias imágenes para un daño
- GIVEN una tarjeta de daño visible
- WHEN el usuario elige varias imágenes desde galería
- THEN las imágenes se agregan únicamente a los archivos pendientes de ese daño

#### Scenario: Seleccionar imágenes en acciones sucesivas
- GIVEN que un destino ya tiene imágenes pendientes
- WHEN el usuario vuelve a elegir imágenes desde galería
- THEN las nuevas imágenes se agregan sin reemplazar las anteriores

### Requirement: Captura de múltiples fotografías
El sistema SHALL permitir iniciar la cámara, tomar una o más fotografías y acumularlas en el destino de imágenes que inició la captura.

#### Scenario: Tomar varias fotos para un daño
- GIVEN que el usuario inició la cámara desde una tarjeta de daño
- WHEN toma y confirma varias fotografías
- THEN todas las fotografías confirmadas quedan asociadas únicamente a ese daño

#### Scenario: Tomar fotos para Observaciones Adicionales
- GIVEN que el usuario inició la cámara desde Observaciones Adicionales
- WHEN toma y confirma una fotografía
- THEN la fotografía queda asociada a los archivos generales del inventario

#### Scenario: Cancelar una captura
- GIVEN que el usuario tomó una fotografía y aún no la confirmó
- WHEN cancela la fotografía
- THEN la fotografía no se agrega a los archivos pendientes
- AND la cámara queda disponible para tomar otra fotografía

### Requirement: Selección y control de cámara
El sistema SHALL listar las cámaras disponibles después de obtener permiso y SHALL permitir cambiar la cámara activa cuando exista más de una.

#### Scenario: Cámara posterior preferida
- GIVEN un dispositivo con cámara posterior disponible
- WHEN el usuario inicia la captura sin selección previa
- THEN el sistema intenta usar la cámara posterior

#### Scenario: Cambio de cámara
- GIVEN que existen varias cámaras disponibles
- WHEN el usuario selecciona otra cámara
- THEN el stream actual se detiene
- AND la vista previa se inicia con la cámara seleccionada

### Requirement: Control de linterna compatible
El sistema SHALL habilitar el control de linterna únicamente cuando la pista de video reporte soporte para `torch`.

#### Scenario: Linterna soportada
- GIVEN que la cámara activa soporta linterna
- WHEN el usuario pulsa el control de linterna
- THEN el sistema alterna el estado encendido o apagado

#### Scenario: Linterna no soportada
- GIVEN que la cámara activa no soporta linterna
- WHEN se muestra la interfaz de captura
- THEN el control de linterna aparece deshabilitado u oculto
- AND el usuario puede continuar tomando fotografías

### Requirement: Manejo de permisos y errores de cámara
El sistema SHALL informar de forma comprensible cuando no pueda acceder a la cámara y SHALL mantener disponible la opción Elegir de galería.

#### Scenario: Permiso de cámara rechazado
- GIVEN que el usuario eligió Tomar foto
- WHEN rechaza el permiso de cámara
- THEN el sistema muestra un mensaje de error
- AND no bloquea ni cierra el Inventario Visual
- AND Elegir de galería sigue disponible

#### Scenario: API de cámara no disponible
- GIVEN un navegador sin soporte para `navigator.mediaDevices.getUserMedia`
- WHEN el usuario intenta usar Tomar foto
- THEN el sistema muestra que la cámara no está disponible
- AND mantiene el flujo de galería operativo

### Requirement: Liberación de recursos de cámara
El sistema SHALL detener todas las pistas del stream de cámara cuando la captura deje de utilizarse.

#### Scenario: Cerrar la interfaz de cámara
- GIVEN una cámara activa
- WHEN el usuario cierra o cancela la interfaz de cámara
- THEN todas las pistas activas se detienen
- AND la linterna se apaga cuando estaba encendida

#### Scenario: Cerrar Inventario Visual
- GIVEN una cámara activa dentro del flujo de inventario
- WHEN el usuario cierra Inventario Visual
- THEN el stream de cámara se libera antes de cerrar el modal

## MODIFIED Requirements

### Requirement: Archivos pendientes por destino
El sistema SHALL mantener en un único conjunto por destino las imágenes elegidas desde galería y las fotos confirmadas desde cámara.

(Previously: los archivos pendientes se originaban únicamente mediante `p-fileUpload`.)

#### Scenario: Mezclar galería y cámara
- GIVEN un daño con imágenes seleccionadas desde galería
- WHEN el usuario agrega fotografías desde cámara al mismo daño
- THEN ambos tipos de archivo aparecen como pendientes del mismo daño
- AND se envían juntos al guardar

### Requirement: Guardado del inventario visual
El sistema SHALL enviar las fotos tomadas mediante el contrato multipart existente, sin requerir cambios en el backend.

(Previously: el contrato enviaba únicamente archivos seleccionados desde el dispositivo.)

#### Scenario: Guardar fotos de daños
- GIVEN fotografías pendientes asociadas a un daño
- WHEN el usuario guarda el inventario
- THEN cada foto se envía bajo `files[<iddanios_vehiculos>][]`

#### Scenario: Guardar fotos generales
- GIVEN fotografías pendientes en Observaciones Adicionales
- WHEN el usuario guarda el inventario
- THEN cada foto se envía bajo `filesMain[]`

### Requirement: Advertencia de cambios no guardados
El sistema SHALL considerar las fotografías tomadas como imágenes pendientes al evaluar el cierre del Inventario Visual.

(Previously: la advertencia consideraba los archivos seleccionados con el cargador existente.)

#### Scenario: Cerrar con fotos tomadas sin guardar
- GIVEN que existe al menos una fotografía confirmada pendiente
- WHEN el usuario intenta cerrar Inventario Visual
- THEN el sistema solicita confirmación antes de descartar los cambios
