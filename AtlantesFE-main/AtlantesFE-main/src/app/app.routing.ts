import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './login/login.component';
import {RecuperarContrasenaComponent} from './recuperar-contrasena/recuperar-contrasena.component';
import {NotFoundComponent} from './not-found/not-found.component';
import {InicioComponent} from './inicio/inicio.component';

import {IngresosComponent} from './ingresos/ingresos.component';
import {IngresosDetalleComponent} from './ingresos-detalle/ingresos-detalle.component';

import {MoverDividirComponent} from './mover-dividir/mover-dividir.component';
import {PedidosComponent} from './pedidos/pedidos.component';
import {PedidosDetalleComponent} from './pedidos-detalle/pedidos-detalle.component';
import {SalidasComponent} from './salidas/salidas.component';
import {SalidasDetalleComponent} from './salidas-detalle/salidas-detalle.component';
import {CambiarClienteAlmacenComponent} from './cambiar-cliente-almacen/cambiar-cliente-almacen.component';
import {InventarioFisicoComponent} from './inventario-fisico/inventario-fisico.component';
import {InventarioFisicoDetalleComponent} from './inventario-fisico-detalle/inventario-fisico-detalle.component';
import {InventarioFisicoConteoComponent} from './inventario-fisico-conteo/inventario-fisico-conteo.component';
import {TimbradoComponent} from './timbrado/timbrado.component';
import {TimbradoDetalleComponent} from './timbrado-detalle/timbrado-detalle.component';
import {BitacoraComponent} from './bitacora/bitacora.component';
import {AteGasComponent} from './ate-gas/ate-gas.component';
import {AsignacionTrabajoComponent} from './asignacion-trabajo/asignacion-trabajo.component';
import {GestionMovimientoComponent} from './gestion-movimiento/gestion-movimiento.component';
import {EstadoPedidosComponent} from './estado-pedidos/estado-pedidos.component';
import {InventarioVinComponent} from './inventario-vin/inventario-vin.component';
import {AteGasSalidasComponent} from './ate-gas-salidas/ate-gas-salidas.component';

import {CotizacionesComponent} from './cotizaciones/cotizaciones.component';
import {EmbarquesComponent} from './embarques/embarques.component';
import {EmbarquesDetalleComponent} from './embarques-detalle/embarques-detalle.component';

import {FacturasComponent} from './facturas/facturas.component';
import {NotasCobranzaComponent} from './notas-cobranza/notas-cobranza.component';
import {OrdenesPagoComponent} from './ordenes-pago/ordenes-pago.component';
import {PagosAgenteExteriorComponent} from './pagos-agente-exterior/pagos-agente-exterior.component';
import {PlanillasComponent} from './planillas/planillas.component';
import {InvoicesComponent} from './invoices/invoices.component';
import {DevolucionesComponent} from './devoluciones/devoluciones.component';
import {CobrosComponent} from './cobros/cobros.component';
import {PagosComponent} from './pagos/pagos.component';
import {DevolucionSaldosComponent} from './devolucion-saldos/devolucion-saldos.component';

import {ClientesComponent} from './clientes/clientes.component';
import {TransportistasComponent} from './transportistas/transportistas.component';
import {AgentesCargaComponent} from './agentes-carga/agentes-carga.component';
import {ProveedoresComponent} from './proveedores/proveedores.component';
import {PrestadoresServicioComponent} from './prestadores-servicio/prestadores-servicio.component';
import {ProductosClienteComponent} from './productos-cliente/productos-cliente.component';
import {UsuariosComponent} from './usuarios/usuarios.component';
import {PerfilComponent} from './perfil/perfil.component';
import {EmpresaComponent} from './empresa/empresa.component';

import {ConceptosComponent} from './conceptos/conceptos.component';
import {CiudadesComponent} from './ciudades/ciudades.component';
import {BancosCuentasComponent} from './bancos-cuentas/bancos-cuentas.component';

import {ConsideracionesComponent} from './consideraciones/consideraciones.component';
import {ContemplacionesComponent} from './contemplaciones/contemplaciones.component';
import {DivisasComponent} from './divisas/divisas.component';
import {TipoCambioComponent} from './tipo-cambio/tipo-cambio.component';

import {AlmacenesComponent} from './almacenes/almacenes.component';
import {AlmacenesDetalleComponent} from './almacenes-detalle/almacenes-detalle.component';

