import { Component, Output, EventEmitter } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';

@Component({
    selector: 'app-cambio-contrasena',
    templateUrl: './cambio-contrasena.component.html',
    styleUrl: './cambio-contrasena.component.css',
    providers:[UsuarioService]
})
export class CambioContrasenaComponent {
    public token:string;
    public tokenDetalle:any;
    
    public contrasenaactual: string='';
    public errorcontrasenaactual: boolean=false;
    public mensajecontrasenaactual: string='';
    public contrasena1: string='';
    public errorcontrasena1: boolean=false;
    public contrasena2: string='';
    public errorcontrasena2: boolean=false;
    public mensajeerrorcontrasena1: string;
    
    public contrasena_valida: boolean=false;
    
    public cambioContrasena: boolean=false;
    
    public bloqueoProceso: boolean=false;
    
    @Output() exitoCambioContrasena = new EventEmitter<boolean>();
    
    constructor(
        private _usuarioService: UsuarioService,
        //private _datomaestroService: DatoMaestroService
        //private _almacenesService: AlmacenesService,
        //private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
    }
    
    ngOnInit(): void {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        console.log(this.tokenDetalle);
    }
    
    validarContrasena(){
        this.contrasena_valida=false;
        let error=false;
        let errores_validacion=[];
        if (this.contrasena1.length<12){
            error=true;
            errores_validacion.push("Debe ser de al menos 12 caracteres");
        }
        if (!/[A-Z]/.test(this.contrasena1)) {
            error=true;
            errores_validacion.push("Debe contener al menos una letra mayúscula.");
        }
        
        if (!/[a-z]/.test(this.contrasena1)) {
            error=true;
            errores_validacion.push("Debe contener al menos una letra minúscula.");
        }

        if (!/\d/.test(this.contrasena1)) {
            error=true;
            errores_validacion.push("Debe contener al menos un número.");
        }

        if (!/[\W_]/.test(this.contrasena1)) {
            error=true;
            errores_validacion.push("Debe contener al menos un carácter especial.");
        }
        
        if (this.contrasena1 != this.contrasena2){
            error=true;
            this.errorcontrasena2=true;
        }
        
        if(error){
            this.errorcontrasena1=true;
            this.mensajeerrorcontrasena1=errores_validacion.join("<br />");
        }else{
            this.errorcontrasena1=false;
            this.errorcontrasena2=false;
            this.contrasena_valida=true;
        }
        
    }
    
    
    cambiarContrasena(){
        let datosguardar;
        datosguardar={
            contrasenaactual: this.contrasenaactual,
            nuevacontrasena: this.contrasena1
        };
        this.bloqueoProceso=true;
        this._usuarioService.cambiarcontrasena(this.token, datosguardar).subscribe(
            response =>{
                //console.log(response);
                //this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    //this.toast_tipo="Exito";
                    //$("#ventanaUsuario").modal('hide');
                    //this.cargarUsuarios();
                    this.cambioContrasena=true;
                    this.bloqueoProceso=false;
                    this.exitoCambioContrasena.emit(this.cambioContrasena);
                }else{
                    //this.toast_tipo="Error";
                    this.errorcontrasenaactual=true;
                    this.bloqueoProceso=false;
                    this.mensajecontrasenaactual=response.mensaje;
                }

                //$("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error);
                this.bloqueoProceso=false;
            }
        );
    }
}
