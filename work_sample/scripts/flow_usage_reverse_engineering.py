from __future__ import annotations

import csv
import json
import re
from collections import Counter, defaultdict
from pathlib import Path


ROOT = Path(__file__).resolve().parents[3]
RELEASE = ROOT / ".brownfield" / "work" / "release"
DOMAINS = RELEASE / "business-analysis" / "processes" / "domains"
DATA = RELEASE / "engineering-analysis" / "data"
TRACE = RELEASE / "traceability" / "verification"
OUT = RELEASE / "business-analysis" / "semantic-flow-analysis"


EVIDENCE_REF_RE = re.compile(r"`([^`]+?:\d+(?:-\d+)?)`")
BACKTICK_RE = re.compile(r"`([^`]+)`")
MUTATION_RE = re.compile(r"\b(INSERT\s+INTO|UPDATE|DELETE\s+FROM|REPLACE\s+INTO|CREATE|DROP|ALTER)\s+`?([A-Za-z0-9_]+)`?", re.I)
SQL_TABLE_RE = re.compile(r"\b(FROM|JOIN|INTO|UPDATE|DELETE\s+FROM)\s+`?([A-Za-z0-9_]+)`?", re.I)


def load_csv(path: Path) -> list[dict]:
    with path.open("r", encoding="utf-8-sig", newline="") as handle:
        return list(csv.DictReader(handle))


def write_csv(path: Path, rows: list[dict], fields: list[str]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", encoding="utf-8", newline="") as handle:
        writer = csv.DictWriter(handle, fieldnames=fields)
        writer.writeheader()
        writer.writerows(rows)


def read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="replace") if path.exists() else ""


def compact(value: str, limit: int = 1200) -> str:
    value = re.sub(r"\s+", " ", value or "").strip()
    return value if len(value) <= limit else value[: limit - 3] + "..."


def extract_backticks(text: str) -> list[str]:
    return [item.strip() for item in BACKTICK_RE.findall(text) if item.strip()]


def extract_evidence_refs(text: str) -> list[str]:
    refs = []
    for ref in EVIDENCE_REF_RE.findall(text):
        if ref not in refs:
            refs.append(ref)
    return refs


def sentence_context(text: str, token: str, limit: int = 6) -> list[str]:
    chunks = re.split(r"(?<=[.!?])\s+|\n+-\s+|\n\|", text)
    found = []
    needle = token.lower()
    for chunk in chunks:
        if needle in chunk.lower():
            clean = compact(chunk, 360)
            if clean and clean not in found:
                found.append(clean)
        if len(found) >= limit:
            break
    return found


def infer_usage(domain: str, table: str, texts: dict[str, str], mutations: list[str]) -> tuple[str, str, str]:
    joined = "\n".join(texts.values()).lower()
    table_l = table.lower()
    mutation_text = " ".join(mutations).lower()

    if "insert" in mutation_text and "update" in mutation_text:
        usage = "CREATE_AND_UPDATE"
    elif "insert" in mutation_text:
        usage = "CREATE"
    elif "update" in mutation_text:
        usage = "UPDATE"
    elif "delete" in mutation_text:
        usage = "DELETE_OR_REMOVE"
    elif table_l.startswith("tmp_"):
        usage = "TEMPORARY_VALIDATION_OR_REPORTING_SUPPORT"
    elif re.search(r"estado|tipo|param|catalog", table_l):
        usage = "REFERENCE_OR_STATE_CATALOG"
    elif "reporte" in joined or "dashboard" in joined:
        usage = "REPORTING_READ_MODEL"
    else:
        usage = "READ_OR_CONTEXT"

    rule_hints = []
    if re.search(r"permiso|autori[sz]a|sesion|cliente", joined):
        rule_hints.append("control de acceso/cliente")
    if re.search(r"estado|finaliz|aprob|observ|pendiente|rechaz", joined):
        rule_hints.append("transicion o bloqueo por estado")
    if re.search(r"document|archivo|ocr|pdf|excel|zip|rar", joined):
        rule_hints.append("regla documental/carga-descarga")
    if re.search(r"monto|costo|gasto|factur|pago|saldo|iva|ice|cif|tribut", joined):
        rule_hints.append("calculo financiero/impositivo")
    if re.search(r"insert|update|mysql_insert_id|max\(|transaccion|concurr", joined):
        rule_hints.append("persistencia/atomicidad/concurrencia")
    if re.search(r"correo|mail|pusher|notific|token", joined):
        rule_hints.append("notificacion o acceso externo")

    risk_hints = []
    if "sql" in joined and ("concaten" in joined or "interpol" in joined):
        risk_hints.append("SQL construido dinamicamente")
    if "transaccion" in joined or "atomicidad" in joined or "concurr" in joined:
        risk_hints.append("atomicidad/concurrencia pendiente")
    if re.search(r"cliente `?\d+`?|cliente especial|cliente", joined) and "confirm" in joined:
        risk_hints.append("variante cliente pendiente de confirmar")
    if re.search(r"catalogo|significado|semantica|tipo .*requiere|estado .*requiere", joined):
        risk_hints.append("catalogo/semantica pendiente")
    if re.search(r"token|password|secret|credencial|permiso", joined):
        risk_hints.append("seguridad/autorizacion sensible")

    return usage, "; ".join(rule_hints) or "uso funcional inferido por cruce de flujo/datos", "; ".join(risk_hints)


