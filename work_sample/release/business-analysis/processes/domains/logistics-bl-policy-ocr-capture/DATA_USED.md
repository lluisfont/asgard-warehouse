# Logistics BL Policy OCR Capture - Data Used

| Tabla | Uso | Campos |
| --- | --- | --- |
| `logis_embarques` | Resolver embarque por intercambio. | `id`, `idExchange` |
| `dav_casosprevios` | Resolver embarque asociado a solicitud. | `idExchange`, `idembarquelogis` |
| `logis_lecturablpoliza` | Persistir lecturas OCR BL/poliza. | `idembarque`, `ubicacionbl`, `numerobl`, `emisorbl`, `fechabl`, `cantidadbl`, `ubicacionps`, `numerops`, `aplicacionps`, `fechaps`, `cantidadps`, `valorps` |

## Mutaciones observadas

- `INSERT INTO logis_lecturablpoliza` para BL.
- `UPDATE logis_lecturablpoliza SET ...` para BL.
- `INSERT INTO logis_lecturablpoliza` para poliza.
- `UPDATE logis_lecturablpoliza SET ...` para poliza.
