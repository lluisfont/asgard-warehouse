# Logistics Quotation Costing - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Cotizacion logistica, carga de costos y seleccion de operador.

Objetivo de negocio candidato: crear una solicitud de cotizacion o embarque, enviarla a operadores logisticos, permitir que cada operador cargue costos/documentos, evaluar las propuestas y aceptar o confirmar el operador seleccionado.

## Trigger

El proceso se inicia desde pantallas/controladores de cotizaciones y embarques (`embarquesController.php`, `cotizacioncrear.php`, `cotizacionver.php`, `vercaso.php`, `evaluarcosto.php`) y desde el formulario externo de costos por token (`costosController.php`).

Evidencia:

- `index_archivos/logistica/embarquesController.php:94-178`
- `index_archivos/logistica/embarquesController.php:238-340`
- `index_archivos/logistica/CostosClass.php:14-35`
- `index_archivos/logistica/CostosClass.php:412-480`
- `index_archivos/logistica/vercaso.php`
- `index_archivos/logistica/costosController.php:9-24`
- `index_archivos/logistica/evaluarcosto.php:16-80`

## Actores

- Cliente usuario: crea cotizacion/embarque y evalua costos.
- Operador logistico: recibe solicitud y carga costos/documentos mediante token.
- ASGARD: registra embarque/cotizacion, envia correos, calcula estructura de costos y actualiza estados de operador.
- Servicio de correo/notificacion: comunica solicitud, revision, aceptacion o confirmacion.

## Resultado Esperado

- Se crea un registro de embarque con `cotizacion = 1` cuando es solicitud de cotizacion.
- La pantalla legacy de nueva cotizacion carga conceptos tarifarios desde `logis_tarifasconceptos`.
- Se crean operadores candidatos en `logis_embarquesoperador`.
- Se envia correo de solicitud de cotizacion a operadores con token.
- El operador carga costos y adjunto, y el token queda anulado.
- El cliente compara costos por operador.
- El cliente acepta un operador y el proceso pasa a embarque, o confirma/reajusta segun caso.

## Estado

Reconstruccion candidata. La revision humana se difiere hasta completar todos los dominios del baseline.

## Comunicaciones relacionadas

- `index_archivos/logistica/mailcotizacionoperador.php` envia solicitud o ajuste de cotizacion a operador mediante token.
- `index_archivos/logistica/mailoperadoracceso.php` informa acceso al sistema de operadores tras aprobacion de cotizacion.
- `index_archivos/logistica/mailoperadorconfirmar.php` comunica confirmaciones relacionadas con operador/cotizacion.
