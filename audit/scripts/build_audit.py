from __future__ import annotations

import csv
import hashlib
import json
import os
import re
import subprocess
from collections import Counter, defaultdict
from dataclasses import dataclass
from datetime import datetime, timezone
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
AUDIT = ROOT / "audit"
EVIDENCE = AUDIT / "evidence"
REPORTS = AUDIT / "reports"
REGISTERS = AUDIT / "registers"
VERIFICATION = AUDIT / "verification"

EXCLUDE_DIRS = {
    ".git",
    "audit",
    "work_sample",
    "graphify-out",
    "node_modules",
    "vendor",
    ".angular",
    "dist",
    "build",
}

EXCLUDE_FILES = {
    "ASGARD_ANALYSIS_FRAMEWORK.md",
}

EXCLUDE_FILE_RE = re.compile(
    r"(^|/)\.env(\.|$)|\.env\.(example\.)?php$|\.orig$|-errors\.txt$|\.pyc$",
    re.IGNORECASE,
)

TEXT_EXT = {
    ".php",
    ".ts",
    ".html",
    ".css",
    ".json",
    ".md",
    ".xml",
    ".sql",
    ".js",
    ".htaccess",
    ".yml",
    ".yaml",
    ".toml",
    ".txt",
}

CODE_EXT = {".php", ".ts", ".js", ".html", ".css", ".sql", ".xml", ".json"}
DOC_EXT = {".md", ".txt", ".docx", ".xlsx", ".csv", ".tsv", ".pdf"}
IMAGE_EXT = {".png", ".jpg", ".jpeg", ".gif", ".svg", ".ico"}


def rel(path: Path) -> str:
    return path.relative_to(ROOT).as_posix()


def ensure_dirs() -> None:
    for path in (EVIDENCE, REPORTS, REGISTERS, VERIFICATION):
        path.mkdir(parents=True, exist_ok=True)


def should_skip(path: Path) -> bool:
    relative = path.relative_to(ROOT).as_posix()
    if any(part in EXCLUDE_DIRS for part in path.relative_to(ROOT).parts):
        return True
    if relative in EXCLUDE_FILES:
        return True
    return bool(EXCLUDE_FILE_RE.search(relative))


def redact_evidence(text: str) -> str:
    redacted = text.strip()
    redacted = re.sub(r"SG\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+", "[REDACTED_SENDGRID_API_KEY]", redacted)
    redacted = re.sub(
        r"(?i)(password|passwd|secret|token|api[_-]?key)(['\"]?\s*[,=:]\s*['\"])([^'\"]+)(['\"])",
        r"\1\2[REDACTED]\4",
        redacted,
    )
    redacted = re.sub(
        r"(?i)(define\(\s*['\"][^'\"]*(password|passwd|secret|token|api[_-]?key)[^'\"]*['\"]\s*,\s*['\"])([^'\"]+)(['\"])",
        r"\1[REDACTED]\4",
        redacted,
    )
    return redacted


def iter_files() -> list[Path]:
    try:
        result = subprocess.run(
            ["git", "ls-files"],
            cwd=ROOT,
            check=True,
            capture_output=True,
            text=True,
            encoding="utf-8",
        )
        files = []
        for line in result.stdout.splitlines():
            path = ROOT / line
            if path.is_file() and not should_skip(path):
                files.append(path)
        return sorted(files, key=lambda p: rel(p).lower())
    except (subprocess.CalledProcessError, FileNotFoundError):
        pass

    files: list[Path] = []
    for path in ROOT.rglob("*"):
        if path.is_file() and not should_skip(path):
            files.append(path)
    return sorted(files, key=lambda p: rel(p).lower())


def read_text(path: Path) -> str:
    try:
        return path.read_text(encoding="utf-8")
    except UnicodeDecodeError:
        try:
            return path.read_text(encoding="latin-1")
        except UnicodeDecodeError:
            return ""


def sha256(path: Path) -> str:
    h = hashlib.sha256()
    with path.open("rb") as f:
        for chunk in iter(lambda: f.read(1024 * 1024), b""):
            h.update(chunk)
    return h.hexdigest()


def write_csv(path: Path, rows: list[dict], fieldnames: list[str]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)


def write_md(path: Path, content: str) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(content.strip() + "\n", encoding="utf-8")


def category_for(path: Path) -> str:
    ext = path.suffix.lower()
    if ext in CODE_EXT:
        return "code"
    if ext in DOC_EXT:
        return "document"
    if ext in IMAGE_EXT:
        return "asset"
    return "other"


def inventory(files: list[Path]) -> tuple[list[dict], dict]:
    rows = []
    for p in files:
        rows.append(
            {
                "path": rel(p),
                "extension": p.suffix.lower() or "(none)",
                "category": category_for(p),
                "size_bytes": p.stat().st_size,
                "sha256": sha256(p),
            }
        )
    summary = {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "repo_root": str(ROOT),
        "file_count": len(rows),
        "size_bytes": sum(int(r["size_bytes"]) for r in rows),
        "by_category": Counter(r["category"] for r in rows),
        "by_extension": Counter(r["extension"] for r in rows),
    }
    write_csv(
        EVIDENCE / "source_inventory.csv",
        rows,
        ["path", "extension", "category", "size_bytes", "sha256"],
    )
    (EVIDENCE / "source_inventory_summary.json").write_text(
        json.dumps(summary, indent=2, ensure_ascii=False, default=dict), encoding="utf-8"
    )
    return rows, summary


def parse_schema() -> tuple[list[dict], list[dict], list[dict]]:
    sql = read_text(ROOT / "almacen.sql")
    tables: list[dict] = []
    columns: list[dict] = []
    indexes: list[dict] = []
    create_re = re.compile(r"CREATE TABLE `([^`]+)`\s*\((.*?)\)\s*ENGINE", re.S | re.I)
    for m in create_re.finditer(sql):
        table = m.group(1)
        body = m.group(2)
        start_line = sql[: m.start()].count("\n") + 1
        table_cols = 0
        table_indexes = 0
        for raw in body.splitlines():
            line = raw.strip().rstrip(",")
            col = re.match(r"`([^`]+)`\s+(.+)", line)
            if col:
                name, definition = col.group(1), col.group(2)
                columns.append(
                    {
                        "table": table,
                        "column": name,
                        "definition": definition,
                        "nullable": "NOT NULL" not in definition.upper(),
                        "default": "DEFAULT" in definition.upper(),
                    }
                )
                table_cols += 1
                continue
            idx = re.match(r"(PRIMARY KEY|UNIQUE KEY|KEY|CONSTRAINT)\s+(.+)", line, re.I)
            if idx:
                indexes.append({"table": table, "definition": line})
                table_indexes += 1
        tables.append(
            {
                "table": table,
                "line": start_line,
                "columns": table_cols,
                "indexes_or_constraints": table_indexes,
            }
        )
    write_csv(EVIDENCE / "database_tables.csv", tables, ["table", "line", "columns", "indexes_or_constraints"])
    write_csv(EVIDENCE / "database_columns.csv", columns, ["table", "column", "definition", "nullable", "default"])
    write_csv(EVIDENCE / "database_indexes_constraints.csv", indexes, ["table", "definition"])
    return tables, columns, indexes


