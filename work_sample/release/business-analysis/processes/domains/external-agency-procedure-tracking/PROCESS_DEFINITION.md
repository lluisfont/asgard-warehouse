# External Agency Procedure Tracking - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Dar seguimiento a gestiones/tramites ante entidades emisoras externas, especialmente SENASAG en el reporte observado, mostrando avance por etapas configurables, estados por etapa, proveedor, factura, carpeta y solicitud.

## Alcance observado

- Reporte de gestiones SENASAG con `identidademisora=2`.
- Filtros por proveedor, factura comercial, etapa y tramite.
- Consulta de tramites vinculados a caso o caso previo.
- Construccion dinamica de columnas de etapa desde `dav_etapasentidademisora`.
- Inclusion dinamica de columnas de estado cuando la etapa tiene estado.
- Filtrado por etapa actual: etapa informada y siguiente etapa aun no informada.
- Cruce con ciudad, tramite de entidad emisora, tipo de tramite, carpeta, solicitud, proveedor y facturas comerciales.
- Exportacion Excel y logging de reporte.
- Creacion/edicion/eliminacion de tramites en `index_archivos/tramites.php` observada como soporte transaccional relacionado.

## Fuera de alcance observado

- Confirmacion de entidad emisora distinta de SENASAG en este reporte.
- Reglas de SLA por etapa.
- Integracion con portales externos.
- Flujo completo de aprobacion documental.
- Costos asociados a SENASAG/SENAVEX, cubiertos en gastos operativos.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario operativo | Consulta avance de gestiones ante entidad externa. |
| ASGARD | Registra tramites, etapas y fechas/estados. |
| Entidad emisora externa | Organismo ante el cual se gestiona el tramite. |
| Proveedor/cliente | Contexto operativo del tramite. |

## Entradas

- Proveedor.
- Factura comercial.
- Etapa de entidad emisora.
- Tramite de entidad emisora.
- Cliente de sesion.
- Tramites vinculados a caso/caso previo.
- Catalogos de entidad emisora, tramites, tipos de tramite, etapas y estados.

## Salidas

- Reporte de tramites con columnas dinamicas por etapa.
- Estado de etapa cuando aplica.
- Ciudad, tramite, tipo de tramite, carpeta, solicitud, proveedor y factura.
- Excel del reporte.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/senasag.php` | UI de reporte de gestiones SENASAG. |
| `index_archivos/operativos/senasagquery.php` | Construye columnas dinamicas por etapa y estado. |
| `index_archivos/tramites.php` | Alta/edicion/eliminacion de tramites por caso previo. |
| `index_archivos/tramites_json.php` | Carga dependiente de tramite/tipo tramite por entidad. |
| `.data_base/asgard.sql` | Tablas `dav_tramites`, `dav_entidademisora`, `dav_entidademisoratramite`, `dav_etapasentidademisora`, `dav_etapastramites`. |

## Criterios de aceptacion candidatos

- El reporte SENASAG usa entidad emisora observada `2`.
- Los tramites se filtran por cliente de sesion desde caso o caso previo.
- Facturas comerciales se obtienen desde documentos tipo `19`.
- Las columnas del reporte dependen de etapas configuradas para la entidad emisora.
- Si una etapa tiene estado, se agrega columna de estado asociada.
- Un filtro de etapa selecciona tramites con esa etapa informada y la siguiente etapa no informada, salvo ultima etapa observada.

