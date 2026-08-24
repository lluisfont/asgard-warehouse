# Business glossary

Estado: candidate_reconstruction
Confianza: media
Idioma: Spanish

Este glosario ha sido reconstruido a partir de dominios, tablas, campos, reglas, flujos y evidencias cruzadas. No es un glosario canonico: cada termino mantiene estado candidato hasta validacion humana.

## Resumen

- Terminos: 107
- Fuente: diccionario semantico, segunda pasada de flujos, reglas por dominio, evidencia Graphify/PHP/SQL.
- Uso recomendado: validacion funcional, workshops de negocio, priorizacion de pruebas de caracterizacion.

## Terminos por categoria

### Accion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Alta` | Creacion inicial de entidad transaccional, como solicitud GA, caso, documento, token o catalogo. | INSERT flows | form1-modification-observation-tracking | candidate |

### Accion de flujo

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Guardar y enviar` | Accion UI que persiste la solicitud y ademas dispara el envio/cierre operativo inmediato. | `guardarEnviar`, shipment-customs-request-management |  | candidate |

### Accion restringida

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Edicion aprobada` | Actualizacion limitada permitida sobre entidad que ya alcanzo aprobacion o caso formal. | shipment-customs-request-management |  | candidate |

### Acronimo / documento

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `DAM` | Documento/declaracion aduanera mencionado en flujos de control, OCR, envio o pendientes; su significado exacto requiere validacion local. | customs-dam-document-send-date-control, campos `dam*` |  | candidate |
| `DEX` | Documento/declaracion de exportacion usado en flujos de OCR, actualizacion y validacion documental. | customs-dex-ocr-validation-update |  | candidate |

### Acronimo / dominio

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `DAV` | Dominio/tablas de declaracion o gestion aduanera con partidas, mercancia, parametros, acuerdos, documentos, costos y aprobaciones. | Familia `dav_*` |  | candidate |

### Actor

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Operador interno` | Usuario operativo que gestiona expedientes, documentos, estados, costos, excepciones y cierre de procesos. | Dominios aduaneros/logisticos |  | candidate |
| `Tercero` | Proveedor, agente, transportista o contacto externo que aporta informacion, documentos o coordinacion bajo permisos/tokens. | third-party-token-document-onboarding |  | candidate |
| `Usuario cliente` | Usuario autenticado asociado a cliente, permisos y sesion; participa en consulta, carga, aprobacion o seguimiento. | identity-access, dav_clienteusuarios* |  | candidate |

### Actor / dato maestro

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Cliente / tenant` | Empresa o segmento funcional que condiciona visibilidad de datos, permisos, reportes, documentos y variantes de proceso. | Campos `idcliente`, permisos y reportes cliente | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-ocr-bulk-shipment-intake, alicorp-operational-document-package-dispatch, alicorp-transit-deadline-control | candidate |

### Actor / entidad

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Agente de aduana` | Entidad o tercero que participa en tramites aduaneros, contactos, documentos y relaciones cliente-agente. | Tablas `ada_*` |  | candidate |
| `Proveedor/coordinador` | Tercero relacionado con gestion documental, transporte, coordinacion o servicios asociados al caso/embarque. | Tablas `prov_*`, `dav_proveedor*` | alicorp-transit-deadline-control, bulk-request-excel-import-validation, bulk-shipment-quotation-import, certification-expiry-control, customs-dav-client-review-approval, customs-request-intake | candidate |

### Aduanas

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Partida` | Linea declarativa o item dentro de una declaracion/tramite aduanero. | `dav_partidas` | alicorp-transit-deadline-control, certification-expiry-control, customs-dav-client-review-approval, customs-guarantee-tax-control, nationalization-weekly-planning, operational-expense-control | candidate |

### Aduanas / calculo

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `CIF` | Valor candidato usado en calculos aduaneros/tributarios; aparece como campo monetario `cif_bs`. | `cif_bs` |  | candidate |

### Aduanas / catalogo

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Regimen` | Clasificador aduanero o modalidad normativa que condiciona documentos, calculos o tramite. | Campos `idregimen` | bulk-request-excel-import-validation, customs-request-intake, vehicle-transitory-depot-compliance | candidate |

### Aduanas / finanzas

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Tributos` | Importes o pagos aduaneros/fiscales solicitados, confirmados o liquidados durante el flujo. | `solicitudpagotributos`, customs-tax-liquidation-* |  | candidate |