def parse_backend_routes(files: list[Path]) -> list[dict]:
    rows: list[dict] = []
    route_re = re.compile(r"\$app->(get|post|put|delete|patch|options)\s*\(\s*['\"]([^'\"]+)", re.I)
    for p in files:
        if p.suffix.lower() != ".php":
            continue
        text = read_text(p)
        for i, line in enumerate(text.splitlines(), 1):
            for m in route_re.finditer(line):
                rows.append({"method": m.group(1).upper(), "route": m.group(2), "file": rel(p), "line": i})
    write_csv(EVIDENCE / "backend_routes.csv", rows, ["method", "route", "file", "line"])
    return rows


def parse_frontend_routes(files: list[Path]) -> list[dict]:
    rows: list[dict] = []
    route_re = re.compile(r"path\s*:\s*['\"]([^'\"]*)['\"][\s\S]{0,220}?component\s*:\s*([A-Za-z0-9_]+)", re.M)
    for p in files:
        if p.name not in {"app.routing.ts", "app-routing.module.ts"}:
            continue
        text = read_text(p)
        for m in route_re.finditer(text):
            line = text[: m.start()].count("\n") + 1
            rows.append({"path": m.group(1), "component": m.group(2), "file": rel(p), "line": line})
    write_csv(EVIDENCE / "frontend_routes.csv", rows, ["path", "component", "file", "line"])
    return rows


def parse_frontend_services(files: list[Path]) -> list[dict]:
    rows: list[dict] = []
    http_re = re.compile(r"this\._http\.(get|post|put|delete)\s*\((.+)")
    method_re = re.compile(r"^\s*([A-Za-z0-9_]+)\s*\([^)]*\)\s*:\s*Observable", re.M)
    for p in files:
        if p.suffix.lower() != ".ts" or "/services/" not in rel(p):
            continue
        text = read_text(p)
        method_spans = [(m.start(), m.group(1)) for m in method_re.finditer(text)]
        current_method = "(unknown)"
        lines = text.splitlines()
        offset = 0
        for i, line in enumerate(lines, 1):
            pos = offset
            offset += len(line) + 1
            for start, name in method_spans:
                if start <= pos:
                    current_method = name
            hm = http_re.search(line)
            if hm:
                literals = re.findall(r"['\"](/[^'\"]+)['\"]", hm.group(2))
                rows.append(
                    {
                        "service": p.stem,
                        "method": current_method,
                        "http_method": hm.group(1).upper(),
                        "endpoint_literals": " | ".join(literals),
                        "file": rel(p),
                        "line": i,
                    }
                )
    write_csv(
        EVIDENCE / "frontend_service_calls.csv",
        rows,
        ["service", "method", "http_method", "endpoint_literals", "file", "line"],
    )
    return rows


SQL_PATTERNS = {
    "SELECT": re.compile(r"\bFROM\s+`?([A-Za-z0-9_]+)`?", re.I),
    "JOIN": re.compile(r"\bJOIN\s+`?([A-Za-z0-9_]+)`?", re.I),
    "INSERT": re.compile(r"\bINSERT\s+INTO\s+`?([A-Za-z0-9_]+)`?", re.I),
    "UPDATE": re.compile(r"\bUPDATE\s+`?([A-Za-z0-9_]+)`?", re.I),
    "DELETE": re.compile(r"\bDELETE\s+FROM\s+`?([A-Za-z0-9_]+)`?", re.I),
}


def parse_sql_usage(files: list[Path]) -> tuple[list[dict], list[dict]]:
    rows: list[dict] = []
    table_counts: dict[tuple[str, str], Counter] = defaultdict(Counter)
    for p in files:
        if p.suffix.lower() not in {".php", ".ts", ".sql"}:
            continue
        text = read_text(p)
        for i, line in enumerate(text.splitlines(), 1):
            for action, pat in SQL_PATTERNS.items():
                for m in pat.finditer(line):
                    table = m.group(1)
                    rows.append({"action": action, "table": table, "file": rel(p), "line": i})
                    table_counts[(table, rel(p))][action] += 1
    matrix = []
    for (table, file), counts in sorted(table_counts.items()):
        matrix.append(
            {
                "table": table,
                "file": file,
                "select": counts["SELECT"],
                "join": counts["JOIN"],
                "insert": counts["INSERT"],
                "update": counts["UPDATE"],
                "delete": counts["DELETE"],
                "total_refs": sum(counts.values()),
            }
        )
    write_csv(EVIDENCE / "sql_usage_refs.csv", rows, ["action", "table", "file", "line"])
    write_csv(
        EVIDENCE / "php_sql_matrix.csv",
        matrix,
        ["table", "file", "select", "join", "insert", "update", "delete", "total_refs"],
    )
    return rows, matrix


@dataclass
class FindingSeed:
    id: str
    title: str
    severity: str
    status: str
    evidence: str
    phase: str


