# Architecture context

Estado: candidate_reconstruction  
Fuente: engineering-analysis/architecture

La arquitectura AS-IS es una aplicacion web PHP legacy con endpoints por pantalla/modulo, AJAX, includes compartidos, SQL MySQL directo, generacion de documentos/reportes y dependencias externas para correo, notificaciones, OCR/SFTP/API y Power BI.

## Restricciones

- Bajo encapsulamiento entre pantalla, validacion, SQL y renderizado.
- Contratos HTTP no formalizados.
- Dependencia de base de datos historica y tablas temporales/vistas.
- Procesos batch/scheduler pendientes de confirmar.
- Comportamiento por cliente embebido en codigo/configuracion.

## Regla OpenSpec

Cualquier cambio debe partir de pruebas de caracterizacion para flujos criticos y preservar contratos legacy visibles.
