import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {UsuarioModel} from '../models/usuario.model';
declare var $: any; 

@Component({
    selector: 'app-perfil',
    templateUrl: './perfil.component.html',
    styleUrls: ['./perfil.component.css'],
    providers:[UsuarioService,DatoMaestroService]
})
export class PerfilComponent implements OnInit {
    public token:string;
    public tokenDetalle:any;
    
    public usuario: UsuarioModel;
    public errornombre: boolean=false;
    public erroremail: boolean=false;
    public mensajeerroremail: string='';
    
    public contrasenaactual: string='';
    public errorcontrasenaactual: boolean=false;
    public contrasena1: string='';
    public errorcontrasena1: boolean=false;
    public mensajeerrorcontrasena1: string='';
    public contrasena2: string='';
    public errorcontrasena2: boolean=false;

    
    public toast_mensaje: string;
    public toast_tipo: string;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService
        //private _almacenesService: AlmacenesService,
        //private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
    }

    ngOnInit(): void {
        //console.log(this.tokenDetalle);
        this._usuarioService.verusuario(this.token, this.tokenDetalle.idusuario).subscribe(
            response =>{
                this.usuario=response.usuario;
                console.log(this.usuario);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
    }
    
    ValidateEmail(inputText: string){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(inputText.match(mailformat)){
            return true;
        }else{
            return false;
        }
    }
    
    saveUsuario(){
        this.errornombre=false;
        if (this.usuario.nombre.length==0){
            this.errornombre=true;
        }
        
        this.erroremail=false;
        if (!this.ValidateEmail(this.usuario.email)){
            this.erroremail=true;
            this.mensajeerroremail='Formato incorrecto';
        }
        
        if (!this.errornombre && !this.erroremail){
            let datosguardar;
            datosguardar={
                nombre: this.usuario.nombre,
                email: this.usuario.email,
                ci: this.usuario.ci,
                telefono: this.usuario.telefono
            };
            //console.log(datosguardar);
            //console.log(this.usuario);
            
            this._usuarioService.saveperfil(this.token, this.usuario).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        //$("#ventanaUsuario").modal('hide');
                        //this.cargarUsuarios();
                    }else{
                        if(response.existeemail){
                            this.erroremail=true;
                            this.mensajeerroremail="Ya existe una cunata con el correo seleccionado"
                        }
                    
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

    cambiarContrasena(valor: boolean){
        if (valor) {
            this.toast_tipo="Exito";
            this.toast_mensaje="Contraseña modificada exitosamente";
            $("#ventanaUsuario").modal('hide');
            $("#liveToast").toast('show');
        }
    }

}
