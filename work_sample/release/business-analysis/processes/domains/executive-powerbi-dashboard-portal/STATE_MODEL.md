# Executive PowerBI Dashboard Portal - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos de acceso

| Estado | Descripcion |
| --- | --- |
| Pantalla disponible en ASGARD | El usuario puede acceder a la ruta PHP desde menu/permisos. |
| Iframe cargado | ASGARD renderiza la URL Power BI. |
| Power BI autorizado | Power BI permite ver el reporte por publicacion, tenant o autenticacion. |
| Power BI no autorizado/no disponible | El iframe no muestra contenido por permisos, expiracion, red o publicacion. |

## Estados candidatos de tablero

| Estado | Descripcion |
| --- | --- |
| Publicado | URL `view?r=` disponible. |
| Embebido autenticado | URL `reportEmbed` con `autoAuth=true`. |
| Obsoleto pendiente | Dashboard existe en ASGARD, pero propietario/refresh no confirmado. |

## Pendiente de validacion

- Catalogo oficial de dashboards vigentes.
- Owner funcional y tecnico de cada dashboard.
- Estado de refresh y de permisos Power BI.
