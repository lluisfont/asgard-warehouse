# Open Questions

Status: IN_PROGRESS

## Identity Access / 2FA

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-IA-001 | Where is the `u` payload generated before `2fa.php`? | Determines whether the MFA context can be tampered with. | `index_archivos/2fa.php:10-13` |
| OQ-IA-002 | Is the `u` payload signed, encrypted, or only base64 encoded? | Base64 alone is not an integrity control. | `index_archivos/2fa.php:10-13`, `index_archivos/2fa/TwoFaClass.php:221-224` |
| OQ-IA-003 | Does `dav_clienteusuarios.2fa` control whether MFA is required? | Needed to document the business rule for MFA enrollment. | `.data_base/asgard.sql:3305-3333` |
| OQ-IA-004 | How are users unblocked after `fechabloqueo` is set? | Needed to complete the operational recovery process. | `index_archivos/2fa/TwoFaClass.php:112-122` |
| OQ-IA-005 | Does `consultar` parameterize or escape interpolated SQL values? | Determines whether authentication flow SQL is exploitable. | `index_archivos/2fa/TwoFaClass.php:80-197` |
| OQ-IA-006 | Should MFA code validation be bound to email or user id in addition to code and user type? | Current inspected query may permit code collision across users. | `index_archivos/2fa/TwoFaClass.php:86-90` |
| OQ-IA-007 | Are CSRF protections applied to the MFA AJAX endpoints? | Endpoints perform security-sensitive state transitions. | `index_archivos/2fa/ajax/*.php` |

## Document Exchange OCR

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-DEO-001 | What is the canonical catalog of `document_id` UUIDs and document names? | Required to complete business-readable document requirements. | `lectura_documentos_iasa.php:67-560` |
| OQ-DEO-002 | What business entities correspond to clients `775` and `755`? | OCR behavior is conditional on these ids. | `lectura_documentos_iasa.php:67` |
| OQ-DEO-003 | Which system owns `intercambiodocumental.exchanges` and `exchange_documents`? | Cross-schema ownership affects integration baseline. | `lectura_documentos_iasa.php:45-58` |
| OQ-DEO-004 | What are the expected document states in `exchange_documents.status`? | Needed to reconstruct full state lifecycle. | `.data_base/asgard.sql:11121-11132` |
| OQ-DEO-005 | How are OCR model constants configured and rotated? | Needed for security and operational baseline. | `OCRClass.php:108-206` |

## Customs Request Intake

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CRI-001 | What is the business boundary between Despacho Aduanero, Gestion Soporte and Vehiculos? | Needed to split or keep variants inside one domain. | `SolicitudClass.php:122-140` |
| OQ-CRI-002 | Which validation errors are blocking versus informational? | Needed for exact user journey and acceptance criteria. | `SolicitudClass.php:300-465` |
| OQ-CRI-003 | What is the full recipient policy for request finalization emails and push notifications? | Needed for governance and notification baseline. | `finsolicitud.php:392-515` |

## Logistics Shipment Tracking

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LST-001 | What is the canonical EDP state catalog and lifecycle order? | Needed to formalize shipment/order state transitions. | `logis_estados_edp`, `DashboardCBN.php:9-33` |
| OQ-LST-002 | What is the precedence when EDP, finalization, customs case and advisory request imply different states? | Needed for reliable AS-IS state model. | `EmbarqueClass.php:123-149` |
| OQ-LST-003 | Are CBN and Alicorp separate product variants or client-specific dashboards of the same logistics domain? | Needed for domain decomposition. | `DashboardCBN.php`, `DashboardAlicorp.php` |

## Logistics Quotation Costing

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LQC-001 | What is the exact business difference between accepted and confirmed operator cost? | Needed to formalize workflow states. | `embarquesController.php:276-313` |
| OQ-LQC-002 | Do cost submission tokens expire by time, by use only, or by manual reset? | Needed for security and process controls. | `CostosClass.php:14-24`, `CostosClass.php:467-480` |
| OQ-LQC-003 | What decision criteria should be used for selecting an operator beyond total cost? | Needed for business rule completeness. | `evaluarcosto.php:16-180` |

## Customs Document Approval

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CDA-001 | What is the exact meaning of `dav_documentosprevios.aceptar` values 0, 1, 2, 3 and 4? | Needed for canonical state lifecycle. | `documentacionaprobado.php:316`, `documentacionaprobado.php:970-999` |
| OQ-CDA-002 | What is the exact meaning of `dav_otrosdocumentosprevios.estado` values? | Needed for other-document state lifecycle. | `documentacionaprobado.php:442-476`, `finsolicitud.php:375` |
| OQ-CDA-003 | Should deleted or replaced attachments retain audit history? | Needed for compliance and traceability. | `documentacion.php:339-357`, `documentacionaprobado.php:300-312` |

## Vehicle Import Management

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VIM-001 | What are the official UI labels and business meanings of `camposmodificar` values 0, 1, 2 and 3? | Needed to formalize modification modes and acceptance criteria. | `VehiculosClass.php:136-156`, `uploadExcelSolicitud.php:16-39` |
| OQ-VIM-002 | What exact conditions make a vehicle eligible to move from one previous request to another? | Needed to validate AP and source/destination constraints. | `VehiculosClass.php:156-205`, `VehiculosClass.php:223-280` |
| OQ-VIM-003 | What is the full business rule for DAM required by item and vehicles without DAM? | Needed to document blocking conditions before request finalization. | `documentacion.php:381-435`, `enviarsolicitud_ajax.php:48-82` |
| OQ-VIM-004 | What ownership and lifecycle rules apply to SOAT lots when a vehicle changes request? | Needed to confirm whether lot creation/reassignment is correct and auditable. | `VehiculosClass.php:290-334`, `.data_base/asgard.sql:16007-16033` |
| OQ-VIM-005 | Should rows with observations remain pending, be deleted on next upload, or require explicit cancellation? | Needed to define operational recovery and audit expectations. | `VehiculosClass.php:15-22`, `uploadExcelSolicitud.php:86-123` |

## Billing Payments Receivables

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-BPR-001 | What is the canonical meaning of `idestadofactura`, `idestadoplanilla` and `idestadopago` values? | Needed to formalize billing and payment lifecycle states. | `.data_base/asgard.sql:6177-6266`, `.data_base/asgard.sql:7890-7986` |
| OQ-BPR-002 | What business rule determines when a factura, planilla or nota de cobranza is considered sent to the client? | Needed to complete receivable delivery lifecycle. | `recepcionplanillas_ajax.php:1-85` |
| OQ-BPR-003 | What is the authoritative formula inside `cobros2` for outstanding balances? | Needed to validate account statement totals and aging. | `estadocuentasquery.php:63`, `.data_base/asgard.sql:43800-44160` |
| OQ-BPR-004 | What are the controls for annulment, incobrables and conciliacion? | Needed for accounting completeness and audit trail. | `.data_base/asgard.sql:6249-6255`, `.data_base/asgard.sql:7508-7515` |
| OQ-BPR-005 | Which document types should share the reception workflow: factura, planilla, nota de cobranza and cite? | Needed to confirm if `Cite` belongs inside this domain or a correspondence domain. | `recepcionplanillas_ajax.php:103-160`, `recepcionplanillas_ajax.php:321-352` |

