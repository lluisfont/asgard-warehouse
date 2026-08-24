# Billing Document Reception Confirmation - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Elemento | Evidencia | Observacion |
| --- | --- | --- |
| Bandeja Enviadas | `index_archivos/contables/recepcionplanillas.php` | UI carga `gettablaenviadas` y permite seleccion/recepcion. |
| Bandeja Recepcionadas | `index_archivos/contables/recepcionplanillas.php` | UI carga `gettablarecibidos`. |
| Planilla/Factura pendiente | `index_archivos/contables/recepcionplanillas_ajax.php` | Condiciones sobre `dav_facturaplanilla.fechaenvioplanilla`, `idestadofactura`, `recepcionplanilla`. |
| Nota de Cobranza pendiente | `index_archivos/contables/recepcionplanillas_ajax.php` | Condiciones `estado_enviado=1` y `estado_recepcionado=0`. |
| Cite pendiente | `index_archivos/contables/recepcionplanillas_ajax.php` | Condiciones `fechasalida IS NOT NULL` y `fecharecepcion IS NULL`. |
| Recepcion individual | `recibirunico` en `recepcionplanillas_ajax.php` | Actualiza un documento por id/tipo. |
| Recepcion masiva | `recibirvarios` en `recepcionplanillas_ajax.php` | Itera lista JSON y actualiza por tipo. |
| Campos persistidos | `.data_base/asgard.sql` | Campos de recepcion en `dav_facturaplanilla`, `dav_notasdebito`, `dav_cite`. |
| Reporte relacionado | `planillaslegalizadas.php`, `planillaslegalizadasquery.php` | Reporte de planillas legalizadas entregadas. |

## Cobertura

- Flujo principal reconstruido: si.
- Reglas de estado reconstruidas: si.
- Datos fisicos principales: si.
- Open questions registradas: si.
- Validacion humana requerida: si.
