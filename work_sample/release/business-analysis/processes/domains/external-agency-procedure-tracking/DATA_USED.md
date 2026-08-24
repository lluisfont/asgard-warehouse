# External Agency Procedure Tracking - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Descripcion candidata | Fuente |
| --- | --- | --- |
| `dav_tramites` | Tramite vinculado a caso/caso previo/solicitud. | `senasagquery.php`, `tramites.php` |
| `dav_entidademisora` | Catalogo de entidades emisoras. | `tramites.php`, schema |
| `dav_entidademisoratramite` | Tramites disponibles por entidad emisora. | `senasagquery.php`, `tramites_json.php` |
| `dav_tipotramite` | Tipo de tramite asociado. | `senasagquery.php`, `tramites_json.php` |
| `dav_etapasentidademisora` | Etapas configurables por entidad emisora. | `senasagquery.php` |
| `dav_etapastramites` | Fecha/estado de etapa para un tramite. | `senasagquery.php` |
| `dav_estadoetapastramite{id}` | Catalogo dinamico de estado por etapa. | `senasagquery.php` |
| `dav_documentos.idtipodocumento=19` | Facturas comerciales del tramite. | `senasagquery.php` |

