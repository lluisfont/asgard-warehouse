# External Agency Procedure Tracking - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Modelo observado

El estado del tramite se reconstruye como avance por etapas. Cada etapa tiene una fecha opcional en `dav_etapastramites`; algunas etapas tienen un estado adicional resuelto desde una tabla de catalogo especifica por etapa.

| Estado candidato | Condicion observada | Evidencia |
| --- | --- | --- |
| `Tramite sin etapa` | No hay fecha para primera etapa reportable. | `senasagquery.php` |
| `Tramite en etapa N` | Fecha de etapa N informada y etapa siguiente no informada. | `senasagquery.php` |
| `Tramite con estado de etapa` | Etapa con `tieneestado=1` y `idestado` resoluble. | `senasagquery.php` |
| `Tramite completo candidato` | Ultima etapa informada o no hay siguiente etapa segun catalogo. | Inferido desde filtro de etapa |

## Pendiente de validacion

- Confirmar transiciones oficiales.
- Confirmar si el orden real debe basarse en `orden`, no en `idetapa+1`.
- Confirmar estados finales por tipo de tramite.

