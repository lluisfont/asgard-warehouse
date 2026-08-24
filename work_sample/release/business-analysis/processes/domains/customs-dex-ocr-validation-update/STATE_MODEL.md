# Customs DEX OCR Validation Update - State Model

## Estados candidatos

| Estado | Condicion observada | Resultado |
| --- | --- | --- |
| DOCUMENTO_RECIBIDO | Se recibe path, nombre y `exchange_id`. | Se puede invocar OCR. |
| OCR_LEIDO | El modelo devuelve documento sin error. | Se extraen campos DEX. |
| CARPETA_RESUELTA | `exchange_id` localiza `idcasosprevios`. | Se compara pertenencia. |
| DEX_NO_CORRESPONDE | La carpeta OCR no coincide con ASGARD. | No se aplican actualizaciones. |
| DATOS_ADUANEROS_ACTUALIZADOS | Carpeta coincide y campos OCR tienen formato utilizable. | Se actualizan DUI/Sidunea/fecha. |
| OBSERVACIONES_GENERADAS | Se comparan campos OCR contra ASGARD. | Se devuelve lista de diferencias. |

## Transiciones

| Transicion | Disparador | Efecto |
| --- | --- | --- |
| Leer OCR | Usuario solicita lectura | OCR con `MODELO_DEX`. |
| Resolver carpeta | OCR sin bloqueo | Consulta por `exchange_id`. |
| Rechazar pertenencia | Carpeta distinta | Mensaje de DEX no perteneciente. |
| Actualizar datos | Carpeta coincide | Mutaciones sobre `dav_casos`. |
| Generar observaciones | Datos disponibles | Mensajes de diferencia para revision. |