## Advisory Management Services

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-AMS-001 | What is the canonical numeric catalog for `ages_solicitudes_asesoria_gestion.estado`? | Needed to formalize lifecycle transitions. | `.data_base/asgard.sql:390-436`, `tbl-estados.js:17-79` |
| OQ-AMS-002 | Which backend routes/controllers own `/nueva-solicitud`, `/editar-solicitud`, `/enviar-solicitud` and report endpoints? | Needed for complete API and technical traceability. | `solicitud.js:150-260`, `operativos/asesoria-gestion.php:212` |
| OQ-AMS-003 | When should a service request create a new document exchange versus reusing an existing `exchange_id`? | Needed to define document integration rules. | `tbl-estados.js:220-260`, `solicitud.js:150-260` |
| OQ-AMS-004 | What is the exact boundary between AGES service request, customs previous case and logistics shipment service? | Needed to decide whether to split the domain later. | `logistica/SolicitudesClass.php:714-850`, `.data_base/asgard.sql:388-436` |
| OQ-AMS-005 | What roles are allowed to move requests through received, assigned, review, process, finalized and closed states? | Needed for permissions and audit baseline. | `tbl-estados.js:17-79`, `.data_base/asgard.sql:390-436` |

## Transport Export Tracking

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-TET-001 | Should trip tracking and export/MIC/DEX/SCP controls be separate business domains? | Needed to avoid over-grouping operational ownership. | `tracking/*`, `operativos/exportaciones/*` |
| OQ-TET-002 | What is the canonical event catalog for `tck_eventos`, especially ids 1 and 26? | Needed to formalize trip lifecycle. | `ReporteViajesClass.php:54-56`, `.data_base/asgard.sql:39514-39564` |
| OQ-TET-003 | What are the official permitted transitions for MIC/DEX documents by cliente versus proveedor? | Needed to validate `ActualizarMICs.php` state/date updates. | `ActualizarMICs.php:12-91` |
| OQ-TET-004 | What is the authoritative source for SCP reception and how should upload conflicts be handled? | Needed to complete import validation and audit rules. | `uploadDatosSCP.php:18-80` |
| OQ-TET-005 | Which export reports are formal fiscal outputs versus operational dashboards? | Needed for compliance and ownership boundaries. | `ExportacionesClass.php:64-360` |

## Master Data Configuration

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-MDC-001 | What is the official provider state catalog for values 0, 1, 2 and 3? | Needed to formalize state lifecycle and approval criteria. | `listaProveedores.php:27-30`, `ProveedorMercancia.php:78-111` |
| OQ-MDC-002 | Who approves provider creation and modification requests, and from which screen or role? | Needed to complete responsibility matrix and transitions. | `ProveedorMercancia.php:750-825`, `dav_proveedor_modificaciones` |
| OQ-MDC-003 | What is the exact permission model for customer reports, menus and modules? | Needed to reconcile UI access, reports and role/catalog semantics. | `Usuarios.php:184-203`, `menu.php`, `MenuClass.php` |
| OQ-MDC-004 | Do provider/operator completion tokens expire, and what reminder policy applies? | Needed for security, operational SLA and audit baseline. | `listaProveedores.php:31-48`, `OperadorTransporte.php:57-119` |
| OQ-MDC-005 | Which catalogs are global and which are customer-specific? | Needed to avoid incorrect data ownership in refactor design. | `.data_base/asgard.sql:2600-3354`, `.data_base/asgard.sql:9016-9135` |

## Certification Expiry Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CEC-001 | What is the official catalog and meaning of `cc_tipos_documentos`, especially type `3` and type `4`? | Needed to document duplicate-code exception and user visibility restrictions. | `ControlCertificacionesController.php:11-13`, `CommonController.php:9-24` |
| OQ-CEC-002 | What is the business definition of alert units `M`, `Y` and day/default in `f_estado_documento`? | Needed to validate date-difference calculations. | `.data_base/asgard.sql:39347-39399` |
| OQ-CEC-003 | Should `notificacion_enviada` reset when a document is edited, extended or renewed? | Needed to avoid missing future notifications. | `notificaciones.php:76-82`, `ControlCertificacionesController.php:109-162` |
| OQ-CEC-004 | Which documents require `archivo_ibmetro`, `monto_boleta`, AP madre, modelo or vehicle attributes? | Needed for form validation and acceptance criteria. | `ajax/registrar.php:10-30`, `.data_base/asgard.sql:707-742` |
| OQ-CEC-005 | Is AP expiry always 180 days for every client/document context? | Needed to confirm regulatory rule. | `ControlAps.php:31-39`, `listaControlAps.php:20` |

## Continuous Improvement Nonconformity

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CIN-001 | Where is the backend implementation for `api_url` endpoints such as `/nueva-mejora-continua`, `/analisis` and `/verificacion`? | Needed for complete technical traceability and API behavior. | `mejora-continua/views/*.php`, `js/config.js` |
| OQ-CIN-002 | What is the exact business difference between MC, OM, NC and SNC in `tipo_registro`? | Needed to document user-facing categories and acceptance criteria. | `views/formulario-caso.php:153-196`, `rnc_mejoras_continuas.tipo_registro_id` |
| OQ-CIN-003 | Does `POSTERGADO` update `estado`, or only store postergation fields while waiting for assignment? | Needed to formalize state transitions. | `constantes.js`, `asignacion-analista.js:54-77` |
| OQ-CIN-004 | What rule changes a case from `VERIFICAR` to `VERIFICADO`? | Needed to ensure all corrective actions are verified before closure. | `verificacion.js:1-151`, `tbl-cerrar.js` |
| OQ-CIN-005 | How should legacy `rnc_noconformidad` relate to modern `rnc_mejoras_continuas`? | Needed before refactor or migration design. | `.data_base/asgard.sql:15468-15582` |

## Warehouse Inventory Reporting

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-WIR-001 | Which system is authoritative for warehouse movements: Atlantes, ASGARD, or both depending on report? | Needed before refactor/data ownership decisions. | `warehouse/*query.php`, `ATLANTES_API_URL` |
| OQ-WIR-002 | What is the complete contract of Atlantes response objects for ingresos, salidas, inventario, timbrado and movimientos? | Needed for typed API specs and field descriptions. | `ingresos.php`, `salidas.php`, `inventario.php`, query files |
| OQ-WIR-003 | Why should inventory report exclude `cantidad <= 0`, and should zero-stock items be available in another report? | Needed to validate business semantics of availability. | `inventarioquery.php:31-36` |
| OQ-WIR-004 | What is the exact relationship between warehouse inventory, vehicle inventory by chasis and warehouse billing periods? | Needed to decide whether to split reporting, inventory execution and billing. | `inventario_*` tables, warehouse report screens |
| OQ-WIR-005 | Should SSL verification be enabled for Atlantes API calls in production? | Needed for security remediation baseline. | cURL options in `warehouse/*query.php` |

## Realtime Notification Center

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-RTC-001 | What is the official catalog and meaning of `push_eventos` ids? | Needed to validate routing, event names and acceptance criteria. | `.data_base/asgard.sql:15144-15158`, `generaUrl` |
| OQ-RTC-002 | What is the official catalog and meaning of `push_tipousuario` ids and provider mappings? | Needed to confirm recipient ownership and authorization rules. | `.data_base/asgard.sql:15216-15229`, `verificarUsuario*`, `listaNotificacionesProveedores` |
| OQ-RTC-003 | What is the official meaning of each `push_estado`, especially ids `1`, `2` and `3`? | Needed because code uses `1` as unread but comments call it sent/enviado. | `.data_base/asgard.sql:15128-15142`, `cambiarEstado.php` |
| OQ-RTC-004 | What retention/archive/delete policy applies to notifications and recipient rows? | Needed for storage, audit and privacy requirements. | `deleted_at` filters in list methods |
| OQ-RTC-005 | Are Pusher channels private/authorized, and can clients subscribe only to channels they are allowed to observe? | Needed for security baseline before refactor. | `js/datos.js`, `enviaNotificacion` |

