import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';

declare var $: any;

@Component({
    selector: 'app-transportistas',
    templateUrl: './transportistas.component.html',
    styleUrls: ['./transportistas.component.css'],
    providers:[UsuarioService,EntidadesService]
})
export class TransportistasComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public transportistas: Array<any>;
    
    public idtransportista: number=0;
    public transportista: string='';
    public errortransportista: boolean=false;
    public numeroidentificacion: string='';
    public telefono: string='';
    public fax: string='';
    public email: string='';
    public nombrecontacto: string='';
    public numerocuenta: string='';
    public plazo: number=0;
    public id_OVPProv: number=0;
    public direcciones: Array<any>=[];
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_entidades_transportistas: boolean=false;
    public editar_entidades_transportistas: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _entidadesService: EntidadesService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_transportistas=true;
            this.editar_entidades_transportistas=true;
        }else{
            let indiceVerEntidadesTransportistas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 3);
            if (indiceVerEntidadesTransportistas>=0){
                if (this.tokenDetalle.permisos[indiceVerEntidadesTransportistas].lectura){
                    this.ver_entidades_transportistas=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEntidadesTransportistas].escritura){
                    this.editar_entidades_transportistas=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarTransportistas();
    }
    
    cargarTransportistas(){
        this._entidadesService.vertransportistas(this.token).subscribe(
            response =>{
                this.transportistas=response.transportistas;
                console.log(this.transportistas);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idtransportista: number){
        if(idtransportista==0){
            this.cabecera_modal="Nuevo";
            
            this.idtransportista = 0;
            this.transportista = '';
            this.numeroidentificacion = '';
            this.telefono = '';
            this.fax = '';
            this.email = '';
            this.nombrecontacto = '';
            this.numerocuenta = '';
            this.plazo = 0;
            this.id_OVPProv = 0;

            this.direcciones=[];
        }else{
            this.cabecera_modal="Editar";
            let indicetransportista = this.transportistas.findIndex(x => x.idtransportista === idtransportista);
            
            console.log(idtransportista+" "+indicetransportista);
            
            this.idtransportista = idtransportista;
            this.transportista = this.transportistas[indicetransportista].transportista;
            this.numeroidentificacion = this.transportistas[indicetransportista].numeroidentificacion;
            this.telefono = this.transportistas[indicetransportista].telefono;
            this.fax = this.transportistas[indicetransportista].fax;
            this.email = this.transportistas[indicetransportista].email;
            this.nombrecontacto = this.transportistas[indicetransportista].nombrecontacto;
            this.numerocuenta = this.transportistas[indicetransportista].numerocuenta;
            this.plazo = this.transportistas[indicetransportista].plazo;
            this.id_OVPProv = this.transportistas[indicetransportista].id_OVPProv;

            this.direcciones=this.transportistas[indicetransportista].direcciones;
            
            
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
            'idtransportistadireccion': identidaddireccion,
            'direccion': '',
            'ciudad': '',
            'pais': ''
        });
        
    }
    
    eliminarDireccion(idtransportistadireccion: number){
        let indicetransportistadireccion = this.direcciones.findIndex(x => x.idtransportistadireccion === idtransportistadireccion);
        this.direcciones.splice(indicetransportistadireccion, 1);
    }
    
    guardarDatos(){
        let datosguardar;
        datosguardar={
            idtransportista: this.idtransportista,
            transportista: this.transportista,
            numeroidentificacion: this.numeroidentificacion,
            telefono: this.telefono,
            fax: this.fax,
            email: this.email,
            nombrecontacto: this.nombrecontacto,
            numerocuenta: this.numerocuenta,
            plazo: this.plazo,
            id_OVPProv: this.id_OVPProv,
            direcciones: this.direcciones
        };
        
        if (this.idtransportista==0){
            this._entidadesService.addtransportista(this.token, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaTransportista").modal('hide');
                        this.cargarTransportistas();
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
            this._entidadesService.savetransportista(this.token, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaTransportista").modal('hide');
                        this.cargarTransportistas();
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
