# Form1 Modification Observation Tracking - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Gestionar y consultar modificaciones, observaciones, documentos faltantes y seguimiento de llamadas asociados a Form1, casos aduaneros y carpetas de asesoria/gestion, permitiendo controlar que se conozca que se debe corregir, que documentos faltan, en que estado esta el tramite y que contactos fueron registrados.

## Alcance observado

- Reporte de modificaciones Form1 con documento modificado, aduana, DIM, chasis, placa, detalle de modificacion, estado del tramite, observaciones, hoja de ruta e historial de llamadas.
- Consolidacion de Form1 vinculados a `dav_casos` o `ages_asesoria_gestion_carpetas`.
- Construccion del texto funcional de modificacion con formato observado "dice/debe decir".
- Estados y fechas de avance desde `dav_form1edp` y catalogo `dav_estadoform1edp`.
- Conteo y acceso al historial de llamadas por Form1.
- Registro de llamadas con fecha, hora, numero, comentario, estado, usuario y adjunto.
- Reporte de carpetas observadas por documentos faltantes pendientes.
- Tratamiento diferenciado de documentos faltantes para casos nuevos desde `2018-12-11` y casos legacy.

## Fuera de alcance observado

- Aprobacion documental general, cubierta en `customs-document-approval`.
- Consulta integral de expediente/carpeta, cubierta en `operational-case-dossier-access`.
- No conformidades de mejora continua, cubiertas en `continuous-improvement-nonconformity`.
- Bitacora visual por chasis, candidata a dominio separado.
- Definicion oficial completa de catalogos de estados Form1 y estados de llamada.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario operativo | Consulta modificaciones, observaciones, documentos faltantes e historial de llamadas. |
| ASGARD | Consolida Form1, casos, carpetas AGES, estados, observaciones y llamadas. |
| Cliente/proveedor | Contexto del caso o carpeta afectada. |
| Usuario que registra llamada | Deja evidencia de contacto asociado al Form1. |

## Entradas

- Cliente de sesion.
- Fecha, filtros operativos y tipo de servicio.
- Form1 vinculado a caso aduanero o carpeta AGES.
- Subcontravenciones y campos `dondedice` / `debedecir`.
- Estados de tramite Form1.
- Observaciones y documentos faltantes.
- Llamadas, adjuntos y usuario registrador.

## Salidas

- Grilla de modificaciones Form1.
- Detalle funcional de modificacion visible al cliente cuando aplica.
- Dias antes de ingreso y dias de tramite calculados desde estados observados.
- Enlace a historial de llamadas y conteo de llamadas.
- Reporte de carpetas observadas con documento faltante.
- Exportacion de historial de llamadas.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/modificacionesquery.php` | Construye reporte de modificaciones Form1, estados, dias y llamadas. |
| `index_archivos/operativos/observadasquery.php` | Reporta carpetas con documentos faltantes pendientes. |
| `index_archivos/operativos/historial_llamadas.php` | Lista llamadas, usuario, estado y adjuntos por Form1. |
| `index_archivos/operativos/historial_llamadas_excel.php` | Exporta historial de llamadas filtrable por chasis. |
| `.data_base/asgard.sql` | Tablas `dav_form1`, `dav_form1edp`, `dav_form1llamadas`, `dav_casossubcontravencion`, `dav_faltadocumentos`, `dav_estadoform1edp`. |

## Criterios de aceptacion candidatos

- El reporte incluye Form1 activos con modificacion visible cuando `permisoclientes=1`.
- El texto de modificacion muestra la subcontravencion y los valores "dice" / "debe decir".
- El estado actual del tramite se obtiene desde el ultimo estado Form1 EDP.
- La fecha de observacion usa el estado observado `idestadoform1edp=1`.
- La fecha de ingreso usa el estado observado `idestadoform1edp=3`.
- La fecha de conclusion usa el estado observado `idestadoform1edp=7`.
- Las llamadas se cuentan por `idform1` y se consultan como historial asociado.
- Las carpetas observadas nuevas usan `dav_faltadocumentos` no resuelto y responsabilidad `0`.
- Las carpetas observadas legacy usan los campos historicos de falta de documento en `dav_casos`.
