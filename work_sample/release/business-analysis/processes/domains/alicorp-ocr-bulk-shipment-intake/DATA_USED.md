# Alicorp OCR Bulk Shipment Intake - Data Used

## Entidades y tablas observadas

| Tabla / fuente | Uso observado |
| --- | --- |
| `ocr_alicorp` | Cabecera OCR de factura Alicorp. |
| `ocr_alicorp_detalle` | Items OCR de factura. |
| `ocr_alicorp_internacional` | Importes internacionales OCR, como flete. |
| `logis_embarques` | Embarque logistico creado y vinculado a `idocr_alicorp`. |
| `logis_embarquesrutas` / datos de tramo | Tramos creados a partir de origen/intermedio/destino OCR. |
| `dav_casosprevios` | Solicitud de Gestion Aduanera creada desde el embarque. |
| `dav_cliente`, `dav_clientedeclarante` | Solicitante/declarante para GA. |
| `prm_puertoaduana` | Mapea puerto nacional a aduana. |
| `prm_usuarioslinea` | Email oficial y coordinador por linea. |
| `dav_proveedor` | Nombre del proveedor OCR. |
| `dav_clientetransportista` | Nombre transportista para la solicitud. |
| `logis_tipoembarque` | Obtiene modalidad transporte. |
| `dav_listaempaque_*` | Destino inferido de lista de empaque asociada por `guadarSolicitudListaEmpaque`. |
| Intercambio Documental | Exchange logistico/aduanero creado desde JS. |

## Datos OCR usados para crear embarque

- Numero de factura.
- Incoterm logistico.
- Ciudad/pais.
- Contrato.
- Pedido/orden de compra.
- Proveedor.
- Descripcion de carga por items unicos.
- Tipo de mercancia.
- Unidades, cantidades, peso neto y peso bruto.
- Origen, intermedio y destino para tramos.
- Operador/transportista.

## Datos constantes observados

| Campo | Valor observado | Uso |
| --- | --- | --- |
| `exportacion` | `1` | Marca embarque como exportacion. |
| `gestor-transporte-id` | `1` | Gestor transporte por defecto. |
| `idagenteseguro` | `AGENTE_SEGURO_CARGA_MASIVA` | Seguro por carga masiva. |
| `idregimen` | `4` | Regimen de GA automatica. |
| `idtipodeclaracion` | `2` | Tipo de declaracion de GA automatica. |
| `idciudad` | `11` | Ciudad de GA automatica. |
| `tiempotransito` | `10` | Transito estimado por defecto. |

## Observaciones de calidad de datos

- `sinData` se activa por campos vacios o catalogos mapeados a `0`.
- La cantidad total suma repetidamente `items[0]["cantidad"]`, lo que podria no representar todos los items.
- La respuesta incluye `filesLE` desde una variable aparente `filesLEm`, no inicializada en el fragmento inspeccionado.
