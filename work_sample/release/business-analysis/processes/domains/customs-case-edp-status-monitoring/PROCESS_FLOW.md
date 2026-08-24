# Customs Case EDP Status Monitoring - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

1. El usuario abre `edp.php`.
2. ASGARD carga etapas EDP candidatas desde `dav_etapaedp`.
3. El usuario marca etapas y filtros operativos.
4. La pantalla incluye `edpquery.php`.
5. `edpquery.php` calcula tablas temporales de ultimo EDP, facturas, parte de recepcion, documentos faltantes y pesos.
6. La grilla muestra caso, pedido, proveedor, etapa, parte, documentos faltantes, factura, DIM, linea y pais destino.
7. El usuario puede abrir `edpdetalle.php` para un caso.
8. `edpdetallequery.php` devuelve la secuencia historica de fecha, estado/etapa y observacion EDP.
9. El uso del reporte se registra mediante `LogReportes.php`.
