# Customs DAV Client Review Approval - State Model

## Estados candidatos de DAV/FDM

| Estado | Valor observado | Significado inferido | Entrada | Salida |
| --- | --- | --- | --- | --- |
| No visible / no iniciado | `0` o nulo | No aparece en la lista de revision cliente inspeccionada. | Creacion externa de DAV/FDM. | Cambio externo a estado visible. |
| Para Revision | `9` | DAV/FDM disponible para decision cliente. | Flujo externo prepara declaracion. | Aprobar o rechazar. |
| Aprobado | `1` | Cliente acepta la DAV/FDM. | `aceptarDemis.php`. | Cierre de carpeta si todas estan decididas. |
| Rechazado | `2` | Cliente rechaza la DAV/FDM. | `rechazarDemis.php`. | Cierre de carpeta si todas estan decididas. |
| Revision Finalizada | `finalizardav = 1` | La carpeta DAV/FDM queda cerrada para acciones cliente. | `finalizarRevision.php`. | Reapertura no observada. |

## Transiciones observadas

| Transicion | Condicion | Persistencia | Evidencia |
| --- | --- | --- | --- |
| Para Revision -> Aprobado | Usuario confirma aprobar. | `idestadocliente = 1`, observaciones. | `aceptarDemis.php`, `cambiarEstadoDav` |
| Para Revision -> Rechazado | Usuario confirma rechazar. | `idestadocliente = 2`, observaciones. | `rechazarDemis.php`, `cambiarEstadoDav` |
| Decididas -> Revision Finalizada | Todas las DAV del caso tienen estado `1` o `2`. | `finalizardav = 1` por `idcasos`. | `verificarCarpeta.php`, `finalizarRevision.php` |
| Revision Finalizada -> Bloqueo UI | `finalizardav = 1`. | No hay cambio; se ocultan botones. | `davdetalle.php`, `embarques_lista_dav.php` |

## Estados pendientes de validar

- Valor oficial de `idestadocliente = 9`.
- Existencia de estados adicionales no mostrados por la expresion `if`.
- Politica de cambio entre Aprobado y Rechazado antes del cierre.
- Politica de reapertura posterior al cierre.

