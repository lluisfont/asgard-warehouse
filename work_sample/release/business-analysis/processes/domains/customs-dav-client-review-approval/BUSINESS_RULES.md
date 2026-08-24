# Customs DAV Client Review Approval - Business Rules

## Reglas inferidas

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-CDCRA-001 | Solo se listan para revision cliente las DAV/FDM con `idestadocliente != 0`. | `embarques_lista_dav.php:22-24` | INFERRED |
| BR-CDCRA-002 | Una DAV/FDM con `finalizardav = 1` no muestra botones de aprobar/rechazar. | `davdetalle.php:885-890` | INFERRED |
| BR-CDCRA-003 | La decision Aprobar se persiste como `idestadocliente = 1`. | `aceptarDemis.php:14-18`, `DemisClass.php:405-407` | OBSERVED |
| BR-CDCRA-004 | La decision Rechazar se persiste como `idestadocliente = 2`. | `rechazarDemis.php:14-18`, `DemisClass.php:405-407` | OBSERVED |
| BR-CDCRA-005 | Las observaciones del cliente se guardan junto con la decision. | `DemisClass.php:405-407` | OBSERVED |
| BR-CDCRA-006 | El cierre de revision se permite solo si todas las DAV de la carpeta tienen estado `1` o `2`. | `verificarCarpeta.php:15-30` | OBSERVED |
| BR-CDCRA-007 | El cierre marca `finalizardav = 1` para todas las DAV del `idcasos`. | `finalizarRevision.php:21-24`, `DemisClass.php:420-422` | OBSERVED |
| BR-CDCRA-008 | Cada DAV cerrada genera seguimiento EDP con estado observado `14`. | `finalizarRevision.php:45-51` | OBSERVED |
| BR-CDCRA-009 | La notificacion de cierre se envia al coordinador y copia al oficial del caso. | `finalizarRevision.php:32-64`, `DemisClass.php:450-453` | OBSERVED |
| BR-CDCRA-010 | Los estados se muestran como Para Revision, APROBADO, RECHAZADO o guion segun `idestadocliente`. | `DemisClass.php:24-27`, `DemisClass.php:445-447` | INFERRED |

## Riesgos y reglas pendientes

- No se observa validacion de obligatoriedad de observaciones para rechazo, aunque la UI etiqueta el campo como causa de rechazo.
- No se observa control de reapertura despues de `finalizardav = 1`.
- Los endpoints reciben ids desde POST y dependen de permisos/sesion incluidos externamente; la autorizacion efectiva debe validarse en revision tecnica.
- No se observa actualizacion de `fecharevisioncliente` ni `idusuariorevision`, aunque los campos son consultados.

