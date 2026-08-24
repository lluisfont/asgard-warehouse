# Business entity relationships

Estado: candidate_reconstruction  
Confianza: media

| Relacion | Interpretacion candidata |
|---|---|
| Cliente - Usuario | Usuarios operan o consultan informacion segun cliente, rol y permisos |
| Cliente - Solicitud/Caso | Las operaciones se segmentan por cliente y pueden tener variantes propias |
| Caso - Documento | Un expediente agrega soportes cargados, generados, OCR o descargados |
| Caso - Estado | Estados y transiciones gobiernan avance y pendientes |
| Embarque - Mercancia/Partida | La operacion transporta o declara items con detalle aduanero/logistico |
| Proveedor/Agente - Caso | Terceros participan en tramite, coordinacion o documentacion |
| Caso - Costo/Factura | La operacion genera gastos, planillas, facturas o cobros |
| Reporte - Dominio | Reportes consumen datos transaccionales y catalogos para control |

## Observacion tecnica

Muchas relaciones pueden no estar declaradas como foreign keys; deben verificarse contra consultas SQL y datos reales.
