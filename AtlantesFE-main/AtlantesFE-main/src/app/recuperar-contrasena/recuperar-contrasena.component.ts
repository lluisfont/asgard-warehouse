import { Component, OnInit } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import { ToastrService } from 'ngx-toastr';

@Component({
    selector: 'app-recuperar-contrasena',
    templateUrl: './recuperar-contrasena.component.html',
    styleUrl: './recuperar-contrasena.component.css',
    providers:[UsuarioService]
})
export class RecuperarContrasenaComponent {
    public username:string='';
    public error_username: boolean=false;
    public mensaje_error_username:string='';
    public mensaje:string;
    public etapa: number=1;
    
    public email: string='';
    public codigo_verificacion: string='';
    public error_codigo_verificacion: boolean=false;
    public mensaje_error_codigo_verificacion: string= '';
    public idcodigo: number=0;
    
    public contrasena1: string='';
    public errorcontrasena1: boolean=false;
    public contrasena2: string='';
    public errorcontrasena2: boolean=false;
    public mensajeerrorcontrasena1: string;
    
    public contrasena_valida: boolean=false;
    
    public datosenviados: boolean;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    constructor(
        private _usuarioService: UsuarioService,
        private toastr: ToastrService,
        private _route: ActivatedRoute,
        private _router: Router
        ) { 
        this.mensaje="";
        this.datosenviados=false;
    }

    ngOnInit(): void {
        
    }
    
    enviarVerificacion(){
        if (this.username==''){
            this.error_username = true;
            this.mensaje_error_username="Campo Obligatorio";
        }
        if(!this.error_username){
            this.datosenviados=true;
            let params={
                username_email: this.username
            };

            this._usuarioService.recuperarcontrasena(params).subscribe(
                response =>{
                    if(response.codigo==200){
                        this.etapa=2;
                        this.email=response.email;
                        this.idcodigo=response.idcodigo_nuevo;
                        this.datosenviados=false;
                    }else{
                        this.datosenviados=false;
                        this.error_username = true;
                        this.mensaje_error_username=response.mensaje;
                    }
                    //console.log(response);
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
            
    }
    
    
    verificarCodigo(){
        if (this.codigo_verificacion.length!=6){
            this.error_codigo_verificacion=true;
            this.mensaje_error_codigo_verificacion="El codigo debe tener 6 caracteres";
        }
        if(!this.error_codigo_verificacion){
            this.datosenviados=true;
            let params={
                idcodigo: this.idcodigo,
                codigo_verificacion: this.codigo_verificacion
            };

            this._usuarioService.verificarcodigo(params).subscribe(
                response =>{
                    if(response.codigo==200){
                        this.datosenviados=false;
                        this.etapa=3;
                        
                    }else{
                        this.datosenviados=false;
                        this.error_codigo_verificacion = true;
                        this.mensaje_error_codigo_verificacion=response.mensaje;
                    }
                    console.log(response);
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
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
        this.datosenviados=true;
        let params={
            idcodigo: this.idcodigo,
            nuevacontrasena: this.contrasena1
        };
        this._usuarioService.resetearcontrasena(params).subscribe(
            response =>{
                if(response.codigo==200){
                    this.datosenviados=false;
                    this.etapa=4;
                }else{
                    this.datosenviados=false;
                }
            },
            error=>{
                console.log(<any>error);
                this.datosenviados=false;
            }
        );
    }

}
