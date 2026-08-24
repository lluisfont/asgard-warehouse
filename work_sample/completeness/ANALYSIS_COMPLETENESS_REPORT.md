# Analysis Completeness Report

Static analysis completed the deterministic seed only. The project is not ready as an OpenSpec baseline until the pending phases are reconstructed and verified.

- Pipeline phases: 13
- Minimum expected artifacts: 279
- Generated deterministic artifacts: 24
- Required release artifacts materialized: 311
- Seeded release artifacts copied from deterministic output: 24
- Deterministic coverage: 8.6%
- Status: INCOMPLETE_BASELINE

## Coverage By Group

| Family | Area | Status | Generated / Minimum | Next phase |
| --- | --- | --- | ---: | --- |
| engineering-analysis | baseline | PARTIAL_DETERMINISTIC_SEED | 2/15 | 04-architecture-reconstruct |
| engineering-analysis | architecture | INFERRED_DRAFT_REVIEW_REQUIRED | 0/16 | 04-architecture-reconstruct |
| engineering-analysis | data | PARTIAL_DETERMINISTIC_SEED | 10/24 | 03-data-archaeology |
| engineering-analysis | interfaces | PARTIAL_DETERMINISTIC_SEED | 6/18 | 04-architecture-reconstruct |
| engineering-analysis | security | PARTIAL_DETERMINISTIC_SEED | 1/18 | 05-security-analyze |
| engineering-analysis | behavior | PARTIAL_DETERMINISTIC_SEED | 1/16 | 06-transaction-analyze |
| engineering-analysis | integrations | PARTIAL_DETERMINISTIC_SEED | 1/14 | 04-architecture-reconstruct |
| engineering-analysis | quality | INFERRED_DRAFT_REVIEW_REQUIRED | 0/15 | 10-characterization-tests-plan |
| engineering-analysis | tests | INFERRED_DRAFT_REVIEW_REQUIRED | 0/14 | 10-characterization-tests-plan |
| traceability | verification | PARTIAL_DETERMINISTIC_SEED | 1/15 | 11-verify-baseline |
| openspec | baseline | INFERRED_DRAFT_REVIEW_REQUIRED | 0/12 | 12-openspec-baseline |
| business-analysis | product-context | INFERRED_DRAFT_REVIEW_REQUIRED | 0/12 | 07-domain-discover |
| business-analysis | actors | INFERRED_DRAFT_REVIEW_REQUIRED | 0/8 | 08-business-reconstruct |
| business-analysis | processes | PARTIAL_DETERMINISTIC_SEED | 1/8 | 08-business-reconstruct |
| business-analysis | rules | INFERRED_DRAFT_REVIEW_REQUIRED | 0/12 | 09-business-rules-reconstruct |
| business-analysis | domain-model | PARTIAL_DETERMINISTIC_SEED | 1/9 | 07-domain-discover |
| business-analysis | use-cases | INFERRED_DRAFT_REVIEW_REQUIRED | 0/5 | 08-business-reconstruct |
| business-analysis | ui | INFERRED_DRAFT_REVIEW_REQUIRED | 0/9 | 08-business-reconstruct |
| business-analysis | reporting | INFERRED_DRAFT_REVIEW_REQUIRED | 0/8 | 09-business-rules-reconstruct |
| business-analysis | documents | INFERRED_DRAFT_REVIEW_REQUIRED | 0/8 | 08-business-reconstruct |
| business-analysis | exceptions | INFERRED_DRAFT_REVIEW_REQUIRED | 0/7 | 08-business-reconstruct |
| business-analysis | variants | INFERRED_DRAFT_REVIEW_REQUIRED | 0/7 | 09-business-rules-reconstruct |
| business-analysis | governance | INFERRED_DRAFT_REVIEW_REQUIRED | 0/9 | 11-verify-baseline |

## Required Pipeline

- 01-inventory
- 02-technical-evidence
- 03-data-archaeology
- 04-architecture-reconstruct
- 05-security-analyze
- 06-transaction-analyze
- 07-domain-discover
- 08-business-reconstruct
- 09-business-rules-reconstruct
- 10-characterization-tests-plan
- 11-verify-baseline
- 12-openspec-baseline
- 13-consolidate-release