### Auditoria / lifecycle

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Borrado logico` | Marca `deleted_at/deleted_by` que desactiva sin eliminar fisicamente, preservando historia. | Campos `deleted_*` |  | candidate |

### Automatizacion documental

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `OCR` | Extraccion automatica de datos desde PDF, imagen, Excel o paquete documental; requiere revision ante errores. | document-exchange-ocr, Alicorp OCR |  | candidate |

### Autorizacion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Permiso` | Control que habilita pantalla, reporte o accion; puede estar distribuido entre menu, sesion, SQL y endpoint. | `dav_clienteusuariospermisos`, permiso 65 | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-transit-deadline-control, billing-invoice-planilla-document-generation, billing-payments-receivables | candidate |

### Calidad

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `No conformidad` | Registro o gestion de problema de calidad/mejora continua. | continuous-improvement-nonconformity |  | candidate |

### Comercio exterior

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Incoterms` | Condicion comercial/logistica candidata asociada a entrega y declaracion. | `idincoterms`, `dav_condicionentrega` | alicorp-transit-deadline-control, vehicle-cost-accounting-reporting | candidate |

### Comportamiento

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Side effect` | Efecto colateral de un flujo: escritura, correo, documento generado, EDP, token, notificacion o archivo. | SIDE_EFFECT_CATALOG |  | candidate |

### Configuracion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Parametro` | Valor configurable por cliente/proceso que modifica comportamiento, documentos requeridos, costos, reportes o validaciones. | `dav_parametros*`, `logis_*` | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-transit-deadline-control, billing-invoice-planilla-document-generation, billing-payments-receivables | candidate |

### Consistencia

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Atomicidad` | Propiedad esperada de completar o revertir un conjunto de cambios relacionados; riesgo cuando falta transaccion. | FLOW semantic risks | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-albo-ocr-payment-reconciliation, alicorp-ocr-bulk-shipment-intake, alicorp-supplier-ocr-payment-reconciliation | candidate |
| `Concurrencia` | Riesgo de resultados incorrectos por operaciones simultaneas, especialmente con `max(id)` o secuencias no atomicas. | shipment-customs-request-management | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-albo-ocr-payment-reconciliation, alicorp-ocr-bulk-shipment-intake, alicorp-supplier-ocr-payment-reconciliation | candidate |
| `Transaccion` | Unidad de consistencia esperada entre varias escrituras/side effects, aunque no siempre exista transaccion DB real. | behavior/transaction analysis | alicorp-albo-ocr-payment-reconciliation, alicorp-supplier-ocr-payment-reconciliation, alicorp-transit-deadline-control, customer-shipment-rating-feedback, customs-dam-document-send-date-control, customs-dav-client-review-approval | candidate |

### Control

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Auditoria` | Trazabilidad de quien creo, modifico o elimino un registro y cuando ocurrio. | `created_by`, `updated_by`, `deleted_by` |  | candidate |
| `Conciliacion` | Comparacion de datos operativos/documentales/financieros para detectar diferencias y confirmar consistencia. | `dav_conciliacion`, OCR/payment reconciliation |  | candidate |

### Dato maestro

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Division` | Clasificador organizativo o comercial que participa en solicitud, proveedor o cliente. | `dav_division` | shipment-customs-request-management | candidate |
| `Linea cliente` | Subsegmento/linea de cliente usado para producto, vehiculo o reglas de solicitud. | `idclientelineas` |  | candidate |
| `Tipo producto` | Clasificador por cliente/producto usado en gestion aduanera o solicitud. | `dav_clientetipoproducto` |  | candidate |

### Dato referencia

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Catalogo` | Tabla o conjunto de valores de referencia que parametriza tipos, estados, permisos, documentos, rutas, mercancias o reglas. | Tablas `tipo*`, `estado*`, `parametro*` | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-transit-deadline-control, billing-invoice-planilla-document-generation, billing-payments-receivables | candidate |

