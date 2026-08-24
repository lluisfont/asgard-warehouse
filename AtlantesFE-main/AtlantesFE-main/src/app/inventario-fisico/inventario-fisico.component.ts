import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { Router } from '@angular/router';
import {GLOBAL} from './../global';
declare var $: any;

@Component({
    selector: 'app-inventario-fisico',
    templateUrl: './inventario-fisico.component.html',
    styleUrl: './inventario-fisico.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class InventarioFisicoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public inventariosFisico: Array<any>;
    public entidades: Array<any>;
    
    public visible_nuevo_recuento: boolean=false;
    
    public idcliente: number;
    public erroridcliente: boolean;
    
    public es_vehiculo: boolean=false;
    public carga_masiva: boolean=false;
    
    public urlFormatoIngreso: string;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    
    public idinventariofisico: number=0;
    public fecha_creacion: string='';
    public almacen: string='';
    public fecha_inicio: string;
    
    public mensajes_error: Array<any>=[];
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_inventario_fisico_gestion: boolean=false;
    public editar_inventario_fisico_gestion: boolean=false;
    public ver_inventario_fisico_conteo: boolean=false;
    public editar_inventario_fisico_conteo: boolean=false;
    public finalizar_inventario_fisico_conteo: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        this.urlFormatoIngreso=GLOBAL.urlFiles+'FormatoInventarioFisicoMasivoVehiculos.xlsx';
        
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_inventario_fisico_gestion=true;
            this.editar_inventario_fisico_gestion=true;
            this.ver_inventario_fisico_conteo=true;
            this.editar_inventario_fisico_conteo=true;
            this.finalizar_inventario_fisico_conteo=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 43);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_inventario_fisico_gestion=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_inventario_fisico_gestion=true;
                }
            }
            let indiceVerInventarioFisicoConteo = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 44);
            if(indiceVerInventarioFisicoConteo>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoConteo].lectura){
                    this.ver_inventario_fisico_conteo=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoConteo].escritura){
                    this.editar_inventario_fisico_conteo=true;
                }
            }
            let indiceFinalizarInventarioFisicoConteo = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 45);
            if(indiceFinalizarInventarioFisicoConteo>=0){
                if(this.tokenDetalle.permisos[indiceFinalizarInventarioFisicoConteo].lectura){
                    this.finalizar_inventario_fisico_conteo=true;
                }
            }
            
        }
        
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
        this.verInventarioFisico(0);
    }
    
    verInventarioFisico(filtro: number){
        this.inventariosFisico=[];
        this._almacenesService.verinventariosfisico(this.token, {}).subscribe(
            response =>{
                switch(filtro){
                    case 0:
                    default:
                        this.inventariosFisico=response.inventariosfisico;
                        break;
                    case 1:
                        this.inventariosFisico=response.inventariosfisico.filter(function (datos){
                            return datos.diferencia;
                        });
                        break;
                    case 2:
                        this.inventariosFisico=response.inventariosfisico.filter(function (datos){
                            return !datos.diferencia;
                        });
                        break;
                }
                this.inventariosFisico.forEach(
                    inventariosfisico => (inventariosfisico.fecha = new Date(inventariosfisico.fecha.replace(/-/g, '\/')))
                );
                
                this.inventariosFisico.forEach(o => {
                    const partes = [o.codigo_almacen, o.almacen]
                      .filter(v => v != null && String(v).trim() !== "");
                    o.codigo_y_almacen = partes.join(" ") || "";
                });

                console.log(this.inventariosFisico);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    preperarAgregar(){
        this.idcliente=null;
        this.erroridcliente=false;
        this.es_vehiculo=false;
        this.carga_masiva=false;
        this.visible_nuevo_recuento=true;
    }
    
    crearInventarioFisico(){
        this.mensajes_error=[];
        this.erroridcliente=false;
        if (this.idcliente==null || this.idcliente==0){
            this.erroridcliente=true;
        }
        
        if(!this.erroridcliente){
            if (this.carga_masiva){
                $('#ventanaLoading').modal('show');
                this._almacenesService.crearinventariofisicocargamasiva(this.token, this.idcliente, this.es_vehiculo, this.uploadFileInput).subscribe(
                    response =>{
                        console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            this.verInventarioFisico(0);
                            this.myInputVariable.nativeElement.value = "";
                            this.archivocargado = false;
                            this.visible_nuevo_recuento=false;
                            //$('#nuevoInventarioFisico').modal('hide');
                        }else{
                            this.toast_tipo="Error";
                            this.mensajes_error=response.mensajes_error;
                        }
                        $('#ventanaLoading').modal('hide');
                        $("#liveToast").toast('show');
                    },
                    error=>{
                        console.log(<any>error)
                        $('#ventanaLoading').modal('hide');
                    }
                );
            }else{
                let datosinventariofisico = {
                    idcliente: this.idcliente,
                    es_vehiculo: this.es_vehiculo
                };
                this._almacenesService.crearinventariofisico(this.token, datosinventariofisico).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.visible_nuevo_recuento=false;
                            //$('#nuevoInventarioFisico').modal('hide');
                            this.abrirDetalle(response.idinventariofisico);
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
    }
    
    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }
    
    abrirDetalle(idinventariofisico: number){
        this._router.navigate(['/inventario-fisico-detalle',idinventariofisico])
    }
    
    iniciarConteo(idinventariofisico: number){
        this._router.navigate(['/inventario-fisico-conteo',idinventariofisico])
    }
    
    verificarInicioConteo(idinventariofisico: number, fecha_inicio_conteo: string){
        if(!fecha_inicio_conteo){
            this.idinventariofisico=idinventariofisico;
            var indice = this.inventariosFisico.findIndex(x => x.idinventariofisico === this.idinventariofisico);
            this.fecha_creacion = this.inventariosFisico[indice].fecha;
            this.almacen = this.inventariosFisico[indice].almacen;
            this.fecha_inicio = formatDate(new Date(), 'dd/MM/yyy hh:mm', 'en');
            $('#confirmarIniciarConteo').modal('show');
        }else{
            this.iniciarConteo(idinventariofisico);
        }
    }
    
    inicializarConteo(){
        $('#confirmarIniciarConteo').modal('hide');
        $('#ventanaLoading').modal('show');
        this._almacenesService.inicializarinventariofisicoconteo(this.token, this.idinventariofisico).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    $('#ventanaLoading').modal('hide');
                    this.iniciarConteo(this.idinventariofisico);
                }else{
                    $('#ventanaLoading').modal('hide');
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    abrirDetalleNuevo(idinventariofisico:number){
        let newRelativeUrl = this._router.createUrlTree(["/inventario-fisico-detalle",idinventariofisico]);
        let baseUrl = window.location.href.replace(this._router.url, '');
        window.open(baseUrl + newRelativeUrl, '_blank');
    }
    
    downloadActa(idinventariofisico: number){
        this._almacenesService.downloadTomaInventarioFisico(this.token, idinventariofisico).subscribe(
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
    
    finalizarInventario(){
        $('#confirmarFinalizarInventarioFisico').modal('hide');
        //$('#ventanaLoading').modal('show');
        this._almacenesService.finalizarinventariofisico(this.token, this.idinventariofisico).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.verInventarioFisico(0);
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
            }
        );
    }
    
    eliminarInventario(){
        $('#confirmarEliminarInventario').modal('hide');
        //$('#ventanaLoading').modal('show');
        this._almacenesService.eliminarinventariofisico(this.token, this.idinventariofisico).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.verInventarioFisico(0);
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
            }
        );
    }
}