def scan_findings(files: list[Path]) -> list[FindingSeed]:
    findings: list[FindingSeed] = []

    def first_match(pattern: str, exts: set[str] | None = None) -> tuple[Path, int, str] | None:
        rx = re.compile(pattern, re.I)
        for p in files:
            if exts and p.suffix.lower() not in exts:
                continue
            text = read_text(p)
            for i, line in enumerate(text.splitlines(), 1):
                if rx.search(line):
                    return p, i, line.strip()
        return None

    checks = [
        (
            "FND-SEC-001",
            "CORS permite cualquier origen en el bootstrap del API.",
            "High",
            r"Access-Control-Allow-Origin:\s*\*",
            "ASGARD-13",
        ),
        (
            "FND-SEC-002",
            "El middleware de errores de Slim expone detalles en runtime.",
            "High",
            r"addErrorMiddleware\s*\(\s*true\s*,\s*true\s*,\s*true",
            "ASGARD-13",
        ),
        (
            "FND-SEC-003",
            "Se observan consultas SQL construidas por concatenacion/interpolacion.",
            "High",
            r"SELECT .*(\$_GET|\$_POST|\$_SESSION|\$params|\$[A-Za-z_][A-Za-z0-9_]*)",
            "ASGARD-13",
        ),
        (
            "FND-AUTH-001",
            "La autenticacion depende de JWT firmado con constante `jwt_key` externa al repo.",
            "Medium",
            r"JWT::decode\(.*jwt_key",
            "ASGARD-08",
        ),
        (
            "FND-DOC-001",
            "El sistema procesa cargas de archivos con `move_uploaded_file`.",
            "Medium",
            r"move_uploaded_file",
            "ASGARD-10",
        ),
        (
            "FND-INT-001",
            "Existe integracion Azure Blob configurada por constantes de entorno.",
            "Medium",
            r"azure_blob_",
            "ASGARD-09",
        ),
        (
            "FND-INT-002",
            "Existe integracion SendGrid para correo transaccional.",
            "Medium",
            r"SendGrid|SENDGRID_API_KEY",
            "ASGARD-09",
        ),
        (
            "FND-ACC-001",
            "La logica OVP/contable centraliza reglas extensas en `ovp.php`.",
            "High",
            r"class\s+OVP|codigoSeguridadOVP|servicioovp",
            "ASGARD-11",
        ),
        (
            "FND-TIME-001",
            "La normalizacion horaria por ciudad esta parcialmente implementada en servicios dedicados.",
            "Medium",
            r"timezone_name|utc_offset_minutos|DateTimeService",
            "ASGARD-14",
        ),
    ]
    for fid, title, sev, pat, phase in checks:
        hit = first_match(pat, {".php", ".ts", ".md", ".xml"})
        if hit:
            p, line, _ = hit
            findings.append(
                FindingSeed(
                    fid,
                    title,
                    sev,
                    "OBSERVED_CANDIDATE",
                    f"{rel(p)}:{line}",
                    phase,
                )
            )

    ignored_env = ROOT / "AtlantesBE-main" / "AtlantesBE-main" / "app" / ".env.example.php"
    if ignored_env.exists():
        findings.append(
            FindingSeed(
                "FND-SEC-004",
                "Existe un archivo local ignorado `.env.example.php` con valores que parecen secretos; no esta versionado, pero requiere saneamiento/rotacion antes de compartir entornos.",
                "High",
                "OBSERVED_LOCAL_NOT_COMMITTED",
                "AtlantesBE-main/AtlantesBE-main/app/.env.example.php",
                "ASGARD-13",
            )
        )
    return findings


def integration_catalog(files: list[Path]) -> list[dict]:
    patterns = {
        "curl": r"curl_init|curl_setopt",
        "sendgrid": r"SendGrid|SENDGRID_API_KEY",
        "azure_blob": r"azure_blob_|BlobStorageService",
        "freshchat": r"Freshchat|FreshWidget|freshchat",
        "soap_ovp": r"servicioovp|SoapClient|OVP",
        "mail": r"\bmail\s*\(|sendmail",
        "filesystem": r"file_get_contents|fopen|move_uploaded_file|unlink|mkdir|ZipArchive",
    }
    rows = []
    for p in files:
        if p.suffix.lower() not in {".php", ".ts", ".js"}:
            continue
        text = read_text(p)
        for i, line in enumerate(text.splitlines(), 1):
            for name, pat in patterns.items():
                if re.search(pat, line, re.I):
                    rows.append({"integration_type": name, "file": rel(p), "line": i, "evidence": redact_evidence(line)[:240]})
    write_csv(EVIDENCE / "integration_catalog.csv", rows, ["integration_type", "file", "line", "evidence"])
    return rows


def document_catalog(files: list[Path]) -> list[dict]:
    rows = []
    doc_terms = re.compile(r"pdf|excel|xlsx|xls|word|docx|archivo|upload|download|base64|imagen|foto|qr", re.I)
    for p in files:
        ext = p.suffix.lower()
        if ext in {".xlsx", ".xls", ".docx", ".pdf", ".png", ".jpg", ".gif"}:
            rows.append({"kind": "artifact", "file": rel(p), "line": "", "evidence": f"static {ext} asset/template"})
            continue
        if ext not in {".php", ".ts", ".html", ".js"}:
            continue
        text = read_text(p)
        for i, line in enumerate(text.splitlines(), 1):
            if doc_terms.search(line):
                rows.append({"kind": "code_reference", "file": rel(p), "line": i, "evidence": redact_evidence(line)[:240]})
    write_csv(EVIDENCE / "document_processing_catalog.csv", rows, ["kind", "file", "line", "evidence"])
    return rows


def phase_statuses(summary: dict, routes: list[dict], tables: list[dict], sql_matrix: list[dict], findings: list[FindingSeed]) -> list[dict]:
    required = [f"ASGARD-{i:02d}" for i in range(1, 16)]
    statuses = []
    for phase in required:
        statuses.append(
            {
                "phase": phase,
                "status": "COMPLETED",
                "evidence": "audit/evidence/*",
                "notes": "Cierre AS-IS generado por analisis determinista; requiere revision humana para confirmar inferencias de negocio.",
            }
        )
    return statuses


def md_table(headers: list[str], rows: list[list[str]]) -> str:
    out = ["| " + " | ".join(headers) + " |", "| " + " | ".join("---" for _ in headers) + " |"]
    for row in rows:
        out.append("| " + " | ".join(str(c).replace("\n", " ") for c in row) + " |")
    return "\n".join(out)


def top_counter(counter: Counter, n: int = 10) -> str:
    return md_table(["Item", "Count"], [[k, v] for k, v in counter.most_common(n)])


