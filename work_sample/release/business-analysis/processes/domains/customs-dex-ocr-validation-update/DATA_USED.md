# Customs DEX OCR Validation Update - Data Used

| Entidad / tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `logis_embarques` | Resolver embarque desde intercambio documental. | `id`, `idExchange` |
| `dav_casosprevios` | Resolver solicitud previa y agrupar casos. | `idcasosprevios`, `idExchange`, `idembarquelogis` |
| `dav_casos` | Caso aduanero consultado y actualizado. | `idcasos`, `idcasosprevios`, `carpeta`, `gestiondui`, `nodui`, `nosidunea`, `fechavalidaciondui`, `idaduana`, `idproveedor`, `idlugarembarque`, `idaduanasalida`, `idpaisdestino`, `idincoterm`, `idlugarentrega` |
| `dav_facturacomercial` | Datos comerciales para contraste OCR. | `idcasos`, `valortotal`, `valorfob`, `pesobruto`, `pesoneto` |
| `dav_partidas` | Datos de item/partida para contraste OCR. | `idcasos`, `subpartida`, `descripcion_general`, `tipobulto` |
| Catalogos aduaneros | Descripciones y codigos de comparacion. | Aduana, proveedor, lugar, pais, incoterm |

## Mutaciones observadas

- `UPDATE dav_casos SET gestiondui=..., nodui=... WHERE idcasosprevios IN (...)`.
- `UPDATE dav_casos SET nosidunea=... WHERE idcasosprevios IN (...)`.
- `UPDATE dav_casos SET fechavalidaciondui=... WHERE idcasosprevios IN (...)`.

## Campos OCR inferidos

- `carpeta`
- `declaracion`
- `sidunea`
- `fecha_aceptacion`
- Campos DEX de aduana, proveedor, pais/lugar, incoterm, valor, pesos, subpartida, descripcion y embalaje.
