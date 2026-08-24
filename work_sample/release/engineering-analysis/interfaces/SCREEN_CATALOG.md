# Screen catalog

Estado: inferred_from_static_evidence  
Confianza: media

| Pantalla/familia | Evidencia | Funcion principal |
|---|---|---|
| Login / 2FA | `index.php`, `ajax/ajaxValida2FA.php` | Acceso, segundo factor y creacion de sesion |
| Historial de usuario | `usuario/historial.php` | Auditoria/consulta de acciones o accesos |
| Gestion aduanera nueva | `embarques_nueva_gestion_aduanera.php` | Alta o gestion de solicitudes/casos |
| Detalle gestion aduanera | `embarques_detalle_gestion_aduanera.php` | Consulta y actualizacion de expediente |
| IH agencia | `getIHAgencia.php` | Consulta de informacion de agencia/interfaz aduanera |
| Caso logistico | `logistica/vercaso.php` | Seguimiento/cotizacion/costos de caso |
| Despachos logisticos | `logistica/despachos.php` | Mantenimiento operativo legacy |
| Exportaciones | `operativos/exportaciones/*` | Seguimiento y comparacion documental |
| Dashboard generico | `ajax/DashboardGenerico.php` | Indicadores y reporteria ejecutiva |
| Vehiculos Excel | `VehiculosExcel.php` | Importacion/exportacion/validacion tabular |
| Proveedores/contactos/token | Gestor transporte/proveedores | Onboarding documental y acceso de terceros |

## Nota

El catalogo de pantallas debe cruzarse con menus/permisos para separar pantallas activas, heredadas y de administracion interna.
