from __future__ import annotations

import csv
import json
import re
from collections import Counter, defaultdict
from pathlib import Path


ROOT = Path(__file__).resolve().parents[3]
WORK = ROOT / ".brownfield" / "work"
SOURCE_DATA = WORK / "data_dictionary"
RELEASE_DATA = WORK / "release" / "engineering-analysis" / "data"
TRACE = WORK / "release" / "traceability" / "verification"


DOMAIN_PREFIXES = [
    ("dav_", "Gestion aduanera, DAV/DAM, clientes, documentos, costos y procesos operativos de comercio exterior"),
    ("logis_", "Gestion logistica: cotizaciones, cargas, rutas, puertos, contenedores, entregas y seguimiento"),
    ("tck_", "Tracking/transporte: ordenes de viaje, cargas, kilometraje, imagenes y eventos de ruta"),
    ("ada_", "Agentes de aduana: entidades, contactos, documentos y relacion con clientes"),
    ("ads_", "Agentes de seguro o soporte documental asociado a seguros/contactos"),
    ("adaprov_", "Relacion entre agentes/proveedores, contactos y mercancias"),
    ("ages_", "Asesoria o gestion de carpetas/tramites, observaciones, pagos y valoraciones"),
    ("cc_", "Control documental o control de chasis/mercancias/documentos"),
    ("cn_", "Concesionarios, contactos y relaciones cliente-concesionario"),
    ("con_", "Contratos, unidades de negocio, documentos, estados y observaciones contractuales"),
    ("dashboard_", "Auditoria o configuracion de dashboards e inteligencia de negocio"),
    ("ges_", "Gestion administrativa u operativa transversal"),
    ("prov_", "Proveedores, clientes/proveedores y datos de terceros"),
    ("tmp_", "Tabla temporal o staging usada para calculos, reportes, importaciones o procesos intermedios"),
    ("v_", "Vista SQL o estructura derivada de consulta para lectura/reporteria"),
    ("bot_", "Automatizacion o bot asociado a procesamiento documental/aduanero"),
]

TERM_MEANINGS = {
    "cliente": "cliente/tenant propietario de la operacion o filtro de visibilidad",
    "clientes": "clientes/tenants propietarios de operaciones o reportes",
    "proveedor": "proveedor o tercero participante en el proceso",
    "proveedores": "proveedores o terceros participantes en el proceso",
    "agente": "agente externo o interno que participa en gestion aduanera/operativa",
    "agentes": "agentes externos o internos que participan en gestion aduanera/operativa",
    "aduana": "aduana o entidad/parametro del tramite aduanero",
    "aduanas": "aduanas o entidades/parametros del tramite aduanero",
    "documento": "documento o soporte asociado al expediente",
    "documentos": "documentos o soportes asociados al expediente",
    "contacto": "persona de contacto y sus datos de comunicacion",
    "contactos": "personas de contacto y sus datos de comunicacion",
    "mercancia": "mercancia o item objeto de transporte/declaracion",
    "mercancias": "mercancias o items objeto de transporte/declaracion",
    "partida": "partida o linea declarativa dentro de un tramite aduanero",
    "partidas": "partidas o lineas declarativas dentro de un tramite aduanero",
    "estado": "estado de ciclo de vida o clasificacion operacional",
    "estados": "estados de ciclo de vida o clasificacion operacional",
    "tipo": "tipo/clasificador que parametriza la regla o pantalla",
    "tipos": "tipos/clasificadores que parametrizan reglas o pantallas",
    "solicitud": "solicitud de servicio o gestion iniciada por usuario/cliente",
    "solicitudes": "solicitudes de servicio o gestion iniciadas por usuario/cliente",
    "embarque": "embarque o movimiento logistico/aduanero",
    "embarques": "embarques o movimientos logisticos/aduaneros",
    "factura": "factura o documento economico/comercial",
    "facturas": "facturas o documentos economicos/comerciales",
    "planilla": "planilla o documento operativo/financiero generado",
    "pago": "pago o movimiento economico",
    "pagos": "pagos o movimientos economicos",
    "conciliacion": "conciliacion financiera u operativa",
    "costo": "costo, gasto o valor economico asociado a la operacion",
    "costos": "costos, gastos o valores economicos asociados a la operacion",
    "monto": "importe monetario o valor numerico economico",
    "valor": "valor declarado, calculado o parametrico",
    "puerto": "puerto usado como origen, destino o referencia logistica",
    "aeropuerto": "aeropuerto usado como origen, destino o referencia logistica",
    "carga": "carga transportada o unidad operativa de transporte",
    "contenedor": "contenedor o unidad de transporte",
    "viaje": "viaje o ruta ejecutada por transporte",
    "ruta": "ruta logistica o tramo operativo",
    "token": "token de acceso, enlace temporal o credencial operacional",
    "mail": "correo electronico o evento de envio por email",
    "correo": "correo electronico o canal de notificacion",
    "telefono": "telefono de contacto",
    "celular": "telefono movil de contacto",
    "nit": "identificador fiscal/tributario",
    "sap": "codigo o referencia de integracion con SAP",
    "ocr": "captura/extraccion automatica desde documento",
    "reporte": "reporte, salida agregada o consulta de gestion",
    "reportes": "reportes, salidas agregadas o consultas de gestion",
    "observacion": "observacion, comentario o motivo de revision",
    "observaciones": "observaciones, comentarios o motivos de revision",
    "parametro": "parametro configurable usado por reglas de negocio",
    "parametros": "parametros configurables usados por reglas de negocio",
    "actividad": "actividad comercial, operativa o descriptiva de la entidad",
}

