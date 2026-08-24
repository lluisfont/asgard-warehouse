# Customs DAV Client Review Approval - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Permitir que el cliente revise las DAV/FDM asociadas a una carpeta logistica, registre aprobacion o rechazo con observaciones, y cierre la revision completa solo cuando todas las declaraciones han sido respondidas.

## Alcance observado

- Lista de DAV/FDM asociadas a un embarque logistico mediante la relacion embarque -> caso previo -> caso -> DAV.
- Visualizacion de referencia, proveedor, facturas, FOB, tipo de formulario, estado cliente y observaciones.
- Apertura del detalle de DAV/FDM para revision.
- Registro de decision cliente: aprobado o rechazado.
- Captura de observaciones del cliente, especialmente causa de rechazo.
- Bloqueo de acciones de aprobacion/rechazo cuando la carpeta ya tiene `finalizardav = 1`.
- Verificacion previa al cierre: todas las DAV de la carpeta deben estar aprobadas o rechazadas.
- Cierre masivo de revision por `idcasos`, marcando `finalizardav = 1`.
- Registro de seguimiento EDP de cierre de revision con estado observado `14`.
- Notificacion por correo al coordinador y copia al oficial con resultado por DAV.

## Fuera de alcance observado

- Creacion tecnica de la DAV/FDM y captura completa de valores declarativos.
- Aprobacion documental previa general.
- Catalogo oficial completo de estados de cliente.
- Reglas externas de revision aduanera, legal o fiscal posteriores al cierre cliente.
- Reglas de reapertura/correccion una vez finalizada la revision.

## Actores

| Actor | Rol observado |
| --- | --- |
| Cliente usuario | Revisa cada DAV/FDM y aprueba o rechaza con observaciones. |
| Coordinador | Recibe notificacion de cierre de revision. |
| Oficial | Recibe copia de la notificacion de cierre. |
| ASGARD | Consulta DAV/FDM, persiste estados cliente, marca cierre y registra EDP. |
| Servicio de correo | Envia el resumen de revision terminada. |

## Entradas

- `idembarque` logistico.
- `idcasos` de la carpeta asociada.
- `iddav` de la declaracion revisada.
- Observaciones cliente.
- Sesion de cliente usuario `idclienteusuarios`.

## Salidas

- `dav_dav.idestadocliente = 1` para aprobado.
- `dav_dav.idestadocliente = 2` para rechazado.
- `dav_dav.observacionescliente` actualizado.
- `dav_dav.finalizardav = 1` para todas las DAV del caso cuando se cierra revision.
- Filas de seguimiento en `dav_edp` mediante `GlobalClass::registrarEDP` con estado observado `14`.
- Correo `NOTIFICACION - DATOS DE DAV REVISADOS` a coordinador y oficial.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/frames/items.php` | Integra lista, detalle y seguimiento DAV dentro del frame logistico de items. |
| `index_archivos/logistica/componentes/demis/embarques_lista_dav.php:11-64` | Presenta DAV/FDM por embarque y muestra accion de finalizar si `finalizardav != 1`. |
| `index_archivos/logistica/componentes/demis/davdetalle.php:881-900` | Captura observaciones y botones Aprobar/Rechazar mientras la revision no este finalizada. |
| `index_archivos/logistica/js/datosDemis.js:1-113` | Orquesta confirmaciones UI y llamadas AJAX para aprobar, rechazar y finalizar revision. |
| `index_archivos/logistica/ajax/demis/aceptarDemis.php:14-18` | Envia `idestadocliente = 1` a `DemisClass::cambiarEstadoDav`. |
| `index_archivos/logistica/ajax/demis/rechazarDemis.php:14-18` | Envia `idestadocliente = 2` a `DemisClass::cambiarEstadoDav`. |
| `index_archivos/logistica/ajax/demis/verificarCarpeta.php:12-31` | Impide finalizar si existe alguna DAV sin aprobar/rechazar. |
| `index_archivos/logistica/ajax/demis/finalizarRevision.php:18-74` | Marca cierre, registra EDP y envia correo de resultado. |
| `index_archivos/logistica/DemisClass.php:13-39` | Obtiene DAV/FDM por embarque y estado cliente. |
| `index_archivos/logistica/DemisClass.php:405-465` | Actualiza estado cliente, cierre y consulta resumen por carpeta. |

## Criterios de aceptacion candidatos

- Solo las DAV/FDM con `idestadocliente != 0` se presentan en la lista de revision cliente.
- Una DAV no finalizada debe permitir aprobar o rechazar desde el detalle.
- Aprobar debe persistir estado cliente `1` y observaciones.
- Rechazar debe persistir estado cliente `2` y observaciones.
- La revision de carpeta no debe cerrarse si queda alguna DAV con estado distinto de `1` y `2`.
- Al cerrar, todas las DAV del `idcasos` deben marcar `finalizardav = 1`.
- Cada DAV incluida en el cierre debe registrar una entrada de seguimiento EDP con el resultado.
- El cierre debe notificar por correo al coordinador y al oficial.

