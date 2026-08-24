import {NgModule, LOCALE_ID } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { routing, appRoutingProviders } from './app.routing';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import {HttpClientModule} from '@angular/common/http';
import { HashLocationStrategy, LocationStrategy  } from '@angular/common';

import { NgSelectModule } from '@ng-select/ng-select';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CookieService } from 'ngx-cookie-service';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ToastrModule } from 'ngx-toastr';

import { LoadingBarHttpClientModule } from '@ngx-loading-bar/http-client';
import { LoadingBarModule } from '@ngx-loading-bar/core';

//import { JwPaginationComponent } from 'jw-angular-pagination';
import {NgxPaginationModule} from 'ngx-pagination'; // <-- import the module
import {AutosizeModule} from 'ngx-autosize';

import {AccordionModule} from 'primeng/accordion';
import {TableModule} from 'primeng/table';
import {DropdownModule} from 'primeng/dropdown';
import {MultiSelectModule} from 'primeng/multiselect';
import {ButtonModule} from 'primeng/button';
import { CalendarModule } from 'primeng/calendar';
import {AutoCompleteModule } from 'primeng/autocomplete';
import { TooltipModule } from 'primeng/tooltip';
import { SplitButtonModule } from 'primeng/splitbutton';
import { MenuModule } from 'primeng/menu';
import { TreeTableModule } from 'primeng/treetable';
import { CheckboxModule } from 'primeng/checkbox';
import { DialogModule } from 'primeng/dialog';
import { TabViewModule } from 'primeng/tabview';
import { GalleriaModule } from 'primeng/galleria';
import { BadgeModule } from 'primeng/badge';
import { FileUploadModule } from 'primeng/fileupload';
import { ToastModule } from 'primeng/toast';
import { DataViewModule } from 'primeng/dataview';
import { TagModule } from 'primeng/tag';
import { SelectButtonModule } from 'primeng/selectbutton';
import { ChartModule } from 'primeng/chart';
import { CardModule } from 'primeng/card';
import { ChipModule } from 'primeng/chip';
import { TimelineModule } from 'primeng/timeline';
import { ImageModule } from 'primeng/image';
import { ConfirmDialogModule } from 'primeng/confirmdialog';

import { LoginComponent } from './login/login.component';
import {NotFoundComponent} from './not-found/not-found.component';
import { MenulateralComponent } from './menulateral/menulateral.component';
import { MenusuperiorComponent } from './menusuperior/menusuperior.component';
import {ChatbotComponent} from './chatbot/chatbot.component';
import { InicioComponent } from './inicio/inicio.component';
import { EmbarquesComponent } from './embarques/embarques.component';
import { EmbarquesDetalleComponent } from './embarques-detalle/embarques-detalle.component';
import { CobrosComponent } from './cobros/cobros.component';
import { FacturasComponent } from './facturas/facturas.component';
import { ClientesComponent } from './clientes/clientes.component';
//import { FilterPipe } from './filter.pipe';


import localeEs from '@angular/common/locales/es';
import {registerLocaleData} from '@angular/common';
registerLocaleData(localeEs,'es');