FIELD_PATTERNS = [
    (re.compile(r"^id$|^id[a-z0-9_]*$"), "Identificador tecnico o referencia a otra entidad; si apunta a otra tabla funciona como clave de relacion funcional."),
    (re.compile(r"created_at|fecha_crea|fechacreacion|fecha_creacion"), "Fecha/hora de creacion del registro, util para auditoria y reconstruccion de ciclo de vida."),
    (re.compile(r"updated_at|fecha_actualiza|fechaactualizacion"), "Fecha/hora de ultima modificacion, util para auditoria y control de cambios."),
    (re.compile(r"deleted_at|fecha_elimina|fechaeliminacion"), "Marca de borrado logico; indica que el registro puede permanecer historicamente aunque no este activo."),
    (re.compile(r"created_by|usuariocreacion|usuario_creacion"), "Usuario que creo el registro; campo de auditoria y responsabilidad."),
    (re.compile(r"updated_by|usuarioactualizacion|usuario_actualizacion"), "Usuario que modifico el registro; campo de auditoria y trazabilidad."),
    (re.compile(r"deleted_by|usuarioeliminacion|usuario_eliminacion"), "Usuario que marco borrado o baja logica; campo de auditoria."),
    (re.compile(r"estado|status|activo|habilitado|vigente"), "Estado o bandera que controla si el registro esta activo, visible o en una fase concreta del proceso."),
    (re.compile(r"tipo|clase|categoria"), "Clasificador funcional usado para aplicar reglas, filtros o variantes de pantalla."),
    (re.compile(r"fecha|fec"), "Fecha de evento, hito, emision, recepcion, vencimiento o registro operacional."),
    (re.compile(r"monto|importe|valor|precio|costo|gasto|saldo|debito|credito|interes|administrativo|cheques"), "Valor monetario o numerico usado en calculos, costos, conciliaciones, liquidaciones o reportes."),
    (re.compile(r"nombre|razon_social|descripcion|detalle|glosa|concepto"), "Texto descriptivo visible para usuarios, reportes o catalogos funcionales."),
    (re.compile(r"mail|email|correo"), "Correo electronico o dato de envio/notificacion; debe tratarse como dato personal o canal sensible."),
    (re.compile(r"telefono|celular|whatsapp"), "Telefono de contacto; dato personal usado para coordinacion o notificacion."),
    (re.compile(r"direccion"), "Direccion fisica o ubicacion de contacto/sucursal; dato personal/empresarial sensible."),
    (re.compile(r"token|password|clave|secret"), "Credencial, token o secreto; requiere tratamiento de seguridad y no debe exponerse en reportes."),
    (re.compile(r"archivo|file|ruta|path|url|documento|adjunto"), "Referencia a archivo, ruta, URL o documento asociado a expediente/soporte."),
    (re.compile(r"nit|ruc|ci|dni|identificacion"), "Identificador fiscal o personal usado para reconocimiento legal/tributario."),
    (re.compile(r"sap"), "Referencia de integracion o codigo externo SAP."),
    (re.compile(r"pais|ciudad|puerto|aeropuerto|origen|destino|lugar"), "Ubicacion geografica/logistica usada para rutas, aduanas, origen/destino o reporteria."),
    (re.compile(r"chasis|vin|placa"), "Identificador vehicular usado para trazabilidad de importacion, inventario o facturacion."),
]