import {ReporteAlmacenInventarioComponent} from './reporte-almacen-inventario/reporte-almacen-inventario.component';
import {ReporteAlmacenIngresosComponent} from './reporte-almacen-ingresos/reporte-almacen-ingresos.component';
import {ReporteAlmacenSalidasComponent} from './reporte-almacen-salidas/reporte-almacen-salidas.component';
import {ReporteAlmacenMovimientoComponent} from './reporte-almacen-movimiento/reporte-almacen-movimiento.component';
import {ReporteAlmacenMovimientoDetalleComponent} from './reporte-almacen-movimiento-detalle/reporte-almacen-movimiento-detalle.component';
import {ReporteAlmacenPosicionesComponent} from './reporte-almacen-posiciones/reporte-almacen-posiciones.component';
import {ReporteAlmacenDescargaComponent} from './reporte-almacen-descarga/reporte-almacen-descarga.component';
import {ReporteAlmacenVencimientoComponent} from './reporte-almacen-vencimiento/reporte-almacen-vencimiento.component';
import {ReporteAlmacenNoconformeComponent} from './reporte-almacen-noconforme/reporte-almacen-noconforme.component';
import {ReporteAlmacenEgresoTiendaComponent} from './reporte-almacen-egreso-tienda/reporte-almacen-egreso-tienda.component';
import {ReporteAlmacenProductosComponent} from './reporte-almacen-productos/reporte-almacen-productos.component';
import {ReporteAlmacenLiquidacionComponent} from './reporte-almacen-liquidacion/reporte-almacen-liquidacion.component';
import {ReporteAlmacenPosicionesDiaComponent} from './reporte-almacen-posiciones-dia/reporte-almacen-posiciones-dia.component';
import {ReporteAlmacenInventarioVencimientoComponent} from './reporte-almacen-inventario-vencimiento/reporte-almacen-inventario-vencimiento.component';
import {ReporteAlmacenPedidosComponent} from './reporte-almacen-pedidos/reporte-almacen-pedidos.component';
import {ReporteAlmacenCapacidadComponent} from './reporte-almacen-capacidad/reporte-almacen-capacidad.component';
import {ReporteAlmacenInventarioFisicoComponent} from './reporte-almacen-inventario-fisico/reporte-almacen-inventario-fisico.component';
import {ReporteAlmacenTotalConteoComponent} from './reporte-almacen-total-conteo/reporte-almacen-total-conteo.component';
import {ReporteAlmacenControlInventarioFisicoComponent} from './reporte-almacen-control-inventario-fisico/reporte-almacen-control-inventario-fisico.component';

import {ReporteTiemposProcesoComponent} from './reporte-tiempos-proceso/reporte-tiempos-proceso.component';
import {ReporteAteGasDemandaComponent} from './reporte-ate-gas-demanda/reporte-ate-gas-demanda.component';
import {ReporteAteGasStatusComponent} from './reporte-ate-gas-status/reporte-ate-gas-status.component';
import {ReporteAteGasIngresosComponent} from './reporte-ate-gas-ingresos/reporte-ate-gas-ingresos.component';
import {ReporteAteGasSalidasComponent} from './reporte-ate-gas-salidas/reporte-ate-gas-salidas.component';
import {ReporteAteGasProduccionComponent} from './reporte-ate-gas-produccion/reporte-ate-gas-produccion.component';

import {ReporteEmbarquesListadoComponent} from './reporte-embarques-listado/reporte-embarques-listado.component';

import {ReporteContabilidadEstadocuentasComponent} from './reporte-contabilidad-estadocuentas/reporte-contabilidad-estadocuentas.component';
import {ReporteContabilidadSaldosComponent} from './reporte-contabilidad-saldos/reporte-contabilidad-saldos.component';
import {ReporteContabilidadLibroventasComponent} from './reporte-contabilidad-libroventas/reporte-contabilidad-libroventas.component';
import {ReporteContabilidadInvoicesComponent} from './reporte-contabilidad-invoices/reporte-contabilidad-invoices.component';
import {ReporteContabilidadFacturasNotascobranzaComponent} from './reporte-contabilidad-facturas-notascobranza/reporte-contabilidad-facturas-notascobranza.component';
import {ReporteContabilidadTransaccionesfncComponent} from './reporte-contabilidad-transaccionesfnc/reporte-contabilidad-transaccionesfnc.component';
import {ReporteContabilidadOrdenesPagoComponent} from './reporte-contabilidad-ordenes-pago/reporte-contabilidad-ordenes-pago.component';
import {ReporteContabilidadCobranzasComponent} from './reporte-contabilidad-cobranzas/reporte-contabilidad-cobranzas.component';
import {ReporteContabilidadFacturasConceptoComponent} from './reporte-contabilidad-facturas-concepto/reporte-contabilidad-facturas-concepto.component';
import {ReporteContabilidadOrdenesPagoConceptoComponent} from './reporte-contabilidad-ordenes-pago-concepto/reporte-contabilidad-ordenes-pago-concepto.component';
import {ReporteContabilidadAnticiposComponent} from './reporte-contabilidad-anticipos/reporte-contabilidad-anticipos.component';
import {ReporteContabilidadConceptosComponent} from './reporte-contabilidad-conceptos/reporte-contabilidad-conceptos.component';

