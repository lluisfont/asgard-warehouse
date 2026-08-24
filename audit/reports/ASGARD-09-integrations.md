# ASGARD-09 - Integraciones externas e internas

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- Se registraron `1058` evidencias de integracion o acceso externo.
- Integraciones candidatas: Azure Blob, SendGrid, Freshchat/Freshservice, OVP/SOAP, filesystem local, cURL/http y API interna Asgard/Atlantes via constantes.
- La configuracion sensible se maneja por constantes de entorno fuera del repo, aunque existe un archivo local ignorado con valores reales que debe sanearse.

## Distribucion de evidencias

| Item | Count |
| --- | --- |
| soap_ovp | 781 |
| filesystem | 117 |
| curl | 68 |
| azure_blob | 47 |
| freshchat | 29 |
| mail | 11 |
| sendgrid | 5 |

## Evidencia

- `audit/evidence/integration_catalog.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: contratos tecnicos identificados; faltan contratos operativos de retry, timeout, SLAs y owners.
