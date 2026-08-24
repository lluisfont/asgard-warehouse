import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {PedidoModel} from '../models/pedido.model';
import {GLOBAL} from './../global';

declare var $: any;

@Component({
    selector: 'app-pedidos-detalle',
    templateUrl: './pedidos-detalle.component.html',
    styleUrls: ['./pedidos-detalle.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService]
})
export class PedidosDetalleComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public idpedido: string;
    public pedido: PedidoModel;
    public pedidotabla: Array<any>;

    public entidades: Array<any>;
    public clientebloqueado: boolean=false;
    public rubros: Array<any>;
    public usuarios: Array<any>;
    public sectores: Array<any>;

    public erroridcliente: boolean=false;
    public errorfecha: boolean=false;

    public urlFormatoPedido: string;

    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    public nombredocumentocargar: string;
    public existedocumento: boolean;

    public total_final: number;
    public cantidad_final: number;

    public indicepreparacioneliminar: number;
    
    public error_tiendas: boolean=false;

    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_pedidos: boolean=false;
    public editar_pedidos: boolean=false;
    public ver_salidas: boolean=false;
    public editar_salidas: boolean=false;
    
    public idsalida: string;
    public erroresfinalizar: Array<any>=[];
    public finalizando: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenService: AlmacenesService,
        private _entidadService: EntidadesService,
        private _route: ActivatedRoute,
        private _router: Router
        ){
            this._route.params.forEach((params: Params)=>{
                this.idpedido = params["idpedido"];
            });
            this.token = this._usuarioService.getToken();
            this.tokenDetalle = this._usuarioService.getTokenDetalle();
            if(this.tokenDetalle.idtipousuario==1){
                this.ver_pedidos=true;
                this.editar_pedidos=true;
                this.ver_salidas=true;
                this.editar_salidas=true;
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
                let indiceVerSalidas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 22);
                if (indiceVerSalidas>=0){
                    if (this.tokenDetalle.permisos[indiceVerSalidas].lectura){
                        this.ver_salidas=true;
                    }
                    if (this.tokenDetalle.permisos[indiceVerSalidas].escritura){
                        this.editar_salidas=true;
                    }
                }
            }
            this.urlFormatoPedido=GLOBAL.urlFiles+'FormatoPreparacionPedidos.xlsx';
            //this.ubicaciondocumentos = 'almacen/adjuntos_ingresos/' + this.idingreso;
        }

    ngOnInit(): void {
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                //console.log(response.entidades);

                //this.entidades=response.entidades;
                this.entidades = response.clientes;
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._usuarioService.usuarios(this.token).subscribe(
            response =>{
                //console.log(response.entidades);

                //this.entidades=response.entidades;
                this.usuarios = response.usuarios.filter(function (us) {
                    return (us.activo && us.almacen);
                });
                //console.log(this.usuarios);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.verPedido();
    }

    verPedido(){
        this.pedidotabla=[];
        this._almacenService.verpedido(this.token, this.idpedido).subscribe(
            response =>{
                this.pedido=response.pedido;
                
                this.pedido.pedidodetalletienda.sort((a, b) => a.idpedidotienda2 - b.idpedidotienda2);
                this.pedidotabla = this.pedido.pedidotabla;
                /*
                for(let dp=0; dp<this.pedido.pedidodetalle.length; dp++){
                    if(this.pedido.pedidodetalle[dp].pedidodisponibilidad.length>0){
                        let total_disponibilidad=0;
                        let total_item=0;
                        for(let pdd=0;pdd<this.pedido.pedidodetalle[dp].pedidodisponibilidad.length;pdd++){
                            this.pedidotabla.push({
                                idpedidodetalle: this.pedido.pedidodetalle[dp].idpedidodetalle,
                                codigo: this.pedido.pedidodetalle[dp].codigo,
                                descripcion: this.pedido.pedidodetalle[dp].descripcion,
                                serie: this.pedido.pedidodetalle[dp].serie,
                                unidadmedida: this.pedido.pedidodetalle[dp].unidadmedida,
                                idpedidodisponibilidad: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].idpedidodisponibilidad,
                                ubicacion: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].ubicacionalmacen,
                                cantidad_disponible: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].cantidad,
                                saldo_disponible: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].cantidad,
                                ppt: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].ppt,
                                fechavencimiento: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].fechavencimiento,
                                lote: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].lote,
                                diasavencer: this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].diasavencer
                            });
                            total_disponibilidad=total_disponibilidad+this.pedido.pedidodetalle[dp].pedidodisponibilidad[pdd].cantidad;
                        }
                        
                        for (let pdt = 0; pdt < this.pedido.pedidodetalletienda.length; pdt++){
                            if (this.pedido.pedidodetalle[dp].idpedidodetalle == this.pedido.pedidodetalletienda[pdt].idpedidodetalle){
                                total_item = total_item + this.pedido.pedidodetalletienda[pdt].cantidad;
                            }
                        }
                        
                        if (total_disponibilidad < total_item){
                            this.pedidotabla.push({
                                idpedidodetalle: this.pedido.pedidodetalle[dp].idpedidodetalle,
                                codigo: this.pedido.pedidodetalle[dp].codigo,
                                descripcion: this.pedido.pedidodetalle[dp].descripcion,
                                serie: this.pedido.pedidodetalle[dp].serie,
                                unidadmedida: this.pedido.pedidodetalle[dp].unidadmedida,
                                idpedidodisponibilidad: null,
                                ubicacion: '',
                                cantidad_disponible: null,
                                saldo_disponible: 0,
                                ppt: '',
                                fechavencimiento: '',
                                lote: '',
                                diasavencer: null
                            });
                        }
                        
                        
                    }else{
                        this.pedidotabla.push({
                            idpedidodetalle: this.pedido.pedidodetalle[dp].idpedidodetalle,
                            codigo: this.pedido.pedidodetalle[dp].codigo,
                            descripcion: this.pedido.pedidodetalle[dp].descripcion,
                            serie: this.pedido.pedidodetalle[dp].serie,
                            unidadmedida: this.pedido.pedidodetalle[dp].unidadmedida,
                            idpedidodisponibilidad: null,
                            ubicacion: '',
                            cantidad_disponible: null,
                            saldo_disponible: 0,
                            ppt: '',
                            fechavencimiento: '',
                            lote: '',
                            diasavencer: null
                        });
                    }
                }
                
                
                //this.pedidotabla = this.pedido.pedidodetalle;
                for (let tt = 0; tt < this.pedido.pedidotienda.length; tt++){
                    this.pedidotabla.forEach(object => {
                        object['t_' + this.pedido.pedidotienda[tt].idpedidotienda] = null;
                    });
                }
                
                let idpedidodetalle_aux: number=null;
                for (let pp = 0; pp < this.pedidotabla.length; pp++){
                    if(idpedidodetalle_aux!=this.pedidotabla[pp].idpedidodetalle){
                        for (let dd = 0; dd < this.pedido.pedidodetalletienda.length; dd++){
                            if (this.pedidotabla[pp].idpedidodetalle == this.pedido.pedidodetalletienda[dd].idpedidodetalle){
                                this.pedidotabla[pp]['t_' + this.pedido.pedidodetalletienda[dd].idpedidotienda] = this.pedido.pedidodetalletienda[dd].cantidad;
                            }
                        }
                    }
                    idpedidodetalle_aux=this.pedidotabla[pp].idpedidodetalle;
                    
                }
                
                for (let dd = 0; dd < this.pedidotabla.length; dd++){
                    if (this.pedidotabla[dd].idpedidodisponibilidad!=null){
                        for (let dt = 0; dt < this.pedido.pedidotienda.length; dt++){
                            if(this.pedidotabla[dd]['t_'+this.pedido.pedidotienda[dt].idpedidotienda]>0){
                                if (this.pedidotabla[dd].saldo_disponible>=this.pedidotabla[dd]['t_'+this.pedido.pedidotienda[dt].idpedidotienda]){
                                    this.pedidotabla[dd].saldo_disponible=this.pedidotabla[dd].saldo_disponible-this.pedidotabla[dd]['t_'+this.pedido.pedidotienda[dt].idpedidotienda];
                                }else{
                                    let faltante=this.pedidotabla[dd]['t_'+this.pedido.pedidotienda[dt].idpedidotienda]-this.pedidotabla[dd].saldo_disponible;
                                    this.pedidotabla[dd]['t_'+this.pedido.pedidotienda[dt].idpedidotienda]=this.pedidotabla[dd].saldo_disponible;
                                    if((dd+1)<=(this.pedidotabla.length-1)){
                                        if(this.pedidotabla[dd].idpedidodetalle==this.pedidotabla[dd+1].idpedidodetalle){
                                            this.pedidotabla[dd+1]['t_'+this.pedido.pedidotienda[dt].idpedidotienda]=faltante;
                                        }
                                    }
                                    this.pedidotabla[dd].saldo_disponible=0;
                                }
                            }

                        }
                    }
                }
                */
                
                this.total_final=0;
                this.pedidotabla.forEach(object => {
                    let total=0;
                    for (let tt = 0; tt < this.pedido.pedidotienda.length; tt++){
                        if (typeof object['t_'+this.pedido.pedidotienda[tt].idpedidotienda]=='number'){
                            total=total+object['t_'+this.pedido.pedidotienda[tt].idpedidotienda];
                        }
                    }
                    object.total = total;
                    this.total_final=this.total_final+total;
                });
                
                this.sectores=[];
                for (let pp = 0; pp < this.pedido.pedidodetalle.length; pp++){
                    if(this.pedido.pedidodetalle[pp].pedidodisponibilidad.length>=1){
                        let indiceSector = this.sectores.findIndex(x => x.sector === this.pedido.pedidodetalle[pp].pedidodisponibilidad[0].sector);
                        if(indiceSector==-1){
                            this.sectores.push({
                                sector: this.pedido.pedidodetalle[pp].pedidodisponibilidad[0].sector
                            });
                        }
                    }
                }
                
                for (let pt = 0; pt < this.pedido.pedidotienda.length; pt++){
                    if (this.pedido.pedidotienda[pt].total_disponible_tienda>0){
                        this.pedido.pedidotienda[pt].marcado=true;
                    }else{
                        this.pedido.pedidotienda[pt].marcado=false;
                    }
                }
                
                if (this.pedido.pedidodetalle.length>0){
                    this.clientebloqueado=true;
                }else{
                    if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
                        this.clientebloqueado=true;
                    }else{
                        this.clientebloqueado=false;
                    }
                }
                
                const codigos_unicos = [...new Set(this.pedidotabla.map(item => item.codigo))];
                this.cantidad_final=codigos_unicos.length;
                
                this._datomaestroService.centros_rubro(this.token, this.pedido.idcliente).subscribe(
                    response_rubro =>{
                        console.log(response_rubro);
                        this.rubros = [...new Set(response_rubro.centros_rubro.map(item => item.rubro))];
                        console.log(this.rubros);
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
                

                this.getCantidadBultos();
                
                console.log(this.pedidotabla);
                console.log(this.pedido);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    verificarCliente(){
        this.erroridcliente=false;
        if(this.pedido.idcliente==null){
            this.pedido.cliente='';
        }else{
            let indiceCliente = this.entidades.findIndex(x => x.id === this.pedido.idcliente);
            this.pedido.cliente = this.entidades[indiceCliente].entidad;
        }
    }

    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }

    cargarMasivamente(){
        this._almacenService.pedidocarga(this.token, this.idpedido, this.uploadFileInput).subscribe(
            response =>{
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }

                $("#ventanaCargaMasiva").modal('hide');
                $("#liveToast").toast('show');

                this.verPedido();
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    getDisponibilidad(){
        $('#ventanaLoading').modal('show');
        this._almacenService.pedidodisponibilidad(this.token, this.idpedido).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                $('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');

                this.verPedido();
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    eliminarTodos(){
        this._almacenService.eliminardatospedido(this.token, this.idpedido).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                $("#liveToast").toast('show');

                $("#confirmarEliminarTodos").modal('hide');

                this.verPedido();
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    generarExcel(){
        this._almacenService.downloadActaPedido(this.token, this.idpedido).subscribe(
            response =>{
                //console.log(response);
                if(response.codigo==200){
                    const byteCharacters = atob(response.data);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: response.pathinfo});
                    var url = window.URL.createObjectURL(blob);
                    const downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.target = "_blank";
                    downloadLink.click();
                }else{
                    this.toast_mensaje="Ocurrio un error, intente mas tarde";
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    agregarPreparacion(){
        this.pedido.preparacion.push({
            idpedidopreparacion: null,
            idpreparador: null,
            preparador: '',
            sector: [],
            texto_sector: '',
            bultos: 0,
            hora_inicio: null,
            hora_fin: null,
            demora: 0,
            conforme: false,
            conforme2: false,
            conforme3: false,
            notas: ''
        });
    }

    getNombreUsuario(indice: number){
        let indiceUsuario = this.usuarios.findIndex(x => x.idusuario === this.pedido.preparacion[indice].idpreparador);
        if(indiceUsuario>=0){
            this.pedido.preparacion[indice].preparador = this.usuarios[indiceUsuario].nombre;
        }else{
            this.pedido.preparacion[indice].preparador='';
        }
    }

    cambioSector(indice: number){
        //let texto_sector = this.pedido.preparacion[indice].sector.map((name) => `${name.sector}`).join(",");
        this.pedido.preparacion[indice].texto_sector=this.pedido.preparacion[indice].sector.map((name) => `${name.sector}`).join(",");
        this.getCantidadBultos();
    }

    getCantidadBultos(){
        for (let pp = 0; pp < this.pedido.preparacion.length; pp++){
            let totalBultos=0;
            for (let ss = 0; ss < this.pedido.preparacion[pp].sector.length;ss++){
                for (let dd = 0; dd < this.pedido.pedidodetalle.length;dd++){
                    if(this.pedido.pedidodetalle[dd].pedidodisponibilidad.length>=1){
                        if (this.pedido.preparacion[pp].sector[ss].sector == this.pedido.pedidodetalle[dd].pedidodisponibilidad[0].sector){
                            totalBultos = totalBultos + this.pedido.pedidodetalle[dd].total;
                        }
                    }
                }
            }
            this.pedido.preparacion[pp].bultos=totalBultos;
        }
    }

    eliminarPreparacion(){
        this.pedido.preparacion.splice(this.indicepreparacioneliminar, 1);
        $('#confirmarEliminarPreparacion').modal('hide');
    }

    savePedido(){
        this.erroridcliente=false;
        if(this.pedido.idcliente==null){
            this.erroridcliente=true;
        }
        this.errorfecha=false;
        if (this.pedido.fecha==''){
            this.errorfecha=true;
        }
        if (!this.erroridcliente && !this.errorfecha){
            let datosguardar;
            datosguardar={
                idcliente: this.pedido.idcliente,
                fecha: this.pedido.fecha,
                rubro: this.pedido.rubro,
                idusuario_revisado: this.pedido.idusuario_revisado,
                nota_adicional: this.pedido.nota_adicional,
                preparacion: this.pedido.preparacion
            };

            //console.log(datosguardar);

            this._almacenService.guardarpedido(this.token, this.idpedido, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.verPedido();
                    }else{
                        this.toast_tipo="Error";
                    }

                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );

        }
    }

    generarEgresos(){
        
        let tiendas=[];
        for (let pt = 0; pt < this.pedido.pedidotienda.length; pt++){
            if (this.pedido.pedidotienda[pt].marcado){
                tiendas.push(this.pedido.pedidotienda[pt].idpedidotienda);
            }
        }
        
        this.error_tiendas=false;
        if(tiendas.length==0){
            this.error_tiendas=true;
        }
        
        if(!this.error_tiendas){
            $('#ventanaLoading').modal('show');
            this._almacenService.crearsalidapedido(this.token, this.idpedido, tiendas).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                    }else{
                        this.toast_tipo="Error";
                    }
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');

                    this.verPedido();
                },
                error=>{
                    $("#liveToast").toast('show');
                    console.log(<any>error)
                }
            );
        }
        
            
    }
    
    abrirDetalle(idsalida: string){
        this._router.navigate(['/salidas-detalle',idsalida])
    }
    
    abrirDetalleNuevo(idsalida: string){
        let newRelativeUrl = this._router.createUrlTree(["/salidas-detalle",idsalida]);
        let baseUrl = window.location.href.replace(this._router.url, '');

        window.open(baseUrl + newRelativeUrl, '_blank');
    }
    
    finalizarSalida(){
        this.erroresfinalizar=[];
        this.finalizando=true;
        this._almacenService.finalizarSalida(this.token, this.idsalida).subscribe(
            response =>{
                //console.log(response);

                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    let indiceSalida = this.pedido.salidas_automaticas.findIndex(x => x.idsalida === this.idsalida);
                    if(indiceSalida>=0){
                        this.pedido.salidas_automaticas[indiceSalida].finalizado=true;
                    }
                }else{
                    this.toast_tipo="Error";
                    this.erroresfinalizar=response.mensajeserror;
                }
                $('#confirmarFinalizar').modal('hide');
                $("#liveToast").toast('show');
                this.finalizando=false;

            },
            error=>{
                console.log(<any>error);
                this.finalizando=false;
            }
        );
    }
    
    verActaSalida(idsalida: string, unidad_salida: number){
        this._almacenService.downloadActaSalida(this.token, idsalida, unidad_salida).subscribe(
            response =>{
                if(response.codigo==200){
                    const byteCharacters = atob(response.data);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: response.pathinfo});
                    var url = window.URL.createObjectURL(blob);
                    const downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.target = "_blank";
                    downloadLink.click();
                }else{
                    this.toast_mensaje="Ocurrio un error, intente mas tarde";
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
