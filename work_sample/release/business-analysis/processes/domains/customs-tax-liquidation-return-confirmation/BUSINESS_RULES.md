# Customs Tax Liquidation Return Confirmation - Business Rules

| ID | Regla | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-CTLRC-001 | La confirmacion solo esta disponible si `fechaenvioliquidacion` existe y `fecharetornoliquidacion` esta vacia. | `detalleitems.php:191-196` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CTLRC-002 | Los destinatarios se resuelven por `dav_casos.idcliente` y `dav_casos.idciudad`. | `detalleitems.php:43-53` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CTLRC-003 | La confirmacion informa que procede el pago de tributos. | Mensaje en `detalleitems.php:24-27` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CTLRC-004 | El asunto debe incluir solicitud previa y carpeta. | `detalleitems.php:39` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CTLRC-005 | El retorno queda registrado con timestamp del servidor cuando el correo no reporta error. | `detalleitems.php:60-63` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CTLRC-006 | El detalle de liquidacion combina tributos base, gastos adicionales y factura/planilla aprobada. | `detalleitemsquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Riesgos / validaciones pendientes

- Confirmar si el retorno debe ser una aprobacion formal del cliente, una validacion interna o ambas.
- Confirmar si debe existir usuario, fecha, comentario y evidencia del correo enviado.
- Revisar la variable `$reponse`/`$response`, porque puede alterar el resultado esperado.
- Revisar si el correo deberia bloquear el retorno ante respuesta vacia de SendGrid.
