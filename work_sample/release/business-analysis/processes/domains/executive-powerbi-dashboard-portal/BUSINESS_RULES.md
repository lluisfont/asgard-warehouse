# Executive PowerBI Dashboard Portal - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-EPDP-001 | Cada dashboard observado esta representado por una pantalla PHP especifica. | `dashboard*.php` |
| BR-EPDP-002 | Las pantallas cargan configuracion y permisos ASGARD antes de renderizar el iframe. | Includes `cnfdb105.php`, `permisos.php` |
| BR-EPDP-003 | Dashboards generales aduanero/logistico usan `reportEmbed` con `reportId`, `autoAuth` y `ctid`. | `dashboardaduanero.php`, `dashboardlogistico.php` |
| BR-EPDP-004 | Muchas variantes por cliente usan URL Power BI publicada `view?r=`. | `dashboardimcruz.php`, `dashboardIASA.php`, dashboards CBN |
| BR-EPDP-005 | El contenido funcional, filtros y calculos del dashboard quedan delegados a Power BI. | Iframes externos |
| BR-EPDP-006 | ASGARD actua como portal de acceso, no como motor analitico del dashboard embebido. | Ausencia de queries locales en `dashboard*.php` |
| BR-EPDP-007 | El acceso real debe considerar tanto permisos ASGARD como permisos/publicacion Power BI. | Includes de permisos y URLs Power BI |

## Riesgos de regla pendientes

- Confirmar matriz de permisos por dashboard/cliente.
- Confirmar si las URLs `view?r=` son publicas o restringidas por tenant.
- Confirmar propietario, refresh y linaje de datasets Power BI.