## Operational Reporting Downloads

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-ORD-001 | What is the official catalog, owner and purpose of every `dav_reportescliente` id? | Needed before baseline sign-off and permission refactor. | `.data_base/asgard.sql:9532-9550`, report hidden `idreportescliente` fields |
| OQ-ORD-002 | Must every report log both visualization and download actions? | Current implementation appears uneven across report screens. | `rg` matches for `LogReportes.php`, `reporteexcel.php` |
| OQ-ORD-003 | Is passing raw query text to `reporteexcel.php` an intended contract or legacy implementation detail? | Needed for security and refactor design. | Report forms posting `query` hidden fields |
| OQ-ORD-004 | What retention and audit obligations apply to `log_asgard_ecosistema` report events? | Needed for compliance, storage and privacy design. | `.data_base/asgard.sql:11749-11779`, `LogReportes.php` |
| OQ-ORD-005 | What is the official contract of `despacho/construirZipAlicorp` and who owns ZIP generation errors? | Needed to document the bulk-download integration boundary. | `descargaMasivaDocumentos.php` |

## SCP Reception Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-SCP-001 | Is `idcliente = 775` intentionally fixed for SCP reporting, or should it use the session client? | Needed to avoid cross-client or incomplete reporting. | `reporte-scp.php` |
| OQ-SCP-002 | What is the official SCP state catalog and when should a row move to `Recibido`? | Needed to validate automatic state transition by date. | `guardarRecepcionSCP` |
| OQ-SCP-003 | Are `cantidad_enviada` and `peso_neto_lista_empaque` directly comparable, and is there an allowed tolerance? | Needed to validate `CUADRA`/`REVISAR`. | `getReporteSCP` |
| OQ-SCP-004 | What is the official uniqueness key for SCP rows? | Needed because import uses `orden + nota + placa + numero_material`. | `uploadDatosSCP.php`, `guardarRecepcionSCP` |
| OQ-SCP-005 | Who owns the upstream loading of `dav_reporte_detalles_transportistas_iasa`? | Needed to complete traceability of packing-list comparison. | `lectura_documentos_iasa.php`, evidence.jsonl |

## Logistics Order Status Milestones

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LOS-001 | What are the official labels and meanings of state ids `11`, `53`, `58`, `99` and `160`? | Needed to validate quantity field, finalization and pick-up email rules. | `estado-pedidos.php`, `EstadoPedidosClass.php` |
| OQ-LOS-002 | Should finalization occur for exactly states `53`, `99`, `160` for all clients? | Needed before refactor of shipment lifecycle. | `agregarEdp` |
| OQ-LOS-003 | Is report permission id `73` the canonical write permission for state milestones? | Needed to enforce authorization consistently outside the UI. | `estado-pedidos.php`, endpoints |
| OQ-LOS-004 | Should order-status notifications use Pusher event `crearSolicitud`, or a dedicated event name? | Needed for notification contract cleanup. | `enviarNotificacionEstado` |
| OQ-LOS-005 | Which actors can create milestones and what are all valid `created_type` values? | Needed to complete audit model. | `getEstadosPedidos`, `logis_edp.created_type` |

## Vehicle Transitory Depot Compliance

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VTDC-001 | Is client `417` intentionally fixed for this reporting flow, or should it use the session/client context? | Needed to avoid incorrect scope and cross-client assumptions. | `PagoTributosTransitorioImcruz.php` |
| OQ-VTDC-002 | What is the legal or commercial source of the 60-day threshold, and is day 60 included as before or after? | Needed to validate payment compliance classification. | `reporte-pago-tributos-transitorio.js` |
| OQ-VTDC-003 | What is the official formula for `tributos_previstos` and `monto_diferido`? | Needed to validate tax/payment totals and deferred amount semantics. | `PagoTributosTransitorioImcruz.php`, `reporte-pago-tributos-transitorio.js` |
| OQ-VTDC-004 | What is the official Atlantes contract for ingress/egress dates, timezone and authentication ownership? | Needed to make the integration baseline refactorable. | `reporte-pago-tributos-transitorio.js`, `reporte-deposito-transitorio.js` |
| OQ-VTDC-005 | What are the official catalog values for `tipo_inventario_id`, especially port versus transitory depot? | Needed to validate inventory evidence interpretation. | `PagoTributosTransitorioImcruz.php` |

## Customs Guarantee Tax Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CGTC-001 | Does `cc_registro_documentos.tipo_documento_id=4` always mean boleta de garantia? | Needed to validate total guarantee amount. | `ContabilidadClass.php:getSeguimientoTotal` |
| OQ-CGTC-002 | What is the official formula for guarantee amount in use? | Needed because the current formula is complex and duplicated. | `ContabilidadClass.php` |
| OQ-CGTC-003 | Is the 90-day threshold legal, commercial, operational, or only a UI/reporting convention? | Needed to validate exposure categories. | `getSeguimientoOperativo`, `boletasgarantia.php` |
| OQ-CGTC-004 | Is exchange rate `6.96` an intended fixed conversion for all guarantee reports? | Needed to avoid wrong USD/Bs reporting. | `boletasgarantiaajax.php` |
| OQ-CGTC-005 | What roles may access/export guarantee and tax-difference reports? | Needed to formalize financial-control permissions. | `boletasgarantia.php`, `boletasgarantiareporte.php`, `tributos.php` |
| OQ-CGTC-006 | Should Imcruz legalized-planilla reporting remain in this domain or be treated as client-specific output? | Needed to avoid over-grouping. | `planillaslegalizadasquery.php` |

## Vehicle Cost Accounting Reporting

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VCAR-001 | What are the official formulas for vehicle expense and logistics cost components? | Needed before refactor of duplicated financial formulas. | `gastosvehiculosquery.php`, `costoslogisticosvquery.php` |
| OQ-VCAR-002 | Are client-line ids `17`, `20`, `23`, `27`, `29`, `31` the full current vehicle/maquinaria catalog? | Needed to avoid excluding or including wrong cases. | Query filters |
| OQ-VCAR-003 | Is exchange rate `6.96` fixed by policy, configurable by period, or legacy? | Needed for financial accuracy. | `costoslogisticosv.php`, formulas |
| OQ-VCAR-004 | What is the official contract and side effect profile of `sp_reportezdam`? | Needed to document ZDAM generation safely. | `reportezdamquery.php` |
| OQ-VCAR-005 | Is the generic Excel export allowed to receive raw SQL query metadata from the report form? | Needed for security/refactor baseline. | `gastosvehiculos.php`, `costoslogisticosv.php` |