def split_identifier(value: str) -> list[str]:
    value = re.sub(r"([a-z0-9])([A-Z])", r"\1_\2", value or "")
    parts = re.split(r"[_\W]+", value.lower())
    tokens: list[str] = []
    for part in parts:
        if not part:
            continue
        if part.startswith("id") and len(part) > 2 and part not in ("idea", "idle"):
            tokens.append("id")
            rest = part[2:]
            if rest:
                tokens.append(rest)
        else:
            tokens.append(part)
    return tokens


def concept_meanings(value: str) -> list[str]:
    meanings: list[str] = []
    normalized = re.sub(r"[_\W]+", "", (value or "").lower())
    for token in split_identifier(value):
        if token in TERM_MEANINGS and TERM_MEANINGS[token] not in meanings:
            meanings.append(TERM_MEANINGS[token])
    for term, meaning in sorted(TERM_MEANINGS.items(), key=lambda item: len(item[0]), reverse=True):
        if len(term) >= 4 and term in normalized and meaning not in meanings:
            meanings.append(meaning)
    return meanings


def table_domain(table: str) -> tuple[str, str]:
    for prefix, meaning in DOMAIN_PREFIXES:
        if table.startswith(prefix):
            return prefix.rstrip("_"), meaning
    tokens = split_identifier(table)
    if any(t in tokens for t in ("vehiculo", "vehiculos", "chasis", "vin")):
        return "vehiculos", "Gestion vehicular, importacion, inventario, documentos y facturacion asociada"
    if any(t in tokens for t in ("factura", "facturas", "pago", "pagos", "conciliacion", "aging")):
        return "finanzas", "Facturacion, pagos, aging, conciliacion y control financiero"
    if any(t in tokens for t in ("documento", "documentos", "ocr", "archivo", "archivos")):
        return "documentos", "Gestion documental, archivos, OCR y soportes operativos"
    return "transversal", "Tabla transversal o legacy sin prefijo funcional inequivoco"


def semantic_table_description(row: dict) -> tuple[str, str, str, str]:
    table = row["table"]
    domain_code, domain_text = table_domain(table)
    tokens = split_identifier(table)
    concepts = concept_meanings(table)
    if not concepts:
        concepts = ["entidad o catalogo legacy cuyo significado debe confirmarse por uso en codigo y datos"]
    reads = int(row.get("read_references") or 0)
    writes = int(row.get("write_references") or 0)
    usage = (
        "Tiene lectura y escritura observada, por lo que probablemente participa en un flujo transaccional activo."
        if reads and writes
        else "Tiene lecturas observadas y parece actuar como consulta, catalogo o fuente de reporte."
        if reads
        else "Tiene escrituras observadas sin lecturas directas en la evidencia disponible; revisar si es staging, log o tabla consumida indirectamente."
        if writes
        else "No tiene uso PHP directo en la evidencia; puede ser historica, consumida por SQL/vistas/procedimientos o estar fuera del alcance estatico."
    )
    description = (
        f"Tabla candidata del dominio {domain_code}: {domain_text}. "
        f"Por su nombre agrupa {', '.join(concepts[:4])}. {usage}"
    )
    lifecycle = infer_lifecycle(table, tokens)
    pii = infer_table_pii(table, tokens)
    return description, domain_code, lifecycle, pii


def infer_lifecycle(table: str, tokens: list[str]) -> str:
    token_set = set(tokens)
    if token_set & {"estado", "estados", "status"}:
        return "CATALOG_STATE"
    if token_set & {"tipo", "tipos", "parametro", "parametros", "catalogo"}:
        return "REFERENCE_DATA"
    if table.startswith("tmp_"):
        return "TEMPORARY_OR_STAGING"
    if token_set & {"log", "logs", "historial", "auditoria"}:
        return "AUDIT_LOG"
    if token_set & {"documento", "documentos", "archivo", "archivos", "ocr"}:
        return "DOCUMENT_LIFECYCLE"
    if token_set & {"solicitud", "solicitudes", "caso", "embarque", "viaje", "factura", "pago"}:
        return "TRANSACTIONAL_LIFECYCLE"
    return "DOMAIN_ENTITY_OR_LEGACY"


