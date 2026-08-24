# Characterization Test Strategy

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

Objetivo: capturar comportamiento AS-IS antes de refactor, priorizando flujos con dinero, aduana, documentos, seguridad, ficheros y estados.

## Estrategia

1. Crear entorno aislado con copia anonimizable de DB y filesystem minimo.
2. Congelar fixtures por cliente/flujo critico.
3. Grabar salidas actuales: HTML/JSON/PDF/Excel/DB diffs/notificaciones.
4. Escribir pruebas golden master alrededor de endpoints antes de modificar codigo.
5. Separar pruebas de seguridad de pruebas funcionales.
6. Ejecutar por dominio y por smoke suite transversal.

## Prioridad

- P0: login/MFA/permisos, documentos, carga Excel, facturacion, EDP, tenant isolation.
- P1: OCR, notificaciones, dashboards/reportes, tracking, DAV/DAM/DEX.
- P2: maestros, UI helper, reportes secundarios.
