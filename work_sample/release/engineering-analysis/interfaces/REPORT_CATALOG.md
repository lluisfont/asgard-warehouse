# Report catalog

Estado: inferred_from_static_evidence  
Confianza: media

| Familia | Evidencia | Salida |
|---|---|---|
| Dashboard generico | `ajax/DashboardGenerico.php` | Indicadores HTML/JSON para pantallas ejecutivas |
| Power BI | Portal/dashboard ejecutivo | Embebidos o enlaces de inteligencia de negocio |
| Reportes cliente | `dav_clientereportescliente` y reporteria cliente | Consultas filtradas por cliente/permiso |
| Operativos logisticos | Logistica, despachos, seguimiento de embarques | Tablas, Excel, PDF o vistas web |
| Exportaciones/transporte | `operativos/exportaciones` | Comparativas documentales y seguimiento |
| Facturacion/planillas | Generadores de documentos contables | PDF/Excel/documentos descargables |
| Vehiculos | `VehiculosExcel.php` | Excel/importaciones y validaciones |
| Warehouse/inventario | Evidencias de modulo operativo | Reportes de stock, movimientos o estados candidatos |

## Dependencias

- Filtros de sesion, cliente y rol.
- Catalogos SQL y tablas temporales/vistas.
- Formatos historicos esperados por usuarios.
- Datos derivados que pueden no existir como entidad persistente unica.
