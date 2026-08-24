# Additional Services Request Management - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Crear solicitud desde embarque

1. El usuario abre la pestaña Servicios Adicionales del embarque.
2. ASGARD verifica si el embarque tiene `fecha_finalizacion`.
3. ASGARD verifica permiso de escritura para reporte cliente `66`.
4. El usuario pulsa Agregar o Nuevo Servicio.
5. ASGARD precarga datos del embarque: email, ciudad, linea y exchange si existe.
6. El usuario selecciona entidad emisora.
7. ASGARD carga tramites disponibles para esa entidad.
8. El usuario selecciona tramite.
9. ASGARD carga tipos de tramite.
10. El usuario guarda la solicitud.
11. ASGARD llama `nueva-solicitud`.
12. ASGARD crea o vincula intercambio documental.
13. ASGARD agrega documentos/participantes al exchange.
14. ASGARD refresca bandeja de pendientes.

## Flujo B - Crear solicitud standalone de Asesoria/Gestion

1. El usuario abre Servicios Adicionales en Asesoria/Gestion.
2. El usuario captura solicitante, email, ciudad, linea y notas.
3. El usuario agrega uno o varios tramites.
4. ASGARD registra la solicitud.
5. Si no hay embarque, ASGARD crea intercambio documental del modulo `servicio_adicional`.
6. ASGARD redirige o refresca la bandeja.

## Flujo C - Editar o eliminar tramite

1. El usuario abre una solicitud editable.
2. ASGARD muestra tramites vinculados.
3. El usuario edita entidad/tramite/tipo o elimina fila.
4. ASGARD llama `editar-tramite`, `editar-tramites` o `borrar-tramite`.
5. ASGARD recarga los tramites.

## Flujo D - Enviar solicitud pendiente

1. El usuario abre una solicitud en estado pendiente.
2. El usuario pulsa Enviar Solicitud.
3. ASGARD llama `enviar-solicitud/{id}`.
4. La solicitud pasa a la bandeja de enviados o siguiente estado definido por API.

## Flujo E - Crear servicios automaticos por exchange

1. ASGARD actualiza `idExchange` de una solicitud previa.
2. ASGARD recibe lista de servicios a cargar.
3. Para certificado de origen, fitosanitario o inocuidad, ASGARD arma cuerpo de nueva solicitud.
4. ASGARD llama `ASESORIA_GESTION_API/nueva-solicitud`.
5. ASGARD devuelve respuesta de creacion y datos enviados.
