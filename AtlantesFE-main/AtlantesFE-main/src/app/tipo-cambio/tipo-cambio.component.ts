import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-tipo-cambio',
    templateUrl: './tipo-cambio.component.html',
    styleUrl: './tipo-cambio.component.css',
    providers:[UsuarioService,DatoMaestroService]
})
export class TipoCambioComponent {
    public token:string;
    public tokenDetalle: any;
    
    public fecha: string;
    public fecha_actual: string;
    public mantener_relacion: boolean=true;
    public tiposcambio: Array<any>=[];
    public divisas: Array<any>=[];
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_tipo_cambio: boolean=false;
    public editar_tipo_cambio: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_tipo_cambio=true;
            this.editar_tipo_cambio=true;
        }else{
            let indiceVerTipoCambio = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 36);
            if (indiceVerTipoCambio>=0){
                if (this.tokenDetalle.permisos[indiceVerTipoCambio].lectura){
                    this.ver_tipo_cambio=true;
                }
                if (this.tokenDetalle.permisos[indiceVerTipoCambio].escritura){
                    this.editar_tipo_cambio=true;
                }
            }
        }
        this.fecha = this._usuarioService.getCurrentDateFilterValue();
        this.fecha_actual = this._usuarioService.getCurrentDateFilterValue();
    }
    
    ngOnInit(): void {
        this._datomaestroService.divisas(this.token).subscribe(
            response =>{
                this.divisas= response.divisas;
                for (let dd = 0; dd < this.divisas.length; dd++){
                    this.divisas[dd].marcado=true;
                }
                console.log(this.divisas);
                this.cargarTipoCambio();
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    cargarTipoCambio(){
        this.tiposcambio=[];
        let divisasmarcadas=[];
        for (let dd = 0; dd < this.divisas.length; dd++){
            if (this.divisas[dd].marcado){
                divisasmarcadas.push(this.divisas[dd].iddivisa);
            }
        }
        //console.log(divisasmarcadas);
        this._datomaestroService.tiposcambio(this.token, this.fecha,divisasmarcadas).subscribe(
            response_tipocambio=>{
                this.tiposcambio=response_tipocambio.tiposcambio;
                //console.log(response_tipocambio);
                for (let dd = 0; dd < this.divisas.length; dd++){
                    if(this.divisas[dd].marcado){
                        for (let ddd = 0; ddd < this.divisas.length; ddd++){
                            if(this.divisas[ddd].marcado){
                                let indice_tipocambio = this.tiposcambio.findIndex(x => (x.iddivisaorigen == this.divisas[dd].iddivisa && x.iddivisadestino == this.divisas[ddd].iddivisa));
                                if(indice_tipocambio>=0){
                                    //tipocambio=this.tiposcambio[indice_tipocambio].tipocambio;
                                }else{
                                    this.tiposcambio.push({
                                        iddivisaorigen: this.divisas[dd].iddivisa,
                                        iddivisadestino: this.divisas[ddd].iddivisa,
                                        tipocambio: 1
                                    });
                                }
                            }
                        }
                    }
                        

                }
                console.log(this.tiposcambio);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    desactivarTipoCambio(iddivisaorigen: number, iddivisadestino: number){
        let desactivar: boolean=false;
        if(iddivisaorigen==iddivisadestino || !this.editar_tipo_cambio){
            desactivar=true;
        }
        
        return desactivar;
    }
    
    calculoOpuesto(iddivisaorigen: number, iddivisadestino: number){
        if (this.mantener_relacion){
            let indice_tipocambio_origen = this.tiposcambio.findIndex(x => (x.iddivisaorigen == iddivisaorigen && x.iddivisadestino == iddivisadestino));
            let indice_tipocambio_destino = this.tiposcambio.findIndex(x => (x.iddivisaorigen == iddivisadestino && x.iddivisadestino == iddivisaorigen));
            if(indice_tipocambio_origen>=0 && indice_tipocambio_destino>=0){
                this.tiposcambio[indice_tipocambio_destino].tipocambio=1/this.tiposcambio[indice_tipocambio_origen].tipocambio;
            }
        }
    }
    
    
    guardarDatos(){
        this._datomaestroService.savetiposcambio(this.token, this.fecha, this.tiposcambio).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.cargarTipoCambio();
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
