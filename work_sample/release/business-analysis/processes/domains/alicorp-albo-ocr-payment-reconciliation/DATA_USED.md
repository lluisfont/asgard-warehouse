# Alicorp Albo OCR Payment Reconciliation - Data Used

| Entidad / tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `logis_embarques` | Resolver contexto logistico por intercambio. | `id`, `idExchange` |
| `dav_casosprevios` | Resolver solicitud aduanera y relacion con embarque. | `idcasosprevios`, `idExchange`, `idembarquelogis` |
| `ages_solicitudes_asesoria_gestion` | Resolver solicitud AGES por intercambio. | `id`, `exchange_id` |
| `ages_asesoria_gestion_carpetas` | Obtener carpeta AGES asociada. | `id`, `solicitud_asesoria_gestion_id` |
| `dav_casos` | Caso aduanero, cierre de transito y DIM construido. | `idcasos`, `idcasosprevios`, `gestiondui`, `nodui`, `idaduana`, `alicorp_cierre_transito` |
| `dav_aduana` | Codigo usado para formar DIM ASGARD. | `idaduana`, `codigo` |
| `dav_pagosdetalle` | Pago pendiente reconciliado por OCR. | `idpagosdetalle`, `idcasos`, `ages_caso_id`, `idconcepto`, `monto`, `nro`, `fecha_numero`, `idusarocr`, `tipoocr`, `idexchangeocr`, `lecturaocr` |
| `dav_notasdebitodetalle` | Nota de debito sincronizada con factura OCR. | `idpagosdetalle`, `nro`, `fecha_numero` |
| `dav_facturacomercial` | Vinculacion con solicitud AGES cuando DIM coincide. | `idcasos`, `ages_id` |

## Mutaciones observadas

- `UPDATE dav_casos SET alicorp_cierre_transito=1`.
- `UPDATE dav_facturacomercial SET ages_id=...`.
- `UPDATE dav_pagosdetalle SET nro=..., fecha_numero=..., idusarocr=..., tipoocr=..., idexchangeocr=...`.
- `UPDATE dav_pagosdetalle SET lecturaocr=...`.
- `UPDATE dav_notasdebitodetalle SET nro=..., fecha_numero=...`.

## Campos OCR inferidos

- `total`
- `dim`
- `num_fact`
- `fecha`
