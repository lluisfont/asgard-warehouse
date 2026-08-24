# Export/import catalog

Estado: inferred_from_static_evidence  
Confianza: media-alta

| Flujo | Tipo | Evidencia | Datos principales |
|---|---|---|---|
| Diccionarios/reportes Excel | Export | `VehiculosExcel.php`, reporteria, archivos `.xlsx` generados | Vehiculos, filtros operativos, resultados tabulares |
| Dashboards y reportes | Export | `DashboardGenerico.php`, Power BI, reportes cliente | Indicadores agregados, estados y comparativas |
| Planillas/facturas | Export | Generadores contables y documentales | Facturacion, despachos, valores, impuestos |
| Descarga documental | Export | `download.php`, modulos documentales | PDFs, ZIP/RAR, imagenes, adjuntos |
| Carga OCR/documental | Import | OCR y formularios de documentos | Soportes aduaneros, facturas, guias, certificados |
| Carga/validacion de vehiculos | Import | `VehiculosExcel.php` | Filas Excel, identificadores, validaciones por cliente |
| Solicitudes/casos | Import | Formularios de gestion aduanera/logistica | Datos de embarque, cliente, proveedor, carga y estados |

## Riesgos de compatibilidad

- Formatos Excel y columnas esperadas pueden estar acoplados a usuarios o clientes concretos.
- Las descargas pueden depender de rutas fisicas historicas.
- Las importaciones pueden aplicar validaciones distribuidas entre JavaScript, PHP y SQL.
- Los reportes pueden incluir reglas de negocio no documentadas fuera de consultas SQL.
