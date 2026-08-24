# Document Service Plan OCR Capture - Data Used

| Tabla | Uso | Campos |
| --- | --- | --- |
| `dav_planillasdp` | Lecturas OCR vigentes/historicas por documento. | `exchange_id`, `document_id`, `ubicacion`, `archivo`, `numero`, `numerobl`, `montocotizado`, `fechaimpresion`, `fecharegistro`, `fechavalidacion`, `deleted_at` |

## Mutaciones observadas

- `UPDATE dav_planillasdp SET deleted_at=CURRENT_TIMESTAMP() WHERE exchange_id=... AND document_id=...`.
- `INSERT INTO dav_planillasdp (...)`.