def infer_table_pii(table: str, tokens: list[str]) -> str:
    pii_tokens = {"contacto", "contactos", "telefono", "celular", "mail", "email", "correo", "direccion", "usuario", "password", "token", "nit", "ci", "dni"}
    return "LIKELY_PERSONAL_OR_SENSITIVE" if pii_tokens & set(tokens) or any(t in table for t in pii_tokens) else "UNKNOWN_OR_BUSINESS_DATA"


def infer_sensitivity(column: str, data_type: str, existing: str) -> str:
    name = column.lower()
    if re.search(r"password|clave|secret|token", name):
        return "SECRET_OR_CREDENTIAL"
    if re.search(r"mail|email|correo|telefono|celular|direccion|nombre|representante|nit|ruc|ci|dni", name):
        return "PERSONAL_OR_CONTACT_DATA"
    if re.search(r"monto|importe|valor|precio|costo|gasto|saldo|debito|credito|cuenta|banco", name):
        return "FINANCIAL_OR_COMMERCIAL"
    if re.search(r"archivo|ruta|path|url|documento", name):
        return "DOCUMENT_OR_FILE_REFERENCE"
    return existing if existing and existing != "UNKNOWN" else "BUSINESS_DATA"


def semantic_column_description(row: dict, table_meta: dict) -> tuple[str, str, str]:
    table = row["table"]
    column = row["column"]
    data_type = row.get("data_type", "")
    col = column.lower()
    tokens = split_identifier(column)
    domain = table_meta[table]["owner_domain"]
    role = None
    for pattern, meaning in FIELD_PATTERNS:
        if pattern.search(col):
            role = meaning
            break
    concepts = concept_meanings(column)
    if col.startswith("id") and concepts:
        role = f"Identificador o clave foranea candidata que referencia {', '.join(dict.fromkeys(concepts[:3]))}."
    if role is None:
        role = (
            f"Campo de negocio relacionado con {', '.join(dict.fromkeys(concepts))}."
            if concepts
            else "Campo legacy cuyo significado se debe confirmar por pantalla, consulta SQL o datos productivos."
        )

    table_desc = table_meta[table]["short_concept"]
    nullability = "obligatorio" if row.get("nullable") == "NO" else "opcional o nullable"
    key = row.get("key_role", "")
    if key == "PRIMARY_KEY":
        key_text = " Actua como clave primaria del registro."
    elif key:
        key_text = f" Rol tecnico detectado: {key}."
    else:
        key_text = ""
    description = (
        f"En `{table}`, campo {nullability} del dominio {domain}. {role} "
        f"Se interpreta dentro de: {table_desc}.{key_text} Tipo fisico: {data_type}."
    )
    source = "SEMANTIC_REVERSE_ENGINEERING_CANDIDATE"
    sensitivity = infer_sensitivity(column, data_type, row.get("sensitivity", ""))
    return description, source, sensitivity


def load_csv(path: Path) -> list[dict]:
    with path.open("r", encoding="utf-8-sig", newline="") as handle:
        return list(csv.DictReader(handle))


