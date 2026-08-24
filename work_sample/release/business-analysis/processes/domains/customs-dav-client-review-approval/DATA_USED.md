# Customs DAV Client Review Approval - Data Used

## Entidades principales

| Entidad / Tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `dav_dav` | Declaraciones DAV/FDM revisadas por cliente. | `iddav`, `idcasos`, `referencia`, `tipo`, `fob`, `idestadocliente`, `observacionescliente`, `finalizardav`, `fecharevisioncliente`, `idusuariorevision` |
| `dav_facturacomercial` | Facturas asociadas a la DAV y enlace al caso. | `iddav`, `idcasos`, `nofactura` |
| `dav_casos` | Carpeta/caso operativo que agrupa DAV y usuarios internos. | `idcasos`, `idcoordinador`, `idusuario` |
| `dav_casosprevios` | Puente observado entre caso y embarque logistico. | `idcasosprevios`, `idembarquelogis` |
| `logis_embarques` | Embarque logistico usado como entrada y para datos de correo. | `id`, `ordencompra`, `ordencompraini` |
| `dav_proveedor` | Proveedor mostrado en la lista de DAV/FDM. | `idproveedor`, `proveedor` |
| `dav_partidas` | Items/partidas de DAV/FDM consultadas en el detalle revisable. | `idpartidas`, `iddav`, `idfacturacomercial`, `idmercancia`, `idestadopartida`, valores FOB/seguro/transporte, parametros 1-14 |
| `dav_mercancia` | Catalogo de mercancia para items DAV/FDM. | `idmercancia`, `mercancia` |
| `dav_estadopartida` | Estado de partida mostrado en el detalle. | `idestadopartida`, `estadopartida`, `iddav` |
| `dav_unidadfactura` | Unidad de factura mostrada por item. | `idunidadfactura`, `unidadfactura`, `iddav` |
| `dav_acuerdo` | Acuerdo comercial/origen asociado a partida. | `idacuerdo`, `codigo` |
| `dav_parametro` / `dav_dato` | Parametros tecnicos y valores 1-14 de mercancia usados para armar detalle DAV/FDM. | `idmercancia`, `idparametro`, `idadta`, `dato`, `solodam` |
| `dav_usuario` | Correos de coordinador y oficial. | `idusuario`, `email` |
| `dav_edp` | Seguimiento de cierre de revision DAV. | `idcasos`, `idestadoedp`, `edp`, `idusuario` |

## Datos de entrada

- `idembarque` para cargar DAV/FDM de un embarque logistico.
- `idcasos` para verificar y cerrar una carpeta.
- `iddav` para aprobar o rechazar una declaracion concreta.
- `observaciones` de cliente.
- Sesion `idcliente`, `idclienteusuarios`.

## Datos derivados

- `formulario`: `DAV` cuando `dav_dav.tipo = 0`, `FDM` en otro caso.
- `estadocliente`: Para Revision si `idestadocliente = 9`, APROBADO si `1`, RECHAZADO si `2`, guion en otros casos.
- Lista de referencias DAV cerradas para componer el correo.
- Texto EDP formado como referencia, facturas y estado cliente.

## Persistencia observada

- `UPDATE dav_dav SET idestadocliente, observacionescliente WHERE iddav`.
- `UPDATE dav_dav SET finalizardav WHERE idcasos`.
- Registro EDP mediante `GlobalClass::registrarEDP` en `index_archivos/controllers/GlobalClass.php`.
