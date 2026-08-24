import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { Router } from '@angular/router';
import {GLOBAL} from './../global';
declare var $: any;

@Component({
    selector: 'app-salidas',
    templateUrl: './salidas.component.html',
    styleUrls: ['./salidas.component.css'],
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class SalidasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public salidas: Array<any>;
    public entidades: Array<any>;
    
    public idcliente: string;
    public erroridcliente: boolean;
    public es_vehiculo: boolean;
    public movimiento: boolean;
    public es_no_conf: boolean=false;
    
    
    public cargamasiva: boolean=false;
    public urlFormatoSalida: string;
    
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    public mensajes_error: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_salidas: boolean=false;
    public editar_salidas: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadService: EntidadesService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_salidas=true;
            this.editar_salidas=true;
        }else{
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
        this.urlFormatoSalida=GLOBAL.urlFiles+'FormatoSalidaMasiva.xlsx';
        this.mensajes_error=[];
    }

    ngOnInit(): void {
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                this.entidades=response.clientes;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.versalidas(0);
    }
    
    versalidas(filtro: number){
        this._almacenesService.versalidas(this.token, this.tokenDetalle.idcliente_almacen).subscribe(
            response =>{
                switch(filtro){
                    case 0:
                    default:
                        this.salidas=response.salidas;
                        break;
                    case 1:
                        this.salidas=response.salidas.filter(function (datos){
                            return datos.finalizado;
                        });
                        break;
                    case 2:
                        this.salidas=response.salidas.filter(function (datos){
                            return !datos.finalizado;
                        });
                        break;
                }
                
                this.salidas.forEach(
                    salidas => (salidas.fecha = new Date(salidas.fecha.replace(/-/g, '\/')))
                );
                
                //this.ingresos=response.ingresos;
                //console.log(this.salidas);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.p=1;
    }
    
    preperarAgregar(){
        if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
            this.idcliente=this.tokenDetalle.idcliente_almacen;
        }else{
            this.idcliente=null;
        }
        //this.idcliente=null;
        this.erroridcliente=false;
        this.es_vehiculo=false;
        this.movimiento=false;
        this.es_no_conf=false;
        this.cargamasiva=false;
        //this.myInputVariable.nativeElement.value = "";
        this.archivocargado = false;
        this.mensajes_error=[];
        
    }
    
    cambioEsVehiculo(){
        this.es_vehiculo=!this.es_vehiculo;
        if (!this.es_vehiculo){
            this.movimiento=false;
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
    
    crearSalida(){
        //$("#ventanaCargaMasiva").modal('hide');
        //
        let error=false
        this.erroridcliente=false;
        if (this.idcliente==null){
            this.erroridcliente=true;
            error=true;
        }
        
        this.errorarchivo=false;
        if (!this.archivocargado && this.cargamasiva){
            this.errorarchivo=true;
            error=true;
        }
        
        
        
        if(!error){
            $('#nuevaSalida').modal('hide');
            $('#ventanaLoading').modal('show');
            this._almacenesService.crearsalida(this.token, this.idcliente, this.es_vehiculo, this.movimiento, this.es_no_conf, this.cargamasiva, this.uploadFileInput).subscribe(
                response =>{
                    
                    console.log(response);
                    $('#ventanaLoading').modal('hide');
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        if (!this.cargamasiva){
                            this.abrirDetalle(response.idsalida);
                        }else{
                            this.versalidas(0);
                        }
                        //this.ingreso.detalle=response.detalle;
                    }else{
                        this.mensajes_error=response.mensajes_error;
                        if (this.mensajes_error.length>0){
                            $('#nuevaSalida').modal('show');
                        }
                    
                        this.toast_tipo="Error";
                    }
                    
                },
                error=>{
                    console.log(<any>error)
                }
            );
            
            $("#liveToast").toast('show');
        }
        
    }
    
    abrirDetalle(idsalida: number){
        this._router.navigate(['/salidas-detalle',idsalida])
    }
    
    abrirDetalleNuevo(idsalida:number){
        let newRelativeUrl = this._router.createUrlTree(["/salidas-detalle",idsalida]);
        let baseUrl = window.location.href.replace(this._router.url, '');

        window.open(baseUrl + newRelativeUrl, '_blank');
        
        //alert("abre en nueva pestaña " + idembarque);
        //event.stopPropagation();
    }

}