## Accounting Ledger Aging Reporting

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-ALAR-001 | What is the official business meaning of `agig`: aging, ahorro, or another register? | Needed for correct domain naming and field descriptions. | `agig.php`, `dav_aging` |
| OQ-ALAR-002 | Who is allowed to edit monthly aging amounts and where is audit recorded? | Needed for financial control. | `agig_ajax.php` |
| OQ-ALAR-003 | Are fixed provider values in libro de compras correct for all customers/reports? | Needed for fiscal accuracy. | `librocomprasquery.php` |
| OQ-ALAR-004 | Is 13% credit fiscal always calculated over `monto` for these rows? | Needed to validate tax logic. | `librocomprasquery.php` |
| OQ-ALAR-005 | Should comision report title/state be reconciled with UI label Estado de Cuentas? | Needed for business terminology consistency. | `comision.php`, `comisionquery.php` |

## Customs Operational KPI Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-COKC-001 | What is the official SLA catalog for AP, DAM, requirements, validation, planillaje and nationalization? | Needed to validate KPI rules. | `kpisquery.php`, `reporteseguimientoquery.php` |
| OQ-COKC-002 | Why do KPI temporals use hardcoded clients `417`, `452`, `471` while final report also filters by session client? | Needed to avoid hidden scope errors. | `kpisquery.php` |
| OQ-COKC-003 | Is the 5% tolerance for tax forecast official and does it apply to all clients/regimes? | Needed before baseline sign-off. | `reportecontroladquery.php` |
| OQ-COKC-004 | Are `positivo/negativo`, `CORRECTO/ATRASADO` and `EN TIEMPO/REINTEGRO` official KPI labels? | Needed for business-readable documentation. | KPI queries |
| OQ-COKC-005 | Which roles may see AD/OL control reports and export them? | Needed for access-control baseline. | Report screens and `LogReportes.php` |

## Operational Expense Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-OEC-001 | What is the official formula for total expense when planilla, factura, otras facturas and nota de debito coexist? | Needed for financial accuracy. | `gastosquery.php` |
| OQ-OEC-002 | What is the contract and ownership of `api/reportes/operativos/gastos-items`? | Needed for API traceability. | `gastos-items.php` |
| OQ-OEC-003 | Are invoice/planilla state ids `1` and payment state `3` official across all expense reports? | Needed to formalize reportability. | `gastosquery.php`, `detallegastosquery.php` |
| OQ-OEC-004 | Should control-gastos and control-gastos-sueltas be separate subflows or report variants? | Needed to avoid over/under grouping. | `control-gastos.php`, `control-gastos-sueltas.php` |
| OQ-OEC-005 | Which roles may export expense reports with full cost/detail data? | Needed for access-control baseline. | Report UIs |

## Operational Case Dossier Access

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-OCDA-001 | What is the official document visibility matrix by `tipo_usuario` and document type? | Needed to validate access control. | `documentosotros.php` |
| OQ-OCDA-002 | Is FTP still used in production and who owns credentials/storage? | Needed for security and operations baseline. | `documentosotros.php` |
| OQ-OCDA-003 | Should downloads be authorized by case/document ownership rather than raw path parameters? | Needed before refactor/security hardening. | `download.php` |
| OQ-OCDA-004 | What retention/audit policy applies to dossier files and downloads? | Needed for compliance baseline. | File listing/download flow |
| OQ-OCDA-005 | Are images intentionally hidden in this document listing or handled elsewhere? | Needed to complete dossier behavior. | `documentosotros.php` |

## External Agency Procedure Tracking

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-EAPT-001 | Does `identidademisora=2` always represent SENASAG in all environments? | Needed to validate report naming and filters. | `senasag.php`, catalog tables |
| OQ-EAPT-002 | Should current-stage logic use stage `orden` rather than `idetapa + 1`? | Needed to avoid wrong stage filtering when ids are not sequential. | `senasagquery.php` |
| OQ-EAPT-003 | What are the official final states and rejection/cancellation states for each procedure type? | Needed to complete lifecycle. | `dav_etapastramites`, state catalogs |
| OQ-EAPT-004 | Who may create, edit or delete external-agency procedures? | Needed for access-control baseline. | `tramites.php` |
| OQ-EAPT-005 | Is there an SLA per stage or only stage tracking? | Needed to decide whether it belongs also in KPI controls. | `senasagquery.php`, time-theory params |

## Form1 Modification Observation Tracking

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-FMOT-001 | What are the official labels and transitions for `dav_estadoform1edp`, especially states `1`, `3` and `7`? | Needed to formalize the lifecycle and elapsed-day calculations. | `modificacionesquery.php`, `dav_estadoform1edp` |
| OQ-FMOT-002 | What is the exact business boundary between modification, contravention, observation and missing document? | Needed for process naming and ownership. | `dav_form1`, `dav_casossubcontravencion`, `dav_faltadocumentos` |
| OQ-FMOT-003 | Which roles may see client-visible modifications, observations and call history? | Needed for access-control baseline. | `modificacionesquery.php`, `historial_llamadas.php` |
| OQ-FMOT-004 | How should Form1 attachments and call-history downloads be authorized and retained? | Needed for security/compliance refactor. | `historial_llamadas.php`, `download.php` |
| OQ-FMOT-005 | How does Form1 ownership differ when it is linked to `dav_casos` versus `ages_asesoria_gestion_carpetas`? | Needed to avoid mixing service lifecycles incorrectly. | `modificacionesquery.php` |

## Vehicle Chassis Timeline Trace

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VCTT-001 | Are the six observed timeline milestones and their ids valid for all customers and vehicle flows? | Needed to formalize the state model. | `bitacora_chasis.php` `revisionData` |
| OQ-VCTT-002 | What is the official API contract and ownership of `url_pedidos` inventory endpoints? | Needed for integration traceability and refactor planning. | `bitacora_chasis.php` |
| OQ-VCTT-003 | Should latest milestone be based on `created_at`, inventory date, signed date or another operational timestamp? | Needed to avoid misleading timeline status. | Latest-record selection logic |
| OQ-VCTT-004 | Which roles may access PDFs, photos and detail evidence for any searched chassis? | Needed for access-control baseline. | PDF/detail/file endpoints |
| OQ-VCTT-005 | Does `reportado=1` only change UI actions or represent a locked/closed inventory state? | Needed to document lifecycle and permissions. | `generarTablaVehiculos` |

## Executive PowerBI Dashboard Portal

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-EPDP-001 | Which dashboard pages are still active and which are legacy? | Needed to avoid documenting obsolete executive assets as canonical. | `dashboard*.php` |
| OQ-EPDP-002 | What Power BI workspace, dataset, owner and refresh schedule correspond to each dashboard? | Needed for complete technical and business lineage. | Embedded URLs |
| OQ-EPDP-003 | Are `view?r=` published links acceptable under current security policy? | Needed for access-control and data exposure review. | Customer dashboard pages |
| OQ-EPDP-004 | How are ASGARD permissions mapped to Power BI permissions or RLS? | Needed to ensure customer data isolation. | `permisos.php`, Power BI URLs |
| OQ-EPDP-005 | Should local indicador reports and Power BI indicadores be reconciled under one KPI catalog? | Needed for metric consistency. | `dashboardIndicadoresCBN.php`, `reporteindicadoresquery.php` |

## Nationalization Weekly Planning

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-NWP-001 | What is the official Excel format for weekly nationalization planning? | Needed to validate import behavior and user documentation. | `cargar-planificacion` |
| OQ-NWP-002 | Should already-nationalized chassis be blocked, ignored or confirmed with warning? | Needed to avoid corrupting operational dates. | `chasis_nacionalizados` modal |
| OQ-NWP-003 | Which table(s) does `confirmar-planificacion` update? | Needed for traceability and refactor planning. | `dav_casos.fecha_planificacion_nacionalizacion`, API endpoint |
| OQ-NWP-004 | How does `part_planificacion_partida` relate to chasis-level planning? | Needed to document reprogramming and history. | `.data_base/asgard.sql` |
| OQ-NWP-005 | Who may upload and confirm weekly planning, and where is audit stored? | Needed for access control and accountability. | `planificacion-nacionalizacion.php`, API backend |

