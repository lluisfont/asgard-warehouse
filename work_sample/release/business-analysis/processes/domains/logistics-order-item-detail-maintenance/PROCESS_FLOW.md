# Logistics Order Item Detail Maintenance - Process Flow

## Flujo principal

1. El usuario abre la vista Items de un pedido/embarque.
2. ASGARD carga almacenes del cliente.
3. La UI solicita el detalle de pedido a la API.
4. La UI construye la tabla de posiciones.
5. El usuario edita campos disponibles.
6. El usuario pulsa Guardar.
7. La UI serializa el formulario.
8. El backend recorre cada campo POST.
9. ASGARD actualiza la columna indicada para el id de posicion indicado.
10. La UI muestra exito.

## Excepciones observadas

- El backend no restringe lista blanca de columnas visibles.
- El backend imprime parametros POST en la respuesta.
- No se observa validacion de pertenencia de posicion al pedido/cliente.
- No se observa auditoria `updated_by`/`updated_at`.