def infer_field_flow_role(field: dict, contexts: list[str]) -> str:
    name = field["column"].lower()
    text = " ".join(contexts).lower()
    if name.startswith("id") and name != "id":
        return "Referencia funcional que vincula el flujo con otra entidad/catalogo."
    if re.search(r"estado|status|finaliz|aprob|pendiente|observ", name):
        return "Campo de estado o hito usado para permitir, bloquear o reportar avance."
    if re.search(r"fecha|fec", name):
        return "Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria."
    if re.search(r"monto|costo|gasto|saldo|iva|ice|cif|valor|precio|pago", name):
        return "Valor economico usado en calculos, liquidaciones, conciliacion o reporteria."
    if re.search(r"document|archivo|pdf|excel|ruta", name) or re.search(r"document|archivo|ocr|pdf|excel", text):
        return "Dato documental o referencia a soporte/carga/descarga dentro del flujo."
    if re.search(r"mail|correo|telefono|celular|nombre|direccion|nit", name):
        return "Dato de contacto/identificacion usado para coordinacion, legalidad o notificacion."
    if re.search(r"token|clave|password", name):
        return "Dato de acceso/credencial que requiere control de seguridad."
    return "Campo de soporte funcional mencionado en datos/reglas del flujo."


def main() -> None:
    table_rows = load_csv(DATA / "SEMANTIC_TABLE_CATALOG.csv")
    field_rows = load_csv(DATA / "SEMANTIC_FIELD_CATALOG.csv")
    tables = {row["table"]: row for row in table_rows}
    fields_by_table: dict[str, list[dict]] = defaultdict(list)
    for row in field_rows:
        fields_by_table[row["table"]].append(row)
    field_names_by_table = {table: {row["column"] for row in rows} for table, rows in fields_by_table.items()}

    evidence_by_source = load_evidence_index()
    flow_rows = []
    field_flow_rows = []
    domain_summaries = []

    for domain_dir in sorted(p for p in DOMAINS.iterdir() if p.is_dir() and p.name != "_domain-template"):
        domain = domain_dir.name
        texts = {
            "definition": read_text(domain_dir / "PROCESS_DEFINITION.md"),
            "flow": read_text(domain_dir / "PROCESS_FLOW.md"),
            "rules": read_text(domain_dir / "BUSINESS_RULES.md"),
            "data": read_text(domain_dir / "DATA_USED.md"),
            "state": read_text(domain_dir / "STATE_MODEL.md"),
            "evidence": read_text(TRACE / f"{domain}-evidence-map.md"),
        }
        joined = "\n".join(texts.values())
        backticks = extract_backticks(joined)
        mentioned_tables = sorted({item for item in backticks if item in tables})
        sql_tables = sorted({m.group(2) for m in SQL_TABLE_RE.finditer(joined) if m.group(2) in tables})
        mentioned_tables = sorted(set(mentioned_tables) | set(sql_tables))
        evidence_refs = extract_evidence_refs(joined)
        mutations_by_table: dict[str, list[str]] = defaultdict(list)
        for match in MUTATION_RE.finditer(joined):
            operation, table = match.group(1).upper(), match.group(2)
            if table in tables:
                mutations_by_table[table].append(operation)

        domain_rules = extract_rule_lines(texts["rules"])
        for table in mentioned_tables:
            table_contexts = []
            for name, text in texts.items():
                table_contexts.extend(sentence_context(text, table, limit=4))
            fields = sorted({item for item in backticks if item in field_names_by_table.get(table, set())})
            if not fields:
                fields = infer_fields_from_context(table, joined, fields_by_table)
            usage, rule_hints, risk_hints = infer_usage(domain, table, texts, mutations_by_table.get(table, []))
            source_refs = refs_for_table(table, evidence_by_source, evidence_refs, table_contexts)
            flow_rows.append(
                {
                    "domain": domain,
                    "table": table,
                    "candidate_domain": tables[table].get("owner_domain", ""),
                    "flow_usage": usage,
                    "semantic_role_in_flow": infer_table_role_in_flow(table, usage, table_contexts),
                    "fields_in_flow": ", ".join(fields[:80]),
                    "rule_hints": rule_hints,
                    "risk_hints": risk_hints,
                    "mutations_observed": ", ".join(sorted(set(mutations_by_table.get(table, [])))),
                    "table_context": " | ".join(table_contexts[:6]),
                    "evidence_refs": " | ".join(source_refs[:20]),
                    "read_references": tables[table].get("read_references", ""),
                    "write_references": tables[table].get("write_references", ""),
                    "validation_status": "FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED",
                }
            )

            for field_name in fields:
                field = next((f for f in fields_by_table.get(table, []) if f["column"] == field_name), None)
                if not field:
                    continue
                field_contexts = []
                for text in texts.values():
                    field_contexts.extend(sentence_context(text, field_name, limit=3))
                if not field_contexts:
                    field_contexts = table_contexts[:2]
                field_flow_rows.append(
                    {
                        "domain": domain,
                        "table": table,
                        "field": field_name,
                        "field_type": field.get("data_type", ""),
                        "sensitivity": field.get("sensitivity", ""),
                        "flow_role": infer_field_flow_role(field, field_contexts),
                        "semantic_description": field.get("business_description", ""),
                        "contexts": " | ".join(field_contexts[:5]),
                        "evidence_refs": " | ".join(source_refs[:12]),
                        "validation_status": "FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED",
                    }
                )

        domain_summaries.append(
            {
                "domain": domain,
                "tables_crossed": len(mentioned_tables),
                "fields_crossed": sum(1 for row in field_flow_rows if row["domain"] == domain),
                "mutation_tables": len([t for t in mentioned_tables if mutations_by_table.get(t)]),
                "rule_count": len(domain_rules),
                "evidence_refs": len(evidence_refs),
                "risk_summary": summarize_domain_risks(joined),
            }
        )

    OUT.mkdir(parents=True, exist_ok=True)
    flow_fields = [
        "domain",
        "table",
        "candidate_domain",
        "flow_usage",
        "semantic_role_in_flow",
        "fields_in_flow",
        "rule_hints",
        "risk_hints",
        "mutations_observed",
        "table_context",
        "evidence_refs",
        "read_references",
        "write_references",
        "validation_status",
    ]
    field_fields = [
        "domain",
        "table",
        "field",
        "field_type",
        "sensitivity",
        "flow_role",
        "semantic_description",
        "contexts",
        "evidence_refs",
        "validation_status",
    ]
    summary_fields = ["domain", "tables_crossed", "fields_crossed", "mutation_tables", "rule_count", "evidence_refs", "risk_summary"]
    write_csv(OUT / "FLOW_TABLE_USAGE_MATRIX.csv", flow_rows, flow_fields)
    write_csv(OUT / "FLOW_FIELD_USAGE_MATRIX.csv", field_flow_rows, field_fields)
    write_csv(OUT / "DOMAIN_FLOW_SEMANTIC_SUMMARY.csv", domain_summaries, summary_fields)
    write_markdown_report(flow_rows, field_flow_rows, domain_summaries)
    write_domain_reports(flow_rows, field_flow_rows, domain_summaries)
    copy_to_traceability()