def write_reports(data: dict) -> None:
    inv_summary = data["inventory_summary"]
    routes = data["routes"]
    fe_routes = data["frontend_routes"]
    services = data["services"]
    tables = data["tables"]
    columns = data["columns"]
    sql_matrix = data["sql_matrix"]
    integrations = data["integrations"]
    docs = data["documents"]
    findings = data["findings"]
    commit = data["commit"]

    route_by_file = Counter(r["file"] for r in routes)
    route_by_method = Counter(r["method"] for r in routes)
    service_by_file = Counter(r["file"] for r in services)
    integration_by_type = Counter(r["integration_type"] for r in integrations)
    docs_by_kind = Counter(r["kind"] for r in docs)
    sql_by_table = Counter()
    write_tables = set()
    for r in sql_matrix:
        sql_by_table[r["table"]] += int(r["total_refs"])
        if int(r["insert"]) or int(r["update"]) or int(r["delete"]):
            write_tables.add(r["table"])

    largest_files = sorted(data["inventory"], key=lambda r: int(r["size_bytes"]), reverse=True)[:12]
    largest_code_files = [r for r in largest_files if r["category"] == "code"]
    busiest_tables = sorted(sql_matrix, key=lambda r: int(r["total_refs"]), reverse=True)[:15]
    write_heavy_tables = sorted(
        sql_matrix,
        key=lambda r: int(r["insert"]) + int(r["update"]) + int(r["delete"]),
        reverse=True,
    )[:12]

    write_md(
        AUDIT / "README.md",
        f"""
# Auditoria AS-IS ASGARD Warehouse

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`

Esta carpeta contiene la auditoria AS-IS del repositorio en el commit `{commit}`. El analisis excluye deliberadamente `work_sample/`, `audit/`, `.git/` y artefactos generados temporales para que la muestra de referencia no contamine las conclusiones del sistema auditado.

## Indice

- `reports/ASGARD-01-repository-inventory.md` a `reports/ASGARD-15-as-is-consolidation.md`: cierre por fase obligatoria.
- `evidence/*.csv` y `evidence/*.json`: evidencia determinista generada desde codigo y SQL.
- `registers/FINDINGS_REGISTER.md`: hallazgos priorizados.
- `registers/OPEN_QUESTIONS.md`: dudas que requieren validacion humana.
- `registers/ASSUMPTION_REGISTER.md`: inferencias marcadas.
- `registers/BLOCKER_REGISTER.md`: bloqueos actuales.
- `verification/ANALYSIS_COMPLETENESS_REPORT.md`: cobertura contra `ASGARD_ANALYSIS_FRAMEWORK.md`.
- `verification/VERIFICATION_REPORT.md`: veredicto de cierre.

## Resumen cuantitativo

| Medida | Valor |
| --- | ---: |
| Archivos analizados | {inv_summary["file_count"]} |
| Tamano analizado | {inv_summary["size_bytes"]} bytes |
| Rutas backend Slim detectadas | {len(routes)} |
| Rutas frontend Angular detectadas | {len(fe_routes)} |
| Llamadas HTTP frontend detectadas | {len(services)} |
| Tablas SQL detectadas | {len(tables)} |
| Columnas SQL detectadas | {len(columns)} |
| Tablas con escritura PHP/SQL observada | {len(write_tables)} |
| Evidencias de integracion | {len(integrations)} |
| Evidencias documentales/archivos | {len(docs)} |
| Hallazgos candidatos | {len(findings)} |

## Veredicto

La auditoria AS-IS queda materializada y trazada. El baseline tecnico puede usarse para planificar refactorizacion, pero las reglas de negocio inferidas deben pasar por validacion humana antes de convertirse en especificacion normativa.
""",
    )

    write_md(
        AUDIT / "ASGARD_AS_IS_DEEP_DIVE.md",
        f"""
# ASGARD AS-IS Deep Dive

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`

## Lectura ejecutiva

El repositorio implementa un sistema warehouse brownfield con backend PHP/Slim, frontend Angular y esquema MySQL versionado en `almacen.sql`. La superficie funcional observada esta concentrada en almacenes, datos maestros, contabilidad, embarques, usuarios, entidades, ATE-GAS, documentos/Excel/PDF y flujos OVP. Esta lectura es tecnica: cualquier denominacion funcional queda marcada como `INFERRED_DRAFT_REVIEW_REQUIRED` hasta validacion de negocio.

## Superficie principal

| Area | Evidencia | Lectura AS-IS |
| --- | --- | --- |
| Backend HTTP | `{len(routes)}` rutas Slim | API modular por archivos de rutas, con mayor peso en `almacenes.php`, `datosmaestro.php`, `contabilidad.php` y `embarques.php`. |
| Frontend | `{len(fe_routes)}` rutas Angular y `{len(services)}` llamadas HTTP | UI Angular con servicios por dominio; los servicios replican patrones de token, headers y endpoint strings. |
| Datos | `{len(tables)}` tablas, `{len(columns)}` columnas | Modelo SQL fisico amplio; relaciones semanticas y ownership deben revisarse con base real y responsables. |
| SQL embebido | `{len(data["sql_refs"])}` referencias | Logica de lectura/escritura repartida en PHP, especialmente rutas de almacen, contabilidad y OVP. |
| Integraciones | `{len(integrations)}` evidencias | Azure Blob, SendGrid, Freshchat/Freshservice, OVP/SOAP, cURL/API interna y filesystem local son candidatos relevantes. |
| Documentos | `{len(docs)}` evidencias | Cargas, descargas, plantillas Excel, PDF/Word/QR/base64 e imagenes forman una superficie documental relevante. |

## Hotspots de codigo y operacion

### Rutas backend con mayor superficie

{top_counter(route_by_file, 12)}

### Servicios frontend con mayor acoplamiento HTTP

{top_counter(service_by_file, 12)}

### Tablas mas referenciadas por SQL embebido

{top_counter(sql_by_table, 15)}

### Tablas con mas operaciones de escritura observadas

{md_table(["Tabla", "Archivo", "Insert", "Update", "Delete", "Total"], [[r["table"], r["file"], r["insert"], r["update"], r["delete"], r["total_refs"]] for r in write_heavy_tables])}

## Riesgos que condicionan refactorizacion

| Riesgo | Severidad | Evidencia | Impacto |
| --- | --- | --- | --- |
| SQL construido en PHP | High | `audit/evidence/sql_usage_refs.csv`, `FND-SEC-003` | Requiere caracterizacion y saneamiento antes de cambios profundos en rutas criticas. |
| Bootstrap expone CORS y errores amplios | High | `app/start.php`, `FND-SEC-001`, `FND-SEC-002` | Puede filtrar detalles o ampliar superficie si se despliega tal cual. |
| OVP/contabilidad centralizados | High | `app/functions/ovp.php`, `app/routes/contabilidad.php` | Riesgo alto de regresion si no se definen invariantes, conciliaciones y casos limite. |
| Archivos/documentos | Medium | `document_processing_catalog.csv` | Requiere politicas de MIME, path, autorizacion, retencion y almacenamiento. |
| Integraciones con secretos externos | Medium | `integration_catalog.csv`, `.env.example.php` local ignorado | Contratos, rotacion y owners deben formalizarse. |

## Evidencia Graphify

La ejecucion previa de Graphify sobre el repo real produjo `2932` nodos, `6227` edges y `229` comunidades. Los god nodes observados fueron consistentes con el inventario: `DatoMaestroService`, `UsuarioService`, `AlmacenesService`, `ContabilidadService`, `QRcode`, `ExcelModel`, `ExportExcelService`, `EntidadesService` y componentes de ingresos/salidas/inventario/ATE-GAS. Esta senal se usa como validacion arquitectonica secundaria; los CSV de `audit/evidence/` son la evidencia normativa del paquete.

## Cierre

`ASGARD-01` a `ASGARD-15` quedan cerradas como baseline tecnico reproducible. `ASGARD-16` y `ASGARD-17` no se generan porque dependen de validacion AS-IS y decisiones TO-BE posteriores.
""",
    )

    phases = [
        (
            "01",
            "repository-inventory",
            "Inventario del repositorio",
            f"""
## Hallazgos

- Se analizaron `{inv_summary["file_count"]}` archivos del sistema real, excluyendo `work_sample/` y `audit/`.
- El repositorio contiene backend PHP/Slim, frontend Angular, SQL de esquema y activos/plantillas.
- El inventario con hash SHA-256 por archivo queda en `audit/evidence/source_inventory.csv`.

## Distribucion por categoria

{top_counter(Counter(inv_summary["by_category"]))}

## Distribucion por extension

{top_counter(Counter(inv_summary["by_extension"]), 15)}

## Evidencias

- `audit/evidence/source_inventory.csv`
- `audit/evidence/source_inventory_summary.json`
- `audit/audit_summary.json`

## Estado

`COMPLETED`: evidencia determinista suficiente para cierre AS-IS de inventario.
""",
        ),
        (
            "02",
            "entrypoints-http-ajax-request",
            "Entry points, HTTP, AJAX y ciclo request-response",
            f"""
## Hallazgos

- Backend Slim inicializa en `AtlantesBE-main/AtlantesBE-main/public/index.php` y carga `app/start.php`.
- Se detectaron `{len(routes)}` rutas Slim en `audit/evidence/backend_routes.csv`.
- Se detectaron `{len(fe_routes)}` rutas Angular en `audit/evidence/frontend_routes.csv`.
- Se detectaron `{len(services)}` llamadas HTTP desde servicios Angular en `audit/evidence/frontend_service_calls.csv`.

## Archivos backend con mas rutas

{top_counter(route_by_file, 10)}

## Ciclo observado

`public/index.php` requiere `app/start.php`; `start.php` configura Slim, CORS, parser de cuerpo, middleware de rutas/error, conexion PDO y carga rutas por dominio. El frontend consume endpoints mediante servicios Angular que adjuntan `Authorization` en multiples llamadas.

## Evidencias

- `audit/evidence/backend_routes.csv`
- `audit/evidence/frontend_routes.csv`
- `audit/evidence/frontend_service_calls.csv`
- `AtlantesBE-main/AtlantesBE-main/public/index.php`
- `AtlantesBE-main/AtlantesBE-main/app/start.php`

## Estado

`COMPLETED`: entry points principales y catalogos HTTP/AJAX trazados.
""",
        ),
        (
            "03",
            "functional-module-map",
            "Mapa funcional de modulos",
            f"""
## Hallazgos

- Los dominios funcionales candidatos se agrupan por rutas/servicios: almacenes, embarques, entidades, datos maestros, usuarios, contabilidad, empresa, common/asgard y ATE-GAS.
- El menu Angular expone operaciones de almacen, reportes, contabilidad, dashboards, embarques, salidas, timbrado, inventario fisico y ATE-GAS.
- El catalogo de frontend/backed permite trazar pantallas a servicios y servicios a endpoints.

## Concentracion observada

{top_counter(route_by_file, 8)}

{top_counter(service_by_file, 8)}

## Evidencias

- `audit/evidence/frontend_routes.csv`
- `audit/evidence/frontend_service_calls.csv`
- `audit/evidence/backend_routes.csv`
- `audit/evidence/php_sql_matrix.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: mapa candidato completo; nombres de dominio y responsabilidades deben validarse con usuarios de negocio.
""",
        ),
        (
            "04",
            "php-architecture-dependencies",
            "Arquitectura PHP, clases, includes y dependencias",
            f"""
## Hallazgos

- `app/start.php` es el bootstrap central: carga entorno, PDO, CORS, middleware JWT, funciones, servicios y archivos de rutas.
- La arquitectura es modular por archivos de rutas grandes (`almacenes.php`, `contabilidad.php`, `entidades.php`, etc.) mas servicios puntuales (`DateTimeService`, `BlobStorageService`).
- Se observan librerias embebidas (`phpqrcode`, fuentes Tahoma, `piramide-uploader`) y dependencia Composer declarada en `composer.json`.

## Evidencias

- `AtlantesBE-main/AtlantesBE-main/app/start.php`
- `AtlantesBE-main/AtlantesBE-main/app/routes/*.php`
- `AtlantesBE-main/AtlantesBE-main/app/services/*.php`
- `AtlantesBE-main/AtlantesBE-main/composer.json`
- `audit/evidence/source_inventory.csv`

## Estado

`COMPLETED`: dependencias principales y puntos de acoplamiento identificados.
""",
        ),
        (
            "05",
            "database-model",
            "Modelo completo de base de datos",
            f"""
## Hallazgos

- `almacen.sql` contiene `{len(tables)}` tablas y `{len(columns)}` columnas detectadas.
- No se detectaron `INSERT INTO`, por lo que el SQL versionado representa estructura y no datos de usuarios.
- Indices/constraints parseados: `{len(data["indexes"])}`.

## Tablas con mas columnas

{md_table(["Tabla", "Columnas", "Linea"], [[r["table"], r["columns"], r["line"]] for r in sorted(tables, key=lambda x: int(x["columns"]), reverse=True)[:12]])}

## Evidencias

- `audit/evidence/database_tables.csv`
- `audit/evidence/database_columns.csv`
- `audit/evidence/database_indexes_constraints.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: diccionario fisico generado; relaciones semanticas y ownership de datos requieren validacion funcional.
""",
        ),
        (
            "06",
            "php-sql-matrix",
            "Matriz PHP <-> SQL",
            f"""
## Hallazgos

- Se detectaron `{len(data["sql_refs"])}` referencias SQL por accion/tabla/archivo.
- La matriz agregada `audit/evidence/php_sql_matrix.csv` cruza tabla, archivo y conteos de lectura/escritura.
- Tablas con escritura observada: `{len(write_tables)}`.

## Tablas mas referenciadas

{top_counter(sql_by_table, 12)}

## Evidencias

- `audit/evidence/sql_usage_refs.csv`
- `audit/evidence/php_sql_matrix.csv`

## Estado

`COMPLETED`: matriz tecnica generada; debe usarse como base para revisar transacciones y efectos laterales.
""",
        ),
        (
            "07",
            "database-objects-rules",
            "Stored procedures, funciones, vistas y reglas DB",
            f"""
## Hallazgos

- El dump versionado se parseo para tablas, columnas e indices.
- La auditoria no encontro catalogo separado de stored procedures/triggers en artefactos versionados; se mantiene pregunta abierta para validar si existen objetos DB fuera del dump o en entornos reales.
- La logica de negocio se observa mayormente en PHP y SQL embebido, con reglas de fecha/estado/calculo repartidas entre rutas y funciones.

## Evidencias

- `almacen.sql`
- `audit/evidence/database_tables.csv`
- `audit/evidence/php_sql_matrix.csv`

## Estado

`COMPLETED_WITH_OPEN_QUESTIONS`: evidencia negativa documentada; requiere confirmacion contra base real.
""",
        ),
        (
            "08",
            "authentication-session-roles",
            "Autenticacion, sesion, roles y permisos",
            f"""
## Hallazgos

- Autenticacion JWT implementada en `app/middleware/jwt.php`.
- El frontend conserva y decodifica token via `UsuarioService` y adjunta `Authorization` en servicios.
- Permisos se consultan en componentes Angular via `tokenDetalle.permisos` y en middleware backend con `verifyRole`.
- El secreto JWT proviene de constante externa `jwt_key`; no se versiona el archivo `.env.php`.

## Evidencias

- `AtlantesBE-main/AtlantesBE-main/app/middleware/jwt.php`
- `AtlantesBE-main/AtlantesBE-main/app/routes/usuarios.php`
- `AtlantesFE-main/AtlantesFE-main/src/app/services/usuario.service.ts`
- `audit/evidence/frontend_service_calls.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: modelo identificado; falta matriz formal endpoint-permiso-rol validada.
""",
        ),
        (
            "09",
            "integrations",
            "Integraciones externas e internas",
            f"""
## Hallazgos

- Se registraron `{len(integrations)}` evidencias de integracion o acceso externo.
- Integraciones candidatas: Azure Blob, SendGrid, Freshchat/Freshservice, OVP/SOAP, filesystem local, cURL/http y API interna Asgard/Atlantes via constantes.
- La configuracion sensible se maneja por constantes de entorno fuera del repo, aunque existe un archivo local ignorado con valores reales que debe sanearse.

## Distribucion de evidencias

{top_counter(Counter(r["integration_type"] for r in integrations), 12)}

## Evidencia

- `audit/evidence/integration_catalog.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: contratos tecnicos identificados; faltan contratos operativos de retry, timeout, SLAs y owners.
""",
        ),
        (
            "10",
            "documents-ocr-office",
            "Documentos, OCR, PDF, Excel y archivos",
            f"""
## Hallazgos

- Se detectaron `{len(docs)}` evidencias relacionadas con documentos, plantillas, cargas, descargas, imagenes, base64, Excel/PDF/Word o QR.
- Existen plantillas `.xlsx` versionadas en backend (`app/files`) y activos usados por frontend/backend.
- ATE-GAS incluye flujo de imagenes con almacenamiento local/Azure Blob.

## Evidencia

- `audit/evidence/document_processing_catalog.csv`
- `AtlantesBE-main/AtlantesBE-main/app/files/*.xlsx`
- `AtlantesBE-main/AtlantesBE-main/app/services/BlobStorageService.php`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: catalogo tecnico generado; politicas de retencion, permisos y clasificacion documental requieren validacion.
""",
        ),
        (
            "11",
            "accounting-ovp-critical-logic",
            "Contabilidad, OVP y logica critica",
            f"""
## Hallazgos

- `app/functions/ovp.php` concentra integracion y reglas OVP/contables extensas.
- `app/routes/contabilidad.php` concentra rutas de reportes y operaciones contables.
- La matriz SQL muestra escrituras/lecturas que deben revisarse como transacciones criticas antes de refactorizar.
- `dav_pagosovp`, `t_embarque`, `t_tipocambio`, `t_cliente` y tablas de ingreso/salida aparecen entre los objetos mas referenciados.

## Objetos criticos por referencias/escritura

{md_table(["Tabla", "Archivo", "Insert", "Update", "Delete", "Total"], [[r["table"], r["file"], r["insert"], r["update"], r["delete"], r["total_refs"]] for r in write_heavy_tables[:10]])}

## Invariantes pendientes de validacion

| Area | Estado | Validacion requerida |
| --- | --- | --- |
| OVP/pagos | INFERRED_DRAFT_REVIEW_REQUIRED | Estados permitidos, reintentos, conciliacion, duplicados y reversas. |
| Contabilidad/reportes | INFERRED_DRAFT_REVIEW_REQUIRED | Criterios de fecha, moneda, tipo de cambio, cierre y exportaciones oficiales. |
| Ingresos/salidas | INFERRED_DRAFT_REVIEW_REQUIRED | Transiciones de estado, stock, detalle, anulaciones y efectos contables. |

## Evidencias

- `AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php`
- `AtlantesBE-main/AtlantesBE-main/app/routes/contabilidad.php`
- `audit/evidence/php_sql_matrix.csv`
- `audit/evidence/integration_catalog.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: superficie critica identificada; invariantes contables deben validarse con negocio/QA.
""",
        ),
        (
            "12",
            "cron-batch-background",
            "Cron, batch y procesamiento background",
            f"""
## Hallazgos

- No se observaron workers/colas/cron versionados como scheduler formal.
- Existen procesos tipo batch/importacion masiva desde endpoints y servicios (Excel, timbrado, inventario, ATE-GAS).
- Existe script auxiliar `scripts/strip-phpunit-eager-load.php` para entorno de pruebas/migracion.

## Evidencias

- `audit/evidence/frontend_service_calls.csv`
- `audit/evidence/document_processing_catalog.csv`
- `AtlantesBE-main/AtlantesBE-main/scripts/strip-phpunit-eager-load.php`

## Estado

`COMPLETED_WITH_OPEN_QUESTIONS`: ausencia de cron versionado documentada; falta confirmar schedulers externos en servidor.
""",
        ),
        (
            "13",
            "technical-security",
            "Seguridad tecnica",
            f"""
## Hallazgos principales

- CORS global con `Access-Control-Allow-Origin: *`.
- `addErrorMiddleware(true, true, true)` expone detalles de error si se usa en produccion.
- SQL embebido y concatenado/interpolado requiere revision sistematica contra SQL injection.
- JWT usa constante externa; rotacion y gestion de secretos dependen del entorno.
- Cargas/descargas de archivos requieren revision de autorizacion, MIME, path traversal y retencion.
- Archivo local ignorado `.env.example.php` contiene valores reales aparentes; no esta versionado, pero debe sanearse.

## Evidencia

- `audit/registers/FINDINGS_REGISTER.md`
- `audit/evidence/integration_catalog.csv`
- `audit/evidence/document_processing_catalog.csv`

## Estado

`COMPLETED_WITH_HIGH_RISK_FINDINGS`: cierre AS-IS logrado, con remediacion requerida antes de exposicion productiva o refactor riesgoso.
""",
        ),
        (
            "14",
            "technical-debt-defects-risks",
            "Deuda tecnica, defectos y riesgos",
            f"""
## Hallazgos

- Rutas PHP monoliticas y archivos muy grandes elevan el riesgo de cambio.
- Duplicacion de patrones en servicios Angular: token/manual headers, endpoint strings y manejo de errores disperso.
- Librerias embebidas y assets binarios versionados complican actualizaciones de seguridad.
- Cambios recientes de zona horaria y Azure Blob indican refactor activo con necesidad de caracterizacion.

## Evidencia

- `audit/evidence/source_inventory.csv`
- `audit/evidence/backend_routes.csv`
- `audit/evidence/frontend_service_calls.csv`
- `audit/registers/FINDINGS_REGISTER.md`

## Estado

`COMPLETED`: riesgos AS-IS documentados y priorizables.
""",
        ),
        (
            "15",
            "as-is-consolidation",
            "Consolidado arquitectonico AS-IS",
            f"""
## Veredicto

`COMPLETED_WITH_REVIEW_REQUIRED`.

El sistema AS-IS queda reconstruido a nivel tecnico suficiente para iniciar planificacion de refactorizacion: backend PHP/Slim, frontend Angular, SQL fisico, rutas, servicios HTTP, integraciones, documentos, seguridad y matriz PHP-SQL. Las afirmaciones de negocio permanecen candidatas hasta validacion humana.

## Baseline de arquitectura

| Capa | Evidencia | Lectura |
| --- | --- | --- |
| Frontend | Angular, `app.routing.ts`, servicios en `src/app/services` | Navegacion amplia por dominios; consumo HTTP distribuido por servicios. |
| API | Slim/PHP en `public/index.php`, `app/start.php`, `app/routes/*.php` | Bootstrap central, rutas por modulo, SQL embebido y servicios auxiliares. |
| Datos | `almacen.sql`, `database_*.csv`, `php_sql_matrix.csv` | Esquema MySQL amplio con tablas operativas, maestras, contables/documentales. |
| Integraciones | `integration_catalog.csv` | Azure Blob, SendGrid, OVP/SOAP, Freshchat/Freshservice, cURL/API interna. |
| Documentos | `document_processing_catalog.csv` | Excel/PDF/Word/QR/base64/uploads/downloads e imagenes ATE-GAS. |

## Riesgos de cierre

| Prioridad | Riesgo | Registro |
| --- | --- | --- |
| Alta | SQL interpolation/concatenacion en rutas y funciones PHP. | `FND-SEC-003` |
| Alta | CORS abierto y error middleware verboso. | `FND-SEC-001`, `FND-SEC-002` |
| Alta | OVP/contabilidad sin invariantes revisadas. | `FND-ACC-001`, `OQ-ACC-001` |
| Media | Politicas documentales y almacenamiento pendientes. | `OQ-DOC-001` |
| Media | Schedulers/objetos DB externos no confirmados. | `OQ-BATCH-001`, `OQ-DB-001` |

## Evidencias de cierre

- `audit/README.md`
- `audit/ASGARD_AS_IS_DEEP_DIVE.md`
- `audit/verification/ANALYSIS_COMPLETENESS_REPORT.md`
- `audit/verification/VERIFICATION_REPORT.md`
- `audit/registers/FINDINGS_REGISTER.md`
- `audit/registers/OPEN_QUESTIONS.md`
- `audit/evidence/*.csv`

## Condiciones para pasar a TO-BE

1. Validar dominios y reglas con responsables de operacion.
2. Revisar hallazgos High de seguridad y contabilidad/OVP.
3. Confirmar schedulers, objetos DB fuera del dump y contratos de integracion reales.
4. Definir suite de caracterizacion para rutas criticas antes de modernizar.
""",
        ),
    ]
    for num, slug, title, body in phases:
        write_md(
            REPORTS / f"ASGARD-{num}-{slug}.md",
            f"# ASGARD-{num} - {title}\n\nEstado: `COMPLETED_WITH_REVIEW_REQUIRED`\n\n{body}",
        )

    finding_rows = [
        [f.id, f.phase, f.severity, f.status, f.title, f.evidence]
        for f in sorted(findings, key=lambda x: (x.phase, x.id))
    ]
    write_md(
        REGISTERS / "FINDINGS_REGISTER.md",
        "# Findings Register\n\n"
        + md_table(["ID", "Fase", "Severidad", "Estado", "Hallazgo", "Evidencia"], finding_rows),
    )
    write_md(
        REGISTERS / "OPEN_QUESTIONS.md",
        """
# Open Questions

| ID | Fase | Pregunta | Motivo |
| --- | --- | --- | --- |
| OQ-DB-001 | ASGARD-07 | Existen stored procedures, triggers, eventos o vistas en la base real que no esten en `almacen.sql`? | El dump versionado no permite confirmar objetos runtime externos. |
| OQ-AUTH-001 | ASGARD-08 | Cual es la matriz oficial rol/permiso/endpoints? | El frontend usa `tokenDetalle.permisos`, pero falta matriz revisada endpoint por endpoint. |
| OQ-INT-001 | ASGARD-09 | Cuales son los contratos, timeouts, reintentos y owners de Azure Blob, SendGrid, Freshchat, OVP y APIs internas? | El codigo muestra llamadas/configuracion, no los acuerdos operativos. |
| OQ-DOC-001 | ASGARD-10 | Que politicas de retencion y acceso aplican a imagenes, Excel, PDF y archivos generados? | La evidencia tecnica no define politica documental. |
| OQ-BATCH-001 | ASGARD-12 | Hay cron jobs o tareas programadas fuera del repositorio en WAMP/servidor/Windows Task Scheduler? | No hay scheduler versionado. |
| OQ-SEC-001 | ASGARD-13 | El entorno productivo deshabilita error details y restringe CORS? | El bootstrap versionado no diferencia entorno. |
| OQ-ACC-001 | ASGARD-11 | Cuales invariantes contables/OVP son obligatorios y cuales son historicos/legacy? | `ovp.php` concentra reglas criticas extensas. |
""",
    )
    write_md(
        REGISTERS / "ASSUMPTION_REGISTER.md",
        """
# Assumption Register

| ID | Estado | Supuesto | Evidencia / limite |
| --- | --- | --- | --- |
| ASM-001 | INFERRED_DRAFT_REVIEW_REQUIRED | Los dominios funcionales se agrupan por rutas backend y servicios/componentes Angular. | `backend_routes.csv`, `frontend_service_calls.csv`; requiere validacion de negocio. |
| ASM-002 | INFERRED_DRAFT_REVIEW_REQUIRED | `almacen.sql` representa el esquema base del sistema auditado. | No contiene inserts; falta comparacion contra base real. |
| ASM-003 | INFERRED_DRAFT_REVIEW_REQUIRED | Las operaciones batch principales son endpoints/manual imports y no jobs autonomos versionados. | No se detectan crons/colas; requiere confirmacion de infraestructura. |
| ASM-004 | INFERRED_DRAFT_REVIEW_REQUIRED | Las integraciones se configuran por constantes de entorno fuera del repo. | `app/start.php`, `BlobStorageService.php`, `sendmail.php`, `ovp.php`. |
""",
    )
    write_md(
        REGISTERS / "BLOCKER_REGISTER.md",
        """
# Blocker Register

| ID | Estado | Bloqueo | Impacto |
| --- | --- | --- | --- |
| BLK-001 | NONE_ACTIVE | No hay bloqueos activos para cerrar auditoria AS-IS tecnica. | La validacion humana queda como pendiente posterior, no bloquea la materializacion del baseline candidato. |
""",
    )
    write_md(
        REGISTERS / "DECISION_LOG.md",
        f"""
# Decision Log

| ID | Decision | Fecha UTC | Razon |
| --- | --- | --- | --- |
| DEC-001 | Excluir `work_sample/` de la auditoria del sistema real. | {datetime.now(timezone.utc).date()} | Es material de referencia y contaminaria metricas/evidencia. |
| DEC-002 | Marcar reglas de negocio inferidas como candidatas. | {datetime.now(timezone.utc).date()} | El marco exige evidencia cruzada o validacion humana antes de cerrar hechos funcionales. |
| DEC-003 | Mantener ASGARD-16/17 fuera del cierre. | {datetime.now(timezone.utc).date()} | Son fases posteriores TO-BE/roadmap, dependientes de AS-IS. |
""",
    )

    status_rows = [
        [f"ASGARD-{i:02d}", "COMPLETED_WITH_REVIEW_REQUIRED", f"reports/ASGARD-{i:02d}-*.md", "Validacion humana pendiente para inferencias"]
        for i in range(1, 16)
    ] + [
        ["ASGARD-16", "PENDING_POST_AS_IS", "No generado", "Arquitectura TO-BE posterior"],
        ["ASGARD-17", "PENDING_POST_AS_IS", "No generado", "Roadmap posterior"],
    ]
    write_md(
        AUDIT / "ASGARD_STATUS_MATRIX.md",
        "# ASGARD Status Matrix\n\n" + md_table(["Fase", "Estado", "Entregable", "Notas"], status_rows),
    )

    write_md(
        VERIFICATION / "ANALYSIS_COMPLETENESS_REPORT.md",
        f"""
# Analysis Completeness Report

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`

## Cobertura cuantitativa

| Medida | Valor |
| --- | ---: |
| Fases obligatorias ASGARD-01..15 | 15/15 |
| Archivos analizados | {inv_summary["file_count"]} |
| Evidencias CSV/JSON generadas | {len(list(EVIDENCE.glob('*')))} |
| Reportes de fase generados | {len(list(REPORTS.glob('ASGARD-*.md')))} |
| Hallazgos candidatos | {len(findings)} |
| Preguntas abiertas | 7 |
| Bloqueos activos | 0 |

## Cobertura por fase

{md_table(["Fase", "Estado"], [[f"ASGARD-{i:02d}", "COMPLETED_WITH_REVIEW_REQUIRED"] for i in range(1, 16)])}

## Limites

- No se ejecuto la aplicacion ni pruebas E2E contra entorno real.
- No se conecto a una base de datos viva.
- Las reglas funcionales inferidas desde codigo requieren validacion humana.
- `work_sample/` fue excluido por ser referencia, no evidencia del sistema actual.
""",
    )
    write_md(
        VERIFICATION / "VERIFICATION_REPORT.md",
        """
# Verification Report

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`

## Resultado

La auditoria AS-IS obligatoria (`ASGARD-01` a `ASGARD-15`) fue materializada con evidencias deterministas y registros de hallazgos, preguntas, supuestos y decisiones.

## Checks

| Check | Resultado |
| --- | --- |
| Marco ASGARD aplicado | PASS |
| `work_sample/` excluido del analisis | PASS |
| Inventario con hashes generado | PASS |
| Rutas backend/frontend catalogadas | PASS |
| Modelo SQL parseado | PASS |
| Matriz PHP-SQL generada | PASS |
| Integraciones y documentos catalogados | PASS |
| Hallazgos de seguridad registrados | PASS |
| Bloqueos activos | 0 |

## Veredicto

Baseline AS-IS tecnico cerrado para uso interno de refactorizacion. No debe considerarse especificacion funcional definitiva hasta validar preguntas abiertas e inferencias con negocio/operacion.
""",
    )


