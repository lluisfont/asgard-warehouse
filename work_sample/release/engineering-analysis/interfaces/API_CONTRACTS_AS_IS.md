# API contracts AS-IS

Estado: inferred_from_static_evidence  
Confianza: media

## Superficie observada

La aplicacion expone contratos HTTP heredados a traves de rutas PHP, modulos `ajax/`, pantallas con formularios y endpoints que devuelven HTML parcial, JSON, ficheros o redirecciones. No se ha observado una especificacion OpenAPI formal ni versionado contractual uniforme.

## Familias de contrato

| Familia | Evidencia | Contrato observado |
|---|---|---|
| Autenticacion y sesion | `index.php`, `ajax/ajaxValida2FA.php`, `usuario/historial.php` | POST de credenciales/codigos, uso de sesion PHP, respuestas mixtas entre redireccion, texto y JSON |
| Gestion aduanera | `embarques_nueva_gestion_aduanera.php`, `embarques_detalle_gestion_aduanera.php`, `getIHAgencia.php` | Formularios y AJAX para crear, consultar y actualizar solicitudes/casos |
| Logistica y cotizaciones | `logistica/vercaso.php`, `logistica/despachos.php` | Pantallas transaccionales con parametros de caso, cliente, puerto, carga y estado |
| Exportaciones/transporte | `operativos/exportaciones/ajax/ComparacionDocumentosIASA.php` | Endpoints AJAX orientados a comparacion documental y seguimiento operativo |
| Reporteria | `ajax/DashboardGenerico.php`, reportes cliente y Power BI | Filtros HTTP, consultas agregadas, HTML/JSON para dashboards y exportaciones |
| Documentos | `download.php`, cargas OCR/documentales | Carga, descarga y visualizacion de documentos asociados a caso, embarque o solicitud |

## Rasgos contractuales

- Los parametros se reciben principalmente por `$_GET`, `$_POST`, `$_REQUEST` y ficheros subidos.
- La autorizacion se resuelve por sesion, cliente, rol/permisos y filtros SQL manuales.
- El formato de respuesta depende del endpoint: HTML, JSON, CSV/Excel/PDF/ZIP, texto plano o salida directa.
- Los errores no siguen un envelope comun; pueden aparecer como `die`, mensajes HTML, arrays JSON parciales o redirecciones.
- La compatibilidad real esta acoplada al comportamiento historico de las pantallas consumidoras.

## Implicacion para refactor

Cualquier modernizacion debe capturar primero contratos de caracterizacion por endpoint critico: parametros aceptados, permisos, codigos/mensajes devueltos, side effects, ficheros generados y comportamiento ante datos incompletos.
