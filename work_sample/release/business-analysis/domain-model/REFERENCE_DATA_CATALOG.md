# Reference data catalog

Estado: candidate_reconstruction  
Confianza: media

| Reference data | Ejemplos candidatos |
|---|---|
| Estados | Estados de caso, documento, solicitud, DAV, viaje, factura |
| Tipos documentales | BL, factura, SOAT, DAM/DEX, soportes, paquetes |
| Tipos de solicitud | Gestion aduanera, logistica, servicios adicionales |
| Tipos de contacto | Cliente, proveedor, agente, tercero |
| Tipos de carga/mercancia | Catalogos logisticos y aduaneros |
| Modalidad/transporte | Ruta, viaje, contenedor, puerto/aeropuerto |
| Codigos cliente/reporte | Reportes habilitados y variantes por cliente |
| Parametros operativos | Umbrales, reglas de envio, vencimientos y flags |

## Riesgo

Los codigos de referencia pueden estar usados como magic values en PHP/SQL. Deben conservarse en migraciones.
