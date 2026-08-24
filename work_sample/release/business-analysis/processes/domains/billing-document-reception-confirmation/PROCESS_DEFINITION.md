# Billing Document Reception Confirmation - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Confirmar la recepcion operativa de documentos de cobro y soporte enviados al cliente o area receptora, separando los documentos pendientes de recibir de los ya recepcionados y permitiendo marcar recepcion individual o masiva.

## Alcance observado

- Bandeja de documentos enviados y pendientes de recepcion.
- Bandeja historica de documentos recepcionados.
- Familias documentales observadas: Planilla, Factura, Nota de Cobranza y Cite.
- Confirmacion individual de recepcion.
- Confirmacion masiva mediante seleccion de documentos.
- Registro de fecha/hora de recepcion en tablas distintas segun tipo documental.
- Reporte relacionado de planillas legalizadas entregadas.

## Fuera de alcance observado

- Emision original de factura, planilla, nota de cobranza o cite.
- Firma fisica o digital del receptor.
- Reversion/undo de una recepcion marcada por error.
- Auditoria nominal del usuario receptor.
- Politica oficial del corte historico `2021-08-02`.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario contable/operativo | Consulta documentos enviados y confirma recepcion individual o masiva. |
| Cliente/area receptora | Recibe fisicamente o administrativamente los documentos. |
| ASGARD | Consolida documentos pendientes/recibidos y aplica marcas de recepcion. |
| Reporte de planillas legalizadas | Permite consultar planillas originales entregadas como evidencia relacionada. |

## Entradas

- Cliente de sesion.
- Documento seleccionado.
- Lista de documentos marcados.
- Tipo documental: Planilla, Factura, Nota de Cobranza o Cite.
- Numero/identificador documental.
- Fechas de envio y pago DIM usadas como criterios de consulta.

## Salidas

- Documento movido de enviado pendiente a recepcionado.
- Fecha/hora de recepcion persistida.
- Bandeja actualizada de enviados y recepcionados.
- Reporte de planillas legalizadas entregadas.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/contables/recepcionplanillas.php` | UI con pestanas Enviadas/Recepcionadas, checkboxes, Recibido y Recibir Marcadas. |
| `index_archivos/contables/recepcionplanillas_ajax.php` | Consultas y actualizaciones para documentos enviados, recibidos, recepcion individual y masiva. |
| `index_archivos/contables/planillaslegalizadas.php` | Reporte relacionado de planillas legalizadas entregadas. |
| `index_archivos/contables/planillaslegalizadasquery.php` | Consulta de planillas originales entregadas por cliente vehicular y Fecha Pago DIM. |
| `.data_base/asgard.sql` | Campos `recepcionplanilla`, `fecharecepcionplanilla`, `estado_recepcionado`, `fecha_recepcionado`, `fecharecepcion`. |

## Criterios de aceptacion candidatos

- Los documentos enviados pendientes aparecen cuando tienen marca de envio y no tienen marca de recepcion.
- Los documentos recepcionados aparecen cuando tienen marca/fecha de recepcion.
- La accion Recibido individual actualiza solamente el documento seleccionado.
- La accion Recibir Marcadas procesa todos los documentos seleccionados.
- La tabla/campo actualizado depende del tipo documental.
- El listado debe distinguir visualmente el tipo Planilla, Factura, Nota de Cobranza o Cite.
