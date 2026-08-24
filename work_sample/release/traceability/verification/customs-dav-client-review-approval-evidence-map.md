# Customs DAV Client Review Approval - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia por capacidad

| Capacidad | Evidencia | Inferencia |
| --- | --- | --- |
| Listar DAV/FDM por embarque | `index_archivos/logistica/DemisClass.php:13-39`, `index_archivos/logistica/componentes/demis/embarques_lista_dav.php:11-64` | El cliente revisa declaraciones enlazadas al embarque logistico. |
| Ver detalle revisable | `index_archivos/logistica/componentes/demis/davdetalle.php:881-900` | El detalle captura observaciones y expone acciones cuando no esta finalizado. |
| Aprobar DAV | `index_archivos/logistica/ajax/demis/aceptarDemis.php:14-18`, `index_archivos/logistica/DemisClass.php:405-407` | Estado cliente `1` representa aprobado. |
| Rechazar DAV | `index_archivos/logistica/ajax/demis/rechazarDemis.php:14-18`, `index_archivos/logistica/DemisClass.php:405-407` | Estado cliente `2` representa rechazado. |
| Bloquear cierre pendiente | `index_archivos/logistica/ajax/demis/verificarCarpeta.php:12-31` | El cierre requiere todas las DAV decididas. |
| Cerrar revision | `index_archivos/logistica/ajax/demis/finalizarRevision.php:18-24`, `index_archivos/logistica/DemisClass.php:420-422` | `finalizardav = 1` cierra la revision por caso. |
| Registrar seguimiento | `index_archivos/logistica/ajax/demis/finalizarRevision.php:45-51` | Cada DAV cerrada genera evento EDP con estado observado `14`. |
| Notificar resultado | `index_archivos/logistica/ajax/demis/finalizarRevision.php:32-64`, `index_archivos/logistica/DemisClass.php:450-453` | Coordinador y oficial reciben resumen de resultados. |

## Brechas

- Catalogo oficial de `idestadocliente` pendiente.
- Reglas de obligatoriedad de observaciones y reapertura no observadas.
- Validacion de autorizacion efectiva depende de includes comunes y requiere revision posterior.

