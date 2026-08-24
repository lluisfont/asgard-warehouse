# Customs Case EDP Status Monitoring - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Dato / tabla | Uso observado | Campos candidatos |
| --- | --- | --- |
| `dav_casos` | Base de expedientes filtrados por cliente, estado, gestion y datos operativos. | `idcasos`, `carpeta`, `pedido`, `idcliente`, `idciudad`, `idproveedor`, `idaduana`, `idregimen`, `idclientelineas`, `anulado`, `gestion`, `nodui` |
| `dav_edp` | Historial de estados/observaciones EDP por caso. | `idedp`, `idcasos`, `fecha`, `idestadoedp`, `edp` |
| `dav_estadoedp` | Catalogo de estados EDP y relacion con etapa. | `idestadoedp`, `estadoedp`, `idetapaedp` |
| `dav_etapaedp` | Etapa agrupadora usada para filtros y bandeja. | `idetapaedp`, `etapaedp` |
| `dav_documentos` | Facturas/documentos comerciales, Parte de Recepcion y documentos de referencia. | `idcasos`, `idtipodocumento`, `numero`, `fecha` |
| `dav_intermediodocumento` | Documentos faltantes pendientes por caso. | `idcasos`, `idtipodocumento` |
| `dav_facturaplanilla` | Exclusion de casos sin EDP cuando ya existe factura activa. | `idcasos`, `idfacturaplanilla`, `idestadofactura` |

## Referencias SQL

- `.data_base/asgard.sql:5626`
- `.data_base/asgard.sql:5930`
- `.data_base/asgard.sql:6050`
