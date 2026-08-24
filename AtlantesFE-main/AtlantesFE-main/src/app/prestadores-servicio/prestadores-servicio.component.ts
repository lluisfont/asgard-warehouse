import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
declare var $: any; 

@Component({
    selector: 'app-prestadores-servicio',
    templateUrl: './prestadores-servicio.component.html',
    styleUrls: ['./prestadores-servicio.component.css'],
    providers:[UsuarioService,EntidadesService]
})
export class PrestadoresServicioComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public prestadores: Array<any>;
    
    public idprestador: number=0;
    public prestador: string='';
    public errorprestador: boolean=false;
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
    
    public ver_entidades_prestadores: boolean=false;
    public editar_entidades_prestadores: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _entidadesService: EntidadesService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_prestadores=true;
            this.editar_entidades_prestadores=true;
        }else{
            let indiceVerEntidadesPrestadores = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 6);
            if (indiceVerEntidadesPrestadores>=0){
                if (this.tokenDetalle.permisos[indiceVerEntidadesPrestadores].lectura){
                    this.ver_entidades_prestadores=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEntidadesPrestadores].escritura){
                    this.editar_entidades_prestadores=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarPrestadores();
    }
    
    cargarPrestadores(){
        this._entidadesService.verprestadores(this.token).subscribe(
            response =>{
                this.prestadores=response.prestadoresservicio;
                console.log(this.prestadores);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idprestador: number){
        if(idprestador==0){
            this.cabecera_modal="Nuevo";
            
            this.idprestador = 0;
            this.prestador = '';
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
            let indiceprestador = this.prestadores.findIndex(x => x.idprestador === idprestador);
            
            
            this.idprestador = idprestador;
            this.prestador = this.prestadores[indiceprestador].prestador;
            this.numeroidentificacion = this.prestadores[indiceprestador].numeroidentificacion;
            this.telefono = this.prestadores[indiceprestador].telefono;
            this.fax = this.prestadores[indiceprestador].fax;
            this.email = this.prestadores[indiceprestador].email;
            this.nombrecontacto = this.prestadores[indiceprestador].nombrecontacto;
            this.numerocuenta = this.prestadores[indiceprestador].numerocuenta;
            this.plazo = this.prestadores[indiceprestador].plazo;
            this.id_OVPProv = this.prestadores[indiceprestador].id_OVPProv;

            this.direcciones=this.prestadores[indiceprestador].direcciones;
            
            
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
            'idprestadordireccion': identidaddireccion,
            'direccion': '',
            'ciudad': '',
            'pais': ''
        });
        
    }
    
    eliminarDireccion(idprestadordireccion: number){
        let indiceprestadordireccion = this.direcciones.findIndex(x => x.idprestadordireccion === idprestadordireccion);
        this.direcciones.splice(indiceprestadordireccion, 1);
    }
    
    guardarDatos(){
        let datosguardar;
        datosguardar={
            idprestador: this.idprestador,
            prestador: this.prestador,
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
        
        if (this.idprestador==0){
            this._entidadesService.addprestadores(this.token, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaPrestador").modal('hide');
                        this.cargarPrestadores();
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
            this._entidadesService.saveprestadores(this.token, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaPrestador").modal('hide');
                        this.cargarPrestadores();
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
