import { Component, OnInit, ViewChild, ElementRef, Input  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { Router } from '@angular/router';
import {GLOBAL} from './../global';
declare var $: any;

@Component({
    selector: 'app-pedidos',
    templateUrl: './pedidos.component.html',
    styleUrls: ['./pedidos.component.css'],
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class PedidosComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public pedidos: Array<any>;
    public entidades: Array<any>;
    
    public idcliente: number;
    public erroridcliente: boolean;
    public no_pedido: string;
    public fecha_entrega: string;
    
    public pedidos_agrupar: Array<any>;
    public error_pedidos_agrupar: boolean;
    
    public idpedido_eliminar: number=null;
    
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public cols: Array<any>;
    public _selectedColumns: Array<any>;
    
    public ver_pedidos: boolean=false;
    public editar_pedidos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_pedidos=true;
            this.editar_pedidos=true;
        }else{
            let indiceVerPedidos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 21);
            if (indiceVerPedidos>=0){
                if (this.tokenDetalle.permisos[indiceVerPedidos].lectura){
                    this.ver_pedidos=true;
                }
                if (this.tokenDetalle.permisos[indiceVerPedidos].escritura){
                    this.editar_pedidos=true;
                }
            }
        }
        
        this._datomaestroService.columnas_pedido(this.token).subscribe(
            response =>{
                this.cols=response.columnas_pedido;
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._usuarioService.verusuario(this.token,this.tokenDetalle["idusuario"]).subscribe(
            response =>{
                this._selectedColumns=response.usuario.columnas_pedido;
                if(this._selectedColumns.length==0){
                    this._selectedColumns = this.cols;
                }
                //console.log(this._selectedColumns);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
    }

    ngOnInit(): void {
        this._entidadesService.vercliente(this.token).subscribe(
            response =>{
                this.entidades = response.clientes;
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.verpedidos();
    }
    
    @Input() get selectedColumns(): Array<any> {
        //console.log(this._selectedColumns);
        return this._selectedColumns;
    }

    set selectedColumns(val: Array<any>) {
        //console.log("entra a set");
        //console.log(val);
        //restore original order
        //this._selectedColumns = this.cols.filter(col => val.includes(col));
        this._selectedColumns = val;
        //console.log(this._selectedColumns);
        this._usuarioService.savecolumnas_pedido(this.token, this._selectedColumns, this.tokenDetalle["idusuario"]).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    //this.getInventario();
                }else{
                    this.toast_tipo="Error";
                }
                //$("#ventanaDetalleItem").modal('hide');
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        //console.log(this._selectedColumns);
    }
    
    verpedidos(){
        this.pedidos=[];
        this._almacenesService.verpedidos(this.token, this.tokenDetalle.idcliente_almacen).subscribe(
            response =>{
                this.pedidos=response.pedidos;
                this.pedidos.forEach(pedidos => (
                    pedidos.fecha = new Date(pedidos.fecha.replace(/-/g, '\/'))
                ));
                
                this.pedidos.filter(pedidos => pedidos.fecha_entrega!=null).forEach(
                    pedidos => (pedidos.fecha_entrega = new Date(pedidos.fecha_entrega.replace(/-/g, '\/')))
                );
                
                this.pedidos.forEach(pedidos => (
                    pedidos.tiendas = pedidos.pedidotiendas.map(e => e.tienda).join(",")
                ));
                this.pedidos.forEach(pedidos => (
                    pedidos.salida = pedidos.salidas.map(e => e.salida).join(",")
                ));
                
                this.pedidos.forEach(pedidos => (
                    pedidos.finalizado = pedidos.salidas.map(e => e.finalizado).join("")
                ));
                //this.ingresos=response.ingresos;
                console.log(this.pedidos);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    preperarAgregar(){
        if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
            this.idcliente=this.tokenDetalle.idcliente_almacen;
        }else{
            this.idcliente=null;
        }
        this.erroridcliente=false;
        this.no_pedido='';
        this.fecha_entrega = this._usuarioService.getCurrentDateFilterValue();
    }
    
    preperarAgrupado(){
        this.pedidos_agrupar=[];
        if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
            this.idcliente=this.tokenDetalle.idcliente_almacen;
            this.verPedidosCliente();
        }else{
            this.idcliente=null;
        }
        this.erroridcliente=false;
        this.fecha_entrega = this._usuarioService.getCurrentDateFilterValue();
    }
    
    verPedidosCliente(){
        this.erroridcliente=false;
        
        let idcliente_agrupar = this.idcliente;
        this.pedidos_agrupar = this.pedidos.filter(function(item){
            return (item.idcliente==idcliente_agrupar && item.tipo_pedido==0);
        });
        
        this.pedidos_agrupar.forEach(
            pedidos_agrupar => (pedidos_agrupar.marcado = false)
        );
        
        
        console.log(this.pedidos_agrupar);
    }
    
    marcarPedido(indice: number){
        this.pedidos_agrupar[indice].marcado=!this.pedidos_agrupar[indice].marcado;
        this.error_pedidos_agrupar=false;
        //console.log(this.pedidos_agrupar);
    }
    
    crearPedido(){
        this.erroridcliente=false;
        if (this.idcliente==null || this.idcliente==0){
            this.erroridcliente=true;
        }
        
        if(!this.erroridcliente){
            let datospedido = {
                idcliente: this.idcliente,
                no_pedido: this.no_pedido,
                fecha_entrega: this.fecha_entrega
            };
            this._almacenesService.crearpedido(this.token, datospedido).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        $('#nuevoPedido').modal('hide');
                        this.abrirDetalle(response.idpedido);
                    }else{
                        this.toast_tipo="Error";
                        $("#liveToast").toast('show');
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }
    
    agruparPedido(){
        var pedidos: Array<any>;
        
        this.erroridcliente=false;
        if (this.idcliente==null || this.idcliente==0){
            this.erroridcliente=true;
        }else{
            let pedidos_marcados = this.pedidos_agrupar.filter(function(item){
                return (item.marcado);
            });

            this.error_pedidos_agrupar=false;
            if (pedidos_marcados.length==0){
                this.error_pedidos_agrupar=true;
            }

            pedidos = pedidos_marcados.map(item => item.idpedido);
        }
        
        
            
        
        
        if(!this.erroridcliente && !this.error_pedidos_agrupar){
            let datospedidoagrupado = {idcliente: this.idcliente, pedidos: pedidos, fecha_entrega: this.fecha_entrega};
            
            //console.log(pedidos);
            
            this._almacenesService.agruparpedido(this.token, datospedidoagrupado).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        $('#nuevoPedidoAgrupado').modal('hide');
                        this.abrirDetalle(response.idpedido);
                    }else{
                        this.toast_tipo="Error";
                        $("#liveToast").toast('show');
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
            
        }
        
    }
    
    eliminarPedido(){
        this._almacenesService.eliminarpedido(this.token, this.idpedido_eliminar).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                $("#liveToast").toast('show');
                
                $("#confirmarEliminarPedido").modal('hide');
                
                this.verpedidos();
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    abrirDetalle(idpedido: number){
        this._router.navigate(['/pedidos-detalle',idpedido])
    }
    
    abrirDetalleNuevo(idpedido: number){
        let newRelativeUrl = this._router.createUrlTree(["/pedidos-detalle",idpedido]);
        let baseUrl = window.location.href.replace(this._router.url, '');

        window.open(baseUrl + newRelativeUrl, '_blank');
    }

}
