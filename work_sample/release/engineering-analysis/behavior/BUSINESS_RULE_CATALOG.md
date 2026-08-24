# Business Rule Catalog

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

Las reglas detalladas viven en `BUSINESS_RULES.md` de cada uno de los 70 dominios. Este catalogo consolida familias transversales.

| Familia | Regla candidata transversal |
| --- | --- |
| Tenant | Las consultas y mutaciones deben estar limitadas por `idcliente`/usuario/proveedor segun contexto. |
| Permisos | Acciones de alta/edicion requieren `dav_clienteusuariospermisos.escritura` cuando existe gate por reporte. |
| Estados EDP | El estado vigente suele derivarse del ultimo EDP por fecha/orden/id. |
| Cierre/finalizacion | Embarques, DAV/FDM y solicitudes bloquean altas/ediciones al estar finalizados. |
| Documentos | La existencia/recepcion/aprobacion documental habilita avances y reportes. |
| OCR/Excel | Datos extraidos son candidatos y requieren validacion funcional antes de persistir o avanzar. |
| Facturacion | Planillas/facturas dependen de dosificacion, estado factura, pagos y documentos. |
| Vehiculos | Chasis, factura, partida/BL, catalogos y AP/DAM controlan continuidad. |
