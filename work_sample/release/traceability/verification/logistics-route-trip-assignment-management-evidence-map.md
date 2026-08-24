# Logistics Route Trip Assignment Management - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia por capacidad

| Capacidad | Evidencia | Inferencia |
| --- | --- | --- |
| Control de permiso y cierre | `index_archivos/logistica/componentes/embarques_rutas.php:1-19`, `index_archivos/logistica/ajax/listaRutas.php:12-63` | Rutas se gestionan solo con permiso y embarque abierto. |
| Listar rutas | `index_archivos/logistica/EmbarqueClass.php:672-701` | Solo rutas no eliminadas se devuelven a la UI. |
| Alta de ruta | `index_archivos/logistica/js/datosEmbarques.js:2696-2718`, `index_archivos/logistica/ajax/guardarRuta.php:12-33`, `index_archivos/logistica/EmbarqueClass.php:713-735` | Se persisten datos de ruta en `logis_embarquesrutas`. |
| Borrado de ruta | `index_archivos/logistica/js/datosEmbarques.js:3438-3464`, `index_archivos/logistica/ajax/eliminar-ruta.php`, `index_archivos/logistica/EmbarqueClass.php:936-948` | La ruta se elimina logicamente. |
| Asignar viaje | `index_archivos/logistica/ajax/asignar-viaje-embarque.php`, `index_archivos/logistica/ViajesClass.php:293-305` | Viaje TCK se vincula al embarque. |
| Retirar viaje | `index_archivos/logistica/js/datosEmbarques.js:3466-3476`, `index_archivos/logistica/ajax/eliminarviaje.php`, `index_archivos/logistica/EmbarqueClass.php:1611-1614` | Viaje se marca eliminado con usuario. |
| Schema rutas/viajes | `.data_base/asgard.sql:12376-12402`, `.data_base/asgard.sql:16140-16160` | Tablas soportan rutas de embarque y asignaciones TCK. |

## Brechas

- No se observa matriz completa de elegibilidad de viajes recuperables.
- No se observan validaciones de fechas, duplicados o consistencia cliente/operador en asignacion.
- Autorizacion server-side de los endpoints requiere revision.