## Packing List Import Validation

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-PLIV-001 | What is the official active version of `FormatoListaEmpaque.xlsx` and its column contract? | Needed before formalizing parser rules. | `frames/listaempaque.php`, parser methods |
| OQ-PLIV-002 | Are weight comparisons strict or should there be decimal tolerance/rounding? | Needed to avoid false observations. | `uploadExcelListaEmpaque.php` |
| OQ-PLIV-003 | Should observations block PDF generation or only warn the user? | Needed for lifecycle/state model. | `status=200` with `msgAlerta` |
| OQ-PLIV-004 | How are multiple `idcargado` loads for the same `idcasosprevios` superseded or audited? | Needed for history and rollback. | `getUltimaSolicitudLEDisponible`, schema |
| OQ-PLIV-005 | Who may upload/export packing lists and where is generated PDF stored? | Needed for access and retention baseline. | `listaempaque.php`, `armarLEparaPDF` |

## Vehicle Request Bulk Update

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VRBU-001 | Which roles may update FOB, flete, pedido, posicion, valoracion or numero de solicitud? | Needed for access-control baseline. | `updateSolicitudVehiculos.php` |
| OQ-VRBU-002 | Does merchandise id `34` always identify vehicular chasis records? | Needed to avoid wrong updates. | `buscarChasisEnCarpeta` |
| OQ-VRBU-003 | Should previous values be stored before applying mass updates? | Needed for audit and rollback. | `dav_historialmodificacionvehiculos` stores new values/message only. |
| OQ-VRBU-004 | What is the official business approval required to move a chasis to another solicitud? | Needed because the flow updates cases, vehicles and SOAT lots. | `actualizarDatosVehiculos` mode `3` |
| OQ-VRBU-005 | Are financial fields `fob_reporte` and `flete1bruto_reporte` report-only overrides or operational source values? | Needed to document downstream impact. | `VehiculosClass.php` |

## Inventory VIN Billing Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-IVBC-001 | What is the official definition of VIN facturable? | Needed to validate precalculation and billing accuracy. | `facturacion-chasis` API |
| OQ-IVBC-002 | Where are tarifa USD and exchange rate for total Bs configured? | Needed for accounting traceability. | Consolidado mensual fields |
| OQ-IVBC-003 | Can a VIN/chasis be billed in more than one period? | Needed for duplicate billing controls. | `inventario_facturacion_chasis` |
| OQ-IVBC-004 | Who may confirm, reopen or delete billing periods? | Needed for access-control and audit baseline. | `inventario_facturacion_periodo` |
| OQ-IVBC-005 | Does confirmation generate invoices or only billing evidence for later invoicing? | Needed to align with receivables process. | `facturacion-inventario.php` |

## Billing Document Reception Confirmation

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-BDRC-001 | What is the official scope of documents to be received: Planilla, Factura, Nota de Cobranza and Cite only, or more families? | Needed to baseline document coverage. | `recepcionplanillas_ajax.php` |
| OQ-BDRC-002 | Should the receive action capture receiving user, physical recipient, signature, comments or attachments? | Needed for audit and operational proof. | Current updates store timestamps only. |
| OQ-BDRC-003 | Is there an official undo/reversal flow for erroneous reception marks? | Needed for correction and control design. | No reverse transition observed. |
| OQ-BDRC-004 | Why does the query use cutoff date `2021-08-02`? | Needed to distinguish migration residue from active rule. | SQL filters in `gettablaenviadas`/`gettablarecibidos`. |
| OQ-BDRC-005 | Does mass receive require additional approval, confirmation or exception review? | Needed because multiple fiscal/support documents can be confirmed at once. | `recibirvarios` |

## Billing Invoice Planilla Document Generation

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-BIPD-001 | Is the combined Factura & Planilla PDF still an official document or only an operational copy? | Needed to define retention and legal baseline. | `generarfacturaplanillacliente.php` |
| OQ-BIPD-002 | Should generated control codes be persisted in `dav_facturaplanilla.codigocontrol`? | The update is present but commented, affecting auditability. | Commented `UPDATE codigocontrol` |
| OQ-BIPD-003 | What is the official cutoff between legacy dosificacion and factura en linea/electronica? | Needed to validate `iddosificacion <= 39`. | `descargarfactura.php` |
| OQ-BIPD-004 | Which roles may download PDFs by `idfacturaplanilla`? | Needed for document confidentiality and client scoping. | Direct GET endpoints |
| OQ-BIPD-005 | Are `/datadrive1` PDF files authoritative records or generated caches? | Needed for backup, retention and regeneration strategy. | Download scripts |

## Additional Services Request Management

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-ASRM-001 | What is the official state transition matrix for states 0-8? | Needed to validate workflow gates and permissions. | `ages_solicitudes_asesoria_gestion.estado` |
| OQ-ASRM-002 | Which roles may create, send, receive, assign, revise, process, finalize, close or mark as billed? | Needed for access-control baseline. | UI checks only `escritura` and API endpoints. |
| OQ-ASRM-003 | Can tramites be modified after Enviado but before Recepcionado? | Needed to document correction window. | `validaEstado` blocks from Recepcionado onward. |
| OQ-ASRM-004 | Is `hash_tramite` the canonical document-template id for every service type? | Needed for exchange/document automation. | `getDatosIntercambio` |
| OQ-ASRM-005 | Should hardcoded automatic service ids/hashes in `actualizaridexchange.php` be replaced by catalog configuration? | Needed to avoid drift when catalogs change. | Certificado origen, fitosanitario, inocuidad creation. |

## Bulk Request Excel Import Validation

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-BREI-001 | Are clients `560` and `755` still the only clients allowed to use bulk request upload? | Needed to baseline access rules. | `solicitud.php` |
| OQ-BREI-002 | Should the upload be all-or-nothing or should valid rows be created even if other rows fail? | Needed for operational recovery. | `uploadExcelSolicitud.php` |
| OQ-BREI-003 | Should duplicate pedido/orden compra/case checks block import? | Needed to prevent duplicate solicitudes. | No duplicate check observed. |
| OQ-BREI-004 | Should values other than exact `SI` be rejected rather than interpreted as `0`? | Needed to prevent silent false values. | `buscarSeleccion` |
| OQ-BREI-005 | What audit/retention policy applies to uploaded Excel files under `cargasolicitudes`? | Needed for privacy and storage governance. | `guardarArchivo` |

## Bulk Shipment Quotation Import

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-BSQI-001 | What is the official Excel template and column contract for logistics bulk import? | Needed to stabilize import mapping. | `uploadExcelCargaMasiva.php` |
| OQ-BSQI-002 | Should unresolved line, provider or package type block creation? | Needed to prevent malformed shipments. | Catalog lookups return `0`. |
| OQ-BSQI-003 | Is line matching by first three characters intentional? | Needed to avoid incorrect line assignment. | `obtenerIdlinea` |
| OQ-BSQI-004 | Should duplicate pedido/orden compra be detected before creating shipments? | Needed to prevent duplicate logistics records. | No duplicate check observed. |
| OQ-BSQI-005 | Should the whole import be transactional if one row fails after some rows were created? | Needed for rollback and reconciliation. | Loop calls `guardarCotizacionCliente` per row. |

