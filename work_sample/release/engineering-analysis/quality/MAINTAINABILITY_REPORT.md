# Maintainability Report

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

## Diagnostico

Mantenibilidad baja-media por acoplamiento, SQL directo, reglas por cliente, magic values, dependencias antiguas y side effects mezclados.

## Mejoras candidatas

1. Crear pruebas de caracterizacion.
2. Centralizar DB, permisos, tenant guard y ficheros.
3. Extraer servicios para OCR, correo, notificaciones, documentos y EDP.
4. Reemplazar magic values por catalogos/configuracion.
5. Migrar progresivamente de `mysql_*` a PDO preparado.
