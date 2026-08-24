# State Model

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| State ID | Constant | Meaning Candidate | Evidence |
| --- | --- | --- | --- |
| 0 | `ELIMINADO` | Caso eliminado logicamente. | `js/constantes.js` |
| 1 | `GUARDADO` | Caso guardado como borrador o pendiente de envio. | `js/constantes.js`, `views/pendientes-envio.php` |
| 2 | `ENVIADO` | Hallazgo enviado y disponible para apertura/asignacion. | `tbl-hallazgos.js` |
| 3 | `ANALISIS` | Caso asignado para analisis causal. | `tbl-analisis.js`, `views/analisis.php` |
| 4 | `VERIFICAR` | Acciones correctivas listas para verificacion. | `tbl-verificar.js`, `views/verificacion.php` |
| 5 | `VERIFICADO` | Verificacion completada, pendiente de cierre. | `tbl-cerrar.js` |
| 6 | `CERRADO` | Caso cerrado con resultado de verificacion. | `views/cerrar-caso.php`, `views/cerrados.php` |
| 7 | `REABIERTO` | Caso reabierto o relacionado con nuevo ciclo. | `js/constantes.js`, `views/re-apertura.php` |
| 8 | `POSTERGADO` | Asignacion/atencion postergada con justificacion. | `js/constantes.js`, `asignacion-analista.js` |

## Attention Status

| Display State | Rule Candidate | Evidence |
| --- | --- | --- |
| Pendiente | Fecha de plazo posterior a fecha actual. | `tbl-hallazgos.js:85-103`, `tbl-analisis.js:76-93` |
| Por Vencer | Fecha de plazo coincide con umbral de un dia. | `tbl-hallazgos.js:85-103`, `tbl-verificar.js:95-113` |
| Vencido | Fecha de plazo vencida. | `tbl-hallazgos.js:85-103`, `tbl-cerrar.js:92-109` |

## Validation Required

- Confirmar si `POSTERGADO` cambia el estado principal o solo registra campos de postergacion.
- Confirmar condiciones exactas para pasar de `VERIFICAR` a `VERIFICADO`.
- Confirmar si reapertura crea nuevo caso, actualiza el anterior o ambas cosas.
