# Unresolved Claims

Estado: IN_PROGRESS
Idioma: Spanish

## Resumen

Este baseline contiene inferencias utiles pero no confirmadas. Ninguna afirmacion de negocio se promueve a confirmada hasta revision humana o verificacion independiente.

| Tipo | Cantidad |
| --- | ---: |
| Findings registrados | 318 |
| Preguntas abiertas | 310 |
| Asunciones registradas | 208 |
| Bloqueos activos | 0 |

## Claims no resueltos por categoria

| Categoria | Ejemplos | Registro fuente |
| --- | --- | --- |
| Estados y catalogos | Significado oficial de estados EDP, DAV/FDM, solicitudes, AP, planillas y tracking. | `OPEN_QUESTIONS.md`, `ASSUMPTION_REGISTER.md` |
| Reglas por cliente | Condiciones especiales observadas para clientes `417`, `429`, `755`, `775`, `802` y variantes Alicorp/CBN/Imcruz. | `FINDINGS_REGISTER.md`, dominios candidatos |
| Seguridad y permisos | Autoridad real de permisos `idreportescliente`, validaciones server-side y controles de canales realtime. | `identity-access`, `realtime-notification-center` |
| Datos maestros | Catalogos de mercancia, agentes, gestores, tramites, tipos documentales y parametros DAV/DAM. | `DATA_USED.md` por dominio |
| Integraciones externas | Power BI, Pusher, Freshservice, OCR, ASESORIA_GESTION_API, procesos externos/robots no incluidos. | `GRAPHIFY_IMPORT.md`, dominios de integracion |
| Tablas SQL-only | Familias `con_*`, `serv_*`, `cn_*`, `bot_*` sin PHP funcional observado. | `DATABASE_TABLE_COVERAGE_AUDIT.md` |

## Politica de uso

- Tratar todos los dominios como `INFERRED_DRAFT_REVIEW_REQUIRED`.
- Usar los procesos, reglas y datos como baseline de discusion tecnica/funcional, no como especificacion firmada.
- Mantener claims sin evidencia directa en registros de asunciones o preguntas abiertas.
- Resolver primero claims que afecten seguridad, dinero, aduanas/impuestos, cierre operativo o integraciones externas.
