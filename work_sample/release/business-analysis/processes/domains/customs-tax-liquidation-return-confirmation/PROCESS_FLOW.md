# Customs Tax Liquidation Return Confirmation - Process Flow

## Flujo principal - Confirmar retorno

1. El usuario abre Detalle de Items Pedido para un caso.
2. ASGARD lee pedido, fecha de envio de liquidacion y fecha de retorno.
3. ASGARD calcula/consulta el detalle de liquidacion por item.
4. Si existe fecha de envio y no existe retorno, la UI muestra Confirmar.
5. El usuario pulsa Confirmar.
6. ASGARD obtiene carpeta, pedido y solicitud previa del caso.
7. ASGARD obtiene destinatarios de retorno por cliente y ciudad.
8. ASGARD arma asunto y mensaje de confirmacion para pago de tributos.
9. ASGARD envia el correo mediante `EmbarqueClass::sendMailEnEmbarques`.
10. Si el envio no contiene error, ASGARD actualiza `dav_casos.fecharetornoliquidacion`.
11. La pantalla muestra mensaje de envio.

## Flujo alternativo - Consulta/Excel sin confirmacion

1. El usuario abre el detalle.
2. Si la liquidacion no fue enviada o ya retorno, la UI no muestra Confirmar.
3. El usuario puede revisar o exportar el detalle sin cambiar el estado.

## Excepciones observadas

- La variable de respuesta se asigna como `$reponse` pero se evalua `$response`, lo que puede impedir detectar correctamente errores de envio.
- El `UPDATE` de retorno no registra usuario confirmador ni referencia explicita del correo.
- El `idcasos` recibido por GET se concatena en SQL.
