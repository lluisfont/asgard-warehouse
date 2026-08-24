# Customs DAV Client Review Approval - Process Flow

## Flujo principal candidato

1. El usuario abre el frame logistico de items de un embarque.
2. ASGARD consulta las DAV/FDM asociadas al embarque y muestra las que tienen estado cliente inicial o pendiente.
3. El usuario abre el formulario DAV/FDM desde la lista.
4. ASGARD muestra el detalle declarativo y el campo de observaciones.
5. El usuario selecciona Aprobar o Rechazar.
6. La interfaz solicita confirmacion.
7. El endpoint correspondiente envia `iddav`, observaciones y estado cliente candidato.
8. `DemisClass::cambiarEstadoDav` actualiza `dav_dav`.
9. El usuario repite la decision para cada DAV/FDM de la carpeta.
10. Desde la lista de DAV, el usuario solicita finalizar revision.
11. ASGARD verifica que todas las DAV de `idcasos` esten aprobadas o rechazadas.
12. Si hay pendientes, informa que existen DAV pendientes por aprobar/rechazar.
13. Si no hay pendientes, ASGARD marca `finalizardav = 1` para la carpeta.
14. ASGARD registra seguimiento EDP por cada DAV revisada.
15. ASGARD envia correo al coordinador con copia al oficial.
16. La pantalla se recarga y deja de mostrar la accion de finalizar.

## Excepciones observadas

- Si el usuario cancela la confirmacion SweetAlert, no se ejecuta el endpoint.
- Si el cambio de estado falla, el endpoint devuelve codigo de error y mensaje de advertencia.
- Si `verificarCarpeta` detecta pendientes, bloquea el cierre.
- Si `finalizarRevision` no obtiene DAV por carpeta, devuelve error generico.
- Si el envio de correo contiene `error`, el script imprime `error.` antes de completar la respuesta JSON observada.

