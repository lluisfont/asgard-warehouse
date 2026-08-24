from __future__ import annotations

import csv
import re
from collections import Counter, defaultdict
from pathlib import Path


ROOT = Path(__file__).resolve().parents[3]
RELEASE = ROOT / ".brownfield" / "work" / "release"
BUSINESS = RELEASE / "business-analysis"
DATA = RELEASE / "engineering-analysis" / "data"
FLOW = BUSINESS / "semantic-flow-analysis"
OPENSPEC = RELEASE / "openspec"
TRACE = RELEASE / "traceability" / "verification"


TERMS = [
    ("ASGARD", "Plataforma legacy analizada para operaciones de clientes, comercio exterior, aduanas, logistica, documentos, facturacion y reporteria.", "Producto", "Repositorios y dominios reconstruidos", "candidate"),
    ("Cliente / tenant", "Empresa o segmento funcional que condiciona visibilidad de datos, permisos, reportes, documentos y variantes de proceso.", "Actor / dato maestro", "Campos `idcliente`, permisos y reportes cliente", "candidate"),
    ("Usuario cliente", "Usuario autenticado asociado a cliente, permisos y sesion; participa en consulta, carga, aprobacion o seguimiento.", "Actor", "identity-access, dav_clienteusuarios*", "candidate"),
    ("Operador interno", "Usuario operativo que gestiona expedientes, documentos, estados, costos, excepciones y cierre de procesos.", "Actor", "Dominios aduaneros/logisticos", "candidate"),
    ("Tercero", "Proveedor, agente, transportista o contacto externo que aporta informacion, documentos o coordinacion bajo permisos/tokens.", "Actor", "third-party-token-document-onboarding", "candidate"),
    ("Agente de aduana", "Entidad o tercero que participa en tramites aduaneros, contactos, documentos y relaciones cliente-agente.", "Actor / entidad", "Tablas `ada_*`", "candidate"),
    ("Proveedor/coordinador", "Tercero relacionado con gestion documental, transporte, coordinacion o servicios asociados al caso/embarque.", "Actor / entidad", "Tablas `prov_*`, `dav_proveedor*`", "candidate"),
    ("Caso", "Expediente operacional que agrupa solicitud, cliente, documentos, estados, costos, aprobaciones y seguimiento.", "Entidad de negocio", "`dav_casos`, `dav_casosprevios`", "candidate"),
    ("Caso previo / solicitud previa", "Registro inicial o pre-expediente usado para capturar una solicitud antes de cierre, envio o conversion a caso formal.", "Entidad de negocio", "`dav_casosprevios`", "candidate"),
    ("Solicitud", "Entrada de servicio o gestion iniciada por usuario/cliente; puede crear caso, documentos previos y estados de seguimiento.", "Proceso", "customs-request-intake, shipment-customs-request-management", "candidate"),
    ("Solicitud GA", "Solicitud de Gestion Aduanera asociada a un embarque o cliente; crea registros en `dav_casosprevios` y documentos previos.", "Proceso aduanero", "shipment-customs-request-management", "candidate"),
    ("Gestion Aduanera (GA)", "Conjunto de actividades para preparar, revisar, documentar, aprobar y seguir tramites aduaneros del cliente.", "Dominio", "Dominios `customs-*`, tablas `dav_*`", "candidate"),
    ("DAV", "Dominio/tablas de declaracion o gestion aduanera con partidas, mercancia, parametros, acuerdos, documentos, costos y aprobaciones.", "Acronimo / dominio", "Familia `dav_*`", "candidate"),
    ("DAM", "Documento/declaracion aduanera mencionado en flujos de control, OCR, envio o pendientes; su significado exacto requiere validacion local.", "Acronimo / documento", "customs-dam-document-send-date-control, campos `dam*`", "candidate"),
    ("DEX", "Documento/declaracion de exportacion usado en flujos de OCR, actualizacion y validacion documental.", "Acronimo / documento", "customs-dex-ocr-validation-update", "candidate"),
    ("EDP", "Estado/evento de proceso usado para registrar hitos historicos de caso o embarque, con fecha, estado y observacion.", "Estado / hito", "`dav_edp`, `logis_edp`, customs-case-edp-status-monitoring", "candidate"),
    ("Hito", "Marca de avance dentro de un caso, embarque, documento o proceso; suele registrarse con fecha y estado.", "Proceso", "Estados EDP y seguimiento logistico", "candidate"),
    ("Estado", "Codigo o bandera que controla visibilidad, bloqueo, avance, aprobacion, finalizacion o clasificacion operacional.", "Regla / dato referencia", "Campos `idestado*`, `estado*`, `status`", "candidate"),
    ("Catalogo", "Tabla o conjunto de valores de referencia que parametriza tipos, estados, permisos, documentos, rutas, mercancias o reglas.", "Dato referencia", "Tablas `tipo*`, `estado*`, `parametro*`", "candidate"),
    ("Parametro", "Valor configurable por cliente/proceso que modifica comportamiento, documentos requeridos, costos, reportes o validaciones.", "Configuracion", "`dav_parametros*`, `logis_*`", "candidate"),
    ("Magic value", "Valor literal en codigo o datos que representa una regla de negocio no documentada, como permiso, cliente especial o estado.", "Riesgo de regla", "Business/behavior analysis", "candidate"),
    ("Permiso", "Control que habilita pantalla, reporte o accion; puede estar distribuido entre menu, sesion, SQL y endpoint.", "Autorizacion", "`dav_clienteusuariospermisos`, permiso 65", "candidate"),
    ("Reporte cliente", "Reporte visible o habilitado para un cliente concreto, condicionado por permisos y filtros multi-cliente.", "Reporteria", "`dav_clientereportescliente`", "candidate"),
    ("Dashboard generico", "Pantalla o endpoint de indicadores agregados para control operativo/ejecutivo.", "Reporteria", "`ajax/DashboardGenerico.php`", "candidate"),
    ("Power BI", "Canal de inteligencia de negocio embebido o enlazado para dashboards ejecutivos.", "Integracion / reporting", "executive-powerbi-dashboard-portal", "candidate"),
    ("KPI", "Indicador de proceso o gestion, como volumen, tiempos, pendientes, vencimientos, estados, backlog o costos.", "Metrica", "KPI_CATALOG y reporting", "candidate"),
    ("Aging", "Reporte o registro financiero de antiguedad/saldos; en el repo aparece asociado a `dav_aging` y terminos de cuenta/ahorro.", "Finanzas / reporting", "accounting-ledger-aging-reporting", "candidate"),
    ("Planilla", "Documento operativo/financiero generado para facturacion, control contable, cobro o soporte de servicio.", "Documento financiero", "billing-invoice-planilla-document-generation", "candidate"),
    ("Factura", "Documento comercial/financiero vinculado a venta, cobro, OCR, referencia comercial o libro contable.", "Documento financiero", "`dav_factura*`, `logis_factura*`, vehicle-sales-invoice-*", "candidate"),
    ("Nota de cobranza", "Documento o comunicacion de cobro asociado a recepcion, envio o seguimiento de pagos.", "Documento financiero", "billing-payments-receivables", "candidate"),
    ("Pago", "Movimiento economico o confirmacion financiera usado en conciliaciones, cuentas por cobrar o reporteria.", "Finanzas", "`dav_pagos*`, `ages_pagos*`", "candidate"),
    ("Conciliacion", "Comparacion de datos operativos/documentales/financieros para detectar diferencias y confirmar consistencia.", "Control", "`dav_conciliacion`, OCR/payment reconciliation", "candidate"),
    ("Costo / gasto", "Importe economico de servicio, transporte, despacho, tramite o componente financiero de la operacion.", "Finanzas / logistica", "`costo*`, `gasto*`, logistics-quotation-costing", "candidate"),
    ("CIF", "Valor candidato usado en calculos aduaneros/tributarios; aparece como campo monetario `cif_bs`.", "Aduanas / calculo", "`cif_bs`", "candidate"),
    ("IVA", "Impuesto o porcentaje usado en calculos de liquidacion/facturacion; aparece como `porcentajeIVA`.", "Impuesto", "`porcentajeIVA`", "candidate"),
    ("ICE", "Impuesto/campo de calculo asociado a importes o litros; aparece como `porcentajeICE` e `ICE_litro`.", "Impuesto", "`porcentajeICE`, `ICE_litro`", "candidate"),
    ("Tributos", "Importes o pagos aduaneros/fiscales solicitados, confirmados o liquidados durante el flujo.", "Aduanas / finanzas", "`solicitudpagotributos`, customs-tax-liquidation-*", "candidate"),
    ("Embarque", "Movimiento logistico/aduanero asociado a cliente, mercancia, documentos, costos, tracking y finalizacion.", "Entidad logistica", "`logis_embarques`", "candidate"),
    ("BL", "Documento logistico de embarque capturado o validado por OCR en flujos de politica/documentacion.", "Documento logistico", "logistics-bl-policy-ocr-capture", "candidate"),
    ("Packing list", "Documento/lista de empaque usado para validacion de importacion o comparacion documental.", "Documento logistico", "packing-list-import-validation", "candidate"),
    ("Mercancia", "Bien, item o conjunto declarado/transportado con atributos aduaneros, logisticos, documentales y de costo.", "Entidad de negocio", "`dav_mercancia*`, `logis_tipomercancia*`", "candidate"),
    ("Partida", "Linea declarativa o item dentro de una declaracion/tramite aduanero.", "Aduanas", "`dav_partidas`", "candidate"),
    ("Regimen", "Clasificador aduanero o modalidad normativa que condiciona documentos, calculos o tramite.", "Aduanas / catalogo", "Campos `idregimen`", "candidate"),
    ("Modalidad de transporte", "Clasificador que determina documentos o reglas de transporte en solicitudes/embarques.", "Logistica / catalogo", "`idmodotransporte`, `dav_modotransportedocumento`", "candidate"),
    ("Tipo de solicitud", "Clasificador que altera campos requeridos, linea cliente o comportamiento de creacion/envio.", "Proceso / catalogo", "`idtiposolicitud`, `tmp_tiposolicitud`", "candidate"),
    ("Tipo de documento", "Clasificador que determina validacion, formato, OCR, descarga, aprobacion o documentos previos.", "Documento / catalogo", "`idtipodocumento`", "candidate"),
    ("Formato de documento", "Clasificador de presentacion o estructura de un documento requerido/generado.", "Documento / catalogo", "`idformatodocumento`", "candidate"),
    ("Documento previo", "Documento inicial generado o requerido al crear una solicitud antes de completar el expediente.", "Documento", "`dav_documentosprevios`", "candidate"),
    ("Documento predeterminado", "Documento configurado como requerido por cliente, modalidad, regimen u otra condicion.", "Documento / configuracion", "`dav_documentos_predeterminados`", "candidate"),
    ("OCR", "Extraccion automatica de datos desde PDF, imagen, Excel o paquete documental; requiere revision ante errores.", "Automatizacion documental", "document-exchange-ocr, Alicorp OCR", "candidate"),
    ("Carga documental", "Subida de archivos vinculados a caso, embarque, OCR, factura, BL, SOAT o soporte operativo.", "Documento", "FILE_UPLOAD_DOWNLOAD_CATALOG", "candidate"),
    ("Descarga documental", "Obtencion de documentos o paquetes por usuario autorizado; sensible a permisos, rutas y cliente.", "Documento / seguridad", "`download.php`, operational-reporting-downloads", "candidate"),
    ("Paquete documental", "Conjunto de archivos asociados a una operacion, cliente o tercero, posiblemente ZIP/RAR/PDF.", "Documento", "alicorp-operational-document-package-dispatch", "candidate"),
    ("Observacion", "Comentario o motivo de revision/rechazo usado para devolver, corregir o bloquear documentos/tramites.", "Excepcion / estado", "`observacion*`, form1-modification-observation-tracking", "candidate"),
    ("Aprobacion", "Accion o estado que confirma documento, solicitud o paso de proceso y habilita avance.", "Estado / control", "customs-document-approval", "candidate"),
    ("Finalizacion", "Cierre o marca temporal que impide o limita nuevas acciones sobre embarque/solicitud/caso.", "Estado / ciclo de vida", "`fecha_finalizacion`, `fechafin`", "candidate"),
    ("Guardar y enviar", "Accion UI que persiste la solicitud y ademas dispara el envio/cierre operativo inmediato.", "Accion de flujo", "`guardarEnviar`, shipment-customs-request-management", "candidate"),
    ("Alta", "Creacion inicial de entidad transaccional, como solicitud GA, caso, documento, token o catalogo.", "Accion", "INSERT flows", "candidate"),
    ("Edicion aprobada", "Actualizacion limitada permitida sobre entidad que ya alcanzo aprobacion o caso formal.", "Accion restringida", "shipment-customs-request-management", "candidate"),
    ("Borrado logico", "Marca `deleted_at/deleted_by` que desactiva sin eliminar fisicamente, preservando historia.", "Auditoria / lifecycle", "Campos `deleted_*`", "candidate"),
    ("Auditoria", "Trazabilidad de quien creo, modifico o elimino un registro y cuando ocurrio.", "Control", "`created_by`, `updated_by`, `deleted_by`", "candidate"),
    ("Soft delete", "Sinonimo tecnico de borrado logico mediante marcas de fecha/usuario.", "Tecnico / dato", "`deleted_at`, `deleted_by`", "candidate"),
    ("Tabla temporal", "Tabla `tmp_*` usada como staging, validacion, reporte intermedio o consulta auxiliar.", "Dato tecnico-funcional", "599 tablas `tmp` detectadas", "candidate"),
    ("Vista / read model", "Estructura derivada de consulta usada principalmente para reporteria o lectura consolidada.", "Dato tecnico-funcional", "Prefijo `v_`, reporting models", "candidate"),
    ("Staging", "Zona o tabla intermedia para carga, normalizacion, importacion Excel/OCR o preparacion de reporte.", "Dato tecnico-funcional", "`tmp_*`, bulk imports", "candidate"),
    ("Golden master", "Prueba de caracterizacion que captura comportamiento legacy esperado antes de refactor.", "Testing", "OpenSpec / tests", "candidate"),
    ("Caracterizacion", "Estrategia de pruebas para fijar contratos reales: entradas, salidas, side effects, documentos y errores.", "Testing", "engineering-analysis/tests", "candidate"),
    ("Side effect", "Efecto colateral de un flujo: escritura, correo, documento generado, EDP, token, notificacion o archivo.", "Comportamiento", "SIDE_EFFECT_CATALOG", "candidate"),
    ("Transaccion", "Unidad de consistencia esperada entre varias escrituras/side effects, aunque no siempre exista transaccion DB real.", "Consistencia", "behavior/transaction analysis", "candidate"),
    ("Atomicidad", "Propiedad esperada de completar o revertir un conjunto de cambios relacionados; riesgo cuando falta transaccion.", "Consistencia", "FLOW semantic risks", "candidate"),
    ("Concurrencia", "Riesgo de resultados incorrectos por operaciones simultaneas, especialmente con `max(id)` o secuencias no atomicas.", "Consistencia", "shipment-customs-request-management", "candidate"),
    ("Token de tercero", "Credencial o enlace temporal para acceso controlado de proveedor/contacto a documentos o procesos.", "Seguridad / tercero", "third-party-token-document-onboarding", "candidate"),
    ("2FA / MFA", "Segundo factor de autenticacion usado para reforzar acceso y validar sesion.", "Seguridad", "identity-access", "candidate"),
    ("Sesion", "Estado autenticado de usuario usado para permisos, cliente, auditoria y filtros.", "Seguridad", "identity-access", "candidate"),
    ("PII / dato personal", "Dato de persona o contacto como nombre, correo, telefono, direccion, NIT o identificador.", "Seguridad / dato", "Semantic field sensitivity", "candidate"),
    ("Secreto", "Clave, token o credencial tecnica que no debe estar hardcodeada ni visible en reportes.", "Seguridad", "SECRET_MANAGEMENT_FINDINGS", "candidate"),
    ("SendGrid", "Servicio externo candidato para envio de correo/notificaciones.", "Integracion", "security/integrations findings", "candidate"),
    ("Pusher", "Servicio realtime candidato para notificaciones o canales en tiempo real.", "Integracion", "realtime-notification-center", "candidate"),
    ("SFTP", "Canal de intercambio de archivos externo candidato.", "Integracion", "integration context", "candidate"),
    ("SAP", "Sistema externo o codigo de integracion mencionado en campos `codigo_sap` y datos maestros.", "Integracion / dato", "`codigo_sap`", "candidate"),
    ("Vehiculo", "Unidad importada/gestionada por chasis/VIN, factura, SOAT, inventario o deposito.", "Entidad vehicular", "vehicle-* domains", "candidate"),
    ("VIN / chasis", "Identificador vehicular usado para trazabilidad, inventario, facturacion y control documental.", "Entidad vehicular", "vehicle-chassis-timeline-trace", "candidate"),
    ("SOAT", "Documento vehicular sujeto a OCR/splitting y validacion documental.", "Documento vehicular", "vehicle-soat-pdf-ocr-splitting", "candidate"),
    ("Deposito transitorio", "Estado o proceso vehicular/logistico asociado a cumplimiento y permanencia temporal.", "Vehiculos / cumplimiento", "vehicle-transitory-depot-compliance", "candidate"),
    ("Inventario", "Control de unidades, stock, VIN/chasis, facturacion o reporteria de almacen.", "Operacion", "warehouse-inventory-reporting, inventory-vin-billing-control", "candidate"),
    ("Ruta", "Secuencia o tramo logistico asociado a viaje, carga, origen/destino y seguimiento.", "Logistica", "logistics-route-trip-assignment-management", "candidate"),
    ("Viaje", "Ejecucion de transporte con orden, ruta, carga, imagenes, kilometraje o eventos.", "Logistica / tracking", "`tck_orden_viaje`, `tck_carga`", "candidate"),
    ("Carga", "Unidad o conjunto transportado que participa en costo, ruta, entrega, tipo de mercancia o contenedor.", "Logistica", "`tck_carga`, `logis_tipocarga`", "candidate"),
    ("Contenedor", "Unidad de transporte vinculada a carga, costo o embarque.", "Logistica", "`logis_contenedor`", "candidate"),
    ("Puerto", "Ubicacion logistica de origen/destino o referencia de tramite.", "Logistica / aduanas", "`dav_puerto`", "candidate"),
    ("Aeropuerto", "Ubicacion logistica aerea usada en cotizacion o embarque.", "Logistica", "`logis_aeropuertos`, `dav_aerolineas`", "candidate"),
    ("Incoterms", "Condicion comercial/logistica candidata asociada a entrega y declaracion.", "Comercio exterior", "`idincoterms`, `dav_condicionentrega`", "candidate"),
    ("Division", "Clasificador organizativo o comercial que participa en solicitud, proveedor o cliente.", "Dato maestro", "`dav_division`", "candidate"),
    ("Linea cliente", "Subsegmento/linea de cliente usado para producto, vehiculo o reglas de solicitud.", "Dato maestro", "`idclientelineas`", "candidate"),
    ("Tipo producto", "Clasificador por cliente/producto usado en gestion aduanera o solicitud.", "Dato maestro", "`dav_clientetipoproducto`", "candidate"),
    ("Servicio adicional", "Tramite o servicio extra detectado por datos/documentos, como certificados o gestiones asociadas.", "Proceso", "additional-services-request-management", "candidate"),
    ("Certificado de origen", "Documento/certificado candidato requerido en flujos de importacion/OCR o servicios adicionales.", "Documento", "alicorp-ocr-bulk-shipment-intake", "candidate"),
    ("Inocuidad", "Servicio/certificado candidato detectado como requisito adicional segun producto/proveedor/peso.", "Documento/regulatorio", "alicorp-ocr-bulk-shipment-intake", "candidate"),
    ("Fitosanitario", "Servicio/certificado candidato de control sanitario vegetal o regulatorio.", "Documento/regulatorio", "alicorp-ocr-bulk-shipment-intake", "candidate"),
    ("No conformidad", "Registro o gestion de problema de calidad/mejora continua.", "Calidad", "continuous-improvement-nonconformity", "candidate"),
    ("Workaround", "Procedimiento manual alternativo ante fallo de sistema, OCR, integracion o datos incompletos.", "Operacion", "WORKAROUND_CATALOG", "candidate"),
    ("Fallback", "Ruta alternativa ante fallo de integracion o automatizacion; puede ser manual.", "Operacion / integracion", "FALLBACK_PROCESSES", "candidate"),
    ("OpenSpec", "Estructura de baseline y cambios usada para gobernar especificaciones, evidencias y futuras modificaciones.", "Gobernanza", "openspec", "candidate"),
    ("Baseline candidato", "Conjunto de documentacion AS-IS inferida desde codigo/schema/evidencia, no confirmado aun por negocio.", "Gobernanza", "PROJECT_STATE / OpenSpec", "candidate"),
    ("Dominio candidato", "Agrupacion funcional reconstruida desde archivos, tablas, reglas, flujos y evidencias; requiere validacion.", "Gobernanza", "70 dominios", "candidate"),
    ("Evidencia cruzada", "Relacion entre flujo, regla, tabla, campo, endpoint o fuente que sostiene una inferencia funcional.", "Trazabilidad", "semantic-flow-analysis", "candidate"),
    ("Validacion humana", "Revision posterior por responsables de negocio/TI para confirmar, corregir o rechazar inferencias.", "Gobernanza", "PROJECT_STATE", "candidate"),
]


