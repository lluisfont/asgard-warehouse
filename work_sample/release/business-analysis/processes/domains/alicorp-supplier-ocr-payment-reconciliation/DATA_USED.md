# Alicorp Supplier OCR Payment Reconciliation - Data Used

| Tabla | Uso | Campos |
| --- | --- | --- |
| `logis_embarques` | Contexto por intercambio. | `id`, `idExchange` |
| `dav_casosprevios` | Contexto aduanero por intercambio o embarque. | `idcasosprevios`, `idExchange`, `idembarquelogis` |
| `ages_solicitudes_asesoria_gestion` / `ages_asesoria_gestion_carpetas` | Contexto AGES. | `exchange_id`, `id` |
| `dav_pagosdetalle` | Pago pendiente reconciliado. | `idpagosdetalle`, `idcasos`, `ages_caso_id`, `idconcepto`, `monto`, `nro`, `fecha_numero`, `idusarocr`, `tipoocr`, `idexchangeocr`, `lecturaocr` |
| `dav_notasdebitodetalle` | Nota sincronizada. | `idpagosdetalle`, `nro`, `fecha_numero` |
| `dav_casos` / `dav_aduana` | Validacion DIM y cierre transito. | `gestiondui`, `nodui`, `codigo`, `alicorp_cierre_transito` |
| `dav_facturacomercial` | Vinculacion AGES en FDAB. | `ages_id`, `idcasos` |

## Mutaciones observadas

- `UPDATE dav_pagosdetalle`.
- `UPDATE dav_notasdebitodetalle`.
- `UPDATE dav_casos SET alicorp_cierre_transito=1`.
- `UPDATE dav_facturacomercial SET ages_id=...` en FDAB.