def write_csv(path: Path, rows: list[dict], fieldnames: list[str]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", encoding="utf-8", newline="") as handle:
        writer = csv.DictWriter(handle, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)


def write_tsv(path: Path, rows: list[dict], fieldnames: list[str]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", encoding="utf-8", newline="") as handle:
        writer = csv.DictWriter(handle, fieldnames=fieldnames, delimiter="\t", lineterminator="\n")
        writer.writeheader()
        writer.writerows(rows)


def main() -> None:
    tables = load_csv(SOURCE_DATA / "tables.csv")
    columns = load_csv(SOURCE_DATA / "columns.csv")

    table_meta: dict[str, dict] = {}
    enriched_tables = []
    for row in tables:
        description, owner_domain, lifecycle, pii = semantic_table_description(row)
        tokens = concept_meanings(row["table"])
        short_concept = ", ".join(dict.fromkeys(tokens[:3])) or table_domain(row["table"])[1]
        new = dict(row)
        new["business_description"] = description
        new["description_source"] = "SEMANTIC_REVERSE_ENGINEERING_CANDIDATE"
        new["validation_status"] = "SEMANTIC_INFERENCE_REVIEW_REQUIRED"
        new["owner_domain"] = owner_domain
        new["lifecycle"] = lifecycle
        new["pii"] = pii
        table_meta[row["table"]] = {
            "owner_domain": owner_domain,
            "short_concept": short_concept,
            "business_description": description,
        }
        enriched_tables.append(new)

    enriched_columns = []
    for row in columns:
        new = dict(row)
        description, source, sensitivity = semantic_column_description(row, table_meta)
        new["business_description"] = description
        new["description_source"] = source
        new["validation_status"] = "SEMANTIC_INFERENCE_REVIEW_REQUIRED"
        new["sensitivity"] = sensitivity
        enriched_columns.append(new)

    table_fields = list(enriched_tables[0].keys())
    column_fields = list(enriched_columns[0].keys())

    for base in (SOURCE_DATA, RELEASE_DATA):
        write_csv(base / "tables.csv", enriched_tables, table_fields)
        write_csv(base / "columns.csv", enriched_columns, column_fields)
        write_csv(base / "SEMANTIC_TABLE_CATALOG.csv", enriched_tables, table_fields)
        write_csv(base / "SEMANTIC_FIELD_CATALOG.csv", enriched_columns, column_fields)
        write_tsv(base / "semantic_tables.tsv", enriched_tables, table_fields)
        write_tsv(base / "semantic_fields.tsv", enriched_columns, column_fields)
        write_business_backlog(base / "business_review_backlog.csv", enriched_tables, enriched_columns)

    write_csv(RELEASE_DATA / "TABLE_CATALOG.csv", enriched_tables, table_fields)
    write_csv(RELEASE_DATA / "COLUMN_CATALOG.csv", enriched_columns, column_fields)

    payload = json.loads((SOURCE_DATA / "data_dictionary.json").read_text(encoding="utf-8"))
    payload["semantic_enrichment"] = {
        "status": "SEMANTIC_INFERENCE_REVIEW_REQUIRED",
        "language": "Spanish",
        "method": [
            "table prefix/domain family",
            "business term decomposition",
            "field role patterns",
            "data type/nullability/key role",
            "read/write evidence counts",
            "sensitivity heuristics",
        ],
    }
    payload["tables"] = enriched_tables
    payload["columns"] = enriched_columns
    for base in (SOURCE_DATA, RELEASE_DATA):
        (base / "data_dictionary.json").write_text(json.dumps(payload, indent=2, ensure_ascii=False) + "\n", encoding="utf-8")
        (base / "DATA_DICTIONARY.json").write_text(json.dumps(payload, indent=2, ensure_ascii=False) + "\n", encoding="utf-8")

    generate_markdown(enriched_tables, enriched_columns)
    generate_summary(enriched_tables, enriched_columns)


def generate_markdown(tables: list[dict], columns: list[dict]) -> None:
    table_by_name = {row["table"]: row for row in tables}
    columns_by_table = defaultdict(list)
    for row in columns:
        columns_by_table[row["table"]].append(row)

    lines = [
        "# Data Dictionary - semantic AS-IS candidate",
        "",
        "Estado: SEMANTIC_INFERENCE_REVIEW_REQUIRED",
        "Idioma de negocio: Spanish",
        "",
        "Este diccionario incluye ingenieria inversa semantica candidata. Las descripciones no estan confirmadas por negocio, pero ya no son placeholders: explican el posible significado funcional de tablas y campos usando nombres, prefijos, tipos, claves, sensibilidad y evidencias de lectura/escritura.",
        "",
        f"- Tablas: {len(tables)}",
        f"- Campos: {len(columns)}",
        "",
        "## Tablas",
        "",
        "| Table | Dominio candidato | Lifecycle | Semantica candidata | Reads | Writes | Evidence |",
        "|---|---|---|---|---:|---:|---|",
    ]
    for row in tables:
        lines.append(
            f"| `{row['table']}` | {row.get('owner_domain','')} | {row.get('lifecycle','')} | {escape_md(row.get('business_description',''))} | {row.get('read_references','')} | {row.get('write_references','')} | `{row.get('evidence_id','')}` |"
        )
    lines.extend(["", "## Campos por tabla", ""])
    for table in sorted(table_by_name):
        lines.extend([
            f"### `{table}`",
            "",
            table_by_name[table]["business_description"],
            "",
            "| Campo | Tipo | Nullable | Sensibilidad | Semantica candidata | Evidence |",
            "|---|---|---|---|---|---|",
        ])
        for col in columns_by_table[table]:
            lines.append(
                f"| `{col['column']}` | `{escape_md(col.get('data_type',''))}` | {col.get('nullable','')} | {col.get('sensitivity','')} | {escape_md(col.get('business_description',''))} | `{col.get('evidence_id','')}` |"
            )
        lines.append("")

    text = "\n".join(lines) + "\n"
    for base in (SOURCE_DATA, RELEASE_DATA):
        (base / "DATA_DICTIONARY.md").write_text(text, encoding="utf-8")
        (base / "SEMANTIC_DATA_DICTIONARY.md").write_text(text, encoding="utf-8")
    (RELEASE_DATA / "TABLE_CATALOG.md").write_text(text, encoding="utf-8")


def write_business_backlog(path: Path, tables: list[dict], columns: list[dict]) -> None:
    rows = []
    for row in tables:
        rows.append(
            {
                "artifact_type": "TABLE",
                "artifact": row["table"],
                "technical_description": row.get("technical_description", ""),
                "semantic_candidate_description": row.get("business_description", ""),
                "validation_status": "SEMANTIC_INFERENCE_REVIEW_REQUIRED",
                "review_question": f"Confirmar si `{row['table']}` representa correctamente: {row.get('business_description', '')}",
                "evidence_ids": json.dumps([row.get("evidence_id", "")], ensure_ascii=False),
                "owner_domain": row.get("owner_domain", ""),
                "sensitivity": row.get("pii", ""),
            }
        )
    for row in columns:
        rows.append(
            {
                "artifact_type": "COLUMN",
                "artifact": f"{row['table']}.{row['column']}",
                "technical_description": row.get("technical_description", ""),
                "semantic_candidate_description": row.get("business_description", ""),
                "validation_status": "SEMANTIC_INFERENCE_REVIEW_REQUIRED",
                "review_question": f"Confirmar significado funcional, reglas y uso en pantalla/reporte de `{row['table']}.{row['column']}`.",
                "evidence_ids": json.dumps([row.get("evidence_id", "")], ensure_ascii=False),
                "owner_domain": table_domain(row["table"])[0],
                "sensitivity": row.get("sensitivity", ""),
            }
        )
    write_csv(
        path,
        rows,
        [
            "artifact_type",
            "artifact",
            "technical_description",
            "semantic_candidate_description",
            "validation_status",
            "review_question",
            "evidence_ids",
            "owner_domain",
            "sensitivity",
        ],
    )


def generate_summary(tables: list[dict], columns: list[dict]) -> None:
    domain_counts = Counter(row.get("owner_domain", "") for row in tables)
    sensitivity_counts = Counter(row.get("sensitivity", "") for row in columns)
    lifecycle_counts = Counter(row.get("lifecycle", "") for row in tables)
    lines = [
        "# Semantic reverse engineering report",
        "",
        "Estado: SEMANTIC_INFERENCE_REVIEW_REQUIRED",
        "Idioma: Spanish",
        "",
        "## Que se ha generado",
        "",
        "- Descripcion semantica candidata para cada tabla.",
        "- Descripcion semantica candidata para cada campo.",
        "- Dominio candidato, lifecycle y sensibilidad.",
        "- Salidas en Markdown, CSV, TSV, JSON y Excel.",
        "",
        "## Metodo",
        "",
        "La inferencia combina prefijo de tabla, terminos funcionales, patrones de campo, tipo fisico, nulabilidad, clave primaria/relaciones candidatas, uso observado y reglas de sensibilidad.",
        "",
        "## Distribucion por dominio",
        "",
        "| Dominio | Tablas |",
        "|---|---:|",
    ]
    for domain, count in domain_counts.most_common():
        lines.append(f"| {domain} | {count} |")
    lines.extend(["", "## Distribucion por lifecycle", "", "| Lifecycle | Tablas |", "|---|---:|"])
    for lifecycle, count in lifecycle_counts.most_common():
        lines.append(f"| {lifecycle} | {count} |")
    lines.extend(["", "## Distribucion por sensibilidad de campos", "", "| Sensibilidad | Campos |", "|---|---:|"])
    for sensitivity, count in sensitivity_counts.most_common():
        lines.append(f"| {sensitivity} | {count} |")
    lines.extend([
        "",
        "## Limitacion",
        "",
        "Esto es ingenieria inversa semantica candidata, no validacion final. Debe revisarse con negocio y con pruebas de caracterizacion antes de canonizar.",
    ])
    text = "\n".join(lines) + "\n"
    (RELEASE_DATA / "SEMANTIC_REVERSE_ENGINEERING.md").write_text(text, encoding="utf-8")
    (TRACE / "SEMANTIC_REVERSE_ENGINEERING.md").write_text(text, encoding="utf-8")


def escape_md(value: str) -> str:
    return str(value).replace("|", "\\|").replace("\n", " ")


if __name__ == "__main__":
    main()