### Dato tecnico-funcional

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Staging` | Zona o tabla intermedia para carga, normalizacion, importacion Excel/OCR o preparacion de reporte. | `tmp_*`, bulk imports | alicorp-transit-deadline-control, customs-document-approval, customs-tax-liquidation-return-confirmation, logistics-shipment-tracking, transport-export-tracking | candidate |
| `Tabla temporal` | Tabla `tmp_*` usada como staging, validacion, reporte intermedio o consulta auxiliar. | 599 tablas `tmp` detectadas | alicorp-transit-deadline-control, customs-document-approval, customs-tax-liquidation-return-confirmation, logistics-shipment-tracking, transport-export-tracking | candidate |
| `Vista / read model` | Estructura derivada de consulta usada principalmente para reporteria o lectura consolidada. | Prefijo `v_`, reporting models |  | candidate |

### Documento

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Carga documental` | Subida de archivos vinculados a caso, embarque, OCR, factura, BL, SOAT o soporte operativo. | FILE_UPLOAD_DOWNLOAD_CATALOG |  | candidate |
| `Certificado de origen` | Documento/certificado candidato requerido en flujos de importacion/OCR o servicios adicionales. | alicorp-ocr-bulk-shipment-intake |  | candidate |
| `Documento previo` | Documento inicial generado o requerido al crear una solicitud antes de completar el expediente. | `dav_documentosprevios` |  | candidate |
| `Paquete documental` | Conjunto de archivos asociados a una operacion, cliente o tercero, posiblemente ZIP/RAR/PDF. | alicorp-operational-document-package-dispatch |  | candidate |

### Documento / catalogo

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Formato de documento` | Clasificador de presentacion o estructura de un documento requerido/generado. | `idformatodocumento` |  | candidate |
| `Tipo de documento` | Clasificador que determina validacion, formato, OCR, descarga, aprobacion o documentos previos. | `idtipodocumento` |  | candidate |

### Documento / configuracion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Documento predeterminado` | Documento configurado como requerido por cliente, modalidad, regimen u otra condicion. | `dav_documentos_predeterminados` |  | candidate |

### Documento / seguridad

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Descarga documental` | Obtencion de documentos o paquetes por usuario autorizado; sensible a permisos, rutas y cliente. | `download.php`, operational-reporting-downloads |  | candidate |

### Documento financiero

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Factura` | Documento comercial/financiero vinculado a venta, cobro, OCR, referencia comercial o libro contable. | `dav_factura*`, `logis_factura*`, vehicle-sales-invoice-* | accounting-ledger-aging-reporting, alicorp-albo-ocr-payment-reconciliation, alicorp-operational-document-package-dispatch, alicorp-supplier-ocr-payment-reconciliation, alicorp-transit-deadline-control, billing-document-reception-confirmation | candidate |
| `Nota de cobranza` | Documento o comunicacion de cobro asociado a recepcion, envio o seguimiento de pagos. | billing-payments-receivables |  | candidate |
| `Planilla` | Documento operativo/financiero generado para facturacion, control contable, cobro o soporte de servicio. | billing-invoice-planilla-document-generation | accounting-ledger-aging-reporting, billing-document-reception-confirmation, billing-invoice-planilla-document-generation, billing-payments-receivables, customs-case-edp-status-monitoring, customs-guarantee-tax-control | candidate |

### Documento logistico

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `BL` | Documento logistico de embarque capturado o validado por OCR en flujos de politica/documentacion. | logistics-bl-policy-ocr-capture |  | candidate |
| `Packing list` | Documento/lista de empaque usado para validacion de importacion o comparacion documental. | packing-list-import-validation |  | candidate |

### Documento vehicular

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `SOAT` | Documento vehicular sujeto a OCR/splitting y validacion documental. | vehicle-soat-pdf-ocr-splitting | vehicle-import-management, vehicle-request-bulk-update | candidate |

### Documento/regulatorio

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Fitosanitario` | Servicio/certificado candidato de control sanitario vegetal o regulatorio. | alicorp-ocr-bulk-shipment-intake |  | candidate |
| `Inocuidad` | Servicio/certificado candidato detectado como requisito adicional segun producto/proveedor/peso. | alicorp-ocr-bulk-shipment-intake |  | candidate |

### Dominio

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Gestion Aduanera (GA)` | Conjunto de actividades para preparar, revisar, documentar, aprobar y seguir tramites aduaneros del cliente. | Dominios `customs-*`, tablas `dav_*` |  | candidate |

