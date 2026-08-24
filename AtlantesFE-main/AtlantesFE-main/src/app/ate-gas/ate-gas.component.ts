import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';

import {GLOBAL} from './../global';

declare var $: any;

@Component({
    selector: 'app-ate-gas',
    templateUrl: './ate-gas.component.html',
    styleUrl: './ate-gas.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class AteGasComponent {
    public token: string;
    public tokenDetalle: any;

    public filtro_chasis: string='';
    
    public entidades: Array<any>;
    public ategas: Array<any>=[];
    public ategas_filtrado: Array<any>=[];
    public total_vin: number=0;
    public total_pendiente: number=0;
    public total_recepcion: number=0;
    
    public error_idalmacen: boolean=false;
    
    public idate_gas_recepcion: number=null;
    public chasis_recepcion: string='';
    public marca_recepcion: string='';
    public modelo_recepcion: string='';
    public color_recepcion: string='';
    public configuracion_recepcion: string='';
    public fecha_carga_recepcion: Date=null;
    public estado_vehiculo: string='';
    public error_estado_vehiculo: boolean=false;
    public observaciones: string='';
    public error_observaciones: boolean=false;
    
    public idcliente: number;
    public erroridcliente: boolean;
    public visible_carga: boolean=false
    
    public mensajes_error: Array<any>=[];
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_ate_gas: boolean=false;
    public editar_ate_gas: boolean=false;
    
    public urlFormatoAteGas: string;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    
    public visible_recepcion: boolean=false;

    public visible_ubicacion: boolean=false;
    public idate_gas_ubicacion: number=null;
    public almacen_ubicaciones: Array<any>=[];
    public idalmacendetalle: number=null;
    public error_idalmacendetalle: boolean=false;

    public idate_gas_eliminar: number=null;

    public visible_edicion: boolean=false;
    public idate_gas_editar: number=null;
    public configuracion: string='';
    public tipo_tanque: string='';
    
    public cargando: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        //private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        this.urlFormatoAteGas=GLOBAL.urlFiles+'FormatoAteGasMasivo.xlsx';
        
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_ate_gas=true;
            this.editar_ate_gas=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 92);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_ate_gas=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_ate_gas=true;
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
        this.getAteGas();
    }
    
    getAteGas(){
        this.ategas=[];
        this._almacenesService.verategas(this.token).subscribe(
            response =>{
                this.ategas = response.ategas;
                this.ategas.forEach(
                    agas => (agas.created_at = new Date(agas.created_at.replace(/-/g, '\/')))
                );
                this.filtrarAteGas();
                
                //console.log(this.ategas);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    filtrarAteGas(){
        this.ategas_filtrado=[];
        if(this.filtro_chasis==''){
            this.ategas_filtrado = this.ategas;
        }else{
            this.ategas_filtrado = this.ategas.filter(product =>
                (product.chasis ?? "").toLowerCase().includes(this.filtro_chasis.toLowerCase())
            );
        }

        this.total_vin = this.ategas_filtrado.length;
        this.total_pendiente = this.ategas_filtrado.filter(item => item.fecha_recepcion == null).length;
        this.total_recepcion = this.total_vin-this.total_pendiente;

    }

    getDetalleAlmacen(idalmacen: number){
        this._almacenesService.veralmacenubicaciones(this.token, idalmacen).subscribe(
            response =>{
                let ubicaciones=response.almacen_ubicaciones;

                this.almacen_ubicaciones = ubicaciones.filter(pr => {
                    if(!pr.ubicacion_unica){
                        return true;
                    }else{
                        if(pr.idalmacendetalle==this.idalmacendetalle){
                            return true;
                        }else{
                            if(pr.items.length==0){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    }
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    preperarCarga(){
        this.idcliente=null;
        this.erroridcliente=false;
        this.visible_carga=true;
    }
    
    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }
    
    cargarAteGas(){
        let error=false;
        this.mensajes_error=[];
        this.erroridcliente=false;
        if (this.idcliente==null || this.idcliente==0){
            this.erroridcliente=true;
            error=true;
        }
        
        if (!this.tokenDetalle.idalmacen){
            error=true;
            this.toast_tipo="Error";
            this.toast_mensaje="No esta seleccionado ningun almacen";
            $("#liveToast").toast('show');
        }
        
        if(!error){
            this.cargando=true;
            this._almacenesService.crearategascargamasiva(this.token, this.idcliente, this.uploadFileInput).subscribe(
                response =>{
                    console.log(response);
                    this.cargando=false;
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.getAteGas();
                        this.myInputVariable.nativeElement.value = "";
                        this.archivocargado = false;
                        this.visible_carga=false;
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
                    this.cargando=false;
                }
            );
        }
    }
    
    abrirRecepcion(idate_gas: number){
        var indice = this.ategas_filtrado.findIndex(x => x.idate_gas == idate_gas);
        if(indice>=0){
            this.idate_gas_recepcion = idate_gas;
            this.chasis_recepcion = this.ategas_filtrado[indice].chasis;
            this.marca_recepcion = this.ategas_filtrado[indice].marca;
            this.modelo_recepcion = this.ategas_filtrado[indice].modelo;
            this.color_recepcion = this.ategas_filtrado[indice].color;
            this.configuracion_recepcion = this.ategas_filtrado[indice].configuracion;
            this.fecha_carga_recepcion = this.ategas_filtrado[indice].created_at;
            this.estado_vehiculo='';
            this.observaciones='';
        }
        this.visible_recepcion=true;
    }
    
    recepcionarVehiculo(){
        let error=false;
        /*
        if (this.estado_vehiculo==''){
            error=true;
            this.error_estado_vehiculo=true;
        }
        if (this.observaciones==''){
            error=true;
            this.error_observaciones=true;
        }
        */
        if(!error){
            let params={
                estado_vehiculo: this.estado_vehiculo,
                observaciones: this.observaciones
            }
            this.cargando=true;
            this._almacenesService.recepcionarategas(this.token, this.idate_gas_recepcion, params).subscribe(
                response =>{
                    //console.log(response);
                    this.cargando=false;
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.getAteGas();
                        this.visible_recepcion=false;
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
                    this.cargando=false;
                }
            );
        }
    }

    abrirUbicacion(idate_gas: number){
        var indice = this.ategas_filtrado.findIndex(x => x.idate_gas == idate_gas);
        if(indice>=0){
            this.idate_gas_ubicacion=idate_gas;
            this.chasis_recepcion=this.ategas_filtrado[indice].chasis;
            this.marca_recepcion=this.ategas_filtrado[indice].marca;

            this.getDetalleAlmacen(this.ategas_filtrado[indice].idalmacen);
            this.idalmacendetalle=this.ategas_filtrado[indice].idalmacendetalle;

            this.visible_ubicacion=true;
        }
    }

    ubicarVehiculo(){
        let error=false;
        if(!this.idalmacendetalle){
            error=true;
            this.error_idalmacendetalle=true;
        }

        if(!error){
            let payload={
                idalmacendetalle: this.idalmacendetalle
            };
            this.cargando=true;
            this._almacenesService.ubicarategas(this.token, this.idate_gas_ubicacion, payload).subscribe(
                response =>{
                    //console.log(response);
                    this.cargando=false;
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.getAteGas();
                        this.visible_ubicacion=false;
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
                    this.cargando=false;
                }
            );
        }
    }

    prepararEliminarItem(idate_gas: number){
        this.idate_gas_eliminar=idate_gas;
        $("#confirmarEliminar").modal('show');
    }

    eliminar(){
        this.cargando=true;
        this._almacenesService.eliminarategas(this.token, this.idate_gas_eliminar).subscribe(
            response =>{
                //console.log(response);
                this.cargando=false;
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getAteGas();
                    
                }else{
                    this.toast_tipo="Error";
                    //this.mensajes_error=response.mensajes_error;
                }
                $('#confirmarEliminar').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error)
                $('#confirmarEliminar').modal('hide');
                this.cargando=false;
            }
        );
    }

    prepararEditar(idate_gas: number){
        var indice = this.ategas_filtrado.findIndex(x => x.idate_gas == idate_gas);
        if(indice>=0){
            this.idate_gas_editar=idate_gas;
            this.chasis_recepcion=this.ategas_filtrado[indice].chasis;
            this.marca_recepcion=this.ategas_filtrado[indice].marca;
            this.configuracion=this.ategas_filtrado[indice].configuracion;
            this.tipo_tanque=this.ategas_filtrado[indice].tipo_tanque;

            this.visible_edicion=true;
        }
    }

    editarVehiculo(){
        this.cargando=true;
        let payload={
            configuracion: this.configuracion,
            tipo_tanque: this.tipo_tanque
        }
        this._almacenesService.editarategas(this.token, this.idate_gas_editar, payload).subscribe(
            response =>{
                //console.log(response);
                this.cargando=false;
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getAteGas();
                    
                }else{
                    this.toast_tipo="Error";
                    //this.mensajes_error=response.mensajes_error;
                }
                this.visible_edicion=false;
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error)
                this.cargando=false;
            }
        );
    }

}