## Customs DAV Client Review Approval

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CDCRA-001 | What is the official catalog for `dav_dav.idestadocliente`? | Needed to validate values `1`, `2`, `9` and any hidden states. | `DemisClass.php` |
| OQ-CDCRA-002 | Should observations be mandatory when the client rejects a DAV/FDM? | Needed for evidence quality and correction workflow. | `davdetalle.php`, `rechazarDemis.php` |
| OQ-CDCRA-003 | Can a client change approval/rejection before finalizing the folder? | Needed for transition matrix and audit requirements. | `cambiarEstadoDav` allows overwrite by `iddav`. |
| OQ-CDCRA-004 | Is there an authorized reopen flow after `finalizardav=1`? | Needed for exception handling. | No reopen path observed. |
| OQ-CDCRA-005 | Should `fecharevisioncliente` and `idusuariorevision` be updated when a decision is made? | Fields are queried but not updated in the inspected method, affecting audit. | `DemisClass.php` |

## Logistics Shipment Finalization Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LSFC-001 | What are the official final EDP states by client and shipment type? | Needed to validate observed values `53`, `160` and `99`. | `finalizar-embarque.php` |
| OQ-LSFC-002 | Is the apparent overwrite of `$estadoEDPFin` to `99` intentional or a defect? | It may make client-specific final states ineffective. | `finalizar-embarque.php:7-13` |
| OQ-LSFC-003 | Are the client `429` gates contractual/SLA rules or temporary operational controls? | Needed before formalizing as canonical business rules. | Required EDP/cost/GA lists. |
| OQ-LSFC-004 | Should shipment closure be atomic across `logis_embarques` and `logis_edp`? | Needed for data consistency and recovery. | `UPDATE` plus `INSERT` without explicit transaction. |
| OQ-LSFC-005 | Is there any authorized reopen or correction flow after finalization? | Needed because UI warns it cannot be enabled again. | No reopen path observed. |

## Logistics Route Trip Assignment Management

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LRTAM-001 | What is the official permission meaning of report id `69`? | Needed to baseline access control. | `embarques_rutas.php`, `listaRutas.php` |
| OQ-LRTAM-002 | Which trips are eligible to be recovered and associated with an embarque? | Needed to document selection rules and prevent wrong linkage. | Recuperar Viajes modal and TCK queries. |
| OQ-LRTAM-003 | Should the system validate route date sequence and duplicate routes? | Needed for planning data quality. | `guardarRuta.php`, `guardarRutaEmbarque` |
| OQ-LRTAM-004 | Does logical deletion of `tck_asignacion_viaje` mean unassigning from the embarque or cancelling the trip operationally? | Needed to avoid confusing logistics and tracking ownership. | `eliminarViaje` |
| OQ-LRTAM-005 | Should assignment validate client, operator and shipment-open state server-side? | Needed for authorization and data-integrity baseline. | `asignarEmbarqueViaje` |

## Shipment Customs Request Management

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-SCRM-001 | What is the official meaning of permission/report id `65`? | Needed for access-control baseline. | `embarques_ver_gestion_aduanera.php` |
| OQ-SCRM-002 | Is client `429` intentionally limited to one GA per embarque? | Needed before formalizing UI rule as business policy. | Count/hide logic in customs tab. |
| OQ-SCRM-003 | What are official meanings of `idtiposolicitud` values `0`, `1` and `2`? | Needed for state/edit rule matrix. | Branches in `actualizarGestionAduanera`. |
| OQ-SCRM-004 | Should GA creation, document seeding, EDP creation and send be transactional? | Needed for consistency and recovery. | `guardarGestionAduanera` multi-step persistence. |
| OQ-SCRM-005 | Should `max(idcasosprevios)` be replaced by created id for concurrency safety? | Needed because concurrent inserts could seed documents on the wrong request. | `SolicitudesClass.php:792-797` |

## Legacy Dispatch Document Maintenance

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LDDM-001 | Is the legacy dispatch module still used in production? | Needed before investing remediation or migration effort. | `despachover.php`, `despachoajax.php` |
| OQ-LDDM-002 | Where are the DDL definitions for `logis_despachos` and `logis_documentos`? | Needed to validate persistence and data dictionary completeness. | Missing from `.data_base/asgard.sql`. |
| OQ-LDDM-003 | Why is cliente `417` hardcoded? | Needed to know if this is customer-specific functionality. | `despachover.php` |
| OQ-LDDM-004 | Should this module be migrated into Document Exchange/OCR or retired? | Needed for modernization planning. | Legacy direct filesystem/document persistence. |
| OQ-LDDM-005 | Is the `INSET INTO` typo present in production code or dead code? | Needed to assess whether document creation works. | `despachoajax.php` |

## Export MIC DEX Physical Reception Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-EMDPRC-001 | What is the official MIC/DEX physical-document state matrix by actor? | Needed to validate accept/reject transitions. | `ActualizarMICs.php` |
| OQ-EMDPRC-002 | Does client accept on `ENVIADO` intentionally mean conclusion? | Needed to confirm ambiguous branch. | `accept q=enviado` updates `fecha_concluido`. |
| OQ-EMDPRC-003 | Should server-side validate that all selected ids share the expected current state? | Needed because UI-only validation can be bypassed. | `recepcion_fisica_mics.js`, `ActualizarMICs.php` |
| OQ-EMDPRC-004 | Are `dex_suma` records created from SUMA integration, import file or manual process? | Needed to document upstream ownership. | Creation not observed in this domain. |
| OQ-EMDPRC-005 | Should history store business date and modification timestamp separately? | Current insert uses `CURRENT_TIMESTAMP()` for both observed fields. | `dex_suma_estado_historial` |

## Alicorp Transit Deadline Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-ATDC-001 | Is client id `775` the complete Alicorp scope for this control? | Needed to avoid missing subsidiaries/lines. | `control_alicorpquery.php` |
| OQ-ATDC-002 | What is the official basis of the 60-day deadline after DEX validation? | Needed before formalizing as contractual/regulatory rule. | `DATE_ADD(fechavalidaciondui, INTERVAL 60 DAY)` |
| OQ-ATDC-003 | Should deadline backfill happen when the report is opened, or in a controlled batch/workflow? | Current implementation mutates business data during query execution. | `control_alicorpquery.php` |
| OQ-ATDC-004 | Should the five-day alert threshold include already-expired cases as a separate state? | Needed for operational prioritization and SLA reporting. | `error_vencimiento` expression |
| OQ-ATDC-005 | What audit evidence is required when OCR marks transit closure as paid? | Current evidence is a case flag, while document/source details are in related OCR flows. | `lectura-ocr-*.php` |

## Customs Tax Liquidation Return Confirmation

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-CTLRC-001 | What exact business event does `fecharetornoliquidacion` represent? | Needed to name the state correctly and avoid confusing email send with approval. | `detalleitems.php` |
| OQ-CTLRC-002 | Who is authorized to confirm return of liquidation? | No explicit permission check is visible in the inspected file. | `detalleitems.php` |
| OQ-CTLRC-003 | Should the confirmation store user, mail response id and recipient snapshot? | Needed for audit and dispute resolution. | Only timestamp update observed. |
| OQ-CTLRC-004 | Is the `$reponse`/`$response` mismatch present in production and does it affect marking return? | It may make success/error handling unreliable. | `detalleitems.php:60-63` |
| OQ-CTLRC-005 | What process sets `fechaenvioliquidacion` before this confirmation becomes available? | Needed to complete upstream lifecycle. | Not observed in this narrow domain. |

