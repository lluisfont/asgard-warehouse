# Form1 Modification Observation Tracking - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato / Tabla | Uso observado |
| --- | --- |
| `dav_form1` | Entidad central de modificacion/contravencion, vinculada a caso o carpeta AGES. |
| `dav_form1edp` | Historial de estados, fechas, observaciones y adjuntos del tramite Form1. |
| `dav_estadoform1edp` | Catalogo de estados del tramite Form1 EDP. |
| `dav_form1estado` | Estado general del Form1. |
| `dav_form1llamadas` | Historial de llamadas, comentarios, numero, estado, adjunto y usuario. |
| `dav_casossubcontravencion` | Detalle "donde dice/debe decir", causa de error y visibilidad cliente. |
| `dav_subcontravencion` | Catalogo funcional de subcontravenciones. |
| `dav_casos` | Caso aduanero, carpeta, DIM, pedido, proveedor y datos de documentos faltantes legacy. |
| `ages_asesoria_gestion_carpetas` | Origen alternativo para Form1 de asesoria/gestion. |
| `dav_faltadocumentos` | Documentos faltantes nuevos, responsabilidad y resolucion. |
| `dav_tipodocumento` | Nombre del documento faltante. |
| `dav_fechaspagodim` | Fecha de pago DIM usada en reporte de observadas. |

## Campos funcionales destacados

| Campo | Descripcion candidata |
| --- | --- |
| `idform1` | Identificador del tramite/modificacion Form1. |
| `idcasos` | Caso aduanero relacionado. |
| `idages` | Carpeta de asesoria/gestion relacionada. |
| `dondedice` | Valor o descripcion actual que debe corregirse. |
| `debedecir` | Valor o descripcion esperada tras la correccion. |
| `permisoclientes` | Indicador observado de visibilidad cliente de la subcontravencion. |
| `idestadoform1edp` | Estado puntual del avance Form1. |
| `form1edp` | Observacion/comentario asociado al estado Form1. |
| `nohojaruta` | Numero de hoja de ruta asociada al tramite. |
| `responsabilidad` | Responsable del documento faltante, usado para filtrar pendientes. |
| `resuelto` | Marca de resolucion del documento faltante. |
| `adjunto` | Archivo asociado al estado o llamada. |

## Contratos externos / filesystem

- Los adjuntos de llamadas se observan bajo `/datadrive1/modificaciones/form1llamadas/{idform1}/`.
- Las descargas se sirven mediante `download.php` con parametros de archivo/ruta.
