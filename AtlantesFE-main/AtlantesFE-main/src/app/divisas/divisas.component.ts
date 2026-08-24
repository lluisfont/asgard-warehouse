import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-divisas',
    templateUrl: './divisas.component.html',
    styleUrl: './divisas.component.css',
    providers:[UsuarioService,DatoMaestroService]
})
export class DivisasComponent {
    public token:string;
    public tokenDetalle: any;
    
    public listaDivisas: Array<any>;
    public divisas: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_divisas: boolean=false;
    public editar_divisas: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_divisas=true;
            this.editar_divisas=true;
        }else{
            let indiceVerDivisas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 35);
            if (indiceVerDivisas>=0){
                if (this.tokenDetalle.permisos[indiceVerDivisas].lectura){
                    this.ver_divisas=true;
                }
                if (this.tokenDetalle.permisos[indiceVerDivisas].escritura){
                    this.editar_divisas=true;
                }
            }
        }

    }
    
    ngOnInit(): void {
        this.cargarDivisas();
    }
    
    cargarDivisas(){
        this._datomaestroService.listadivisas(this.token).subscribe(
            response =>{
                
                this.listaDivisas= response.divisas;
                console.log(this.listaDivisas);
                this._datomaestroService.divisas(this.token).subscribe(
                    response_divisa =>{

                        this.divisas = response_divisa.divisas;
                        for (let dd = 0; dd < this.listaDivisas.length; dd++){
                            this.listaDivisas[dd].marcado=false;
                        }
                        
                        
                        for (let dd = 0; dd < this.divisas.length; dd++){
                            let indice_divisa = this.listaDivisas.findIndex(x => x.iddivisa === this.divisas[dd].iddivisa);
                            this.listaDivisas[indice_divisa].marcado=true;
                        }
                        
                        console.log(this.divisas);
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    guardarDatos(){
        let datosguardar=[];
        for(let dd=0; dd<this.listaDivisas.length; dd++){
            if(this.listaDivisas[dd].marcado){
                datosguardar.push(this.listaDivisas[dd].iddivisa)
            }
        }
        this._datomaestroService.savedivisas(this.token, datosguardar).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.cargarDivisas();
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