import {FilterPipe} from './filter.pipe';
import { AlmacenesComponent } from './almacenes/almacenes.component';
import { AlmacenesDetalleComponent } from './almacenes-detalle/almacenes-detalle.component';
import { IngresosComponent } from './ingresos/ingresos.component';
import { IngresosDetalleComponent } from './ingresos-detalle/ingresos-detalle.component';
import { SalidasComponent } from './salidas/salidas.component';
import { SalidasDetalleComponent } from './salidas-detalle/salidas-detalle.component';
import { MoverDividirComponent } from './mover-dividir/mover-dividir.component';
import { ReporteAlmacenInventarioComponent } from './reporte-almacen-inventario/reporte-almacen-inventario.component';
import { ReporteAlmacenIngresosComponent } from './reporte-almacen-ingresos/reporte-almacen-ingresos.component';
import { ReporteAlmacenSalidasComponent } from './reporte-almacen-salidas/reporte-almacen-salidas.component';
import { NotasCobranzaComponent } from './notas-cobranza/notas-cobranza.component';
import { OrdenesPagoComponent } from './ordenes-pago/ordenes-pago.component';
import { PagosAgenteExteriorComponent } from './pagos-agente-exterior/pagos-agente-exterior.component';
import { PlanillasComponent } from './planillas/planillas.component';
import { InvoicesComponent } from './invoices/invoices.component';
import { TransportistasComponent } from './transportistas/transportistas.component';
import { AgentesCargaComponent } from './agentes-carga/agentes-carga.component';
import { ProveedoresComponent } from './proveedores/proveedores.component';
import {PrestadoresServicioComponent} from './prestadores-servicio/prestadores-servicio.component';
import { PagosComponent } from './pagos/pagos.component';
import { ProductosClienteComponent } from './productos-cliente/productos-cliente.component';
import { DevolucionSaldosComponent } from './devolucion-saldos/devolucion-saldos.component';
import { DevolucionesComponent } from './devoluciones/devoluciones.component';
import { CotizacionesComponent } from './cotizaciones/cotizaciones.component';
import { UsuariosComponent } from './usuarios/usuarios.component';
import { ConceptosComponent } from './conceptos/conceptos.component';
import {CiudadesComponent} from './ciudades/ciudades.component';
import {BancosCuentasComponent} from './bancos-cuentas/bancos-cuentas.component';
import {ConsideracionesComponent} from './consideraciones/consideraciones.component';
import {ContemplacionesComponent} from './contemplaciones/contemplaciones.component';
import {DivisasComponent} from './divisas/divisas.component';
import {TipoCambioComponent} from './tipo-cambio/tipo-cambio.component';
import {EmpresaComponent} from './empresa/empresa.component';
import { PedidosComponent } from './pedidos/pedidos.component';
import { PedidosDetalleComponent } from './pedidos-detalle/pedidos-detalle.component';
import { ReporteContabilidadEstadocuentasComponent } from './reporte-contabilidad-estadocuentas/reporte-contabilidad-estadocuentas.component';
import { ReporteContabilidadSaldosComponent } from './reporte-contabilidad-saldos/reporte-contabilidad-saldos.component';
import { ReporteContabilidadLibroventasComponent } from './reporte-contabilidad-libroventas/reporte-contabilidad-libroventas.component';
import { ReporteContabilidadInvoicesComponent } from './reporte-contabilidad-invoices/reporte-contabilidad-invoices.component';
import { ReporteContabilidadFacturasNotascobranzaComponent } from './reporte-contabilidad-facturas-notascobranza/reporte-contabilidad-facturas-notascobranza.component';
import { ReporteContabilidadTransaccionesfncComponent } from './reporte-contabilidad-transaccionesfnc/reporte-contabilidad-transaccionesfnc.component';
import { PerfilComponent } from './perfil/perfil.component';
import { ReporteAlmacenMovimientoComponent } from './reporte-almacen-movimiento/reporte-almacen-movimiento.component';
import { ReporteAlmacenMovimientoDetalleComponent } from './reporte-almacen-movimiento-detalle/reporte-almacen-movimiento-detalle.component';
import { ReporteContabilidadOrdenesPagoComponent } from './reporte-contabilidad-ordenes-pago/reporte-contabilidad-ordenes-pago.component';
import { DashboardAlmacenComponent } from './dashboard-almacen/dashboard-almacen.component';
import {DashboardMonitoreoCentrosComponent} from './dashboard-monitoreo-centros/dashboard-monitoreo-centros.component';
import {DashboardGraficoCentrosComponent} from './dashboard-grafico-centros/dashboard-grafico-centros.component';
import {DashboardAteGasComponent} from './dashboard-ate-gas/dashboard-ate-gas.component';
import { ReporteContabilidadCobranzasComponent } from './reporte-contabilidad-cobranzas/reporte-contabilidad-cobranzas.component';
import { ReporteAlmacenPosicionesComponent } from './reporte-almacen-posiciones/reporte-almacen-posiciones.component';
import { ReporteAlmacenDescargaComponent } from './reporte-almacen-descarga/reporte-almacen-descarga.component';
import { ReporteAlmacenVencimientoComponent } from './reporte-almacen-vencimiento/reporte-almacen-vencimiento.component';
import { ReporteAlmacenNoconformeComponent } from './reporte-almacen-noconforme/reporte-almacen-noconforme.component';
import { ReporteAlmacenEgresoTiendaComponent } from './reporte-almacen-egreso-tienda/reporte-almacen-egreso-tienda.component';
import { ReporteAlmacenProductosComponent } from './reporte-almacen-productos/reporte-almacen-productos.component';
import {ReporteAlmacenPosicionesDiaComponent} from './reporte-almacen-posiciones-dia/reporte-almacen-posiciones-dia.component';
import {ReporteAlmacenInventarioVencimientoComponent} from './reporte-almacen-inventario-vencimiento/reporte-almacen-inventario-vencimiento.component';
import {ReporteAlmacenPedidosComponent} from './reporte-almacen-pedidos/reporte-almacen-pedidos.component';
import {ReporteAlmacenCapacidadComponent} from './reporte-almacen-capacidad/reporte-almacen-capacidad.component';
import {ReporteAlmacenInventarioFisicoComponent} from './reporte-almacen-inventario-fisico/reporte-almacen-inventario-fisico.component';
import {ReporteAlmacenTotalConteoComponent} from './reporte-almacen-total-conteo/reporte-almacen-total-conteo.component';
import {ReporteAlmacenControlInventarioFisicoComponent} from './reporte-almacen-control-inventario-fisico/reporte-almacen-control-inventario-fisico.component';