### Entidad de negocio

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Caso` | Expediente operacional que agrupa solicitud, cliente, documentos, estados, costos, aprobaciones y seguimiento. | `dav_casos`, `dav_casosprevios` | accounting-ledger-aging-reporting, advisory-management-services, alicorp-albo-ocr-payment-reconciliation, alicorp-ocr-bulk-shipment-intake, alicorp-operational-document-package-dispatch, alicorp-supplier-ocr-payment-reconciliation | candidate |
| `Caso previo / solicitud previa` | Registro inicial o pre-expediente usado para capturar una solicitud antes de cierre, envio o conversion a caso formal. | `dav_casosprevios` |  | candidate |
| `Mercancia` | Bien, item o conjunto declarado/transportado con atributos aduaneros, logisticos, documentales y de costo. | `dav_mercancia*`, `logis_tipomercancia*` | certification-expiry-control, customs-dav-client-review-approval, logistics-quotation-costing, logistics-shipment-tracking, third-party-token-document-onboarding, vehicle-transitory-depot-compliance | candidate |

### Entidad logistica

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Embarque` | Movimiento logistico/aduanero asociado a cliente, mercancia, documentos, costos, tracking y finalizacion. | `logis_embarques` | additional-services-request-management, alicorp-operational-document-package-dispatch, alicorp-transit-deadline-control, bulk-shipment-quotation-import, customs-dav-client-review-approval, document-exchange-ocr | candidate |

### Entidad vehicular

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `VIN / chasis` | Identificador vehicular usado para trazabilidad, inventario, facturacion y control documental. | vehicle-chassis-timeline-trace |  | candidate |
| `Vehiculo` | Unidad importada/gestionada por chasis/VIN, factura, SOAT, inventario o deposito. | vehicle-* domains | vehicle-cost-accounting-reporting, vehicle-excel-intake-validation, vehicle-import-management, vehicle-invoice-data-bulk-update, vehicle-request-bulk-update, warehouse-inventory-reporting | candidate |

### Estado / ciclo de vida

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Finalizacion` | Cierre o marca temporal que impide o limita nuevas acciones sobre embarque/solicitud/caso. | `fecha_finalizacion`, `fechafin` |  | candidate |

### Estado / control

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Aprobacion` | Accion o estado que confirma documento, solicitud o paso de proceso y habilita avance. | customs-document-approval |  | candidate |

### Estado / hito

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `EDP` | Estado/evento de proceso usado para registrar hitos historicos de caso o embarque, con fecha, estado y observacion. | `dav_edp`, `logis_edp`, customs-case-edp-status-monitoring |  | candidate |

### Excepcion / estado

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Observacion` | Comentario o motivo de revision/rechazo usado para devolver, corregir o bloquear documentos/tramites. | `observacion*`, form1-modification-observation-tracking | advisory-management-services | candidate |

### Finanzas

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Pago` | Movimiento economico o confirmacion financiera usado en conciliaciones, cuentas por cobrar o reporteria. | `dav_pagos*`, `ages_pagos*` | accounting-ledger-aging-reporting, advisory-management-services, alicorp-albo-ocr-payment-reconciliation, alicorp-supplier-ocr-payment-reconciliation, billing-invoice-planilla-document-generation, billing-payments-receivables | candidate |

### Finanzas / logistica

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Costo / gasto` | Importe economico de servicio, transporte, despacho, tramite o componente financiero de la operacion. | `costo*`, `gasto*`, logistics-quotation-costing | advisory-management-services, logistics-quotation-costing, logistics-shipment-cost-capture-control, logistics-shipment-finalization-control, transport-export-tracking | candidate |

### Finanzas / reporting

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Aging` | Reporte o registro financiero de antiguedad/saldos; en el repo aparece asociado a `dav_aging` y terminos de cuenta/ahorro. | accounting-ledger-aging-reporting | accounting-ledger-aging-reporting, alicorp-transit-deadline-control, customs-document-approval, customs-tax-liquidation-return-confirmation, logistics-shipment-tracking, transport-export-tracking | candidate |

