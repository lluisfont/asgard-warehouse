import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-consideraciones',
    templateUrl: './consideraciones.component.html',
    styleUrl: './consideraciones.component.css',
    providers:[UsuarioService,DatoMaestroService]
})
export class ConsideracionesComponent {
    public token:string;
    public tokenDetalle: any;
    
    public consideraciones: Array<any>;
    
    public idconsideraciones: number;
    public consideracion: string;
    public error_consideracion: boolean;
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_consideraciones: boolean=false;
    public editar_consideraciones: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_consideraciones=true;
            this.editar_consideraciones=true;
        }else{
            let indiceVerConsideraciones= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 31);
            if (indiceVerConsideraciones>=0){
                if (this.tokenDetalle.permisos[indiceVerConsideraciones].lectura){
                    this.ver_consideraciones=true;
                }
                if (this.tokenDetalle.permisos[indiceVerConsideraciones].escritura){
                    this.editar_consideraciones=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this.cargarConsideraciones();
    }
    
    cargarConsideraciones(){
        this._datomaestroService.consideraciones(this.token).subscribe(
            response =>{
                
                this.consideraciones= response.consideraciones;
                //console.log(this.cuentas);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idconsideraciones: number){
        this.idconsideraciones = idconsideraciones;
        if (idconsideraciones==0){
            this.cabecera_modal="Nueva";
            this.consideracion='';
            this.error_consideracion=false;
        }else{
            this.cabecera_modal="Editar";
            let indice = this.consideraciones.findIndex(x => x.idconsideraciones === idconsideraciones);
            this.consideracion = this.consideraciones[indice].consideraciones;
            this.error_consideracion=false;
        }
    }
    
    guardarDatos(){
        let error=false;
        this.error_consideracion=false;
        if (this.consideracion==''){
            this.error_consideracion=true;
            error=true;
        }
        
        if(!error){
            let datosguardar;
            datosguardar={
                consideraciones: this.consideracion
            };
            if (this.idconsideraciones==0){
                this._datomaestroService.addconsideracion(this.token, datosguardar).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaConsideracion").modal('hide');
                            this.cargarConsideraciones();
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
                this._datomaestroService.saveconsideracion(this.token, datosguardar, this.idconsideraciones).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaConsideracion").modal('hide');
                            this.cargarConsideraciones();
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

}
