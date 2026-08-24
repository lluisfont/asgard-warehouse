# Form1 Modification Observation Tracking - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados Form1 EDP observados

| Estado observado | Interpretacion candidata | Evidencia |
| --- | --- | --- |
| `idestadoform1edp=1` | Primera observacion / fecha base de observacion. | `modificacionesquery.php` usa este estado para `tmp_observacion`. |
| `idestadoform1edp=3` | Ingreso del tramite o ingreso posterior a observacion. | `modificacionesquery.php` usa este estado para `tmp_ingreso`. |
| `idestadoform1edp=7` | Conclusion del tramite. | `modificacionesquery.php` usa este estado para `tmp_concluido`. |
| Ultimo estado por `ultimoidform1edp(idform1)` | Estado actual mostrado en grilla. | `modificacionesquery.php` crea `tmp_ultimoestado`. |

## Estados de documento faltante

| Condicion | Interpretacion candidata |
| --- | --- |
| `responsabilidad=0` y `resuelto=0` | Documento faltante pendiente bajo responsabilidad observada del cliente/proceso. |
| Campos legacy `faltadocumento` / `faltadocumentosolucion` | Observacion historica de documento faltante antes del cambio de modelo. |

## Estados de llamada

El historial almacena `idestadoform1llamadas`, pero el catalogo y sus transiciones no fueron reconstruidos en este bloque. Debe validarse antes de formalizar estados canonicos.

## Transiciones candidatas

1. Form1 observado.
2. Form1 ingresado.
3. Form1 en tramite con comentarios/llamadas.
4. Form1 concluido.

## Pendiente de validacion

- Catalogo oficial de `dav_estadoform1edp`.
- Catalogo oficial de estados de llamadas.
- Relacion entre estado general `dav_form1estado` y estados EDP.
- Significado exacto de responsabilidad en documentos faltantes.