def load_evidence_index() -> dict[str, list[str]]:
    result: dict[str, list[str]] = defaultdict(list)
    path = TRACE / "EVIDENCE_INDEX.jsonl"
    if not path.exists():
        return result
    with path.open("r", encoding="utf-8", errors="replace") as handle:
        for line in handle:
            try:
                item = json.loads(line)
            except json.JSONDecodeError:
                continue
            source = str(item.get("source", ""))
            obs = str(item.get("observation", ""))
            if source and obs:
                result[source].append(f"{item.get('evidence_id','')} {source}:{item.get('line_start','')} {obs}")
    return result


def refs_for_table(table: str, evidence_by_source: dict[str, list[str]], explicit_refs: list[str], contexts: list[str]) -> list[str]:
    refs = list(explicit_refs)
    for context in contexts:
        refs.extend(extract_evidence_refs(context))
    table_l = table.lower()
    for source_refs in evidence_by_source.values():
        for ref in source_refs:
            if table_l in ref.lower() and ref not in refs:
                refs.append(ref)
                if len(refs) >= 25:
                    return refs
    return refs


def infer_fields_from_context(table: str, joined: str, fields_by_table: dict[str, list[dict]]) -> list[str]:
    candidates = []
    text_l = joined.lower()
    for field in fields_by_table.get(table, []):
        name = field["column"]
        if len(name) > 2 and name.lower() in text_l:
            candidates.append(name)
    return sorted(set(candidates))