import { CambiarClienteAlmacenComponent } from './cambiar-cliente-almacen/cambiar-cliente-almacen.component';
import { ReporteAlmacenLiquidacionComponent } from './reporte-almacen-liquidacion/reporte-almacen-liquidacion.component';
import {ReporteContabilidadFacturasConceptoComponent} from './reporte-contabilidad-facturas-concepto/reporte-contabilidad-facturas-concepto.component';
import {ReporteContabilidadOrdenesPagoConceptoComponent} from './reporte-contabilidad-ordenes-pago-concepto/reporte-contabilidad-ordenes-pago-concepto.component';
import {ReporteContabilidadAnticiposComponent} from './reporte-contabilidad-anticipos/reporte-contabilidad-anticipos.component';
import {ReporteContabilidadConceptosComponent} from './reporte-contabilidad-conceptos/reporte-contabilidad-conceptos.component';
import {InventarioFisicoComponent} from './inventario-fisico/inventario-fisico.component';
import {InventarioFisicoDetalleComponent} from './inventario-fisico-detalle/inventario-fisico-detalle.component';
import {InventarioFisicoConteoComponent} from './inventario-fisico-conteo/inventario-fisico-conteo.component';
import {InventarioFisicoBitacoraComponent} from './inventario-fisico-bitacora/inventario-fisico-bitacora.component';
import {TimbradoComponent} from './timbrado/timbrado.component';
import {TimbradoDetalleComponent} from './timbrado-detalle/timbrado-detalle.component';
import {BitacoraComponent} from './bitacora/bitacora.component';
import {AteGasComponent} from './ate-gas/ate-gas.component';
import {AsignacionTrabajoComponent} from './asignacion-trabajo/asignacion-trabajo.component';
import {GestionMovimientoComponent} from './gestion-movimiento/gestion-movimiento.component';
import {EstadoPedidosComponent} from './estado-pedidos/estado-pedidos.component';
import {InventarioVinComponent} from './inventario-vin/inventario-vin.component';
import {AteGasSalidasComponent} from './ate-gas-salidas/ate-gas-salidas.component';

import {ReporteTiemposProcesoComponent} from './reporte-tiempos-proceso/reporte-tiempos-proceso.component';
import {ReporteAteGasDemandaComponent} from './reporte-ate-gas-demanda/reporte-ate-gas-demanda.component';
import {ReporteAteGasStatusComponent} from './reporte-ate-gas-status/reporte-ate-gas-status.component';
import {ReporteAteGasIngresosComponent} from './reporte-ate-gas-ingresos/reporte-ate-gas-ingresos.component';
import {ReporteAteGasSalidasComponent} from './reporte-ate-gas-salidas/reporte-ate-gas-salidas.component';
import {ReporteAteGasProduccionComponent} from './reporte-ate-gas-produccion/reporte-ate-gas-produccion.component';

import {ReporteEmbarquesListadoComponent} from './reporte-embarques-listado/reporte-embarques-listado.component';

import {CambioContrasenaComponent} from './cambio-contrasena/cambio-contrasena.component';
import {RecuperarContrasenaComponent} from './recuperar-contrasena/recuperar-contrasena.component';

import { NgxScannerQrcodeModule, LOAD_WASM } from 'ngx-scanner-qrcode';
LOAD_WASM('assets/wasm/ngx-scanner-qrcode.wasm').subscribe();



