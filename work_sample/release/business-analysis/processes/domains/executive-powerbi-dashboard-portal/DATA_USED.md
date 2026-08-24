# Executive PowerBI Dashboard Portal - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato / Artefacto | Uso observado |
| --- | --- |
| URL Power BI `reportEmbed` | Carga dashboards embebidos con report id y tenant. |
| URL Power BI `view?r=` | Carga reportes publicados por identificador codificado. |
| `reportId` | Identifica reporte embebido. |
| `ctid` | Identifica tenant Microsoft/Power BI observado. |
| `config` | Configuracion embebida del cluster Power BI. |
| Sesion ASGARD | Contexto de usuario para navegar hasta la pantalla. |
| Permisos ASGARD | Control observado previo a mostrar dashboard. |

## Dashboards observados

| Grupo | Archivos |
| --- | --- |
| General | `dashboardaduanero.php`, `dashboardlogistico.php` |
| Cliente/operacion | `dashboardimcruz.php`, `dashboardimcruzdt.php`, `dashboardimcruzimplementaciondt.php`, `dashboardIASA.php`, `dashboardtigo.php`, `dashboardyanbal.php` |
| Aduanero cliente | `dashboardaduanerobelia.php`, `dashboardaduanerobioferm.php`, `dashboardaduanerocopijsud.php`, `dashboardaduaneroembol.php`, `dashboardaduanerofacrulesa.php`, `dashboardaduaneroimcruz.php`, `dashboardaduaneroprescott.php`, `dashboardaduanerovemassa.php`, `dashboardaduanerovenado.php` |
| CBN | `dashboardIndicadoresCBN.php`, `dashboardAdherenciaCBN.php`, `dashboardUsabilidadCBN.php`, `dashboardUsabilidadTrackingCBN.php`, `dashboardtimbradocbn.php` |
| Logistico cliente | `dashboardlogisticoembol.php` |

## Datos no observados en repo

- Dataset Power BI.
- Consultas Power Query / DAX.
- Refresh schedule.
- Workspace Power BI.
- Roles Row Level Security.
