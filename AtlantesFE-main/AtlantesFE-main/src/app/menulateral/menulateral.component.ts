import { Component, OnInit, Input } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import { FreshchatService } from '../services/freshchat.service';

@Component({
    selector: 'app-menulateral',
    templateUrl: './menulateral.component.html',
    providers:[UsuarioService]
})
export class MenulateralComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    @Input() menu_item: number = 0;
    public parte_entera: number;
    
    public ver_ingresos: boolean=false;
    public ver_mover_dividir: boolean=false;
    public ver_inventario_fisico: boolean=false;
    public ver_pedidos: boolean=false;
    public ver_salidas: boolean=false;
    public ver_cambio_cliente: boolean=false;
    public ver_timbrado: boolean=false;
    public ver_bitacora: boolean=false;
    
    public ver_ate_gas: boolean=false;
    public ver_prevision_vin: boolean=false;
    public ver_prevision_vin_carga_masiva: boolean=false;
    public ver_asignacion_trabajo: boolean=false;
    public ver_gestion_movimiento: boolean=false;
    public ver_estado_pedidos: boolean=false;
    public ver_inventario_vin: boolean=false;
    public ver_ate_gas_salidas: boolean=false;
    
    public ver_cotizaciones: boolean=false;
    public ver_embarques: boolean=false;
    
    public ver_facturas: boolean=false;
    public ver_notas_cobranza: boolean=false;
    public ver_ordenes_pago: boolean=false;
    public ver_pagos_agentes_exterior: boolean=false;
    public ver_planillas: boolean=false;
    public ver_invoices: boolean=false;
    public ver_devoluciones: boolean=false;
    public ver_cobros: boolean=false;
    public ver_pagos: boolean=false;
    public ver_devolucion_saldos: boolean=false;
    
    public ver_entidades_clientes: boolean=false;
    public ver_entidades_transportistas: boolean=false;
    public ver_entidades_agentes_carga: boolean=false;
    public ver_entidades_proveedores: boolean=false;
    public ver_entidades_prestadores: boolean=false;
    
    public ver_divisas: boolean=false;
    public ver_tipo_cambio: boolean=false;
    
    public ver_almacenes: boolean=false;
    public ver_productos_cliente: boolean=false;
    
    public ver_empresa: boolean=false;
    public ver_conceptos: boolean=false;
    public ver_usuarios: boolean=false;
    public ver_ciudades: boolean=false;
    public ver_bancos: boolean=false;
    public ver_contemplaciones: boolean=false;
    public ver_consideraciones: boolean=false;
    
    public ver_reporte_almacen_inventario: boolean=false;
    public ver_reporte_almacen_ingresos: boolean=false;
    public ver_reporte_almacen_salidas: boolean=false;
    public ver_reporte_almacen_movimiento: boolean=false;
    public ver_reporte_almacen_movimiento_detalle: boolean=false;
    public ver_reporte_almacen_posiciones: boolean=false;
    public ver_reporte_almacen_descarga: boolean=false;
    public ver_reporte_almacen_vencimiento: boolean=false;
    public ver_reporte_almacen_no_conforme: boolean=false;
    public ver_reporte_almacen_egreso_por_tiendas: boolean=false;
    public ver_reporte_almacen_productos: boolean=false;
    public ver_reporte_almacen_liquidacion: boolean=false;
    public ver_reporte_almacen_posicion_por_dia: boolean=false;
    public ver_reporte_almacen_inventario_vencimiento: boolean=false;
    public ver_reporte_almacen_pedidos: boolean=false;
    public ver_reporte_almacen_capacidad: boolean=false;
    public ver_reporte_almacen_inventario_fisico: boolean=false;
    public ver_reporte_almacen_total_conteo: boolean=false;
    public ver_reporte_control_general_inventario_fisico: boolean=false;

    public ver_reporte_tiempos_proceso: boolean=false;
    public ver_reporte_ate_gas_demanda: boolean=false;
    public ver_reporte_ate_gas_status: boolean=false;
    public ver_reporte_ate_gas_ingresos: boolean=false;
    public ver_reporte_ate_gas_salidas: boolean=false;
    public ver_reporte_ate_gas_produccion: boolean=false;
    
    public ver_reporte_embarques_listado: boolean=false;
    
    public ver_reporte_contabilidad_facturas_notas_cobranza: boolean=false;
    public ver_reporte_contabilidad_list_trans: boolean=false;
    public ver_reporte_contabilidad_facturas_concepto: boolean=false;
    public ver_reporte_contabilidad_ordenes_pago_concepto: boolean=false;
    public ver_reporte_contabilidad_montos_concepto: boolean=false;
    public ver_reporte_contabilidad_ordenes_pago: boolean=false;
    public ver_reporte_contabilidad_invoices: boolean=false;
    public ver_reporte_contabilidad_estado_cuentas: boolean=false;
    public ver_reporte_contabilidad_saldos: boolean=false;
    public ver_reporte_contabilidad_cobranza: boolean=false;
    public ver_reporte_contabilidad_anticipos: boolean=false;
    public ver_reporte_contabilidad_libro_ventas: boolean=false;
    
    public ver_dashboard_interno_almacen: boolean=false;
    public ver_dashboard_monitoreo_centros: boolean=false;
    
    public ver_dashboard_grafico_centros: boolean=false;
    public ver_dashboard_ate_gas: boolean=false;
    
    private readonly permisosModulo: { prop: keyof MenulateralComponent; ids: number[] }[] = [
        // ===== OPERACIÓNES =====
        { prop: 'ver_ingresos',                         ids: [1]  },
        { prop: 'ver_mover_dividir',                    ids: [20] },
        { prop: 'ver_pedidos',                          ids: [21] },
        { prop: 'ver_salidas',                          ids: [22] },
        { prop: 'ver_cambio_cliente',                   ids: [23] },

        // Inventario físico tiene 3 módulos válidos
        { prop: 'ver_inventario_fisico',                ids: [43, 44, 45] },

        { prop: 'ver_timbrado',                         ids: [26] },
        { prop: 'ver_bitacora',                         ids: [46] },
        
        { prop: 'ver_ate_gas',                          ids: [92,93,94,95,98,99,101] },
        { prop: 'ver_prevision_vin',                    ids: [92] },
        { prop: 'ver_prevision_vin_carga_masiva',       ids: [93] },
        { prop: 'ver_asignacion_trabajo',               ids: [94] },
        { prop: 'ver_gestion_movimiento',               ids: [95] },
        { prop: 'ver_estado_pedidos',                   ids: [98] },
        { prop: 'ver_inventario_vin',                   ids: [99] },
        { prop: 'ver_ate_gas_salidas',                   ids: [101] },
        
        { prop: 'ver_cotizaciones',                     ids: [37] },
        { prop: 'ver_embarques',                        ids: [38] },

        // ===== CONTABILIDAD / FINANZAS =====
        { prop: 'ver_facturas',                         ids: [12] },
        { prop: 'ver_notas_cobranza',                   ids: [13] },
        { prop: 'ver_ordenes_pago',                     ids: [14] },
        { prop: 'ver_pagos_agentes_exterior',           ids: [47] },
        { prop: 'ver_planillas',                        ids: [48] },
        { prop: 'ver_invoices',                         ids: [49] },
        { prop: 'ver_devoluciones',                     ids: [50] },
        { prop: 'ver_cobros',                           ids: [40] },
        { prop: 'ver_pagos',                            ids: [41] },
        { prop: 'ver_devolucion_saldos',                ids: [42] },

        // ===== ENTIDADES =====
        { prop: 'ver_entidades_clientes',               ids: [2]  },
        { prop: 'ver_entidades_transportistas',         ids: [3]  },
        { prop: 'ver_entidades_agentes_carga',          ids: [4]  },
        { prop: 'ver_entidades_proveedores',            ids: [5]  },
        { prop: 'ver_entidades_prestadores',            ids: [6]  },

        // ===== CATÁLOGOS =====
        { prop: 'ver_divisas',                          ids: [34] },
        { prop: 'ver_tipo_cambio',                      ids: [36] },
        { prop: 'ver_almacenes',                        ids: [8]  },
        { prop: 'ver_productos_cliente',                ids: [9]  },
        { prop: 'ver_empresa',                          ids: [32] },
        { prop: 'ver_conceptos',                        ids: [7]  },
        { prop: 'ver_usuarios',                         ids: [10] },
        { prop: 'ver_ciudades',                         ids: [28] },
        { prop: 'ver_bancos',                           ids: [29] },
        { prop: 'ver_contemplaciones',                  ids: [30] },
        { prop: 'ver_consideraciones',                  ids: [31] },

        // ===== REPORTES ALMACÉN =====
        { prop: 'ver_reporte_almacen_inventario',             ids: [53] },
        { prop: 'ver_reporte_almacen_ingresos',               ids: [54] },
        { prop: 'ver_reporte_almacen_salidas',                ids: [55] },
        { prop: 'ver_reporte_almacen_movimiento',             ids: [56] },
        { prop: 'ver_reporte_almacen_movimiento_detalle',     ids: [88] },
        { prop: 'ver_reporte_almacen_posiciones',             ids: [57] },
        { prop: 'ver_reporte_almacen_descarga',               ids: [58] },
        { prop: 'ver_reporte_almacen_vencimiento',            ids: [59] },
        { prop: 'ver_reporte_almacen_no_conforme',            ids: [60] },
        { prop: 'ver_reporte_almacen_egreso_por_tiendas',     ids: [61] },
        { prop: 'ver_reporte_almacen_productos',              ids: [62] },
        { prop: 'ver_reporte_almacen_liquidacion',            ids: [63] },
        { prop: 'ver_reporte_almacen_posicion_por_dia',       ids: [64] },
        { prop: 'ver_reporte_almacen_inventario_vencimiento', ids: [65] },
        { prop: 'ver_reporte_almacen_pedidos',                ids: [66] },
        { prop: 'ver_reporte_almacen_capacidad',              ids: [67] },
        { prop: 'ver_reporte_almacen_inventario_fisico',      ids: [68] },
        { prop: 'ver_reporte_almacen_total_conteo',           ids: [90] },
        { prop: 'ver_reporte_control_general_inventario_fisico',           ids: [99] },

        // ===== REPORTES ATE GAS =====
        { prop: 'ver_reporte_tiempos_proceso',              ids: [103] },
        { prop: 'ver_reporte_ate_gas_demanda',              ids: [105] },
        { prop: 'ver_reporte_ate_gas_status',               ids: [108] },
        { prop: 'ver_reporte_ate_gas_ingresos',             ids: [106] },
        { prop: 'ver_reporte_ate_gas_salidas',              ids: [107] },
        { prop: 'ver_reporte_ate_gas_produccion',           ids: [109] },

        // ===== OTROS REPORTES =====
        { prop: 'ver_reporte_embarques_listado',              ids: [70] },

        // ===== REPORTES CONTABILIDAD =====
        { prop: 'ver_reporte_contabilidad_facturas_notas_cobranza', ids: [72] },
        { prop: 'ver_reporte_contabilidad_list_trans',              ids: [73] },
        { prop: 'ver_reporte_contabilidad_facturas_concepto',       ids: [74] },
        { prop: 'ver_reporte_contabilidad_ordenes_pago_concepto',   ids: [75] },
        { prop: 'ver_reporte_contabilidad_montos_concepto',         ids: [76] },
        { prop: 'ver_reporte_contabilidad_ordenes_pago',            ids: [77] },
        { prop: 'ver_reporte_contabilidad_invoices',                ids: [78] },
        { prop: 'ver_reporte_contabilidad_estado_cuentas',          ids: [79] },
        { prop: 'ver_reporte_contabilidad_saldos',                  ids: [80] },
        { prop: 'ver_reporte_contabilidad_cobranza',                ids: [81] },
        { prop: 'ver_reporte_contabilidad_anticipos',               ids: [82] },
        { prop: 'ver_reporte_contabilidad_libro_ventas',            ids: [83] },
        
        // ===== DASHBOARD =====
        { prop: 'ver_dashboard_interno_almacen', ids: [85] },
        { prop: 'ver_dashboard_monitoreo_centros', ids: [86] },
        
        { prop: 'ver_dashboard_grafico_centros', ids: [87] },
        { prop: 'ver_dashboard_ate_gas', ids: [104] },
        
        
    ];
    
    /*
    public menus: Array<any>=[
        { id:'inicio', nombre: 'Inicio', icono: 'bi-house', submenus: [], marcado: false, link: 'inicio' },
        { id: 'almacen', nombre: 'Almacen', icono: 'bi-building', submenus: [{nombre: 'Ingresos', marcado: false, link: ''}], marcado: false, link: '' }
        
    ];
    */
    constructor(
        private _usuarioService: UsuarioService,
        private freshchat: FreshchatService
        ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        
        
        
        this.permisosModulo.forEach(({ prop, ids }) => {
            const tienePermiso =
              this.tokenDetalle.idtipousuario === 1 ||
              ids.some(id => this.tokenDetalle.permisos.some(p => p.idmodulo === id));

            if (tienePermiso) {
              (this as any)[prop] = true;   // activa la flag
            }
        });
        
        
        
        /*
        let indiceVerIngresos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 1);
        if(indiceVerIngresos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_ingresos=true;
        }
        let indiceVerMoverDividir = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 20);
        if(indiceVerMoverDividir>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_mover_dividir=true;
        }
        let indiceVerPedidos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 21);
        if (indiceVerPedidos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_pedidos=true;
        }
        let indiceVerSalidas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 22);
        if (indiceVerSalidas>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_salidas=true;
        }
        let indiceVerCambioCliente = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 23);
        if (indiceVerCambioCliente>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_cambio_cliente=true;
        }
        let indiceVerInventarioFisico = this.tokenDetalle.permisos.findIndex(x => (x.idmodulo == 43 || x.idmodulo == 44 || x.idmodulo == 45));
        if(indiceVerInventarioFisico>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_inventario_fisico=true;
        }
        let indiceVerTimbrado = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 26);
        if (indiceVerTimbrado>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_timbrado=true;
        }
        let indiceVerBitacora = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 46);
        if (indiceVerBitacora>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_bitacora=true;
        }
        let indiceVerCotizaciones = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 37);
        if (indiceVerCotizaciones>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_cotizaciones=true;
        }
        let indiceVerEmbarques = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 38);
        if (indiceVerEmbarques>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_embarques=true;
        }
        
        let indiceVerFacturas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 12);
        if (indiceVerFacturas>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_facturas=true;
        }
        let indiceVerNotasCobranza = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 13);
        if (indiceVerNotasCobranza>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_notas_cobranza=true;
        }
        let indiceVerOrdenesPago = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 14);
        if (indiceVerOrdenesPago>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_ordenes_pago=true;
        }
        let indiceVerPagosAgentesExterior = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 47);
        if (indiceVerPagosAgentesExterior>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_pagos_agentes_exterior=true;
        }
        let indiceVerPlanillas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 48);
        if (indiceVerPlanillas>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_planillas=true;
        }
        let indiceVerInvoices = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 49);
        if (indiceVerInvoices>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_invoices=true;
        }
        let indiceVerDevoluciones = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 50);
        if (indiceVerDevoluciones>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_devoluciones=true;
        }
        
        let indiceVerCobros = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 40);
        if (indiceVerCobros>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_cobros=true;
        }
        let indiceVerPagos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 41);
        if (indiceVerPagos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_pagos=true;
        }
        let indiceVerDevolucionSaldos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 42);
        if (indiceVerDevolucionSaldos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_devolucion_saldos=true;
        }
        
        let indiceVerEntidadesClientes = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 2);
        if (indiceVerEntidadesClientes>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_clientes=true;
        }
        let indiceVerEntidadesTransportistas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 3);
        if (indiceVerEntidadesTransportistas>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_transportistas=true;
        }
        let indiceVerEntidadesAgentesCarga = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 4);
        if (indiceVerEntidadesAgentesCarga>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_agentes_carga=true;
        }
        let indiceVerEntidadesProveedores = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 5);
        if (indiceVerEntidadesProveedores>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_proveedores=true;
        }
        let indiceVerEntidadesPrestadores = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 6);
        if (indiceVerEntidadesPrestadores>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_prestadores=true;
        }
        
        let indiceVerDivisas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 34);
        if (indiceVerDivisas>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_divisas=true;
        }
        let indiceVerTipoCambio = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 36);
        if (indiceVerTipoCambio>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_tipo_cambio=true;
        }
        
        let indiceVerAlmacenes = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 8);
        if (indiceVerAlmacenes>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_almacenes=true;
        }
        let indiceVerProductosCliente = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 9);
        if (indiceVerProductosCliente>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_productos_cliente=true;
        }
        
        let indiceVerEmpresa = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 32);
        if (indiceVerEmpresa>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_empresa=true;
        }
        let indiceVerConceptos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 7);
        if (indiceVerConceptos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_conceptos=true;
        }
        let indiceVerUsuarios = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 10);
        if (indiceVerUsuarios>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_usuarios=true;
        }
        let indiceVerCiudades = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 28);
        if (indiceVerCiudades>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_ciudades=true;
        }
        let indiceVerBancos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 29);
        if (indiceVerBancos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_bancos=true;
        }
        let indiceVerContemplaciones = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 30);
        if (indiceVerContemplaciones>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_contemplaciones=true;
        }
        let indiceVerConsideraciones = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 31);
        if (indiceVerConsideraciones>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_consideraciones=true;
        }
        
        let indiceVerReporteAlmacenInventario = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 53);
        if (indiceVerReporteAlmacenInventario>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_inventario=true;
        }
        let indiceVerReporteAlmacenIngresos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 54);
        if (indiceVerReporteAlmacenIngresos>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_ingresos=true;
        }
        let indiceVerReporteAlmacenSalidas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 55);
        if (indiceVerReporteAlmacenSalidas>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_salidas=true;
        }
        let indiceVerReporteAlmacenMovimiento = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 56);
        if (indiceVerReporteAlmacenMovimiento>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_movimiento=true;
        }
        let indiceVerReporteAlmacenPosiciones = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 57);
        if (indiceVerReporteAlmacenPosiciones>=0 || this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_posiciones=true;
        }
        let indiceVerReporteAlmacenDescarga = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 58);
        if (indiceVerReporteAlmacenDescarga >= 0 || this.tokenDetalle.idtipousuario === 1) {
            this.ver_reporte_almacen_descarga = true;
        }

        let indiceVerReporteAlmacenVencimiento = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 59
        );
        if (indiceVerReporteAlmacenVencimiento >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_vencimiento = true;
        }

        // NO CONFORME (idmodulo 60)
        let indiceVerReporteAlmacenNoConforme = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 60
        );
        if (indiceVerReporteAlmacenNoConforme >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_no_conforme = true;
        }

        // EGRESO POR TIENDAS (idmodulo 61)
        let indiceVerReporteAlmacenEgresoPorTiendas = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 61
        );
        if (indiceVerReporteAlmacenEgresoPorTiendas >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_egreso_por_tiendas = true;
        }

        // PRODUCTOS (idmodulo 62)
        let indiceVerReporteAlmacenProductos = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 62
        );
        if (indiceVerReporteAlmacenProductos >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_productos = true;
        }

        // LIQUIDACIÓN (idmodulo 63)
        let indiceVerReporteAlmacenLiquidacion = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 63
        );
        if (indiceVerReporteAlmacenLiquidacion >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_liquidacion = true;
        }

        // POSICIÓN POR DÍA (idmodulo 64)
        let indiceVerReporteAlmacenPosicionPorDia = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 64
        );
        if (indiceVerReporteAlmacenPosicionPorDia >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_posicion_por_dia = true;
        }

        // INVENTARIO VENCIMIENTO (idmodulo 65)
        let indiceVerReporteAlmacenInventarioVencimiento = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 65
        );
        if (indiceVerReporteAlmacenInventarioVencimiento >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_inventario_vencimiento = true;
        }

        // PEDIDOS (idmodulo 66)
        let indiceVerReporteAlmacenPedidos = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 66
        );
        if (indiceVerReporteAlmacenPedidos >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_pedidos = true;
        }

        // CAPACIDAD (idmodulo 67)
        let indiceVerReporteAlmacenCapacidad = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 67
        );
        if (indiceVerReporteAlmacenCapacidad >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_capacidad = true;
        }

        // INVENTARIO FÍSICO (idmodulo 68)
        let indiceVerReporteAlmacenInventarioFisico = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 68
        );
        if (indiceVerReporteAlmacenInventarioFisico >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_almacen_inventario_fisico = true;
        }
        
        let indiceVerReporteEmbarquesListado = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 70
        );
        if (indiceVerReporteEmbarquesListado >= 0 || this.tokenDetalle.idtipousuario === 1) {
            this.ver_reporte_embarques_listado = true;
        }
        
        let indiceVerReporteContabilidadFacturasNotasCobranza = this.tokenDetalle.permisos.findIndex(
            x => x.idmodulo === 72
        );
        if (indiceVerReporteContabilidadFacturasNotasCobranza >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_facturas_notas_cobranza = true;
        }

        // LIST TRANS (idmodulo 73)
        let indiceVerReporteContabilidadListTrans = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 73
        );
        if (indiceVerReporteContabilidadListTrans >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_list_trans = true;
        }

        // FACTURAS POR CONCEPTO (idmodulo 74)
        let indiceVerReporteContabilidadFacturasConcepto = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 74
        );
        if (indiceVerReporteContabilidadFacturasConcepto >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_facturas_concepto = true;
        }

        // ÓRDENES DE PAGO POR CONCEPTO (idmodulo 75)
        let indiceVerReporteContabilidadOrdenesPagoConcepto = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 75
        );
        if (indiceVerReporteContabilidadOrdenesPagoConcepto >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_ordenes_pago_concepto = true;
        }

        // MONTOS POR CONCEPTO (idmodulo 76)
        let indiceVerReporteContabilidadMontosConcepto = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 76
        );
        if (indiceVerReporteContabilidadMontosConcepto >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_montos_concepto = true;
        }

        // ÓRDENES DE PAGO (idmodulo 77)
        let indiceVerReporteContabilidadOrdenesPago = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 77
        );
        if (indiceVerReporteContabilidadOrdenesPago >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_ordenes_pago = true;
        }

        // INVOICES (idmodulo 78)
        let indiceVerReporteContabilidadInvoices = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 78
        );
        if (indiceVerReporteContabilidadInvoices >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_invoices = true;
        }

        // ESTADO DE CUENTAS (idmodulo 79)
        let indiceVerReporteContabilidadEstadoCuentas = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 79
        );
        if (indiceVerReporteContabilidadEstadoCuentas >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_estado_cuentas = true;
        }

        // SALDOS (idmodulo 80)
        let indiceVerReporteContabilidadSaldos = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 80
        );
        if (indiceVerReporteContabilidadSaldos >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_saldos = true;
        }

        // COBRANZA (idmodulo 81)
        let indiceVerReporteContabilidadCobranza = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 81
        );
        if (indiceVerReporteContabilidadCobranza >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_cobranza = true;
        }

        // ANTICIPOS (idmodulo 82)
        let indiceVerReporteContabilidadAnticipos = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 82
        );
        if (indiceVerReporteContabilidadAnticipos >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_anticipos = true;
        }

        // LIBRO DE VENTAS (idmodulo 83)
        let indiceVerReporteContabilidadLibroVentas = this.tokenDetalle.permisos.findIndex(
          x => x.idmodulo === 83
        );
        if (indiceVerReporteContabilidadLibroVentas >= 0 || this.tokenDetalle.idtipousuario === 1) {
          this.ver_reporte_contabilidad_libro_ventas = true;
        }
        */
        
        
    }

    ngOnInit(): void {
        this.parte_entera=Math.trunc(this.menu_item);
        //this.freshchat.loadFreshchat();
        this.freshchat.loadFreshserviceWidget();
        
        //console.log(this.tokenDetalle);
    }
    
    cambioClass(clase: boolean){
        //console.log(clase);
    }

}