@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    NotFoundComponent,
    MenulateralComponent,
    MenusuperiorComponent,
    ChatbotComponent,
    InicioComponent,
    EmbarquesComponent,
    EmbarquesDetalleComponent,
    CobrosComponent,
    FacturasComponent,
    ClientesComponent,
    FilterPipe,
    AlmacenesComponent,
    AlmacenesDetalleComponent,
    IngresosComponent,
    IngresosDetalleComponent,
    SalidasComponent,
    SalidasDetalleComponent,
    MoverDividirComponent,
    ReporteAlmacenInventarioComponent,
    ReporteAlmacenIngresosComponent,
    ReporteAlmacenSalidasComponent,
    NotasCobranzaComponent,
    OrdenesPagoComponent,
    PagosAgenteExteriorComponent,
    PlanillasComponent,
    InvoicesComponent,
    TransportistasComponent,
    AgentesCargaComponent,
    ProveedoresComponent,
    PrestadoresServicioComponent,
    PagosComponent,
    ProductosClienteComponent,
    DevolucionSaldosComponent,
    DevolucionesComponent,
    CotizacionesComponent,
    UsuariosComponent,
    ConceptosComponent,
    CiudadesComponent,
    BancosCuentasComponent,
    ConsideracionesComponent,
    ContemplacionesComponent,
    DivisasComponent,
    TipoCambioComponent,
    EmpresaComponent,
    PedidosComponent,
    PedidosDetalleComponent,
    ReporteContabilidadEstadocuentasComponent,
    ReporteContabilidadSaldosComponent,
    ReporteContabilidadLibroventasComponent,
    ReporteContabilidadInvoicesComponent,
    ReporteContabilidadFacturasNotascobranzaComponent,
    ReporteContabilidadTransaccionesfncComponent,
    PerfilComponent,
    ReporteAlmacenMovimientoComponent,
    ReporteAlmacenMovimientoDetalleComponent,
    ReporteContabilidadOrdenesPagoComponent,
    DashboardAlmacenComponent,
    DashboardMonitoreoCentrosComponent,
    DashboardGraficoCentrosComponent,
    DashboardAteGasComponent,
    ReporteContabilidadCobranzasComponent,
    ReporteAlmacenPosicionesComponent,
    ReporteAlmacenDescargaComponent,
    ReporteAlmacenVencimientoComponent,
    ReporteAlmacenNoconformeComponent,
    ReporteAlmacenEgresoTiendaComponent,
    ReporteAlmacenProductosComponent,
    ReporteAlmacenPosicionesDiaComponent,
    ReporteAlmacenInventarioVencimientoComponent,
    ReporteAlmacenPedidosComponent,
    ReporteAlmacenCapacidadComponent,
    ReporteAlmacenInventarioFisicoComponent,
    ReporteAlmacenTotalConteoComponent,
    ReporteAlmacenControlInventarioFisicoComponent,
    CambiarClienteAlmacenComponent,
    ReporteAlmacenLiquidacionComponent,
    ReporteContabilidadFacturasConceptoComponent,
    ReporteContabilidadOrdenesPagoConceptoComponent,
    ReporteContabilidadAnticiposComponent,
    ReporteContabilidadConceptosComponent,
    InventarioFisicoComponent,
    InventarioFisicoDetalleComponent,
    InventarioFisicoConteoComponent,
    InventarioFisicoBitacoraComponent,
    TimbradoComponent,
    TimbradoDetalleComponent,
    BitacoraComponent,
    AteGasComponent,
    AsignacionTrabajoComponent,
    GestionMovimientoComponent,
    EstadoPedidosComponent,
    InventarioVinComponent,
    AteGasSalidasComponent,
    ReporteEmbarquesListadoComponent,
    ReporteTiemposProcesoComponent,
    ReporteAteGasDemandaComponent,
    ReporteAteGasStatusComponent,
    ReporteAteGasIngresosComponent,
    ReporteAteGasSalidasComponent,
    ReporteAteGasProduccionComponent,
    CambioContrasenaComponent,
    RecuperarContrasenaComponent
  ],
  imports: [
    BrowserModule,
    NgxPaginationModule,
    AutosizeModule,
    AccordionModule,
    TableModule,
    DropdownModule,
    MultiSelectModule,
    ButtonModule,
    CalendarModule,
    AutoCompleteModule,
    TooltipModule,
    SplitButtonModule,
    MenuModule,
    TreeTableModule,
    CheckboxModule,
    DialogModule,
    TabViewModule,
    GalleriaModule,
    BadgeModule,
    FileUploadModule,
    ToastModule,
    DataViewModule,
    TagModule,
    SelectButtonModule,
    ChartModule,
    CardModule,
    ChipModule,
    TimelineModule,
    ImageModule,
    ConfirmDialogModule,
    AppRoutingModule,
    routing,
    HttpClientModule,
    NgSelectModule,
    FormsModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    ToastrModule.forRoot(),
    LoadingBarHttpClientModule,
    LoadingBarModule,
    NgxScannerQrcodeModule
  ],
  providers: [
      appRoutingProviders,
      CookieService,
      {provide : LocationStrategy , useClass: HashLocationStrategy},
      {provide: LOCALE_ID, useValue: 'es'}
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
