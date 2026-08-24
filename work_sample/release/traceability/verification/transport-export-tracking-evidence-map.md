# Transport Export Tracking Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Trips are listed with state, route, driver, plate and last position. | `PROCESS_DEFINITION.md`, `PROCESS_FLOW.md`, `spec.md` | `listaviajes.php:1-170`, `ReporteViajesClass.php:29-99` |
| Trip detail/reporting uses events, incidents, localizations and last track. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `ReporteViajesClass.php:69-207` |
| Trip state labels map null/0/1/2/3 to assigned/rejected/accepted/interrupted/completed. | `STATE_MODEL.md`, `spec.md` | `listaviajes.php:70-104` |
| MIC/DEX state is derived from received/sent/concluded dates. | `PROCESS_FLOW.md`, `STATE_MODEL.md`, `spec.md` | `RecepcionFisicaMICs.php:18-107` |
| MIC/DEX state changes write history and update dates. | `BUSINESS_RULES.md`, `UC-001.md`, `spec.md` | `ActualizarMICs.php:12-91` |
| SCP upload reads Excel data and delegates persistence. | `PROCESS_FLOW.md`, `DATA_USED.md`, `UC-001.md` | `uploadDatosSCP.php:18-80` |
| Export reports combine invoice, case, item, cost, exchange-rate and cancelled-invoice data. | `PROCESS_DEFINITION.md`, `DATA_USED.md`, `spec.md` | `ExportacionesClass.php:64-360` |
