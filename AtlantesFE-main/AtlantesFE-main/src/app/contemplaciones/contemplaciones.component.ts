import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-contemplaciones',
    templateUrl: './contemplaciones.component.html',
    styleUrl: './contemplaciones.component.css',
    providers:[UsuarioService,DatoMaestroService]
})
export class ContemplacionesComponent {
    public token:string;
    public tokenDetalle: any;
    
    public contemplaciones: Array<any>;
    
    public idcontemplacion: number;
    public contemplacion: string;
    public error_contemplacion: boolean;
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_contemplaciones: boolean=false;
    public editar_contemplaciones: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_contemplaciones=true;
            this.editar_contemplaciones=true;
        }else{
            let indiceVerContemplaciones= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 30);
            if (indiceVerContemplaciones>=0){
                if (this.tokenDetalle.permisos[indiceVerContemplaciones].lectura){
                    this.ver_contemplaciones=true;
                }
                if (this.tokenDetalle.permisos[indiceVerContemplaciones].escritura){
                    this.editar_contemplaciones=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this.cargarContemplaciones();
    }
    
    cargarContemplaciones(){
        this._datomaestroService.contemplaciones(this.token).subscribe(
            response =>{
                
                this.contemplaciones= response.contemplaciones;
                //console.log(this.cuentas);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idcontemplacion: number){
        this.idcontemplacion = idcontemplacion;
        if (idcontemplacion==0){
            this.cabecera_modal="Nueva";
            this.contemplacion='';
            this.error_contemplacion=false;
        }else{
            this.cabecera_modal="Editar";
            let indice = this.contemplaciones.findIndex(x => x.idcontemplacion === idcontemplacion);
            this.contemplacion = this.contemplaciones[indice].contemplacion;
            this.error_contemplacion=false;
        }
    }
    
    guardarDatos(){
        let error=false;
        this.error_contemplacion=false;
        if (this.contemplacion==''){
            this.error_contemplacion=true;
            error=true;
        }
        
        if(!error){
            let datosguardar;
            datosguardar={
                contemplacion: this.contemplacion
            };
            if (this.idcontemplacion==0){
                this._datomaestroService.addcontemplacion(this.token, datosguardar).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaContemplacion").modal('hide');
                            this.cargarContemplaciones();
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
                this._datomaestroService.savecontemplacion(this.token, datosguardar, this.idcontemplacion).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaContemplacion").modal('hide');
                            this.cargarContemplaciones();
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