def extract_rule_lines(text: str) -> list[str]:
    lines = []
    for line in text.splitlines():
        if re.search(r"\|\s*BR-|^-|regla|requiere|debe|no se|si ", line, re.I):
            clean = compact(line.strip(" |"), 500)
            if clean:
                lines.append(clean)
    return lines


def infer_table_role_in_flow(table: str, usage: str, contexts: list[str]) -> str:
    text = " ".join(contexts).lower()
    if usage in {"CREATE", "CREATE_AND_UPDATE", "UPDATE"}:
        return "Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor."
    if table.startswith("tmp_"):
        return "Tabla temporal/staging usada para preparar, validar o consolidar informacion del flujo."
    if re.search(r"catalogo|determina|parametro|permiso|tipo", text):
        return "Catalogo o parametro que condiciona reglas, permisos o variantes del flujo."
    if re.search(r"document|archivo|ocr|pdf|excel", text):
        return "Entidad documental o soporte usado para validar, adjuntar, generar o descargar evidencias."
    if re.search(r"reporte|dashboard|kpi|consulta", text):
        return "Modelo de lectura o fuente de reporteria del flujo."
    return "Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio."


def summarize_domain_risks(text: str) -> str:
    risks = []
    lower = text.lower()
    if "sql" in lower and ("concaten" in lower or "interpol" in lower):
        risks.append("SQL dinamico")
    if "transaccion" in lower or "atomicidad" in lower or "concurr" in lower:
        risks.append("atomicidad/concurrencia")
    if "permiso" in lower or "autoriz" in lower:
        risks.append("permisos/autorizacion")
    if "document" in lower or "archivo" in lower or "ocr" in lower:
        risks.append("documentos/OCR")
    if "cliente `" in lower or "cliente especial" in lower:
        risks.append("variante cliente")
    if "catalogo" in lower or "significado" in lower:
        risks.append("catalogos/semantica")
    return "; ".join(risks)


def write_markdown_report(flow_rows: list[dict], field_rows: list[dict], summaries: list[dict]) -> None:
    usage_counts = Counter(row["flow_usage"] for row in flow_rows)
    lines = [
        "# Flow usage semantic reverse engineering",
        "",
        "Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED",
        "Idioma: Spanish",
        "",
        "## Proposito",
        "",
        "Segunda pasada de ingenieria inversa centrada en flujos, usos, reglas y evidencias cruzadas. A diferencia del diccionario por identificador, esta capa cruza dominios de negocio con tablas, campos, reglas, mutaciones, estados, documentos, permisos y evidencias.",
        "",
        "## Cobertura",
        "",
        f"- Dominios analizados: {len(summaries)}",
        f"- Cruces dominio-tabla: {len(flow_rows)}",
        f"- Cruces dominio-tabla-campo: {len(field_rows)}",
        "",
        "## Distribucion de uso",
        "",
        "| Uso de tabla en flujo | Cruces |",
        "|---|---:|",
    ]
    for usage, count in usage_counts.most_common():
        lines.append(f"| {usage} | {count} |")
    lines.extend([
        "",
        "## Dominios con mas cruce semantico",
        "",
        "| Dominio | Tablas | Campos | Tablas mutadas | Reglas | Riesgos |",
        "|---|---:|---:|---:|---:|---|",
    ])
    for row in sorted(summaries, key=lambda item: int(item["tables_crossed"]), reverse=True)[:30]:
        lines.append(
            f"| `{row['domain']}` | {row['tables_crossed']} | {row['fields_crossed']} | {row['mutation_tables']} | {row['rule_count']} | {row['risk_summary']} |"
        )
    lines.extend([
        "",
        "## Ejemplos de inferencia cruzada",
        "",
        "| Dominio | Tabla | Uso | Rol semantico en flujo | Reglas/riesgos | Evidencia |",
        "|---|---|---|---|---|---|",
    ])
    for row in flow_rows[:80]:
        lines.append(
            f"| `{row['domain']}` | `{row['table']}` | {row['flow_usage']} | {escape_md(row['semantic_role_in_flow'])} | {escape_md(row['rule_hints'] + ('; ' + row['risk_hints'] if row['risk_hints'] else ''))} | {escape_md(row['evidence_refs'][:350])} |"
        )
    lines.extend([
        "",
        "## Artefactos generados",
        "",
        "- `FLOW_TABLE_USAGE_MATRIX.csv`: matriz dominio-tabla con uso, rol, reglas, riesgos y evidencias.",
        "- `FLOW_FIELD_USAGE_MATRIX.csv`: matriz dominio-tabla-campo solo para campos presentes en flujos/datos/reglas.",
        "- `DOMAIN_FLOW_SEMANTIC_SUMMARY.csv`: resumen por dominio.",
        "- `domains/*.md`: informe semantico cruzado por dominio.",
        "",
        "## Limitacion",
        "",
        "Las inferencias siguen siendo candidatas. Deben validarse con negocio, datos reales y pruebas de caracterizacion antes de canonizar.",
    ])
    (OUT / "FLOW_USAGE_SEMANTIC_REVERSE_ENGINEERING.md").write_text("\n".join(lines) + "\n", encoding="utf-8")


