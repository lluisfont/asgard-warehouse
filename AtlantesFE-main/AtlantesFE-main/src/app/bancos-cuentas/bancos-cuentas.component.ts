import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-bancos-cuentas',
    templateUrl: './bancos-cuentas.component.html',
    styleUrl: './bancos-cuentas.component.css',
    providers:[UsuarioService,DatoMaestroService]
})
export class BancosCuentasComponent {
    public token:string;
    public tokenDetalle: any;
    
    public cuentas: Array<any>;
    public monedas: Array<any>;
    
    public idcuenta: number;
    public banco: string;
    public error_banco: boolean;
    public cuenta: string;
    public moneda: string;
    public error_moneda: boolean;
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_bancos: boolean=false;
    public editar_bancos: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_bancos=true;
            this.editar_bancos=true;
        }else{
            let indiceVerBancos= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 29);
            if (indiceVerBancos>=0){
                if (this.tokenDetalle.permisos[indiceVerBancos].lectura){
                    this.ver_bancos=true;
                }
                if (this.tokenDetalle.permisos[indiceVerBancos].escritura){
                    this.editar_bancos=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this.cargarBancosCuentas();
        
        this._datomaestroService.divisas(this.token).subscribe(
            response =>{
                
                this.monedas = response.divisas;
                console.log(this.monedas);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
    }
    
    cargarBancosCuentas(){
        this._datomaestroService.cuentas(this.token).subscribe(
            response =>{
                
                this.cuentas = response.cuentas;
                console.log(this.cuentas);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idcuenta: number){
        this.idcuenta = idcuenta;
        if (idcuenta==0){
            this.cabecera_modal="Nueva";
            this.banco='';
            this.cuenta='';
            this.moneda=null;
            this.error_banco=false;
            this.error_moneda=false;
        }else{
            this.cabecera_modal="Editar";
            let indice = this.cuentas.findIndex(x => x.idcuenta === idcuenta);
            this.banco = this.cuentas[indice].banco;
            this.error_banco=false;
            this.cuenta=this.cuentas[indice].cuenta;
            this.error_moneda=false;
            this.moneda=this.cuentas[indice].moneda;
        }
    }
    
    guardarDatos(){
        let error=false;
        this.error_banco=false;
        if (this.banco==''){
            this.error_banco=true;
            error=true;
        }
        this.error_moneda=false;
        if (this.moneda==null){
            this.error_moneda=true;
            error=true;
        }
        
        if(!error){
            let datosguardar;
            datosguardar={
                banco: this.banco,
                cuenta: this.cuenta,
                moneda: this.moneda
            };
            if (this.idcuenta==0){
                this._datomaestroService.addcuenta(this.token, datosguardar).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaCuenta").modal('hide');
                            this.cargarBancosCuentas();
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
                this._datomaestroService.savecuenta(this.token, datosguardar, this.idcuenta).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaCuenta").modal('hide');
                            this.cargarBancosCuentas();
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
