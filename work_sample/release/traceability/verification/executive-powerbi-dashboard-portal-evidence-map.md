# executive-powerbi-dashboard-portal Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| ASGARD exposes dashboard pages as PHP containers. | `index_archivos/operativos/dashboard*.php` | High |
| General aduanero and logistico dashboards use `reportEmbed`. | `dashboardaduanero.php`, `dashboardlogistico.php` | High |
| Multiple customer dashboards use published Power BI `view?r=` URLs. | `dashboardimcruz.php`, `dashboardIASA.php`, CBN dashboards | High |
| Dashboard pages load ASGARD config and permissions before rendering iframe. | Includes in dashboard files | High |
| Analytical calculations and dataset refresh are external to this repo. | Dashboard files contain iframe only/no local query logic | High |
| Local detailed indicadores report exists as related but separate evidence. | `reporteindicadores.php`, `reporteindicadoresquery.php`, `indicadores.php` | Medium |

## Review Needed

- Confirm active dashboard inventory.
- Confirm permissions by dashboard and customer.
- Confirm Power BI workspace, owner, RLS and refresh schedule.
- Confirm whether public `view?r=` links are acceptable.
