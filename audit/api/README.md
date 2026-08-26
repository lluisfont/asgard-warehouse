# ASGARD API Auditor

ASGARD API Auditor is a centralized repository tool for inventorying integrations, discovering APIs, reconstructing HTTP contracts and generating traceable audit artifacts from the Warehouse codebase.

The auditor source code lives outside this Warehouse repository:

- Repository: https://github.com/lluisfont/asgard-api-auditor
- Pinned commit: `b58638665281d4e5bf720075e963828f62e33651`
- Version: `0.6.0`

This repository installs the auditor as a versioned tool dependency. Its source code is not copied into Warehouse.

## Install

From the repository root:

```bash
python -m venv .venv-api-audit
source .venv-api-audit/bin/activate
pip install -r audit/api/requirements.txt
```

On Windows PowerShell:

```powershell
python -m venv .venv-api-audit
.\.venv-api-audit\Scripts\Activate.ps1
pip install -r audit/api/requirements.txt
```

## Run Technical Inventory

```bash
asgard-api-auditor inventory . \
  --repository-id asgard-warehouse \
  --exclude-path audit \
  --exclude-path work_sample \
  --output audit/api/results/technical-inventory.json
```

## Run API Discovery

```bash
asgard-api-auditor discover . \
  --repository-id asgard-warehouse \
  --exclude-path audit \
  --exclude-path work_sample \
  --output audit/api/results/api-discovery.json
```

The current Warehouse SOAP integration references `servicioovp`, but its WSDL is not versioned yet. Once the approved snapshot exists at `contracts/soap/ovp.wsdl`, use:

```bash
asgard-api-auditor discover . \
  --repository-id asgard-warehouse \
  --exclude-path audit \
  --exclude-path work_sample \
  --soap-wsdl servicioovp=contracts/soap/ovp.wsdl \
  --output audit/api/results/api-discovery.json
```

The WSDL snapshot must be inside this repository and tracked by Git. The auditor does not download SOAP contracts from the network.

## Generate Audit Artifacts

```bash
asgard-api-auditor audit . \
  --repository-id asgard-warehouse \
  --exclude-path audit \
  --exclude-path work_sample \
  --output audit/api/results/audit
```

This generates:

```text
audit/api/results/audit/
├── openapi.yaml
├── api-knowledge.md
├── findings.json
└── audit-report.md
```

When the OVP WSDL is available, add:

```bash
--soap-wsdl servicioovp=contracts/soap/ovp.wsdl
```

## Correlate Providers and Consumers

v0.6.0 adds deterministic provider-consumer correlation over generated `findings.json` artifacts. Correlation is intentionally separate from repository scanning so multiple audited ASGARD repositories can be combined reproducibly.

Example:

```bash
asgard-api-auditor correlate \
  --findings warehouse/findings.json \
  --findings mobile/findings.json \
  --output correlation-results/
```

It generates:

```text
correlation-results/
├── correlations.json
└── api-relations.md
```

Correlation uses exact HTTP method plus normalized path shape. Unique structural matches are candidates unless provider identity is independently proven. It does not use fuzzy matching, host guessing, repository-name heuristics or mandatory manual mappings.

### Current v0.6.0 semantics

- OpenAPI contains only proven HTTP endpoints exposed by Warehouse.
- HTTP calls consumed by Warehouse stay in `findings.json` and `api-knowledge.md`; they are not represented as Warehouse provider paths.
- SOAP remains a separate integration surface.
- Source routes whose templates differ only by parameter names are represented through a canonical OpenAPI path while preserving every original source path and parameter name in ASGARD traceability extensions.
- If two equivalent source templates use the same HTTP method, generation fails closed instead of silently overwriting one route.
- Slim/PHP request shapes are reconstructed conservatively from demonstrated source usage, including JSON arrays, nested object arrays, local function propagation and multipart fields.
- Dynamic keys remain fail-closed unless their array-index role is demonstrated by local loop structure.
- Consumer detectors ignore commented HTTP calls while preserving original source offsets and line-number evidence.
- Warehouse currently exposes 338 HTTP endpoints and contains 336 active consumed HTTP calls after removing comment-only false positives.
- Warehouse HTTP contract enrichment reaches 208/208 path parameters, 112/112 applicable requests, 338/338 responses and 332/332 applicable security findings with zero contract-enrichment unresolved findings.
- Raw JWT authentication remains represented as the `Authorization` header using an OpenAPI `apiKey` scheme; Bearer is not inferred without explicit evidence.
- Correlation artifacts validate fail-closed against packaged schemas, including when the auditor is installed as a wheel in another repository.
- `audit` remains `partial`: the OVP WSDL is still pending and global cross-repository dependency coverage is not complete merely because the correlation engine exists.

## OpenAPI validation

The generated Warehouse OpenAPI is linted with the versioned policy at:

```text
audit/api/redocly.yaml
```

This policy keeps actual structural conflicts such as equivalent OpenAPI templates as errors, while it does not require inventing unknown server URLs or security schemes. Source-observed trailing slashes are preserved and reported only as guidance.

## Completion levels

`inventory_complete` concerns technical inventory coverage.

`discovery_complete` concerns API/integration discovery coverage.

`audit status=complete` is stricter and additionally requires all configured completion gates, including contract and cross-repository dependency coverage. v0.6.0 keeps the global audit deliberately `partial` until those remaining gates are satisfied.

## Results

Generated results are written under:

```text
audit/api/results/
```

Generated result files are intentionally ignored by Git. Only `audit/api/results/.gitkeep` is versioned so the directory exists.
