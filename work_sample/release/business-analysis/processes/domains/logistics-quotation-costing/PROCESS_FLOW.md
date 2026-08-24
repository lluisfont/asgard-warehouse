# Logistics Quotation Costing - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Flujo Principal Candidato

1. El cliente crea una cotizacion o embarque.
2. ASGARD arma datos de embarque, magnitudes, tramos y operadores.
3. ASGARD guarda cabecera en `logis_embarques`.
4. ASGARD guarda operadores candidatos en `logis_embarquesoperador`.
5. El cliente envia solicitud de cotizacion.
6. ASGARD envia correo a cada operador con token.
7. El operador accede por token y carga costos.
8. ASGARD guarda detalle de costos en `logis_costosdetalles`.
9. ASGARD calcula `TT` desde ETD/ETA cuando ambos existen en el mismo grupo.
10. ASGARD anula el token y marca `llenadocot = 1`.
11. El cliente abre evaluacion de costos.
12. ASGARD muestra costos por operador, grupos y conceptos.
13. El cliente acepta o confirma operador.
14. ASGARD marca `aceptado` o `confirmado`, envia correo y bloquea el proceso correspondiente.

## Flujos Alternativos

- Reajustar cotizacion: se reabre o reajusta si existe.
- Borrar cotizacion: se elimina/anula si existe.
- Revisar costos: se solicita revision al operador y se incrementa revision.
- Adjuntar documento de operador: se guarda archivo y ruta en `logis_embarquesoperador`.

## Evidencia

- `index_archivos/logistica/embarquesController.php:94-178`
- `index_archivos/logistica/embarquesController.php:238-340`
- `index_archivos/logistica/CostosClass.php:412-480`
- `index_archivos/logistica/CostosClass.php:514-540`
- `index_archivos/logistica/costosController.php:9-24`
- `index_archivos/logistica/evaluarcosto.php:16-180`
