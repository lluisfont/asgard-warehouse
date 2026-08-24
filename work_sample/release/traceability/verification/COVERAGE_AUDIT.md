# Coverage Audit

Estado: IN_PROGRESS
Idioma: Spanish

## Checkpoints completados

| Checkpoint | Resultado | Fecha |
| --- | --- | --- |
| Escrituras SQL en `index_archivos` | 0 ficheros funcionales sin clasificar tras dominios + infraestructura compartida. | 2026-07-31 |
| Integraciones externas/filesystem/correo/OCR/ZIP en `index_archivos` | 0 ficheros funcionales sin clasificar tras dominios + infraestructura compartida. | 2026-07-31 |
| PHP raiz `index_archivos/*.php` | 0 ficheros sin clasificar tras dominios + infraestructura compartida. | 2026-07-31 |
| Directorios de primer nivel | Directorios no funcionales restantes clasificados como assets, layout, librerias o vendor. | 2026-07-31 |
| Componentes Graphify con lectura/escritura/estado en `index_archivos` | Pasada de componentes residuales incorporada a dominios existentes o infraestructura compartida. | 2026-07-31 |

## Nuevas coberturas incorporadas en este bloque

- Dominio candidato `customs-case-edp-status-monitoring`.
- Artefacto `SHARED_INFRASTRUCTURE_COVERAGE.md`.
- Variantes y wrappers enlazados a dominios existentes:
  - notificaciones logisticas;
  - subida de documentos de embarque;
  - seguimiento Parte de Recepcion;
  - control Alicorp MIC/DEX;
  - reportes operativos con tablas temporales;
  - Android legacy de EDP;
  - generacion documental desde vehiculos previos;
  - cambio de password desde perfil;
  - libro de compras;
  - Intercambio Documental V2;
  - detalle legacy de solicitud previa;
  - dashboards genericos;
  - helpers de certificaciones y terceros.
  - componentes detectados por Graphify para Excel vehicular, GA de embarque, dashboard generico, notificaciones, despachos legacy, IASA e historial de sesion.

## Siguiente criterio de cobertura

Repetir residuales de Graphify/component_index y revisar si los remanentes son exclusivamente librerias, assets o pantallas sin evidencia funcional suficiente.
