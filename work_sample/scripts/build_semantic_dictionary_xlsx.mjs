import fs from "node:fs/promises";
import path from "node:path";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const root = "C:/Repos/clientes";
const dataDir = path.join(root, ".brownfield/work/release/engineering-analysis/data");
const outPath = path.join(dataDir, "SEMANTIC_DATA_DICTIONARY.xlsx");

function parseTsv(text) {
  const rows = text.replace(/^\uFEFF/, "").trimEnd().split(/\r?\n/).map((line) => line.split("\t"));
  const headers = rows.shift();
  return rows.map((values) => Object.fromEntries(headers.map((header, index) => [header, values[index] ?? ""])));
}

function matrix(rows, fields) {
  return [fields, ...rows.map((row) => fields.map((field) => row[field] ?? ""))];
}

function counts(rows, field) {
  const map = new Map();
  for (const row of rows) {
    const key = row[field] || "UNKNOWN";
    map.set(key, (map.get(key) || 0) + 1);
  }
  return Array.from(map.entries()).sort((a, b) => b[1] - a[1]);
}

const tables = parseTsv(await fs.readFile(path.join(dataDir, "semantic_tables.tsv"), "utf8"));
const fields = parseTsv(await fs.readFile(path.join(dataDir, "semantic_fields.tsv"), "utf8"));

const workbook = Workbook.create();
const summary = workbook.worksheets.add("Resumen");
const tableSheet = workbook.worksheets.add("Tablas semanticas");
const fieldSheet = workbook.worksheets.add("Campos semanticos");
const method = workbook.worksheets.add("Metodo");

summary.getRange("A1:E1").values = [["Diccionario semantico AS-IS candidato", "", "", "", ""]];
summary.getRange("A1:E1").merge();
summary.getRange("A1").format = { fill: "#1F4E79", font: { bold: true, color: "#FFFFFF", size: 14 } };
summary.getRange("A3:B9").values = [
  ["Estado", "SEMANTIC_INFERENCE_REVIEW_REQUIRED"],
  ["Idioma", "Spanish"],
  ["Tablas", tables.length],
  ["Campos", fields.length],
  ["Naturaleza", "Ingenieria inversa semantica candidata, pendiente de validacion humana"],
  ["Fuente", "DDL, nombres, prefijos, tipos, claves, sensibilidad y evidencias read/write"],
  ["Uso recomendado", "Revisar con negocio antes de canonizar o refactorizar"],
];
summary.getRange("A11:B11").values = [["Dominio candidato", "Tablas"]];
summary.getRangeByIndexes(11, 0, counts(tables, "owner_domain").length, 2).values = counts(tables, "owner_domain");
summary.getRange("D11:E11").values = [["Sensibilidad", "Campos"]];
summary.getRangeByIndexes(11, 3, counts(fields, "sensitivity").length, 2).values = counts(fields, "sensitivity");

const tableColumns = ["table", "owner_domain", "lifecycle", "pii", "business_description", "read_references", "write_references", "usage_status", "evidence_id"];
const fieldColumns = ["table", "column", "data_type", "nullable", "key_role", "sensitivity", "business_description", "business_rule", "source", "line", "evidence_id"];
tableSheet.getRangeByIndexes(0, 0, tables.length + 1, tableColumns.length).values = matrix(tables, tableColumns);
fieldSheet.getRangeByIndexes(0, 0, fields.length + 1, fieldColumns.length).values = matrix(fields, fieldColumns);

method.getRange("A1:B9").values = [
  ["Regla", "Descripcion"],
  ["Prefijo de tabla", "Clasifica familias como dav, logis, tck, ada, ads, prov, tmp o vista."],
  ["Terminos de negocio", "Descompone nombres en conceptos como cliente, documento, estado, factura, puerto, mercancia."],
  ["Rol de campo", "Detecta identificadores, estados, fechas, importes, contactos, archivos, tokens y auditoria."],
  ["Tipo fisico", "Incluye int, varchar, decimal, date/datetime y nullability como soporte de interpretacion."],
  ["Uso observado", "Diferencia tablas leidas, escritas, transaccionales, de catalogo o sin uso PHP directo."],
  ["Sensibilidad", "Marca datos personales, financieros, documentos, credenciales o datos de negocio."],
  ["Validacion", "Todas las inferencias quedan pendientes de confirmacion funcional."],
  ["No inventar", "Cuando el nombre no permite semantica fiable, se marca como legacy o pendiente de confirmar."],
];

for (const sheet of [summary, tableSheet, fieldSheet, method]) {
  sheet.showGridLines = false;
  const used = sheet.getUsedRange();
  used.format.wrapText = true;
  used.format.borders = { preset: "all", style: "thin", color: "#D9E2F3" };
  sheet.getRange("A1:K1").format = { fill: "#1F4E79", font: { bold: true, color: "#FFFFFF" } };
  used.format.autofitColumns();
  used.format.autofitRows();
}

tableSheet.freezePanes.freezeRows(1);
fieldSheet.freezePanes.freezeRows(1);
method.freezePanes.freezeRows(1);

await fs.mkdir(dataDir, { recursive: true });
const xlsx = await SpreadsheetFile.exportXlsx(workbook);
await xlsx.save(outPath);

const inspect = await workbook.inspect({
  kind: "sheet,table",
  maxChars: 4000,
  tableMaxRows: 4,
  tableMaxCols: 5,
});
console.log(inspect.ndjson);
console.log(JSON.stringify({ output: outPath, tables: tables.length, fields: fields.length }));