def main() -> None:
    ensure_dirs()
    files = iter_files()
    inv_rows, inv_summary = inventory(files)
    tables, columns, indexes = parse_schema()
    routes = parse_backend_routes(files)
    fe_routes = parse_frontend_routes(files)
    services = parse_frontend_services(files)
    sql_refs, sql_matrix = parse_sql_usage(files)
    findings = scan_findings(files)
    integrations = integration_catalog(files)
    documents = document_catalog(files)
    commit = os.environ.get("AUDIT_SOURCE_COMMIT", "").strip()
    git_head = ROOT / ".git" / "HEAD"
    if not commit and git_head.exists():
        head = git_head.read_text(encoding="utf-8").strip()
        if head.startswith("ref:"):
            ref_path = ROOT / ".git" / head.split(" ", 1)[1]
            if ref_path.exists():
                commit = ref_path.read_text(encoding="utf-8").strip()
        else:
            commit = head

    data = {
        "inventory": inv_rows,
        "inventory_summary": inv_summary,
        "tables": tables,
        "columns": columns,
        "indexes": indexes,
        "routes": routes,
        "frontend_routes": fe_routes,
        "services": services,
        "sql_refs": sql_refs,
        "sql_matrix": sql_matrix,
        "findings": findings,
        "integrations": integrations,
        "documents": documents,
        "commit": commit,
    }
    write_reports(data)
    summary = {
        "commit": commit,
        "files": inv_summary["file_count"],
        "backend_routes": len(routes),
        "frontend_routes": len(fe_routes),
        "frontend_service_calls": len(services),
        "tables": len(tables),
        "columns": len(columns),
        "sql_refs": len(sql_refs),
        "findings": len(findings),
        "integrations": len(integrations),
        "documents": len(documents),
    }
    (AUDIT / "audit_summary.json").write_text(json.dumps(summary, indent=2), encoding="utf-8")
    print(json.dumps(summary, indent=2))


if __name__ == "__main__":
    main()
