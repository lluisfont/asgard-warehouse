# Alicorp Transit Deadline Control - Data Used

## Entidades principales

| Entidad / Tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `dav_casos` | Caso operativo Alicorp y campos de control de transito. | `idcasos`, `carpeta`, `pedido`, `idcliente`, `idproveedor`, `idclientelineas`, `fechavalidaciondui`, `alicorp_vencimiento`, `alicorp_hora_recepcion`, `alicorp_hora_envio`, `alicorp_observacion`, `alicorp_cierre_transito`, `alicorp_cedeim`, `alicorp_reemplazo`, `fechapasesalida`, `anulado`, `anuladocliente` |
| `dav_facturacomercial` | Factura comercial y fecha base del filtro. | `idcasos`, `nofactura`, `fechafactura` |
| `dav_partidas` | Agregacion de producto, pesos neto/bruto y detalle de mercancia. | `idfacturacomercial`, `descripciongeneral`, `pesoneto`, `pesobruto` |
| `dav_clientefacturaanulada` | Facturas anuladas visibles en control Alicorp. | `idcliente`, `idclientelineas`, `facturacomercial`, `fecha`, `deleted_at` |
| `dav_casosprevios` | Relacion del caso con embarque logistico previo. | `idcasosprevios`, `idembarquelogis` |
| `logis_embarques` | Enlace a OCR Alicorp desde la operacion logistica. | `id`, `idocr_alicorp` |
| `ocr_alicorp` | Cabecera OCR usada como contexto de control Alicorp. | `idocr_alicorp`, `no_factura` |
| `ages_solicitudes_asesoria_gestion` / `dav_tramites` | Servicios adicionales y estado GE asociados al caso. | `casos_id`, `casos_extra_id`, `estado`, `idtipotramite` |
| Catalogos `dav_clientelineas`, `dav_aduana`, `dav_proveedor`, `dav_incoterms`, `dav_pais`, `dav_canal`, `dav_usuario` | Enriquecen la grilla de control. | Codigos, nombres y descripciones |

## Datos derivados

- `alicorp_vencimiento`: `fechavalidaciondui + 60 dias` cuando esta nulo.
- `diasvencimiento`: diferencia entre vencimiento y fecha actual.
- `error_vencimiento`: alerta si `diasvencimiento <= 5` y no hay pase de salida.
- `alicorp_cierre_transito`: etiqueta `PAGADO` / `SIN PAGAR`.

## Persistencia observada

- `UPDATE dav_casos SET alicorp_vencimiento = DATE_ADD(fechavalidaciondui, INTERVAL 60 DAY)`.
- `UPDATE dav_casos SET alicorp_cierre_transito = 1` desde lectores OCR/intercambio documental.
- Tablas temporales `tmp_productos` y `tmp_tramites` para consolidar la salida.
