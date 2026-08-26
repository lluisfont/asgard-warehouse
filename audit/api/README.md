# ASGARD API Auditor

ASGARD API Auditor is a centralized repository tool for inventorying integrations, discovering APIs and generating traceable audit artifacts from the Warehouse codebase.

The auditor source code lives outside this Warehouse repository:

- Repository: https://github.com/lluisfont/asgard-api-auditor
- Pinned commit: `9cc356633a0a8bbe2a54b5b6a6ffee98cd4ed4a4`
- Version: `0.5.3`

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

## Generate v0.5 Audit Artifacts

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

### Current v0.5.3 semantics

- OpenAPI contains only proven HTTP endpoints exposed by Warehouse.
- HTTP calls consumed by Warehouse stay in `findings.json` and `api-knowledge.md`; they are not represented as Warehouse provider paths.
- SOAP remains a separate integration surface.
- Source routes whose templates differ only by parameter names are represented through a canonical OpenAPI path while preserving every original source path and parameter name in ASGARD traceability extensions.
- If two equivalent source templates use the same HTTP method, generation fails closed instead of silently overwriting one route.
- Slim/PHP request shapes are reconstructed conservatively from demonstrated source usage, including JSON arrays, nested object arrays, local function propagation and multipart fields.
- Dynamic keys remain fail-closed unless their array-index role is demonstrated by local loop structure.
- Warehouse HTTP contract enrichment currently reaches 208/208 path parameters, 112/112 applicable requests, 338/338 responses and 332/332 applicable security findings with zero contract-enrichment unresolved findings.
- Raw JWT authentication remains represented as the `Authorization` header using an OpenAPI `apiKey` scheme; Bearer is not inferred without explicit evidence.
- `audit` remains `partial` because the OVP WSDL and later provider/consumer correlation gates are still pending.

## OpenAPI validation

The generated Warehouse OpenAPI is linted with the versioned policy at:

```text
audit/api/redocly.yaml
```

This policy keeps actual structural conflicts such as equivalent OpenAPI templates as errors, while it does not require inventing unknown server URLs or security schemes. Source-observed trailing slashes are preserved and reported only as guidance.

## Completion levels

`inventory_complete` concerns technical inventory coverage.

`discovery_complete` concerns API/integration discovery coverage.

`audit status=complete` is stricter and additionally requires all configured completion gates, including contract and cross-repository dependency coverage. v0.5.3 keeps the global audit deliberately `partial` until those remaining gates are satisfied.

## Results

Generated results are written under:

```text
audit/api/results/
```

Generated result files are intentionally ignored by Git. Only `audit/api/results/.gitkeep` is versioned so the directory exists.
