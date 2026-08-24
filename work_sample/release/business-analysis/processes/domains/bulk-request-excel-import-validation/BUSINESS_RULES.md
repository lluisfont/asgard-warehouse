# Bulk Request Excel Import Validation - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-BREI-001 | La pestaña Carga Masiva solo se muestra para clientes `560` o `755`. | `solicitud.php` |
| BR-BREI-002 | El formato se basa en `FormatoSolicitudMasiva.xlsx` y se enriquece con listas maestras. | `formatoSolicitudMasiva.php` |
| BR-BREI-003 | Proveedores y operadores se filtran por cliente de sesion. | `dav_clienteproveedor`, `dav_clientetransportista` |
| BR-BREI-004 | La importacion borra staging previo del mismo cliente/usuario antes de insertar nuevas filas. | `guadarSolicitudConvertidas` |
| BR-BREI-005 | Tipo solicitud se convierte desde texto a ids 0 Despacho Aduanero, 1 Gestion Soporte, 2 Vehiculos. | `buscarTipoSolicitud` |
| BR-BREI-006 | Ciudad solo acepta valores encontrados en `dav_ciudad` con ids `4` o `11`. | `buscarCiudad` |
| BR-BREI-007 | Coordinador debe estar activo y pertenecer a la ciudad validada. | `buscarUsuario` |
| BR-BREI-008 | Flags SI/NO se convierten a `1`/`0`; cualquier otro texto queda tratado como NO por la funcion observada. | `buscarSeleccion` |
| BR-BREI-009 | Fecha embarque y fecha llegada deben ser fechas validas y no `0000-00-00`. | `validarFecha` |
| BR-BREI-010 | El transportista no encontrado no bloquea; se guarda `idtransportista=0` y mensaje de observacion. | `validarSolicitud` |
| BR-BREI-011 | Si existe cualquier error bloqueante, no se crea ninguna solicitud del lote. | `uploadExcelSolicitud.php` |
| BR-BREI-012 | Una fila valida crea `dav_casosprevios`, documentos previos por modo transporte y tramite adicional si corresponde. | `crearGestionAduanera` |

## Riesgos de regla pendientes

- Confirmar si la restriccion de clientes 560/755 sigue vigente.
- Confirmar si la carga debe permitir exito parcial por filas validas.
- Confirmar si `buscarSeleccion` debe rechazar valores distintos a `SI`.
- Confirmar si hay control de duplicados por pedido/orden de compra.