import {DashboardAlmacenComponent} from './dashboard-almacen/dashboard-almacen.component';
import {DashboardMonitoreoCentrosComponent} from './dashboard-monitoreo-centros/dashboard-monitoreo-centros.component';

import {DashboardGraficoCentrosComponent} from './dashboard-grafico-centros/dashboard-grafico-centros.component';
import {DashboardAteGasComponent} from './dashboard-ate-gas/dashboard-ate-gas.component';

const appRoutes: Routes = [
    {path: '', component: LoginComponent},
    {path: 'login', component: LoginComponent},
    {path: 'recuperar-contrasena', component: RecuperarContrasenaComponent},
    {path: 'inicio', component: InicioComponent},

    {path: 'ingresos', component: IngresosComponent},
    {path: 'ingresos-detalle/:idingreso', component: IngresosDetalleComponent},
    {path: 'mover-dividir', component: MoverDividirComponent},
    {path: 'pedidos', component: PedidosComponent},
    {path: 'pedidos-detalle/:idpedido', component: PedidosDetalleComponent},

    {path: 'salidas', component: SalidasComponent},
    {path: 'salidas-detalle/:idsalida', component: SalidasDetalleComponent},
    {path: 'cambiar-cliente-almacen', component: CambiarClienteAlmacenComponent},
    {path: 'inventario-fisico', component: InventarioFisicoComponent},
    {path: 'inventario-fisico-detalle/:idinventariofisico', component: InventarioFisicoDetalleComponent},
    {path: 'inventario-fisico-conteo/:idinventariofisico', component: InventarioFisicoConteoComponent},
    {path: 'timbrado', component: TimbradoComponent},
    {path: 'timbrado-detalle/:idtimbrado', component: TimbradoDetalleComponent},
    {path: 'bitacora', component: BitacoraComponent},
    {path: 'ate-gas', component: AteGasComponent},
    {path: 'asignacion-trabajo', component: AsignacionTrabajoComponent},
    {path: 'gestion-movimiento', component: GestionMovimientoComponent},
    {path: 'estado-pedidos', component: EstadoPedidosComponent},
    {path: 'inventario-vin', component: InventarioVinComponent},
    {path: 'ate-gas-salidas', component: AteGasSalidasComponent},
    
    

    {path: 'cotizaciones', component: CotizacionesComponent},
    {path: 'embarques', component: EmbarquesComponent},
    {path: 'embarques-detalle/:idembarque', component: EmbarquesDetalleComponent},

    {path: 'facturas', component: FacturasComponent},
    {path: 'notas-cobranza', component: NotasCobranzaComponent},
    {path: 'ordenes-pago', component: OrdenesPagoComponent},
    {path: 'pagos-agente-exterior', component: PagosAgenteExteriorComponent},
    {path: 'planillas', component: PlanillasComponent},
    {path: 'invoices', component: InvoicesComponent},
    {path: 'devoluciones', component: DevolucionesComponent},
    {path: 'cobros', component: CobrosComponent},
    {path: 'pagos', component: PagosComponent},
    {path: 'devolucion-saldos', component: DevolucionSaldosComponent},


    {path: 'clientes', component: ClientesComponent},
    {path: 'transportistas', component: TransportistasComponent},
    {path: 'agentes-carga', component: AgentesCargaComponent},
    {path: 'proveedores', component: ProveedoresComponent},
    {path: 'prestadores-servicio', component: PrestadoresServicioComponent},
    {path: 'productos-cliente', component: ProductosClienteComponent},
    {path: 'conceptos', component: ConceptosComponent},
    {path: 'usuarios', component: UsuariosComponent},
    {path: 'perfil', component: PerfilComponent},
    {path: 'almacenes', component: AlmacenesComponent},
    {path: 'almacenes-detalle/:idalmacen', component: AlmacenesDetalleComponent},
    {path: 'ciudades', component: CiudadesComponent},
    {path: 'bancos-cuentas', component: BancosCuentasComponent},
    {path: 'consideraciones', component: ConsideracionesComponent},
    {path: 'contemplaciones', component: ContemplacionesComponent},
    {path: 'contemplaciones', component: ContemplacionesComponent},
    {path: 'divisas', component: DivisasComponent},
    {path: 'tipo-cambio', component: TipoCambioComponent},
    {path: 'empresa', component: EmpresaComponent},



    {path: 'reporte-almacen-inventario', component: ReporteAlmacenInventarioComponent},
    {path: 'reporte-almacen-ingresos', component: ReporteAlmacenIngresosComponent},
    {path: 'reporte-almacen-salidas', component: ReporteAlmacenSalidasComponent},
    {path: 'reporte-almacen-movimiento', component: ReporteAlmacenMovimientoComponent},
    {path: 'reporte-almacen-movimiento-detalle', component: ReporteAlmacenMovimientoDetalleComponent},
    {path: 'reporte-almacen-posiciones', component: ReporteAlmacenPosicionesComponent},
    {path: 'reporte-almacen-descarga', component: ReporteAlmacenDescargaComponent},
    {path: 'reporte-almacen-vencimiento', component: ReporteAlmacenVencimientoComponent},
    {path: 'reporte-almacen-noconforme', component: ReporteAlmacenNoconformeComponent},
    {path: 'reporte-almacen-egreso-tienda', component: ReporteAlmacenEgresoTiendaComponent},
    {path: 'reporte-almacen-productos', component: ReporteAlmacenProductosComponent},
    {path: 'reporte-almacen-liquidacion', component: ReporteAlmacenLiquidacionComponent},
    {path: 'reporte-almacen-posiciones-dia', component: ReporteAlmacenPosicionesDiaComponent},
    {path: 'reporte-almacen-inventario-vencimiento', component: ReporteAlmacenInventarioVencimientoComponent},
    {path: 'reporte-almacen-pedidos', component: ReporteAlmacenPedidosComponent},
    {path: 'reporte-almacen-capacidad', component: ReporteAlmacenCapacidadComponent},
    {path: 'reporte-almacen-inventario-fisico', component: ReporteAlmacenInventarioFisicoComponent},
    {path: 'reporte-almacen-total-conteo', component: ReporteAlmacenTotalConteoComponent},
    {path: 'reporte-almacen-control-inventario-fisico', component: ReporteAlmacenControlInventarioFisicoComponent},

    {path: 'reporte-tiempos-proceso', component: ReporteTiemposProcesoComponent},
    {path: 'reporte-ate-gas-demanda', component: ReporteAteGasDemandaComponent},
    {path: 'reporte-ate-gas-status', component: ReporteAteGasStatusComponent},
    {path: 'reporte-ate-gas-ingresos', component: ReporteAteGasIngresosComponent},
    {path: 'reporte-ate-gas-salidas', component: ReporteAteGasSalidasComponent},
    {path: 'reporte-ate-gas-produccion', component: ReporteAteGasProduccionComponent},
    
    {path: 'reporte-embarques-listado', component: ReporteEmbarquesListadoComponent},

    {path: 'reporte-contabilidad-estadocuentas', component: ReporteContabilidadEstadocuentasComponent},
    {path: 'reporte-contabilidad-saldos', component: ReporteContabilidadSaldosComponent},
    {path: 'reporte-contabilidad-libroventas', component: ReporteContabilidadLibroventasComponent},
    {path: 'reporte-contabilidad-invoices', component: ReporteContabilidadInvoicesComponent},
    {path: 'reporte-contabilidad-facturas-notascobranza', component: ReporteContabilidadFacturasNotascobranzaComponent},
    {path: 'reporte-contabilidad-transaccionesfnc', component: ReporteContabilidadTransaccionesfncComponent},
    {path: 'reporte-contabilidad-ordenes-pago', component: ReporteContabilidadOrdenesPagoComponent},
    {path: 'reporte-contabilidad-cobranzas', component: ReporteContabilidadCobranzasComponent},
    {path: 'reporte-contabilidad-facturas-concepto', component: ReporteContabilidadFacturasConceptoComponent},
    {path: 'reporte-contabilidad-ordenes-pago-concepto', component: ReporteContabilidadOrdenesPagoConceptoComponent},
    {path: 'reporte-contabilidad-anticipos', component: ReporteContabilidadAnticiposComponent},
    {path: 'reporte-contabilidad-conceptos', component: ReporteContabilidadConceptosComponent},


    {path: 'dashboard-almacen', component: DashboardAlmacenComponent},
    {path: 'dashboard-monitoreo-centros', component: DashboardMonitoreoCentrosComponent},
    {path: 'dashboard-grafico-centros', component: DashboardGraficoCentrosComponent},
    {path: 'dashboard-ate-gas', component: DashboardAteGasComponent},




    {path: '**', component: NotFoundComponent}
];

export const appRoutingProviders: any[] = [];

export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes, {});
