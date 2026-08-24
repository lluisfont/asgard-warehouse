# Alicorp Operational Document Package Dispatch - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Envio programado de legajos documentales operativos Alicorp/IASA.

Objetivo de negocio candidato: identificar casos Alicorp pendientes de envio documental, construir paquetes ZIP con documentos operativos desde Document Exchange, enviarlos por correo a contactos de consignatario/operador y marcar las carpetas como enviadas para evitar reproceso.

## Trigger

El proceso se ejecuta desde `cron/documentacionAlicorp.php` cuando recibe `cron=1`.

Evidencia:

- `index_archivos/cron/documentacionAlicorp.php:24-29`
- `index_archivos/logistica/SolicitudesClass.php:160-218`
- `index_archivos/logistica/SolicitudesClass.php:1203-1206`

## Actores

- Job/cron ASGARD: ejecuta el barrido programado.
- Sistema ASGARD: selecciona casos, parametriza documentos, descarga archivos, arma ZIP y actualiza marca de envio.
- Document Exchange API: provee lista y archivos de documentos del embarque.
- Contactos de consignatario y operador logistico: reciben el legajo por correo.

## Alcance

Incluye:

- Clientes observados `775` y `755`.
- Seleccion de casos no anulados, con exchange de embarque y `embarque_documentos_enviados IS NULL`.
- Parametrizacion por proveedor y linea.
- Seleccion de documentos requeridos desde `logis_parametrizacionconcatenado` / `logis_parametrizaciondocumentos`.
- Creacion de ZIP por embarque.
- Persistencia del ZIP bajo `documentosOperativosAlicorp/{idembarque}`.
- Envio de correo "TRAMITES IASA" con adjuntos.
- Actualizacion de `dav_casos.embarque_documentos_enviados`.
- Descarga manual de ZIP documental operativo desde `index_archivos/logistica/ajax/downloadDocumentos.php` como variante bajo demanda.

Fuera de alcance:

- Validacion del contenido documental antes del envio.
- Confirmacion de recepcion por destinatarios.
- Reenvios manuales o correcciones posteriores.

## Resultado Esperado

- Cada grupo de casos elegible genera uno o mas ZIPs con documentos requeridos.
- El correo incluye tabla de carpetas, proveedor/consignatario, pedido, DEX, factura, fecha, agencia y operador.
- Las carpetas incluidas quedan marcadas con fecha/hora de envio.

## Estado de Validacion

Reconstruccion candidata desde codigo y SQL. La revision humana se difiere hasta completar el baseline completo.
