# External Agency Procedure Tracking - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Consultar gestiones SENASAG

1. El usuario abre Reporte de gestiones en SENASAG.
2. El usuario filtra por proveedor, factura, etapa o tramite.
3. ASGARD obtiene etapas configuradas de la entidad emisora.
4. ASGARD crea tabla temporal con columnas dinamicas por etapa y estado.
5. ASGARD carga tramites vinculados a caso o solicitud.
6. ASGARD actualiza fechas de etapas y estados.
7. ASGARD aplica filtro de etapa si corresponde.
8. La UI muestra grilla y permite Excel.

## Flujo B - Mantener tramites

1. El usuario agrega o edita tramites asociados a un caso previo.
2. ASGARD guarda entidad emisora, tramite de entidad y tipo de tramite.
3. ASGARD permite eliminar tramites observados.
4. La UI actualiza listas dependientes por AJAX.

