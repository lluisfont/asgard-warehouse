# Business domain map

Estado: candidate_reconstruction  
Confianza: media

## Dominios macro

| Dominio macro | Subdominios candidatos |
|---|---|
| Aduanas | Solicitudes, DAV, DAM/DEX, aprobacion documental, garantias, liquidaciones, agentes y partidas |
| Logistica | Cotizacion, embarques, tracking, rutas, costos, documentos, hitos y finalizacion |
| Vehiculos | Importacion, VIN/chasis, facturas, SOAT, deposito transitorio, Excel y control de inventario |
| Documentos y OCR | Captura, validacion, comparacion, paquetes documentales, descargas y procesamiento externo |
| Facturacion y contabilidad | Planillas, facturas, pagos, aging, gastos, ledger y conciliaciones |
| Reporteria | Dashboards ejecutivos, Power BI, reportes operativos, KPI y exportaciones |
| Seguridad/usuarios | Login, 2FA, roles, permisos, historiales y accesos de terceros |
| Configuracion/master data | Clientes, proveedores, contactos, catalogos, parametros, paises, puertos y tablas maestras |

## Relacion principal

El flujo de negocio suele avanzar desde solicitud/intake hacia gestion aduanera o logistica, incorporando documentos, estados, costos, aprobaciones y reportes. Las variantes por cliente y los catalogos parametrizan buena parte de la experiencia.
