# Business capability map

Estado: candidate_reconstruction  
Confianza: media

| Capacidad | Dominios candidatos relacionados | Evidencia |
|---|---|---|
| Gestion de acceso e identidad | identity-access, customer-primary-login-session-audit, customer-password-recovery | Login, 2FA, historial, permisos y sesion |
| Intake de solicitudes | customs-request-intake, shipment-customs-request-management, bulk-request-excel-import-validation | Formularios, Excel, tablas `dav_*` y `tmp_*` |
| Gestion aduanera | customs-dav-client-review-approval, customs-document-approval, customs-guarantee-tax-control, customs-tax-liquidation-return-confirmation | DAV/DAM/DEX, partidas, mercancia, estados y agentes |
| Gestion logistica | logistics-quotation-costing, logistics-shipment-tracking, logistics-route-trip-assignment-management, logistics-order-status-milestones | Puertos, carga, ordenes, rutas, costos y seguimiento |
| Gestion documental/OCR | document-exchange-ocr, document-service-plan-ocr-capture, logistics-bl-policy-ocr-capture | OCR, adjuntos, validacion documental y descargas |
| Vehiculos/importacion | vehicle-import-management, vehicle-excel-intake-validation, inventory-vin-billing-control | Chasis/VIN, Excel, facturas, SOAT, deposito |
| Facturacion y control financiero | billing-invoice-planilla-document-generation, billing-payments-receivables, accounting-ledger-aging-reporting | Planillas, facturas, cuentas por cobrar y reportes |
| Reporteria e inteligencia | executive-powerbi-dashboard-portal, operational-reporting-downloads, warehouse-inventory-reporting | Dashboards, Power BI, Excel/PDF, KPI |
| Integracion con terceros | third-party-token-document-onboarding, external-agency-procedure-tracking, realtime-notification-center | Tokens, agentes/proveedores, Pusher/correo/SFTP |
| Control operacional | certification-expiry-control, nationalization-weekly-planning, continuous-improvement-nonconformity | Vencimientos, planificacion, no conformidades |

## Lectura

Las capacidades deben entenderse como agrupaciones candidatas derivadas de codigo, tablas y nombres de componentes. No sustituyen el mapa organizativo validado por stakeholders.