## Logistics Shipment Cost Capture Control

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LSCCC-001 | What is the official meaning of permission/report id `70`? | Needed to baseline access control. | `frames/costos.php` |
| OQ-LSCCC-002 | Should cost saves replace the full active set or update categories incrementally? | Current implementation soft-deletes all active rows before insert. | `deleteOldCost` |
| OQ-LSCCC-003 | What categories and concepts are mandatory by client, incoterm or shipment type? | Needed for completeness and finalization rules. | `logis_categorias_costos`, `logis_costos_concepto` |
| OQ-LSCCC-004 | Should automatic merchandise costs be recalculated after manual edits or frozen after closure? | Needed for audit and financial consistency. | `costosInternos` |
| OQ-LSCCC-005 | Should cost save be transactional and audited per user? | Needed because replacement can leave partial data on failure. | `guardarCostos`, `guardaCostoMaestro` |

## Logistics Shipment Document Attachment Management

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LSDAM-001 | Is local attachment mode a legacy fallback or an accepted permanent document channel? | Needed for migration and retention planning. | `frames/documentos.php` |
| OQ-LSDAM-002 | Should local document delete be physical delete, logical delete, or versioned replacement? | Needed for audit and legal retention. | `eliminarEmbarqueDocumentos` |
| OQ-LSDAM-003 | What file types, size limits and antivirus checks are required? | No strong validation observed in upload path. | `guardarEmbarqueDocumentos` |
| OQ-LSDAM-004 | Should document add/delete be blocked after shipment finalization? | Need consistency with other shipment tabs. | Upload/delete endpoints inspected do not show finalization gate. |
| OQ-LSDAM-005 | Are the hardcoded Document Exchange document ids stable catalog values? | Needed to avoid brittle uploads. | `carga-documentos-id.php` |

## Logistics Shipment Quotation Duplication

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LSQD-001 | Which roles may duplicate quotations and shipments? | Permission checks are not explicit in duplicate endpoints. | `duplicarCotizacion.php`, `duplicarEmbarque.php` |
| OQ-LSQD-002 | Should duplicated order numbers/purchase orders be allowed unchanged? | UI warns to review, but no blocking recapture is visible. | JS duplicate warnings |
| OQ-LSQD-003 | Should costs, local documents, routes, GA, EDP or exchange state be copied for duplicated shipments? | Current shipment copy omits several related objects and comments document copy. | `guardarEmbarqueDuplicado` |
| OQ-LSQD-004 | Should duplication be transactional across all copied child tables? | Partial copies could create unusable records. | `CotizacionClass.php` |
| OQ-LSQD-005 | Should exchange creation be performed server-side with rollback, instead of UI after backend success? | Current flow can create shipment without exchange if UI/API fails. | `datosEmbarques.js` |

## Logistics Order Item Detail Maintenance

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LOIDM-001 | Which exact `logis_pedidos_detalle` columns are allowed to be edited from Items? | Current backend trusts POST field names as columns. | `saveDescripcionItems.php` |
| OQ-LOIDM-002 | Should edits be blocked for positions already grouped/embarked/finalized? | Needed to avoid changing operational data after downstream use. | Items view and endpoint |
| OQ-LOIDM-003 | Should updates set `updated_at` and `updated_by`? | Needed for audit. | Schema has `updated_at`; endpoint does not set it. |
| OQ-LOIDM-004 | Are client-specific columns for `417` and `802` official rules? | Needed before formalizing UI behavior. | `items_pedido.php` |

## Vehicle Invoice Data Bulk Update

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VIDBU-001 | Which roles may execute bulk invoice-data corrections for vehicles? | Needed for access-control baseline. | `fechasfacturas.php` |
| OQ-VIDBU-002 | Is SUMA/DAM presence the definitive lock for every field in this flow, or only for invoice date? | Needed because the UI message mentions date but the code skips all updates. | `idsuma` check in `fechasfacturas.php` |
| OQ-VIDBU-003 | Should uploaded Excel files be validated against an official template version, extension, size and duplicate chasis rules? | Needed to avoid malformed or unsafe bulk corrections. | Excel upload/read block |
| OQ-VIDBU-004 | Should all table updates for a chasis be transactional and audited with previous values/user/date? | Needed for rollback, compliance and financial traceability. | Multi-table update block |
| OQ-VIDBU-005 | Is the `idtipodeclaracion` update using `$_DATOS_EXCEL[$c]` instead of the matched `$id` intentional? | It may apply the declaration type from the wrong Excel row when order differs. | `fechasfacturas.php:150` |

## Alicorp OCR Bulk Shipment Intake

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-AOBSI-001 | Which users/roles may run Alicorp OCR mass intake? | Needed for access-control baseline. | `formOCRMasivo`, `get-ocr-alicorp-masivo.php` |
| OQ-AOBSI-002 | Should the flow block duplicate invoices, pedidos or ordenes before creating shipments? | Needed to avoid duplicate logistics/customs records. | No duplicate guard observed. |
| OQ-AOBSI-003 | What is the official recovery process when an embarque is created but GA, exchange or packing-list association fails? | Needed because the flow can leave partial records. | Backend includes plus UI AJAX chain. |
| OQ-AOBSI-004 | Are hardcoded service rules by line/proveedor/product/weight still valid and who owns them? | Needed before formalizing additional-service automation. | `cargar_servicios` switch blocks. |
| OQ-AOBSI-005 | Should OCR mass intake have a staging/review step before entity creation? | Current flow creates records immediately from OCR data. | `get-ocr-alicorp-masivo.php` |
| OQ-AOBSI-006 | Is the apparent response field `filesLE=>$filesLEm` a production defect or dead field? | Undefined response data can affect diagnostics/UI. | Response construction in `get-ocr-alicorp-masivo.php`. |

## Shipment Commercial Invoice Reference Sync

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-SCIRS-001 | Which UI/service calls `embarquereferencia.php` and at what point in the exchange lifecycle? | Needed to place the flow in the end-to-end journey. | Endpoint source only. |
| OQ-SCIRS-002 | Is client `429` the only intended scope for this synchronization? | Needed before formalizing client-specific behavior. | `intval($idcliente) == 429`. |
| OQ-SCIRS-003 | Should existing `facturacomercial` values be overwritten or merged with history? | Needed for audit and correction behavior. | Update overwrites field. |
| OQ-SCIRS-004 | Should the endpoint validate that `idembarque` belongs to the session client? | Needed for authorization and data-integrity baseline. | Direct update by posted id. |

## Logistics Shipment Edit Participant Sync

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-LSEPS-001 | Which shipment states permit editing cabecera, tramos, magnitudes and operator? | Needed to avoid changes after operational/fiscal closure. | Edit form plus server methods. |
| OQ-LSEPS-002 | Should child replacement preserve previous magnitudes/tramos/operators as history instead of hard delete? | Needed for audit and rollback. | Delete/insert logic. |
| OQ-LSEPS-003 | Should local edit and document participant sync be transactional or reconciled by retry queue? | Needed to prevent divergence with exchange. | API call after local update. |
| OQ-LSEPS-004 | What is the exact business difference between editing cotizacion and editing embarque for operators? | Needed to formalize mode-specific lifecycle. | `actualizarEmbarque` branch. |
| OQ-LSEPS-005 | Are assignment emails/notifications required for client `802` or intentionally excluded? | Needed to validate client-specific communication rule. | Conditions exclude `802`. |

