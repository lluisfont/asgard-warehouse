# Export MIC DEX Physical Reception Control - Process Flow

## Flujo principal

1. El usuario abre Reporte Recepcion Fisica de MICs.
2. Ingresa filtros y genera el reporte.
3. ASGARD consulta `dex_suma` con fecha de verificacion de salida.
4. ASGARD deriva estado documental por fechas recibida/enviada/concluida.
5. El usuario selecciona registros de un mismo estado.
6. El usuario pulsa Marcar registros o Revertir.
7. La UI envia ids, accion y estado a `ActualizarMICs.php`.
8. ASGARD calcula estado historial y campo de fecha a modificar segun accion, estado y tipo de usuario.
9. ASGARD inserta historial.
10. ASGARD actualiza fechas en `dex_suma`.
11. La UI informa exito y recarga la consulta.

## Flujo historial

1. El usuario pulsa historial de un registro.
2. ASGARD obtiene datos DEX y estados historicos.
3. La UI muestra cabecera y tabla de historial en modal.

## Excepciones observadas

- Si no hay registros seleccionados, la UI muestra advertencia.
- Si la insercion de historial o update falla, se devuelve error.
- Si la consulta no encuentra datos, devuelve status `400`.