### Gobernanza

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Baseline candidato` | Conjunto de documentacion AS-IS inferida desde codigo/schema/evidencia, no confirmado aun por negocio. | PROJECT_STATE / OpenSpec |  | candidate |
| `Dominio candidato` | Agrupacion funcional reconstruida desde archivos, tablas, reglas, flujos y evidencias; requiere validacion. | 70 dominios |  | candidate |
| `OpenSpec` | Estructura de baseline y cambios usada para gobernar especificaciones, evidencias y futuras modificaciones. | openspec |  | candidate |
| `Validacion humana` | Revision posterior por responsables de negocio/TI para confirmar, corregir o rechazar inferencias. | PROJECT_STATE |  | candidate |

### Impuesto

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `ICE` | Impuesto/campo de calculo asociado a importes o litros; aparece como `porcentajeICE` e `ICE_litro`. | `porcentajeICE`, `ICE_litro` |  | candidate |
| `IVA` | Impuesto o porcentaje usado en calculos de liquidacion/facturacion; aparece como `porcentajeIVA`. | `porcentajeIVA` |  | candidate |

### Integracion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Pusher` | Servicio realtime candidato para notificaciones o canales en tiempo real. | realtime-notification-center |  | candidate |
| `SFTP` | Canal de intercambio de archivos externo candidato. | integration context |  | candidate |
| `SendGrid` | Servicio externo candidato para envio de correo/notificaciones. | security/integrations findings |  | candidate |

### Integracion / dato

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `SAP` | Sistema externo o codigo de integracion mencionado en campos `codigo_sap` y datos maestros. | `codigo_sap` |  | candidate |

### Integracion / reporting

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Power BI` | Canal de inteligencia de negocio embebido o enlazado para dashboards ejecutivos. | executive-powerbi-dashboard-portal |  | candidate |

### Logistica

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Aeropuerto` | Ubicacion logistica aerea usada en cotizacion o embarque. | `logis_aeropuertos`, `dav_aerolineas` | logistics-quotation-costing | candidate |
| `Carga` | Unidad o conjunto transportado que participa en costo, ruta, entrega, tipo de mercancia o contenedor. | `tck_carga`, `logis_tipocarga` | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-albo-ocr-payment-reconciliation, alicorp-ocr-bulk-shipment-intake, alicorp-operational-document-package-dispatch | candidate |
| `Contenedor` | Unidad de transporte vinculada a carga, costo o embarque. | `logis_contenedor` | logistics-quotation-costing, logistics-shipment-edit-participant-sync, logistics-shipment-quotation-duplication | candidate |
| `Ruta` | Secuencia o tramo logistico asociado a viaje, carga, origen/destino y seguimiento. | logistics-route-trip-assignment-management | bulk-shipment-quotation-import, logistics-route-trip-assignment-management | candidate |

### Logistica / aduanas

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Puerto` | Ubicacion logistica de origen/destino o referencia de tramite. | `dav_puerto` | logistics-quotation-costing, logistics-shipment-tracking | candidate |

### Logistica / catalogo

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Modalidad de transporte` | Clasificador que determina documentos o reglas de transporte en solicitudes/embarques. | `idmodotransporte`, `dav_modotransportedocumento` |  | candidate |

### Logistica / tracking

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Viaje` | Ejecucion de transporte con orden, ruta, carga, imagenes, kilometraje o eventos. | `tck_orden_viaje`, `tck_carga` | customs-operational-kpi-control, logistics-route-trip-assignment-management, logistics-shipment-tracking, master-data-configuration, transport-export-tracking, vehicle-transitory-depot-compliance | candidate |

### Metrica

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `KPI` | Indicador de proceso o gestion, como volumen, tiempos, pendientes, vencimientos, estados, backlog o costos. | KPI_CATALOG y reporting |  | candidate |

### Operacion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Inventario` | Control de unidades, stock, VIN/chasis, facturacion o reporteria de almacen. | warehouse-inventory-reporting, inventory-vin-billing-control | inventory-vin-billing-control, vehicle-transitory-depot-compliance, warehouse-inventory-reporting | candidate |
| `Workaround` | Procedimiento manual alternativo ante fallo de sistema, OCR, integracion o datos incompletos. | WORKAROUND_CATALOG |  | candidate |

### Operacion / integracion

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Fallback` | Ruta alternativa ante fallo de integracion o automatizacion; puede ser manual. | FALLBACK_PROCESSES |  | candidate |

### Proceso

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Hito` | Marca de avance dentro de un caso, embarque, documento o proceso; suele registrarse con fecha y estado. | Estados EDP y seguimiento logistico |  | candidate |
| `Servicio adicional` | Tramite o servicio extra detectado por datos/documentos, como certificados o gestiones asociadas. | additional-services-request-management |  | candidate |
| `Solicitud` | Entrada de servicio o gestion iniciada por usuario/cliente; puede crear caso, documentos previos y estados de seguimiento. | customs-request-intake, shipment-customs-request-management | additional-services-request-management, advisory-management-services, alicorp-transit-deadline-control, bulk-request-excel-import-validation, customs-request-intake, logistics-shipment-tracking | candidate |

