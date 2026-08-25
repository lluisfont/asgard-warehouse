# ASGARD API Auditor

ASGARD API Auditor is a centralized repository tool for generating a technical inventory and discovering APIs/endpoints exposed and consumed by a codebase.

The auditor source code lives outside this Warehouse repository:

- Repository: https://github.com/lluisfont/asgard-api-auditor
- Pinned commit: `69380ee725363f7009c270da29580ad5fd4819ce`

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

From the repository root:

```bash
asgard-api-auditor inventory . \
  --repository-id asgard-warehouse \
  --output audit/api/results/technical-inventory.json
```

## Run API Discovery

From the repository root:

```bash
asgard-api-auditor discover . \
  --repository-id asgard-warehouse \
  --output audit/api/results/api-discovery.json
```

The current Warehouse SOAP integration references `servicioovp`, but its WSDL is not versioned yet. Once the approved snapshot exists at `contracts/soap/ovp.wsdl`, v0.4.5 supports reproducible contract validation with:

```bash
asgard-api-auditor discover . \
  --repository-id asgard-warehouse \
  --soap-wsdl servicioovp=contracts/soap/ovp.wsdl \
  --output audit/api/results/api-discovery.json
```

The WSDL snapshot must be inside this repository and tracked by Git. The auditor does not download SOAP contracts from the network.

## `discovery_complete`

The discovery output may include `discovery_complete`.

- `true`: the auditor completed discovery with coverage it considers satisfactory.
- `false`: the auditor generated a JSON result, but found unsupported patterns or could not guarantee complete coverage.

A `false` value should be treated as a signal to inspect the generated JSON and the auditor findings before relying on the discovery as complete.

## Results

Generated results are written under:

```text
audit/api/results/
```

Result JSON files are intentionally ignored by Git. Only `audit/api/results/.gitkeep` is versioned so the directory exists.
