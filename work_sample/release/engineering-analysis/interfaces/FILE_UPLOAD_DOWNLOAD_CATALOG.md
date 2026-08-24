# File upload/download catalog

Estado: inferred_from_static_evidence  
Confianza: media

## Descargas

| Area | Evidencia | Observaciones |
|---|---|---|
| Descarga generica | `download.php` | Punto sensible por rutas, permisos y tipos de fichero |
| Documentos de caso/embarque | Modulos de gestion aduanera, logistica y documentos | Asociados a estados y expedientes |
| Reportes | Reporteria operativa, Excel, PDF, ZIP | Generacion bajo demanda desde filtros |
| Planillas/facturas | Modulos contables/documentales | Requiere preservar formato legal/operativo |

## Cargas

| Area | Evidencia | Observaciones |
|---|---|---|
| OCR/documentos | Carpetas y endpoints documentales | Procesamiento externo o asincrono candidato |
| Vehiculos Excel | `VehiculosExcel.php` | Importacion tabular con validacion |
| Soportes aduaneros | Gestion aduanera/DAV | Documentos ligados a partidas, mercancia, proveedores y estados |
| Adjuntos operativos | Logistica/exportaciones | Evidencias de seguimiento o comparacion |

## Controles que deben caracterizarse

- Extension y MIME permitidos por flujo.
- Tamano maximo real.
- Reglas de nombre y ubicacion fisica.
- Permisos de lectura/escritura por cliente, rol y estado.
- Antivirus/OCR/procesamiento externo, si aplica.
- Respuesta exacta ante fichero corrupto, faltante o duplicado.