def write_domain_reports(flow_rows: list[dict], field_rows: list[dict], summaries: list[dict]) -> None:
    domain_dir = OUT / "domains"
    domain_dir.mkdir(parents=True, exist_ok=True)
    by_domain = defaultdict(list)
    fields_by_domain = defaultdict(list)
    for row in flow_rows:
        by_domain[row["domain"]].append(row)
    for row in field_rows:
        fields_by_domain[row["domain"]].append(row)
    summary_by_domain = {row["domain"]: row for row in summaries}
    for domain in sorted(summary_by_domain):
        rows = by_domain.get(domain, [])
        summary = summary_by_domain.get(domain, {})
        lines = [
            f"# {domain} - semantic flow usage",
            "",
            "Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED",
            "",
            "## Resumen",
            "",
            f"- Tablas cruzadas: {summary.get('tables_crossed', len(rows))}",
            f"- Campos cruzados: {summary.get('fields_crossed', len(fields_by_domain[domain]))}",
            f"- Tablas con mutacion observada: {summary.get('mutation_tables', 0)}",
            f"- Riesgos candidatos: {summary.get('risk_summary', '')}",
            "",
            "## Tablas en el flujo",
            "",
            "| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |",
            "|---|---|---|---|---|---|",
        ]
        if rows:
            for row in rows:
                lines.append(
                    f"| `{row['table']}` | {row['flow_usage']} | {escape_md(row['semantic_role_in_flow'])} | {escape_md(row['fields_in_flow'][:500])} | {escape_md(row['rule_hints'] + ('; ' + row['risk_hints'] if row['risk_hints'] else ''))} | {escape_md(row['evidence_refs'][:500])} |"
                )
        else:
            lines.append("| _Sin tabla cruzada_ | N/A | No se detectaron tablas explicitas en los artefactos del flujo; revisar evidencia, pantallas o reportes fuente. |  |  |  |")
        lines.extend(["", "## Campos con uso cruzado", "", "| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |", "|---|---|---|---|---|"])
        for row in fields_by_domain[domain][:160]:
            lines.append(
                f"| `{row['table']}` | `{row['field']}` | {escape_md(row['flow_role'])} | {row['sensitivity']} | {escape_md(row['contexts'][:600])} |"
            )
        (domain_dir / f"{domain}.md").write_text("\n".join(lines) + "\n", encoding="utf-8")


def copy_to_traceability() -> None:
    target = TRACE / "FLOW_USAGE_SEMANTIC_REVERSE_ENGINEERING.md"
    target.write_text((OUT / "FLOW_USAGE_SEMANTIC_REVERSE_ENGINEERING.md").read_text(encoding="utf-8"), encoding="utf-8")


def escape_md(value: str) -> str:
    return str(value).replace("|", "\\|").replace("\n", " ")


if __name__ == "__main__":
    main()
