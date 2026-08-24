# Process Flow

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Main Flow

1. El usuario abre el modulo de mejora continua.
2. El sistema inicializa credenciales de usuario/cliente y consume la API configurada en `api_url`.
3. El usuario registra un hallazgo con tipo de registro, origen, area, proceso afectado, tipo de hallazgo, consecuencia, impacto, nivel, proveedor si aplica, descripcion y accion inmediata.
4. El sistema calcula plazos de asignacion y atencion a partir del impacto.
5. El usuario guarda el caso en estado `GUARDADO` o lo envia en estado `ENVIADO`.
6. El administrador revisa los casos enviados y abre la asignacion.
7. Si el caso requiere postergacion, se informa plazo y justificacion; si requiere atencion, se asigna responsable de analisis y plazo de atencion.
8. El analista registra normativa/procedimiento/requisito, analisis de causa, objetivos esperados y plan de acciones correctivas.
9. El analista adjunta evidencias del analisis cuando aplica.
10. El caso pasa a verificacion.
11. El administrador o responsable de verificacion registra resultado, evidencias y marca acciones verificadas.
12. Si la verificacion es suficiente, el administrador cierra el caso con resultado de verificacion.
13. Si no es suficiente o requiere nuevo ciclo, el caso puede reabrirse generando relacion con caso anterior/nuevo.

## Boards / Work Queues

- Pendientes de envio: lista `GUARDADO`.
- Hallazgos enviados: lista `ENVIADO`.
- Analisis: lista `ANALISIS`.
- Verificacion: lista `VERIFICAR`.
- Cierre: lista `VERIFICADO`.
- Cerrados: lista `CERRADO`.

## Alternate Flows

- Derivacion: durante analisis, un caso puede derivarse a otro integrante del equipo de analistas.
- Reapertura: desde cierre o detalle, un caso puede reabrirse y crear/relacionar un nuevo registro.
- Postergacion: si no hay atencion inmediata, se registra plazo de postergacion y justificacion.
