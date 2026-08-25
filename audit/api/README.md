# ASGARD API Auditor

ASGARD API Auditor is a centralized repository tool for inventorying integrations, discovering APIs and generating traceable audit artifacts from the Warehouse codebase.

The auditor source code lives outside this Warehouse repository:

- Repository: https://github.com/lluisfont/asgard-api-auditor
- Pinned commit: `c378669201101c1da4ee50cb661d0920fd58c317`
- Version: `0.5.0`

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

### Current v0.5 semantics

- OpenAPI contains only proven HTTP endpoints exposed by Warehouse.
- HTTP calls consumed by Warehouse stay in `findings.json` and `api-knowledge.md`; they are not represented as Warehouse provider paths.
- SOAP remains a separate integration surface.
- Request/response/authentication/authorization reconstruction is not complete yet.
- Therefore `audit` currently returns a partial status through `contract-enrichment-v0.5.0`; this is intentional and must not be treated as a successful complete behavioral contract.

## Completion levels

`inventory_complete` concerns technical inventory coverage.

`discovery_complete` concerns API/integration discovery coverage.

`audit status=complete` is stricter and additionally requires a reconstructed and validated behavioral contract. v0.5.0 generates the artifacts but deliberately remains `partial` until contract enrichment is implemented.

## Results

Generated results are written under:

```text
audit/api/results/
```

Generated result files are intentionally ignored by Git. Only `audit/api/results/.gitkeep` is versioned so the directory exists.