## Vehicle Excel Intake Validation

| ID | Question | Why it matters | Evidence Context |
| --- | --- | --- | --- |
| OQ-VEIV-001 | What is the official meaning of vehicle error codes `1`, `2` and `3`? | Needed to formalize blocking vs warning vs master-data workflow. | `dav_vehiculosprevios.error`. |
| OQ-VEIV-002 | Should every vehicle upload create a versioned load rather than deleting/replacing rows? | Needed for audit and rollback. | Delete/insert in `CargaVehiculos.php`. |
| OQ-VEIV-003 | Who approves catalog insertions/modifications requested from vehicle validation errors? | Needed to complete responsibility matrix. | Mail/request text in `vehiculos.php`. |
| OQ-VEIV-004 | Which Excel template versions are valid for main upload, DAM upload and completion upload? | Needed before refactor/parser hardening. | `CargaVehiculos.php`, `VehiculosExcel.php`. |
| OQ-VEIV-005 | When exactly should `fecha_info_completa` be set and can it be reversed? | Needed for AP/DAM downstream gates. | `vehiculos.php:712`. |
| OQ-CDOVU-001 | Who owns approval of OCR-applied DUI/Sidunea/date updates before they become official customs data? | Customs operations | OPEN |
| OQ-CDOVU-002 | Should DEX comparison differences be persisted as review tasks instead of only returned in the AJAX response? | Product / operations | OPEN |
| OQ-CDOVU-003 | What exact declaration format variants are valid for parsing `gestiondui` and `nodui`? | Customs SME | OPEN |
| OQ-AAOPR-001 | What business concept does `idconcepto=272` represent officially and is it exclusive to ALBO/FALBO reconciliation? | Finance / customs SME | OPEN |
| OQ-AAOPR-002 | Should payment matching tolerate rounding/currency differences or require exact amount equality as observed? | Finance | OPEN |
| OQ-AAOPR-003 | Should ZIP/RAR and PDF OCR branches persist the same metadata and audit trail? | Product / engineering | OPEN |
| OQ-AAOPR-004 | Who owns rotation/removal of hardcoded SSH credentials and remote decompression host configuration? | Security / infrastructure | OPEN |
| OQ-LBPOC-001 | What is the official tolerance/action when BL date is earlier than policy date or vice versa? | Logistics operations | OPEN |
| OQ-VSILC-001 | Is `logis_libroventas` owned by logistics, vehicle operations or finance for OCR-created invoice rows? | Operations / finance | OPEN |
| OQ-DSPC-001 | What business document is represented by `dav_planillasdp` and who validates rejected OCR reads? | Product / operations | OPEN |
| OQ-DSPC-002 | Should Azure OCR credentials be externalized and SSL verification enforced? | Security / engineering | OPEN |
| OQ-ASOPR-001 | Should SENAVEX, FDAB, Jennefer and ALBO/FALBO reconciliation be one canonical provider-payment domain or separate provider flows? | Product / finance | OPEN |
| OQ-ASOPR-002 | What are the official meanings of concepts `208`, `256`, `270`, `271`, `273` and `274`? | Finance / customs SME | OPEN |
| OQ-CDDSDC-001 | Should DAM send date be set by document event alone, or only after content/status validation of the DAM document? | Customs operations | OPEN |
| OQ-CDDSDC-002 | Who should receive alerts when DAM cannot be marked due to missing AP date? | Operations | OPEN |
| OQ-CSRF-001 | What is the official rating scale and is comment mandatory for low scores? | Product / customer success | OPEN |
| OQ-CSRF-002 | Should a customer be allowed to submit multiple ratings for the same context within the 30-day window? | Product | OPEN |
| OQ-CPR-001 | What is the intended recovery-token lifetime: calendar day, fixed hours/minutes, or configurable TTL? | Security / product | OPEN |
| OQ-CPR-002 | Should the reset token be stored hashed and protected against brute-force attempts? | Security / engineering | OPEN |
| OQ-CPR-003 | Should the SendGrid API key and BCC recipient be externalized from source and environment-specific? | Security / infrastructure | OPEN |
| OQ-CPR-004 | Should resetting a password require an audit/event log beyond the reset row and user update timestamp? | Compliance / operations | OPEN |
| OQ-VRPODG-001 | What is the official meaning of document type `71` and document 901 in the reception lifecycle? | Customs / vehicle operations | OPEN |
| OQ-VRPODG-002 | Are client ids `417` and `755` the complete intended scope or a temporary rollout gate? | Product / operations | OPEN |
| OQ-VRPODG-003 | Should chasis not found by OCR create a structured exception task instead of only an email alert? | Operations / product | OPEN |
| OQ-VRPODG-004 | Should `ocr_parte_recepcion` and `dav_documentos` insertion run transactionally with duplicate/concurrency protection? | Engineering | OPEN |
| OQ-CPLSA-001 | Who may use master password login and what approval/audit is required? | Security / compliance | OPEN |
| OQ-CPLSA-002 | Should failed-attempt locking use rolling window, fixed 24h lock, or configurable policy? | Security / product | OPEN |
| OQ-CPLSA-003 | Should `ultimoenlace` be restricted to internal routes and allowlisted? | Security / engineering | OPEN |
| OQ-CPLSA-004 | What privacy notice or retention rule applies to IP, browser and geolocation cookies in `log_asgard_ecosistema`? | Legal / security | OPEN |
| OQ-TPTDO-001 | Should third-party onboarding tokens expire, be single-use or rotate after document completion? | Security / product | OPEN |
| OQ-TPTDO-002 | Which third-party families require formal approval after token completion? | Operations / compliance | OPEN |
| OQ-TPTDO-003 | Should aduana, seguro and gestor onboarding be one canonical workflow or separate owned workflows? | Product / operations | OPEN |
| OQ-TPTDO-004 | Which documents are mandatory by third-party type and customer? | Compliance / operations | OPEN |
| OQ-AODPD-001 | Are customers `755` and `775` the complete intended scope for Alicorp/IASA document dispatch? | Operations / product | OPEN |
| OQ-AODPD-002 | Should document package dispatch require delivery confirmation before marking `embarque_documentos_enviados`? | Operations / engineering | OPEN |
| OQ-AODPD-003 | How are re-sends, failed downloads and corrected document packages handled? | Operations | OPEN |
| OQ-AODPD-004 | Should Document Exchange credentials and SSL policy be externalized/hardened for the cron? | Security / infrastructure | OPEN |
| OQ-VSPOCR-001 | What is the downstream action after SOAT PDFs are generated under `/datadrive1/temporales/soat`? | Vehicle operations | OPEN |
| OQ-VSPOCR-002 | Should generated SOAT PDFs be attached automatically to vehicle/case documents? | Product / operations | OPEN |
| OQ-VSPOCR-003 | Which document ids are officially supported for SOAT/comprobante splitting, and can they change by client? | Product / document exchange | OPEN |
| OQ-VSPOCR-004 | Should temporary SOAT outputs have cleanup, collision protection and audit records? | Engineering / security | OPEN |
