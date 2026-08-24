# Process dependency map

Estado: candidate_reconstruction  
Confianza: media

| Proceso | Depende de | Alimenta |
|---|---|---|
| Identidad/acceso | Usuarios, roles, clientes, permisos | Todas las pantallas y reportes |
| Intake de solicitudes | Cliente, catalogos, datos minimos | Casos, documentos, asignacion |
| Gestion aduanera | Solicitud, documentos, agentes, DAV/DAM/DEX | Estados, liquidaciones, reportes |
| Gestion logistica | Cliente, carga, rutas, proveedores, puertos | Tracking, costos, documentos |
| OCR/documentos | Casos, tipos documentales, archivos | Validaciones, aprobaciones, reportes |
| Facturacion | Casos/costos/servicios | Planillas, facturas, aging, pagos |
| Reporteria | Datos transaccionales y permisos | Dashboards, KPI, decisiones |
| Master data | Administracion funcional | Validaciones y variantes de proceso |

## Riesgo

Las dependencias estan acopladas por consultas SQL y no siempre por contratos explicitos.
