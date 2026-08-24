import { Component, OnInit, ViewChild  } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {CambioContrasenaComponent} from '../cambio-contrasena/cambio-contrasena.component';
import { ToastrService } from 'ngx-toastr';

declare var $: any; 

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers:[UsuarioService]
})
export class LoginComponent implements OnInit {
    public username:string;
    public contrasena:string;
    public mensaje:string;
    public datosenviados: boolean;
    
    public idusuario: number=0;
    public doble_factor: boolean=false;
    public email: string='';
    public codigo_verificacion: string='';
    public error_codigo_verificacion: boolean=false;
    public mensaje_error_codigo_verificacion: string= '';
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    @ViewChild(CambioContrasenaComponent) cambioContrasenaComponent!: CambioContrasenaComponent;
    
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
    
    onSubmit(){
        this.datosenviados=true
        
        this._usuarioService.verificardoblefactor(this.username, this.contrasena).subscribe(
            response_2f =>{
                //console.log(response);
                
                if(response_2f.codigo==200){
                    if(response_2f.doblefactor){
                        this.datosenviados=false;
                        this.email=response_2f.email;
                        this.doble_factor=true;
                        this.idusuario=response_2f.idusuario;
                        //alert("doble factor");
                    }else{
                        this.login();
                    }
                    
                }else{
                    this.datosenviados=false;
                    this.mensaje=response_2f.mensaje;
                    this.showError();
                }
                
            },
            error_2f=>{
                this.datosenviados=false;
                console.log(<any>error_2f)
            }
        );
    }
    
    verificarCodigo(){
        if (this.codigo_verificacion.length!=6){
            this.error_codigo_verificacion=true;
            this.mensaje_error_codigo_verificacion="El codigo debe tener 6 caracteres";
        }
        if(!this.error_codigo_verificacion){
            this.datosenviados=true;
            let params={
                idusuario: this.idusuario,
                codigo_verificacion: this.codigo_verificacion
            };

            this._usuarioService.verificarcodigodoblefactor(params).subscribe(
                response =>{
                    if(response.codigo==200){
                        //this.datosenviados=false;
                        this.login();
                        
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
    
    login(){
        this._usuarioService.login(this.username, this.contrasena).subscribe(
            response =>{
                //console.log(response);

                if(response.codigo==200){
                    this.datosenviados=false;
                    this._usuarioService.setToken(response.token);
                    if(response.cambiocontrasena){
                        this.cambioContrasenaComponent.ngOnInit();
                        $("#ventanaUsuario").modal('show');
                    }else{
                        this._router.navigate(['/inicio']);
                    }
                }else{
                    this.datosenviados=false;
                    this.mensaje=response.mensaje;
                    this.showError();
                }

            },
            error=>{
                this.datosenviados=false;
                console.log(<any>error)
            }
        );
    }
    
    cambiarContrasena(valor: boolean){
        if (valor) {
            this.toast_tipo="Exito";
            this.toast_mensaje="Contraseña modificada exitosamente";
            $("#ventanaUsuario").modal('hide');
            $("#liveToast").toast('show');
            this.contrasena='';
        }
    }
    
    showError() {
        
        this.toastr.error(this.mensaje, 'Error', {
            closeButton: true,
            timeOut: 2000
        });
    }

}
