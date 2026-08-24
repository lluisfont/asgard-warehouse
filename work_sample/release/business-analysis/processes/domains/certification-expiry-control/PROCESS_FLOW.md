# Process Flow

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Register / Edit Document Flow

1. El usuario abre el modulo de control de certificaciones.
2. El sistema carga tipos de documento, modelos, AP madres y datos derivados de producto cuando aplica.
3. El usuario captura tipo documental, codigo, fechas, plazo de alerta, unidad de plazo, extension, modelo/AP, atributos del vehiculo o mercancia y monto de boleta si corresponde.
4. El usuario adjunta uno o varios archivos y opcionalmente carga mercancias desde Excel.
5. El sistema valida si el codigo documental ya existe. La validacion no bloquea cuando `tipo_documento_id = 3`.
6. El sistema crea o actualiza `cc_registro_documentos`.
7. El sistema guarda archivos en ruta de cliente y registra `cc_archivos`.
8. El sistema registra mercancias manuales y/o importadas desde Excel en `cc_mercancias`.
9. Si falla la carga de mercancias o archivos durante registro, el sistema intenta revertir registros creados.
10. El documento queda disponible para consulta y alertas.

## Control / Search Flow

1. El usuario filtra por codigo, estado, tipo documental o mercancia.
2. El sistema consulta documentos del cliente no eliminados.
3. El sistema calcula `estado_documento` mediante `f_estado_documento`.
4. El sistema devuelve documentos con archivos asociados.

## Notification Flow

1. Un proceso programado obtiene correos de `dav_email_notificaciones` para el tipo de notificacion correspondiente.
2. Por cada cliente, consulta documentos no eliminados y no notificados.
3. El sistema calcula estado documental.
4. Si el estado no es `VIGENTE`, compone correo con documento, codigo, vencimiento y mercancia.
5. Si el documento esta `VENCIDO`, actualiza `notificacion_enviada = 1`.

## AP Control Flow

1. El usuario consulta autorizaciones previas por filtro de vencimiento.
2. El sistema cruza `dav_autorizacionprevia` con partidas, facturas y casos.
3. Calcula vencimiento a 180 dias desde fecha de emision y diferencia en dias.
4. Excluye casos anulados o liquidados.
