import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EmbarqueService} from '../services/embarque.service';
import { ToastrService } from 'ngx-toastr';
import { Tab } from 'bootstrap';
declare var $: any;

@Component({
    selector: 'app-cotizaciones',
    templateUrl: './cotizaciones.component.html',
    styleUrls: ['./cotizaciones.component.css'],
    providers:[UsuarioService,EmbarqueService,DatoMaestroService]
})
export class CotizacionesComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public cotizaciones: Array<any>;
    public idestadocotizacionactual: number;
    public entidades: Array<any>;
    public expedidores: Array<any>;
    public tiposembarque:Array<any>;
    public importacion_exportaciones:Array<any>;
    public ciudades:Array<any>;
    public incoterms:Array<any>;
    public tiposevento:Array<any>;
    public conceptos:Array<any>;
    public divisas:Array<any>;
    public contemplaciones:Array<any>;
    public consideraciones:Array<any>;
    public tipos_bulto: Array<any>;
    
    
    public idcliente: number;
    public erroridcliente: boolean;
    public check_otrocliente: boolean=false;
    public otrocliente: string;
    public errorotrocliente: boolean;
    public idcotizacion: number;
    public cotizacion: string;
    public cliente: string;
    public nombre: string;
    public idtipoembarque: number;
    public erroridtipoembarque: boolean;
    public importacion_exportacion: number;
    public errorimportacion_exportacion: boolean;
    public noidentificacion: string;
    public idexpedidor: string;
    public descripcioncarga: string;
    public idorigen: number;
    public iddestino: number;
    public peso: string;
    public volumen: number;
    public piezas: number;
    public idtipobulto: number;
    public idincoterms: number;
    public errorgeneral: boolean;
    public idestadocotizacion: number;
    public eventos: Array<{ideventocotizacion: number, idtipoevento: number, tipoevento: string, fechaplanificada: string, evento: string}>;
    public erroreseventos: Array<any>=[];
    public erroreventos: boolean;
    public costos: Array<{idcostocotizacion: number, idconcepto: number, concepto: string, cantidad: number, montocargo: number, montocosto: number, iddivisa: number, codigodivisa: string}>;
    public errorescostos: Array<any>;
    public errorcostos: boolean;
    
    public divisadocumento: number=null;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_cotizaciones: boolean=false;
    public editar_cotizaciones: boolean=false;
    public editar_embarques: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        
        private _embarqueService: EmbarqueService,
        private toastr: ToastrService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_cotizaciones=true;
            this.editar_cotizaciones=true;
            this.editar_embarques=true;
        }else{
            let indiceVerCotizaciones = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 37);
            if (indiceVerCotizaciones>=0){
                if (this.tokenDetalle.permisos[indiceVerCotizaciones].lectura){
                    this.ver_cotizaciones=true;
                }
                if (this.tokenDetalle.permisos[indiceVerCotizaciones].escritura){
                    this.editar_cotizaciones=true;
                }
            }
            let indiceVerEmbarques = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 38);
            if (indiceVerEmbarques>=0){
                if (this.tokenDetalle.permisos[indiceVerEmbarques].escritura){
                    this.editar_embarques=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades = response.entidades.filter(function(cc){
                    return cc.idtipoentidad==1
                });
                this.expedidores = response.entidades.filter(function(cc){
                    return cc.idtipoentidad!=4
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.tiposembarque(this.token).subscribe(
            response =>{
                this.tiposembarque=response.tiposembarque;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.importacion_exportacion(this.token).subscribe(
            response =>{
                this.importacion_exportaciones=response.importacion_exportacion;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.ciudades(this.token).subscribe(
            response =>{
                this.ciudades=response.ciudades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.incoterms(this.token).subscribe(
            response =>{
                this.incoterms=response.incoterms;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.tiposevento(this.token).subscribe(
            response =>{
                this.tiposevento=response.tiposevento;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.conceptos(this.token).subscribe(
            response =>{
                this.conceptos = response.conceptos.filter(function(cc){
                    return cc.tipo==1
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.divisas(this.token).subscribe(
            response =>{
                this.divisas=response.divisas;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.contemplaciones(this.token).subscribe(
            response =>{
                this.contemplaciones=response.contemplaciones;
                this.contemplaciones.forEach(object => {
                    object.estado = 0;
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.consideraciones(this.token).subscribe(
            response =>{
                this.consideraciones=response.consideraciones;
                this.consideraciones.forEach(object => {
                    object.marcado = false;
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tipos_bulto(this.token).subscribe(
            response =>{
                this.tipos_bulto=response.tipos_bulto;

            },
            error=>{
                console.log(<any>error)
            }
        );

        this.idestadocotizacionactual=0;
        this.getCotizaciones(this.idestadocotizacionactual);
    }
    
    getCotizaciones(idestado: number){
        this.idestadocotizacionactual=idestado;
        this._embarqueService.cotizaciones(this.token).subscribe(
            response =>{
                switch(idestado){
                    case 0:
                        this.cotizaciones=response.cotizaciones;
                        break;
                    case 1:
                        this.cotizaciones = response.cotizaciones.filter(function(cc){
                            return cc.idestadocotizacion==1
                        });
                        break;
                    case 4:
                        this.cotizaciones = response.cotizaciones.filter(function(cc){
                            return cc.idestadocotizacion==4
                        });
                        break;
                }
                this.p=1;
                console.log(this.cotizaciones);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    preperarAgregar(){
        this.idcliente=null;
        this.erroridcliente=false;
        this.check_otrocliente=false;
        this.otrocliente='';
        this.errorotrocliente=false;
    }
  
    crearCotizacion(){
        let error=false;
        this.erroridcliente=false;
        this.errorotrocliente=false;
        if (this.check_otrocliente){
            if (this.otrocliente==''){
                error=true;
                this.errorotrocliente=true;
            }
        }else{
            if (this.idcliente==null){
                error=true;
                this.erroridcliente=true;
            }
        }
        
        if (!error){
            let datoscotizacion = {idcliente: this.idcliente, otrocliente: this.otrocliente};
            this._embarqueService.crearcotizacion(this.token, datoscotizacion).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        $('#nuevaCotizacion').modal('hide');
                        this.getCotizaciones(this.idestadocotizacionactual);
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
    
    prepararCotizacion(idcotizacion: number){
        this.erroridtipoembarque=false;
        this.errorimportacion_exportacion=false;
        this.idcotizacion=idcotizacion;
        this._embarqueService.cotizaciondetalle(this.token, idcotizacion).subscribe(
            response =>{
                if(response.codigo==200){
                    console.log(response)
                    this.cotizacion='COT-'+response.cotizacion.numero+'-'+response.cotizacion.gestion;
                    this.idcliente=response.cotizacion.idcliente;
                    this.erroridcliente=false;
                    this.cliente=response.cotizacion.cliente;
                    this.nombre=response.cotizacion.nombre;
                    this.idtipoembarque=response.cotizacion.idtipoembarque;
                    this.erroridtipoembarque=false;
                    this.importacion_exportacion=response.cotizacion.importacion_exportacion;
                    this.errorimportacion_exportacion=false;
                    this.noidentificacion=response.cotizacion.noidentificacion;
                    if(response.cotizacion.idtipoexpedidor==0){
                        this.idexpedidor=null;
                    }else{
                        this.idexpedidor=response.cotizacion.idtipoexpedidor+'-'+response.cotizacion.idexpedidor;
                    }
                    this.descripcioncarga=response.cotizacion.descripcioncarga;
                    this.idorigen=response.cotizacion.idorigen;
                    this.iddestino=response.cotizacion.iddestino;
                    this.peso=response.cotizacion.peso;
                    this.volumen=response.cotizacion.volumen;
                    this.piezas=response.cotizacion.piezas;
                    this.idtipobulto=response.cotizacion.idtipobulto;
                    this.idincoterms=response.cotizacion.idincoterms;
                    this.idestadocotizacion=response.cotizacion.idestadocotizacion;
                    this.eventos=response.cotizacion.eventos;
                    this.erroreventos=false;
                    this.erroreseventos=[];
                    for (let ee = 0; ee < this.eventos.length; ee++){
                        this.erroreseventos.push({
                            errortipoevento: false,
                            errorfecha: false
                        });
                    }
                    
                    this.costos=response.cotizacion.costos;
                    this.errorcostos=false;
                    this.errorescostos=[];
                    for (let ee = 0; ee < this.costos.length; ee++){
                        this.errorescostos.push({
                            errorconcepto: false,
                            errordivisa: false
                        });
                    }
                    
                    this.contemplaciones.forEach(object => {
                        object.estado = 0;
                    });
                    for(let cc=0; cc<response.cotizacion.contemplaciones.length; cc++){
                        let indice_contemplacion = this.contemplaciones.findIndex(x => x.idcontemplacion === response.cotizacion.contemplaciones[cc].idcontemplacion);
                        this.contemplaciones[indice_contemplacion].estado=response.cotizacion.contemplaciones[cc].estado;
                    }
                    this.consideraciones.forEach(object => {
                        object.marcado = false;
                    });
                    for(let cc=0; cc<response.cotizacion.consideraciones.length; cc++){
                        let indice_consideracion = this.consideraciones.findIndex(x => x.idconsideraciones === response.cotizacion.consideraciones[cc].idconsideraciones);
                        this.consideraciones[indice_consideracion].marcado=true;
                    }
                    
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
    
    verificarCodigoEvento(indice: number){
        this.erroreseventos[indice].errortipoevento=false;
        this.erroreventos=false;
        if(this.eventos[indice].idtipoevento==0){
            this.eventos[indice].tipoevento = '[Seleccione]';
        }else{
            var indiceevento = this.tiposevento.findIndex(x => x.idtipoevento === this.eventos[indice].idtipoevento);
            this.eventos[indice].tipoevento = this.tiposevento[indiceevento].tipoevento;
        }
    }
    
    agregarEvento(){
        this.eventos.push({
            ideventocotizacion: 0,
            idtipoevento: 0,
            tipoevento: '[Seleccione]',
            fechaplanificada: '',
            evento: ''
        });
        this.erroreseventos.push({
            errortipoevento: false,
            errorfecha: false
        });
    }
    
    eliminarEvento(indice: number){
        this.eventos.splice(indice, 1);
        this.erroreseventos.splice(indice, 1);
    }
    
    verificarCodigoConcepto(indice: number){
        this.errorescostos[indice].errorconcepto=false;
        this.errorcostos=false;
        if (this.costos[indice].idconcepto==0){
            this.costos[indice].concepto = '[Seleccione]';
        }else{
            var indiceconcepto = this.conceptos.findIndex(x => x.idconcepto === this.costos[indice].idconcepto);
            this.costos[indice].concepto = this.conceptos[indiceconcepto].concepto;
        }
    }
    
    verificarCodigoDivisa(indice: number){
        this.errorescostos[indice].errordivisa=false;
        this.errorcostos=false;
        if (this.costos[indice].iddivisa==0){
            this.costos[indice].codigodivisa = '[Seleccione]';
        }else{
            var indicedivisa = this.divisas.findIndex(x => x.iddivisa === this.costos[indice].iddivisa);
            this.costos[indice].codigodivisa = this.divisas[indicedivisa].codigo;
        }
    }
    
    agregarCosto(){
        this.costos.push({
            idcostocotizacion: 0,
            idconcepto: 0,
            concepto: '[Selecciones]',
            cantidad: 0,
            montocargo: 0,
            montocosto: 0,
            iddivisa: 0,
            codigodivisa: '[Seleccione]'
        });
        this.errorescostos.push({
            errorconcepto: false,
            errordivisa: false
        });
    }
    
    eliminarCosto(indice: number){
        this.costos.splice(indice, 1);
        this.errorescostos.splice(indice, 1);
    }

    
    guardarCotizacion(convertir_embarque: boolean){
        let error=false;
        let error_general=false;
        let error_eventos=false;
        let error_costos=false;
        
        this.errorgeneral=false;
        this.erroridcliente=false;
        if (this.idcliente==null && convertir_embarque){
            error=true;
            error_general=true;
            this.erroridcliente=true;
            this.errorgeneral=true;
        }
        
        this.erroridtipoembarque=false;
        if (this.idtipoembarque==null && convertir_embarque){
            error=true;
            error_general=true;
            this.erroridtipoembarque=true;
            this.errorgeneral=true;
        }
        
        this.errorimportacion_exportacion=false;
        if (this.importacion_exportacion==null && convertir_embarque){
            error=true;
            error_general=true;
            this.errorimportacion_exportacion=true;
            this.errorgeneral=true;
        }
        
        this.erroreventos=false;
        for (let ee = 0; ee < this.eventos.length; ee++){
            if (this.eventos[ee].idtipoevento==0){
                this.erroreseventos[ee].errortipoevento=true;
                error=true;
                error_eventos=true;
                this.erroreventos=true;
            }
            if (this.eventos[ee].fechaplanificada==''){
                this.erroreseventos[ee].errorfecha=true;
                error=true;
                error_eventos=true;
                this.erroreventos=true;
            }
        }
        
        this.errorcostos=false;
        for (let ee = 0; ee < this.costos.length; ee++){
            if (this.costos[ee].idconcepto==0){
                this.errorescostos[ee].errorconcepto=true;
                error=true;
                error_costos=true;
                this.errorcostos=true;
            }
            if (this.costos[ee].iddivisa==0){
                this.errorescostos[ee].errordivisa=true;
                error=true;
                error_costos=true;
                this.errorcostos=true;
            }
        }
        
        if (error_general){
            $('#general-tab').tab('show');
            /*
            const profileTabElement = document.getElementById('general-tab');
            if (profileTabElement) {
              const tabInstance = new Tab(profileTabElement); // Crear instancia del tab
              tabInstance.show(); // Mostrar el tab
            }
            */
        }
        if (error_eventos){
            $('#eventos-tab').tab('show');
            /*
            const profileTabElement = document.getElementById('eventos-tab');
            if (profileTabElement) {
              const tabInstance = new Tab(profileTabElement); // Crear instancia del tab
              tabInstance.show(); // Mostrar el tab
            }
            */
        }
        if (error_costos){
            $('#cotizacion-tab').tab('show');
            /*
            const profileTabElement = document.getElementById('cotizacion-tab');
            if (profileTabElement) {
              const tabInstance = new Tab(profileTabElement); // Crear instancia del tab
              tabInstance.show(); // Mostrar el tab
            }
            */
        }
        let contemplaciones:Array<any>=[];
        for (let cc = 0; cc < this.contemplaciones.length; cc++){
            if (this.contemplaciones[cc].estado>0){
                contemplaciones.push({
                    idcontemplacion: this.contemplaciones[cc].idcontemplacion,
                    estado: this.contemplaciones[cc].estado
                });
            }
        }
        
        let consideraciones:Array<any>=[];
        for (let cc = 0; cc < this.consideraciones.length; cc++){
            if (this.consideraciones[cc].marcado){
                consideraciones.push({
                    idconsideraciones: this.consideraciones[cc].idconsideraciones
                });
            }
        }
        
        let datosguardar;
        datosguardar={
            idcliente: this.idcliente,
            nombre: this.nombre,
            idtipoembarque: this.idtipoembarque,
            importacion_exportacion: this.importacion_exportacion,
            noidentificacion: this.noidentificacion,
            idexpedidor: this.idexpedidor,
            descripcioncarga: this.descripcioncarga,
            idorigen: this.idorigen,
            iddestino: this.iddestino,
            peso: this.peso,
            volumen: this.volumen,
            piezas: this.piezas,
            idtipobulto: this.idtipobulto,
            idincoterms: this.idincoterms,
            eventos: this.eventos,
            costos: this.costos,
            contemplaciones: contemplaciones,
            consideraciones: consideraciones
        };
        
        console.log(datosguardar);
        
        if (!error){
            
            this._embarqueService.savecotizacion(this.token, this.idcotizacion, datosguardar).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        if (convertir_embarque){
                            this._embarqueService.convertircotizacion(this.token, this.idcotizacion).subscribe(
                                responseCrear =>{
                                    this.toast_mensaje=responseCrear.mensaje;
                                    if(responseCrear.codigo==200){
                                        $('#verCotizacion').modal('hide');
                                        this._router.navigate(['/embarques-detalle',responseCrear.idembarque])
                                    }else{
                                        this.toast_tipo="Error";
                                    }
                                },
                                error=>{
                                    console.log(<any>error)
                                }
                            );
                        }else{
                            this.getCotizaciones(this.idestadocotizacionactual);
                        }

                    }else{
                        this.toast_tipo="Error";
                    }

                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
            
            //alert("convertir en embarque");
        }
            
        
        console.log(datosguardar);
        
        
    }
    
    generarPDF(){
        this._embarqueService.descargarcotizacion(this.token, this.idcotizacion, this.divisadocumento).subscribe(
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

}