def load_csv(path: Path) -> list[dict]:
    if not path.exists():
        return []
    with path.open("r", encoding="utf-8-sig", newline="") as handle:
        return list(csv.DictReader(handle))


def summarize_flow_domains() -> dict[str, str]:
    rows = load_csv(FLOW / "FLOW_TABLE_USAGE_MATRIX.csv")
    by_term = defaultdict(set)
    for row in rows:
        text = " ".join([row.get("domain", ""), row.get("table", ""), row.get("semantic_role_in_flow", ""), row.get("rule_hints", "")]).lower()
        for term, *_ in TERMS:
            normalized = term.lower().split("/")[0].strip()
            if len(normalized) >= 4 and normalized in text:
                by_term[term].add(row.get("domain", ""))
    return {term: ", ".join(sorted(domains)[:6]) for term, domains in by_term.items()}


def write_glossary() -> None:
    flow_domains = summarize_flow_domains()
    grouped = defaultdict(list)
    for term, definition, category, evidence, status in TERMS:
        grouped[category].append((term, definition, evidence, status, flow_domains.get(term, "")))

    lines = [
        "# Business glossary",
        "",
        "Estado: candidate_reconstruction",
        "Confianza: media",
        "Idioma: Spanish",
        "",
        "Este glosario ha sido reconstruido a partir de dominios, tablas, campos, reglas, flujos y evidencias cruzadas. No es un glosario canonico: cada termino mantiene estado candidato hasta validacion humana.",
        "",
        "## Resumen",
        "",
        f"- Terminos: {len(TERMS)}",
        "- Fuente: diccionario semantico, segunda pasada de flujos, reglas por dominio, evidencia Graphify/PHP/SQL.",
        "- Uso recomendado: validacion funcional, workshops de negocio, priorizacion de pruebas de caracterizacion.",
        "",
        "## Terminos por categoria",
        "",
    ]
    for category in sorted(grouped):
        lines.extend([f"### {category}", "", "| Termino | Definicion candidata | Evidencia / origen | Dominios relacionados | Estado |", "|---|---|---|---|---|"])
        for term, definition, evidence, status, domains in sorted(grouped[category]):
            lines.append(f"| `{term}` | {escape(definition)} | {escape(evidence)} | {escape(domains)} | {status} |")
        lines.append("")
    lines.extend([
        "## Preguntas de validacion",
        "",
        "- Confirmar siglas locales: DAV, DAM, DEX, EDP, GA, CIF, ICE.",
        "- Confirmar sinonimos usados por usuarios frente a nombres de tablas/campos.",
        "- Confirmar si terminos financieros como planilla, nota de cobranza, aging y conciliacion tienen definicion contractual.",
        "- Confirmar diferencias por cliente, pais, unidad de negocio o modulo legacy.",
        "- Confirmar que terminos de documento/OCR corresponden a formatos reales y no solo nombres de codigo.",
    ])
    BUSINESS.joinpath("BUSINESS_GLOSSARY.md").write_text("\n".join(lines) + "\n", encoding="utf-8")

    open_lines = [
        "# OpenSpec glossary",
        "",
        "Estado: candidate_reconstruction",
        "Idioma: Spanish",
        "",
        "Glosario operativo para interpretar el baseline OpenSpec y los cambios futuros.",
        "",
        "| Termino | Significado candidato | Uso en OpenSpec | Estado |",
        "|---|---|---|---|",
        "| `Baseline candidato` | Documentacion AS-IS inferida desde codigo, schema y evidencia; no confirmada aun. | Punto de partida para validacion y cambios. | candidate |",
        "| `Dominio candidato` | Agrupacion funcional reconstruida desde componentes, flujos, tablas y reglas. | Unidad de analisis/especificacion. | candidate |",
        "| `Evidencia determinista` | Dato observado sin interpretacion: archivo, linea, tabla, columna, ruta, consulta. | Soporte minimo de trazabilidad. | observed |",
        "| `Evidencia cruzada` | Relacion entre regla, flujo, tabla, campo, endpoint y fuente. | Soporte de inferencias funcionales. | candidate |",
        "| `Inferencia funcional` | Interpretacion razonada del comportamiento AS-IS marcada como pendiente de validar. | Redaccion de reglas/procesos. | candidate |",
        "| `Inferencia semantica` | Significado probable de datos o terminos deducido por nombre, uso, flujo y evidencia. | Diccionario y glosario. | candidate |",
        "| `Graphify` | Grafo de codigo utilizado para comunidades, dependencias y cobertura estructural. | Evidencia complementaria. | observed |",
        "| `Golden master` | Prueba de caracterizacion que preserva comportamiento legacy observable. | Requisito antes de refactor sensible. | candidate |",
        "| `OpenSpec` | Estructura de especificacion de baseline, cambios, riesgos, tareas y pruebas. | Gobierno del refactor. | candidate |",
        "| `PASS` | Baseline confirmado, sin blockers y con aprobacion humana requerida por politica. | Estado final canonico. | governed |",
        "| `CONDITIONAL_PASS` | Resultado verificable suficiente para revision, pero no canonico. | Estado actual de verificacion candidata. | governed |",
        "| `IN_PROGRESS` | Proyecto aun no canonizado ni confirmado. | Estado actual del proyecto. | governed |",
        "| `Template` | Plantilla deliberada para futuros dominios/cambios, no evidencia del sistema analizado. | No debe confundirse con placeholder real. | governed |",
        "| `Placeholder real` | Artefacto requerido que quedo sin contenido util fuera de carpetas template. | Debe ser 0 antes de entregar. | governed |",
    ]
    OPENSPEC.joinpath("glossary.md").write_text("\n".join(open_lines) + "\n", encoding="utf-8")

    TRACE.joinpath("BUSINESS_GLOSSARY_REVIEW.md").write_text(
        "# Business glossary review\n\n"
        "El glosario inicial era corto porque fue creado como semilla manual antes del enriquecimiento semantico y antes de la segunda pasada de flujos. "
        "No se alimentaba automaticamente de tablas, campos, reglas ni evidencias cruzadas.\n\n"
        f"Se regenero con {len(TERMS)} terminos candidatos y se actualizaron `business-analysis/BUSINESS_GLOSSARY.md` y `openspec/glossary.md`.\n",
        encoding="utf-8",
    )


def escape(value: str) -> str:
    return str(value).replace("|", "\\|").replace("\n", " ")


if __name__ == "__main__":
    write_glossary()
