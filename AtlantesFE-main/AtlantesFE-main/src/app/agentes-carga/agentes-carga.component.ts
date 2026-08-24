import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
import {DatoMaestroService} from '../services/datomaestro.service';

declare var $: any;

@Component({
    selector: 'app-agentes-carga',
    templateUrl: './agentes-carga.component.html',
    styleUrls: ['./agentes-carga.component.css'],
    providers:[UsuarioService,EntidadesService,DatoMaestroService]
})
export class AgentesCargaComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public agentescarga: Array<any>;
    
    public idagentecarga: number=0;
    public agentecarga: string='';
    public erroragentecarga: boolean=false;
    public numeroidentificacion: string='';
    public telefono: string='';
    public fax: string='';
    public email: string='';
    public nombrecontacto: string='';
    public numerocuenta: string='';
    public plazo: number=0;
    public id_OVPProv: number=0;
    public direcciones: Array<any>=[];
    public tiposdocumento: Array<any>
    
    public idtipodocumento: number= null;
    public numerofacturacion: number=null;
    public razonsocial: string='';
    public correosfacturacion: Array<any>=[];
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_entidades_agentes_carga: boolean=false;
    public editar_entidades_agentes_carga: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _entidadesService: EntidadesService,
        private _datosmaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_agentes_carga=true;
            this.editar_entidades_agentes_carga=true;
        }else{
            let indiceVerEntidadesAgentesCarga = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 4);
            if (indiceVerEntidadesAgentesCarga>=0){
                if (this.tokenDetalle.permisos[indiceVerEntidadesAgentesCarga].lectura){
                    this.ver_entidades_agentes_carga=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEntidadesAgentesCarga].escritura){
                    this.editar_entidades_agentes_carga=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._datosmaestroService.tiposdocumento(this.token).subscribe(
            response =>{
                this.tiposdocumento=response.tiposdocumento;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.cargarAgentesCarga();
    }
    
    cargarAgentesCarga(){
        this._entidadesService.veragentescarga(this.token).subscribe(
            response =>{
                this.agentescarga=response.agentescarga;
                console.log(this.agentescarga);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idagentecarga: number){
        if(idagentecarga==0){
            this.cabecera_modal="Nuevo";
            
            this.idagentecarga = 0;
            this.agentecarga = '';
            this.numeroidentificacion = '';
            this.telefono = '';
            this.fax = '';
            this.email = '';
            this.nombrecontacto = '';
            this.numerocuenta = '';
            this.plazo = 0;
            this.id_OVPProv = 0;

            this.direcciones=[];
            this.idtipodocumento=null;
            this.numerofacturacion=0;
            this.razonsocial='S/N';
            this.correosfacturacion=[];
        }else{
            this.cabecera_modal="Editar";
            let indiceagentecarga = this.agentescarga.findIndex(x => x.idagentecarga === idagentecarga);
            

            
            this.idagentecarga = idagentecarga;
            this.agentecarga = this.agentescarga[indiceagentecarga].agentecarga;
            this.numeroidentificacion = this.agentescarga[indiceagentecarga].numeroidentificacion;
            this.telefono = this.agentescarga[indiceagentecarga].telefono;
            this.fax = this.agentescarga[indiceagentecarga].fax;
            this.email = this.agentescarga[indiceagentecarga].email;
            this.nombrecontacto = this.agentescarga[indiceagentecarga].nombrecontacto;
            this.numerocuenta = this.agentescarga[indiceagentecarga].numerocuenta;
            this.plazo = this.agentescarga[indiceagentecarga].plazo;
            this.id_OVPProv = this.agentescarga[indiceagentecarga].id_OVPProv;

            this.direcciones=this.agentescarga[indiceagentecarga].direcciones;
            
            this.idtipodocumento=this.agentescarga[indiceagentecarga].idtipodocumento;
            this.numerofacturacion=this.agentescarga[indiceagentecarga].numerofacturacion;
            this.razonsocial=this.agentescarga[indiceagentecarga].razonsocial;
            this.correosfacturacion=this.agentescarga[indiceagentecarga].correosfacturacion;
            
            
        }
    }
    
    randomInteger(min: number, max: number) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    
    trackByFn(index: number, item: any) {
        return index;
    }
    
    agregarDireccion(){
        let identidaddireccion=this.randomInteger(1000,9999)*(-1);
        this.direcciones.push({
            'idagentecargadireccion': identidaddireccion,
            'direccion': '',
            'ciudad': '',
            'pais': ''
        });
        
    }
    
    eliminarDireccion(idagentecargadireccion: number){
        let indiceagentecargadireccion = this.direcciones.findIndex(x => x.idagentecargadireccion === idagentecargadireccion);
        this.direcciones.splice(indiceagentecargadireccion, 1);
    }
    
    agregarCorreoFactura(){
        this.correosfacturacion.push({
            'idclientecorreofacturacion': 0,
            'correo': '',
            'error': false
        });
    }
    
    eliminarCorreoFactura(indice: number){
        this.correosfacturacion.splice(indice, 1);
    }
    
    guardarDatos(){
        let error_correos=false;
        for (let cc = 0; cc<this.correosfacturacion.length; cc++){
            if (!this.ValidateEmail(this.correosfacturacion[cc].correo)){
                error_correos=true;
                this.correosfacturacion[cc].error=true;
            }
        }
        
        if(!error_correos){
            let datosguardar;
            datosguardar={
                idagentecarga: this.idagentecarga,
                agentecarga: this.agentecarga,
                numeroidentificacion: this.numeroidentificacion,
                telefono: this.telefono,
                fax: this.fax,
                email: this.email,
                nombrecontacto: this.nombrecontacto,
                numerocuenta: this.numerocuenta,
                plazo: this.plazo,
                id_OVPProv: this.id_OVPProv,
                direcciones: this.direcciones,
                idtipodocumento: this.idtipodocumento,
                numerofacturacion: this.numerofacturacion,
                razonsocial: this.razonsocial,
                correosfacturacion: this.correosfacturacion
            };

            if (this.idagentecarga==0){
                this._entidadesService.addagentescarga(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaAgenteCarga").modal('hide');
                            this.cargarAgentesCarga();
                        }else{
                            this.toast_tipo="Error";
                        }

                        $("#liveToast").toast('show');
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }else{
                this._entidadesService.saveagentescarga(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaAgenteCarga").modal('hide');
                            this.cargarAgentesCarga();
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
            
    }
    
    ValidateEmail(inputText: string){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(inputText.match(mailformat)){
            return true;
        }else{
            return false;
        }
    }

}
