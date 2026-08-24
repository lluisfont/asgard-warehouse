# Customs Case EDP Status Monitoring - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Seguimiento EDP de expedientes aduaneros.

Objetivo de negocio candidato: permitir al cliente consultar el ultimo estado/etapa EDP de sus casos aduaneros, identificar casos aun abiertos, revisar documentos/facturas/parte de recepcion relacionados y consultar el historial completo de observaciones por expediente.

## Trigger

El proceso inicia cuando el usuario abre el reporte operativo `Estado de Pedidos`, selecciona filtros de etapa, ciudad, proveedor, aduana, regimen, linea, pedido, factura o existencia de parte de recepcion, y genera la consulta.

Evidencia:

- `index_archivos/operativos/edp.php`
- `index_archivos/operativos/edpquery.php`
- `index_archivos/operativos/edpdetalle.php`
- `index_archivos/operativos/edpdetallequery.php`
- `index_archivos/android/consultatodo.php`

## Actores

- Usuario cliente: consulta estados EDP de sus expedientes.
- ASGARD: calcula ultimo EDP por caso, etapa visible, documentos faltantes, facturas, parte de recepcion y detalle historico.
- Operacion aduanera: registra estados EDP en otros flujos; este dominio los explota para seguimiento.

## Alcance

Incluye:

- Filtro por etapas `dav_etapaedp`, excluyendo por defecto etapas observadas `4`, `7` y `8`.
- Seleccion de casos no anulados, con gestion posterior a 2016 y cliente de sesion.
- Ultimo EDP por caso desde `dav_edp` y etapa derivada por `dav_estadoedp.idetapaedp`.
- Exclusion funcional de casos facturados cuando no tienen EDP y exclusion de etapas observadas `8` y `11`.
- Identificacion de facturas/documentos comerciales por tipos `171`, `117`, `20` y `19`.
- Identificacion de Parte de Recepcion por documento tipo `71`.
- Identificacion de documentos faltantes desde `dav_intermediodocumento`.
- Consulta de detalle historico de EDP por caso.
- Consulta legacy Android/paginada de casos por mes/anio o pedido, mostrando ultimo EDP por caso.
- Registro de uso de reporte mediante `LogReportes.php`.

Fuera de alcance:

- Alta primaria de estados `dav_edp`, cubierta por flujos de aprobacion/documentacion donde se registra EDP.
- Catalogo funcional oficial de estados y etapas EDP.
- Reglas de SLA o vencimiento asociadas a cada etapa.

## Resultado Esperado

- El usuario obtiene una bandeja de casos con etapa EDP vigente y contexto documental.
- Puede abrir el detalle para ver la secuencia historica de fechas, estados y observaciones EDP del caso.
- El reporte queda registrado como uso operativo.

## Estado de Validacion

Reconstruccion candidata desde codigo y SQL. La revision humana se difiere hasta completar el baseline completo.
