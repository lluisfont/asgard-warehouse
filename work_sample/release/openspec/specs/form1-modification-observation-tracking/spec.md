# form1-modification-observation-tracking Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Report visible Form1 modifications

ASGARD SHALL report Form1 modifications visible to the client with operational case context and correction detail.

#### Scenario: Modification has client permission

- GIVEN a Form1 with a subcontravention row marked `permisoclientes=1`
- WHEN the modification report is generated
- THEN ASGARD includes the subcontravention and its "dice/debe decir" correction text

### Requirement: Calculate Form1 elapsed days

ASGARD SHALL calculate elapsed days from observed Form1 EDP dates.

#### Scenario: Form1 has observation and ingress dates

- GIVEN a Form1 with observed state dates
- WHEN the report is generated
- THEN ASGARD calculates days before ingress and days in procedure using ingress/conclusion/current date

### Requirement: Expose call history

ASGARD SHALL expose call history associated with a Form1.

#### Scenario: Form1 has registered calls

- GIVEN calls exist for an `idform1`
- WHEN the user opens the history
- THEN ASGARD lists date, time, number, comment, state, user and attachment when present

### Requirement: Report unresolved missing documents

ASGARD SHALL report observed folders/cases with unresolved missing documents.

#### Scenario: New missing-document model

- GIVEN a case has `dav_faltadocumentos` rows with `responsabilidad=0` and `resuelto=0`
- WHEN the observed-folder report is generated
- THEN ASGARD includes the missing document name and operational case context
