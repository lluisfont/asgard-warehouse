# Logistics Quotation Costing - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Tabla / Fuente | Uso candidato | Evidencia |
| --- | --- | --- |
| `logis_embarques` | Cabecera de cotizacion/embarque; contiene flag `cotizacion`, incoterm, modalidad, proveedor, carga y vigencia. | `embarquesController.php:94-178`, `.data_base/asgard.sql:12175-12210` |
| `logis_embarquesoperador` | Operadores candidatos y estado de solicitud/costos/aceptacion/confirmacion. | `CotizacionClass.php:605-627`, `.data_base/asgard.sql:12344-12370` |
| `logis_costosconcepto` | Conceptos de costos por incoterm/tipo embarque y grupo. | `CostosClass.php:49-61`, `CostosClass.php:412-427` |
| `logis_costosdetalles` | Detalle de costos cargado por operador en cotizacion. | `CostosClass.php:467-480`, `.data_base/asgard.sql:12014-12024` |
| `logis_costos` / `logis_costos_detalle` | Costos internos o costos por categoria de embarque. | `CostosClass.php:572-705`, `.data_base/asgard.sql:11948-11983` |
| `logis_costosgrupo` | Agrupacion de conceptos en evaluacion. | `.data_base/asgard.sql:12027-12036` |
| `dav_puerto` | Puerto/origen/destino usado en tramos de cotizacion y embarque. | `CotizacionClass.php`, `CostosClass.php`, `EmbarqueClass.php` |
| `logis_aeropuertos`, `logis_contenedor` | Catalogos logisticos auxiliares para cotizacion/tramos. | `CotizacionClass.php` |
| `tck_lugaresentregacarga` | Lugar de entrega/carga usado en tramos. | `CotizacionClass.php`, `CostosClass.php` |
| `logis_tipocarga` | Tipo de carga y carguio para tramos/costos. | `CotizacionClass.php`, `CostosClass.php` |
| `logis_tipomercancia`, `logis_tipomercanciaclase`, `logis_tipomercanciaun` | Tipo, clase y unidad de mercancia usada en cotizacion/embarque. | `CotizacionClass.php`, `CostosClass.php`, `EmbarqueClass.php` |
| `dav_gestlogistica`, `dav_gestion_aduanera1`, `dav_gestion_aduanera2`, `dav_gestion_aduanera3`, `dav_gestaduanera` | Parametros de gestion/logistica y tiempos/costos aduaneros consultados para costos y tramos. | `CotizacionClass.php`, `CostosClass.php` |
| `dav_clientealmacen`, `dav_region` | Almacen/region de tramos logisticos. | `CostosClass.php`, `CotizacionClass.php`, `EmbarqueClass.php` |
| Archivos `embarquedocs/{cliente}/{embarque}/formcostos` | Adjuntos del operador para costos. | `CostosClass.php:514-540` |

## Datos Criticos

- Token de operador.
- Costos, fechas ETD/ETA, TT, documentos de costos.
- Flags `enviocot`, `revisioncot`, `llenadocot`, `aceptado`, `confirmado`.
