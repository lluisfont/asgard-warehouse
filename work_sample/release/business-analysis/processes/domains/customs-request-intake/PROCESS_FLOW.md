# Customs Request Intake - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Flujo Principal Candidato

1. El usuario abre o inicia una nueva solicitud.
2. Opcionalmente sube un archivo de solicitudes.
3. ASGARD guarda el archivo en `cargasolicitudes/{idusuario}`.
4. ASGARD carga filas a `dav_solicitudesprevias`.
5. ASGARD transforma fechas Excel si corresponde.
6. ASGARD valida tipo de solicitud, proveedor, transportista, ciudad, usuario, regimen, tipo de declaracion, aduana, modalidad, linea, declarante y opciones SI/NO.
7. ASGARD registra por fila los ids resueltos, contador de errores y mensaje.
8. Cuando una fila es valida, ASGARD inserta `dav_casosprevios`.
9. ASGARD crea documentos previos iniciales y tramites si la solicitud lo requiere.
10. El usuario envia/finaliza la solicitud.
11. ASGARD actualiza fechas y estado de documentos adicionales.
12. ASGARD envia correos y notificaciones push a destinatarios internos/externos.

## Variantes Observadas

- Tipo solicitud `0`: Despacho Aduanero.
- Tipo solicitud `1`: Gestion Soporte.
- Tipo solicitud `2`: Vehiculos.
- Vehiculos puede tener validaciones DAM antes de fecha de aprobacion.

## Evidencia

- `index_archivos/controllers/SolicitudClass.php:14-118`
- `index_archivos/controllers/SolicitudClass.php:122-300`
- `index_archivos/controllers/SolicitudClass.php:300-465`
- `index_archivos/controllers/SolicitudClass.php:482-509`
- `index_archivos/enviarsolicitud_ajax.php:46-114`
- `index_archivos/finsolicitud.php:12-92`
- `index_archivos/finsolicitud.php:375-515`
