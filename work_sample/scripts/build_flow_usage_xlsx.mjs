import fs from "node:fs/promises";
import path from "node:path";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const root = "C:/Repos/clientes";
const dir = path.join(root, ".brownfield/work/release/business-analysis/semantic-flow-analysis");
const outPath = path.join(dir, "FLOW_USAGE_SEMANTIC_ANALYSIS.xlsx");

function parseCsv(text) {
  const rows = [];
  let row = [];
  let cell = "";
  let quoted = false;
  for (let i = 0; i < text.length; i++) {
    const ch = text[i];
    const next = text[i + 1];
    if (quoted) {
      if (ch === '"' && next === '"') {
        cell += '"';
        i++;
      } else if (ch === '"') {
        quoted = false;
      } else {
        cell += ch;
      }
    } else if (ch === '"') {
      quoted = true;
    } else if (ch === ",") {
      row.push(cell);
      cell = "";
    } else if (ch === "\n") {
      row.push(cell);
      rows.push(row);
      row = [];
      cell = "";
    } else if (ch !== "\r") {
      cell += ch;
    }
  }
  if (cell.length || row.length) {
    row.push(cell);
    rows.push(row);
  }
  const headers = rows.shift() ?? [];
  return rows.filter((r) => r.some((v) => v !== "")).map((values) =>
    Object.fromEntries(headers.map((header, index) => [header, values[index] ?? ""]))
  );
}

function matrix(rows, cols) {
  return [cols, ...rows.map((row) => cols.map((col) => row[col] ?? ""))];
}

function counts(rows, col) {
  const map = new Map();
  for (const row of rows) {
    const key = row[col] || "UNKNOWN";
    map.set(key, (map.get(key) ?? 0) + 1);
  }
  return Array.from(map.entries()).sort((a, b) => b[1] - a[1]);
}

const tableRows = parseCsv(await fs.readFile(path.join(dir, "FLOW_TABLE_USAGE_MATRIX.csv"), "utf8"));
const fieldRows = parseCsv(await fs.readFile(path.join(dir, "FLOW_FIELD_USAGE_MATRIX.csv"), "utf8"));
const domainRows = parseCsv(await fs.readFile(path.join(dir, "DOMAIN_FLOW_SEMANTIC_SUMMARY.csv"), "utf8"));

const workbook = Workbook.create();
const summary = workbook.worksheets.add("Resumen");
const domains = workbook.worksheets.add("Dominios");
const tables = workbook.worksheets.add("Uso tablas");
const fields = workbook.worksheets.add("Uso campos");
const method = workbook.worksheets.add("Metodo");

summary.getRange("A1:F1").merge();
summary.getRange("A1").values = [["Ingenieria inversa semantica por flujos"]];
summary.getRange("A1").format = { fill: "#1F4E79", font: { bold: true, color: "#FFFFFF", size: 14 } };
summary.getRange("A3:B8").values = [
  ["Estado", "FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED"],
  ["Dominios analizados", domainRows.length],
  ["Cruces dominio-tabla", tableRows.length],
  ["Cruces dominio-tabla-campo", fieldRows.length],
  ["Objetivo", "Cruzar flujos, usos, reglas y evidencias; no describir identificadores aislados"],
  ["Validacion", "Pendiente de negocio y pruebas de caracterizacion"],
];
summary.getRange("D3:E3").values = [["Uso de tabla", "Cruces"]];
summary.getRangeByIndexes(3, 3, counts(tableRows, "flow_usage").length, 2).values = counts(tableRows, "flow_usage");

const domainCols = ["domain", "tables_crossed", "fields_crossed", "mutation_tables", "rule_count", "evidence_refs", "risk_summary"];
const tableCols = ["domain", "table", "candidate_domain", "flow_usage", "semantic_role_in_flow", "fields_in_flow", "rule_hints", "risk_hints", "mutations_observed", "evidence_refs", "validation_status"];
const fieldCols = ["domain", "table", "field", "field_type", "sensitivity", "flow_role", "semantic_description", "contexts", "evidence_refs", "validation_status"];

domains.getRangeByIndexes(0, 0, domainRows.length + 1, domainCols.length).values = matrix(domainRows, domainCols);
tables.getRangeByIndexes(0, 0, tableRows.length + 1, tableCols.length).values = matrix(tableRows, tableCols);
fields.getRangeByIndexes(0, 0, fieldRows.length + 1, fieldCols.length).values = matrix(fieldRows, fieldCols);
method.getRange("A1:B8").values = [
  ["Paso", "Descripcion"],
  ["1", "Leer PROCESS_DEFINITION, PROCESS_FLOW, BUSINESS_RULES, DATA_USED, STATE_MODEL y evidence-map por dominio."],
  ["2", "Detectar tablas y campos mencionados en contexto de flujo, reglas y datos."],
  ["3", "Cruzar con diccionario semantico, sensibilidad y conteos de lectura/escritura."],
  ["4", "Clasificar uso: lectura, catalogo, temporal, reporting, create, update, delete."],
  ["5", "Inferir reglas/riesgos: permisos, estados, documentos, importes, notificaciones, atomicidad."],
  ["6", "Mantener cada afirmacion como candidata pendiente de validacion."],
  ["7", "Generar matrices auditables y reportes por dominio."],
];

for (const sheet of [summary, domains, tables, fields, method]) {
  sheet.showGridLines = false;
  const used = sheet.getUsedRange();
  used.format.wrapText = true;
  used.format.borders = { preset: "all", style: "thin", color: "#D9E2F3" };
  sheet.getRange("A1:K1").format = { fill: "#1F4E79", font: { bold: true, color: "#FFFFFF" } };
  used.format.autofitColumns();
  used.format.autofitRows();
}
domains.freezePanes.freezeRows(1);
tables.freezePanes.freezeRows(1);
fields.freezePanes.freezeRows(1);

const xlsx = await SpreadsheetFile.exportXlsx(workbook);
await xlsx.save(outPath);
console.log(JSON.stringify({ output: outPath, domains: domainRows.length, table_crosses: tableRows.length, field_crosses: fieldRows.length }));
