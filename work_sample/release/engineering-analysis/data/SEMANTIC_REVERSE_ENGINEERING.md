# Semantic reverse engineering report

Estado: SEMANTIC_INFERENCE_REVIEW_REQUIRED
Idioma: Spanish

## Que se ha generado

- Descripcion semantica candidata para cada tabla.
- Descripcion semantica candidata para cada campo.
- Dominio candidato, lifecycle y sensibilidad.
- Salidas en Markdown, CSV, TSV, JSON y Excel.

## Metodo

La inferencia combina prefijo de tabla, terminos funcionales, patrones de campo, tipo fisico, nulabilidad, clave primaria/relaciones candidatas, uso observado y reglas de sensibilidad.

## Distribucion por dominio

| Dominio | Tablas |
|---|---:|
| tmp | 599 |
| dav | 484 |
| transversal | 184 |
| logis | 46 |
| tck | 42 |
| documentos | 20 |
| ages | 10 |
| con | 10 |
| ada | 5 |
| ads | 5 |
| cc | 5 |
| ges | 5 |
| prov | 5 |
| cn | 4 |
| adaprov | 3 |
| bot | 2 |
| vehiculos | 2 |
| dashboard | 1 |

## Distribucion por lifecycle

| Lifecycle | Tablas |
|---|---:|
| DOMAIN_ENTITY_OR_LEGACY | 756 |
| TEMPORARY_OR_STAGING | 587 |
| DOCUMENT_LIFECYCLE | 30 |
| CATALOG_STATE | 20 |
| REFERENCE_DATA | 19 |
| AUDIT_LOG | 11 |
| TRANSACTIONAL_LIFECYCLE | 9 |

## Distribucion por sensibilidad de campos

| Sensibilidad | Campos |
|---|---:|
| BUSINESS_DATA | 9439 |
| PERSONAL_OR_CONTACT_DATA | 2412 |
| FINANCIAL_OR_COMMERCIAL | 984 |
| DOCUMENT_OR_FILE_REFERENCE | 288 |
| SECRET_OR_CREDENTIAL | 26 |

## Limitacion

Esto es ingenieria inversa semantica candidata, no validacion final. Debe revisarse con negocio y con pruebas de caracterizacion antes de canonizar.
