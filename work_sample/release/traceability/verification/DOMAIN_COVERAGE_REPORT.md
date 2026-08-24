# Domain Coverage Report

Estado: IN_PROGRESS
Idioma: Spanish

## Resumen

- Dominios candidatos reconstruidos: 70.
- Dominios sin `PROCESS_DEFINITION.md`: 0.
- Dominios sin `BUSINESS_RULES.md`: 0.
- Dominios sin `DATA_USED.md`: 0.
- Artefactos por dominio presentes: `PROCESS_DEFINITION.md`, `BUSINESS_RULES.md`, `DATA_USED.md`, `PROCESS_FLOW.md`, `PROCESS_FLOW.mmd`, `STATE_MODEL.md`.
- Estado de validacion: `INFERRED_DRAFT_REVIEW_REQUIRED`; la revision humana queda diferida hasta completar el baseline.

## Criterios de cobertura aplicados

| Criterio | Resultado |
| --- | --- |
| Escrituras SQL funcionales en `index_archivos` | 0 ficheros funcionales sin clasificar tras dominios + infraestructura compartida. |
| Integraciones externas/filesystem/correo/OCR/ZIP | 0 ficheros funcionales sin clasificar tras dominios + infraestructura compartida. |
| PHP raiz `index_archivos/*.php` | 0 ficheros sin clasificar tras dominios + infraestructura compartida. |
| Componentes Graphify con lectura/escritura/estado | Residuales incorporados a dominios existentes o clasificados como infraestructura/libreria. |
| Tablas con evidencia PHP directa | 0 tablas residuales relevantes fuera de dominios/soporte; falsos positivos documentados. |

## Familias funcionales cubiertas

- Aduana/casos/DAV/DAM/DEX/EDP, Form1, documentos, tributos, garantia, AP, nacionalizacion y KPIs.
- Logistica: embarques, pedidos, cotizaciones, costos, rutas, viajes, estados, documentos, almacenes, tracking y notificaciones.
- Vehiculos: carga Excel, importacion, facturas, chasis, SOAT, OCR, inventario, DT, costos y reportes.
- Facturacion/contabilidad/cobranzas: planilla/factura, pagos, libro mayor, aging, recepcion documental.
- Exportacion/Alicorp/IASA/SCP/MIC/DEX: OCR, control fisico, gastos, reportes y conciliaciones.
- Terceros y maestros: agentes, seguros, gestores, proveedores, tokens, certificaciones, servicios adicionales y asesoria/gestion.
- Seguridad/identidad: login primario, 2FA, recuperacion, cambio de password, permisos y auditoria de sesion.
- Reporting/BI: dashboards Power BI/genericos, reportes operativos, descargas y soporte `pbi_*`.

## Limites actuales

- La cobertura es candidata e inferida desde codigo, SQL, rutas, nombres y evidencias; no esta validada por negocio.
- Las tablas SQL-only sin PHP directo (`con_*`, `serv_*`, `cn_*`, `bot_*`) se mantienen como pendientes externos, no dominios confirmados.
- Los artefactos de arquitectura, seguridad, pruebas, OpenSpec y empaquetado formal siguen en fases posteriores del pipeline.
