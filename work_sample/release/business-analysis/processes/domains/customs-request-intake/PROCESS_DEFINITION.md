# Customs Request Intake - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Registro, validacion y envio de solicitud operativa de aduana/gestion/vehiculos.

Objetivo de negocio candidato: permitir que un cliente o usuario operativo cree solicitudes previas, las importe desde archivo, valide datos maestros, genere el caso previo y notifique a los participantes internos y externos.

## Trigger

El proceso puede iniciarse desde pantalla de nueva solicitud, carga de archivo o envio/finalizacion de solicitud.

Evidencia:

- `index_archivos/asesoria-gestion/views/nueva-solicitud.php:46-83`
- `index_archivos/controllers/SolicitudClass.php:14-48`
- `index_archivos/controllers/SolicitudClass.php:54-118`
- `index_archivos/nuevoinsert.php:59-86`
- `index_archivos/impresion.php`
- `index_archivos/versolicitud.php`
- `index_archivos/versolicituddetalle.php`
- `index_archivos/versolicituddetalleaprobado.php`
- `index_archivos/enviarsolicitud_ajax.php:6-114`
- `index_archivos/finsolicitud.php:12-92`

## Actores

- Cliente usuario: crea o envia solicitud.
- Coordinador / usuario interno: puede recibir notificaciones y atender tramite.
- Proveedor y transportista: se validan como datos maestros asociados al cliente.
- Sistema ASGARD: valida, crea caso previo, documentos iniciales, tramites y notificaciones.

## Resultado Esperado

- Solicitudes importadas quedan en `dav_solicitudesprevias` por cliente y usuario.
- Los valores textuales se convierten a ids de maestros.
- Los errores de validacion quedan registrados con mensaje por fila.
- Una solicitud valida crea `dav_casosprevios`.
- El alta directa heredada en `nuevoinsert.php` crea `dav_casosprevios` y documentos previos base cuando no hay `idExchange`.
- Se crean documentos previos y tramites iniciales cuando aplica.
- El envio marca fechas de finalizacion/aprobacion y dispara notificaciones.
- La pantalla heredada `versolicitud.php` permite borrado permanente de solicitud previa y datos hijos; se documenta como riesgo operativo pendiente de validacion.
- Las pantallas heredadas de detalle permiten consultar y actualizar datos operativos de `dav_casosprevios` segun tipo de solicitud.
- `impresion.php` genera la representacion imprimible/PDF de la solicitud.

## Estado

Reconstruccion candidata. La revision humana se difiere hasta completar el baseline completo.
