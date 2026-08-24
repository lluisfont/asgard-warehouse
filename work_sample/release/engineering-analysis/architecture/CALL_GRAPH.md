# Call Graph

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Fuente

- Grafo importado desde Graphify.
- Nodos: 32896.
- Aristas: 53235.
- Commit de grafo: `cad2cda9`.
- Reporte completo: `GRAPHIFY_GRAPH_REPORT.md`.

## Interpretacion

- El call/dependency graph es util para navegacion tecnica, deteccion de hubs e impactos.
- El grafo puede estar desfasado si el repo local cambio despues del commit indicado.
- Para refactor, regenerar con `graphify update .` antes de decidir cortes tecnicos.

## Hubs candidatos

- `cnfdb105.php`, `permisos.php`, menus/layouts.
- Clases `GlobalClass`, `SolicitudClass`, `CotizacionClass`, `EmbarqueClass`, `CostosClass`, `ServicioNotificacionesClass`, `OCRClass`, `MailClass`.
- Vistas y endpoints AJAX por dominio.
