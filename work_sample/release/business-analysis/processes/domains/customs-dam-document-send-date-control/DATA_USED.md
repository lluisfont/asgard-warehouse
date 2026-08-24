# Customs DAM Document Send Date Control - Data Used

| Tabla | Uso | Campos |
| --- | --- | --- |
| `dav_casosprevios` | Solicitud previa relacionada con intercambio. | `idcasosprevios`, `idExchange`, `idembarquelogis` |
| `logis_embarques` | Resolucion alternativa de intercambio. | `id`, `idExchange` |
| `dav_casos` | Casos de la solicitud. | `idcasos`, `idcasosprevios` |
| `dav_facturacomercial` | Facturas comerciales marcadas. | `idfacturacomercial`, `idcasos`, `fechaenvioap`, `fechaenviodam` |

## Mutaciones observadas

- `UPDATE dav_facturacomercial SET fechaenviodam=CURRENT_DATE() WHERE idcasos IN (...)`.

## Salida secundaria

- Correo de alerta cuando falta `fechaenvioap`.
