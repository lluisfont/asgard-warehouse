# Executive PowerBI Dashboard Portal - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Dar acceso desde ASGARD a dashboards ejecutivos y tableros Power BI por cliente, tema u operacion, permitiendo que usuarios autorizados consulten indicadores aduaneros, logisticos, usabilidad, adherencia, timbrado e indicadores especificos sin salir del portal operativo.

## Alcance observado

- Pantallas `dashboard*.php` en `index_archivos/operativos`.
- Dashboard generico local con detalles por etapa EDP/cobranzas.
- Inclusion de configuracion, permisos y menu ASGARD antes de mostrar el tablero.
- Dashboards generales aduanero y logistico embebidos con `reportEmbed`.
- Dashboards publicados por URL `view?r=` para clientes o tableros especificos.
- Variantes por cliente/tema: Imcruz, CBN, IASA, Tigo, Yanbal, Embol, Belia, Bioferm, Copijsud, Facrulesa, Prescott, Vemassa, Venado.
- Dashboards CBN de indicadores, adherencia, usabilidad, tracking y timbrado.
- Publicacion visual via iframe y pantalla ASGARD como contenedor.
- Tablas `pbi_*` y vistas/materializaciones SQL usadas como soporte de reporting/BI cuando aplican.

## Fuera de alcance observado

- Modelo semantico, dataset y refresh de Power BI.
- Control de permisos dentro de Microsoft Power BI.
- Definicion funcional completa de cada indicador mostrado dentro del dashboard embebido.
- Reporte local detallado de indicadores/KPI, cubierto principalmente en dominios de KPI/reporting.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario ejecutivo/operativo | Consulta dashboard embebido desde ASGARD. |
| ASGARD | Controla acceso a la pantalla y renderiza el iframe. |
| Power BI | Presenta reportes y tableros embebidos. |
| Cliente/operacion | Define contexto del tablero publicado. |

## Entradas

- Sesion ASGARD.
- Permisos de pantalla/menu.
- URL de Power BI embebida.
- Report id, tenant id y configuracion de cluster cuando se usa `reportEmbed`.
- Token/autenticacion Power BI cuando aplica fuera del repo.

## Salidas

- Dashboard Power BI visible dentro de ASGARD.
- Vista de indicadores ejecutivos por cliente/tema.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/dashboardaduanero.php` | Dashboard aduanero general con `reportEmbed`. |
| `index_archivos/operativos/dashboardlogistico.php` | Dashboard logistico general con `reportEmbed`. |
| `index_archivos/operativos/dashboardyanbal.php` | Dashboard cliente con `reportEmbed`. |
| `index_archivos/operativos/dashboard*.php` | 23 pantallas dashboard embebidas. |
| `index_archivos/DashboardGenerico.php` | Dashboard generico local con filtros y modal de detalle via `ajax/DashboardGenerico.php`. |
| `index_archivos/ajax/DashboardGenerico.php` | Endpoint de datos del dashboard generico: agrupa casos por ultima etapa EDP y agrega planillas por pagar via procedimiento `cobros`. |
| `index_archivos/operativos/dashboardIndicadoresCBN.php` | Dashboard de indicadores CBN. |
| `index_archivos/operativos/dashboardUsabilidadCBN.php` | Dashboard de usabilidad CBN. |
| `index_archivos/operativos/dashboardAdherenciaCBN.php` | Dashboard de adherencia CBN. |
| `index_archivos/operativos/reporteindicadores.php` | Reporte local relacionado de indicadores detallados. |

## Criterios de aceptacion candidatos

- La pantalla carga configuracion y permisos ASGARD antes de mostrar el iframe.
- El dashboard se muestra en el contexto de navegacion ASGARD.
- Las URLs Power BI publicadas o embebidas corresponden al cliente/tema de la pantalla.
- La pantalla permite visualizacion a pantalla completa cuando el iframe lo soporta.
- La autorizacion efectiva combina permisos ASGARD y permisos/publicacion Power BI.
