# Customs Case EDP Status Monitoring - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Estado candidato | Descripcion observada | Evidencia |
| --- | --- | --- |
| Sin EDP / etapa 12 candidata | Caso sin ultimo EDP; solo se muestra si no tiene factura-planilla activa. | `edpquery.php` |
| En etapa EDP activa | Caso con ultimo `dav_edp` y etapa derivada por `dav_estadoedp`. | `edpquery.php` |
| Con Parte de Recepcion | Caso con documento tipo `71` asociado. | `edpquery.php`, `edpdetalle.php` |
| Con documentos faltantes | Caso con filas en `dav_intermediodocumento`. | `edpquery.php` |
| Historial consultado | Usuario abre detalle y ve todos los EDP del caso. | `edpdetalle.php`, `edpdetallequery.php` |

Nota: los significados oficiales de cada `idestadoedp` e `idetapaedp` deben validarse con negocio.
