# Proposal: Migrar runtime Slim 2 a Slim 4

## Intent
Actualizar el backend de Slim 2 a Slim 4 sobre PHP 7.4 manteniendo los contratos actuales de entrada y salida de todos los endpoints.

## Scope
- Reemplazar el arranque `new \Slim\Slim()` por Slim 4 `AppFactory::create()`.
- Mantener rutas, middlewares, payloads JSON, nombres de campos y códigos de respuesta existentes.
- Adaptar CORS, body parsing, routing y error middleware al modelo Slim 4.
- Añadir una capa temporal de compatibilidad para callbacks Slim 2 mientras se migra endpoint por endpoint.

## Out of Scope
- Rediseñar modelos, SQL, estructura de base de datos o formato de respuestas.
- Cambiar autenticación JWT salvo lo mínimo necesario para que siga funcionando en Slim 4.
- Modernizar lógica de negocio interna no relacionada con Slim.

## Approach
Aplicar una migración brownfield incremental. Primero se introduce un facade de compatibilidad Slim 2 sobre Slim 4 para reducir riesgo y preservar comportamiento. Luego se podrán convertir rutas grandes a PSR-7 nativo por módulo.

## Risks and Open Questions
- Algunos endpoints hacen descargas, `echo`, `header()` o `die()`; deben validarse con pruebas manuales o E2E.
- Se debe confirmar el `basePath` real si la API corre en subdirectorio.
- Las rutas con archivos y formularios multipart requieren validación específica.
