# Tenancy Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Modelo observado

- Tenant principal: `idcliente`.
- Usuario tenant: `idclienteusuarios`.
- Lineas/divisiones/proveedores/agentes amplian el alcance funcional.
- La mayoria de consultas filtra manualmente por `$_SESSION["idcliente"]`.
- Algunos contextos externos usan `ASGARD_TYPE` y proveedor/transportista.
- Filesystem suele segmentar por cliente, solicitud, caso o documento, pero con rutas construidas en PHP.

## Riesgos

| Riesgo | Motivo |
| --- | --- |
| Omision de filtro tenant | No hay capa central obligatoria por recurso. |
| IDOR | Muchos endpoints reciben IDs de caso/documento/embarque. |
| Descarga transversal | `download.php` permite subruta controlada. |
| Reportes agregados | Temporales/procedimientos pueden mezclar datos si faltan filtros. |
| Terceros/proveedores | Contextos multi-rol requieren matriz de recurso/destinatario. |

## Controles candidatos

- Guard central `assertResourceBelongsToTenant`.
- Repositorios/consultas parametrizadas por tenant.
- Paths canonicos por recurso y tenant.
- Tests IDOR para casos/documentos/embarques/facturas.