### Proceso / catalogo

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Tipo de solicitud` | Clasificador que altera campos requeridos, linea cliente o comportamiento de creacion/envio. | `idtiposolicitud`, `tmp_tiposolicitud` |  | candidate |

### Proceso aduanero

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Solicitud GA` | Solicitud de Gestion Aduanera asociada a un embarque o cliente; crea registros en `dav_casosprevios` y documentos previos. | shipment-customs-request-management |  | candidate |

### Producto

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `ASGARD` | Plataforma legacy analizada para operaciones de clientes, comercio exterior, aduanas, logistica, documentos, facturacion y reporteria. | Repositorios y dominios reconstruidos | customer-primary-login-session-audit, operational-reporting-downloads | candidate |

### Regla / dato referencia

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Estado` | Codigo o bandera que controla visibilidad, bloqueo, avance, aprobacion, finalizacion o clasificacion operacional. | Campos `idestado*`, `estado*`, `status` | accounting-ledger-aging-reporting, additional-services-request-management, advisory-management-services, alicorp-albo-ocr-payment-reconciliation, alicorp-ocr-bulk-shipment-intake, alicorp-operational-document-package-dispatch | candidate |

### Reporteria

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Dashboard generico` | Pantalla o endpoint de indicadores agregados para control operativo/ejecutivo. | `ajax/DashboardGenerico.php` |  | candidate |
| `Reporte cliente` | Reporte visible o habilitado para un cliente concreto, condicionado por permisos y filtros multi-cliente. | `dav_clientereportescliente` |  | candidate |

### Riesgo de regla

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Magic value` | Valor literal en codigo o datos que representa una regla de negocio no documentada, como permiso, cliente especial o estado. | Business/behavior analysis |  | candidate |

### Seguridad

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `2FA / MFA` | Segundo factor de autenticacion usado para reforzar acceso y validar sesion. | identity-access |  | candidate |
| `Secreto` | Clave, token o credencial tecnica que no debe estar hardcodeada ni visible en reportes. | SECRET_MANAGEMENT_FINDINGS |  | candidate |
| `Sesion` | Estado autenticado de usuario usado para permisos, cliente, auditoria y filtros. | identity-access |  | candidate |

### Seguridad / dato

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `PII / dato personal` | Dato de persona o contacto como nombre, correo, telefono, direccion, NIT o identificador. | Semantic field sensitivity |  | candidate |

### Seguridad / tercero

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Token de tercero` | Credencial o enlace temporal para acceso controlado de proveedor/contacto a documentos o procesos. | third-party-token-document-onboarding |  | candidate |

### Tecnico / dato

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Soft delete` | Sinonimo tecnico de borrado logico mediante marcas de fecha/usuario. | `deleted_at`, `deleted_by` |  | candidate |

### Testing

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Caracterizacion` | Estrategia de pruebas para fijar contratos reales: entradas, salidas, side effects, documentos y errores. | engineering-analysis/tests |  | candidate |
| `Golden master` | Prueba de caracterizacion que captura comportamiento legacy esperado antes de refactor. | OpenSpec / tests |  | candidate |

### Trazabilidad

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Evidencia cruzada` | Relacion entre flujo, regla, tabla, campo, endpoint o fuente que sostiene una inferencia funcional. | semantic-flow-analysis |  | candidate |

### Vehiculos / cumplimiento

| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |
|---|---|---|---|---|
| `Deposito transitorio` | Estado o proceso vehicular/logistico asociado a cumplimiento y permanencia temporal. | vehicle-transitory-depot-compliance |  | candidate |

## Preguntas de validacion

- Confirmar siglas locales: DAV, DAM, DEX, EDP, GA, CIF, ICE.
- Confirmar sinonimos usados por usuarios frente a nombres de tablas/campos.
- Confirmar si terminos financieros como planilla, nota de cobranza, aging y conciliacion tienen definicion contractual.
- Confirmar diferencias por cliente, pais, unidad de negocio o modulo legacy.
- Confirmar que terminos de documento/OCR corresponden a formatos reales y no solo nombres de codigo.
