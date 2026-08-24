import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';

declare var $: any;

@Component({
    selector: 'app-pagos',
    templateUrl: './pagos.component.html',
    styleUrls: ['./pagos.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService]
})
export class PagosComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    public identidad: string=null;
    public erroridentidad: boolean=false;
    
    public pagos:Array<any>;
    public pagados:Array<any>;
    public pagosmarcados:Array<any>=[];
    public cuentas:Array<any>;
    public tipostransferencia:Array<any>;
    
    public totalpagar: number=0;
    public iddivisamarcada: number=0;
    public divisamarcada: string='';
    
    public fecha: string=null;
    public error_fecha: boolean=false;
    public idcuenta: number=null;
    public error_idcuenta: boolean=false;
    public pagoa: string;
    public idtipotransferencia: number=null;
    public error_idtipotransferencia: boolean=false
    public nrotransaccion: string='';
    public alaordende: string='';
    public concepto: string='';
    
    public errordetalle: boolean=false;
    
    public total_saldo_pagosmarcados: number=0;
    public total_saldonuevo_pagosmarcados: number=0;
    public total_diferencia_saldo_pagosmarcados: number=0;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_pagos: boolean=false;
    public editar_pagos: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_pagos=true;
            this.editar_pagos=true;
        }else{
            let indiceVerPagos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 41);
            if (indiceVerPagos>=0){
                if (this.tokenDetalle.permisos[indiceVerPagos].lectura){
                    this.ver_pagos=true;
                }
                if (this.tokenDetalle.permisos[indiceVerPagos].escritura){
                    this.editar_pagos=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades=response.entidades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.cuentas(this.token).subscribe(
            response =>{
                this.cuentas=response.cuentas;
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.tipostransferencia(this.token).subscribe(
            response =>{
                this.tipostransferencia=response.tipostransferencia;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    consultarDatos(){
        this.erroridentidad=false;
        if (this.identidad==null){
            this.erroridentidad=true;
        }
        if (!this.erroridentidad){
            this.cargarPagos();
            this.cargarPagados();
            //this.cargarAnticipos();
            //this.verHistorial();
        }
    }
    
    cargarPagos(){
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);
        this._contabilidadService.pagos(this.token, idtipoentidad, id).subscribe(
            response =>{
                this.pagos=response.pagos;
                

                this.pagos.forEach(object => {
                    object.marcado = false;
                });
                this.getTotal();
                console.log(this.pagos);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }
    
    getTotal(){
        this.totalpagar=0;
        this.divisamarcada='';
        this.iddivisamarcada=0;
        for (let ee = 0; ee < this.pagos.length; ee++){
            if(this.pagos[ee].marcado){
                this.iddivisamarcada=this.pagos[ee].iddivisa;
                this.divisamarcada=this.pagos[ee].divisa;
                this.totalpagar=this.totalpagar+this.pagos[ee].saldo;
            }
        }
    }
    
    prepararPago(){
        this.error_fecha=false;
        this.fecha=this._usuarioService.getCurrentDateFilterValue();
        this.idcuenta=null;
        this.error_idcuenta=false;
        this.pagoa='0';
        this.idtipotransferencia=null;
        this.error_idtipotransferencia=false;
        this.nrotransaccion='';
        this.alaordende='';
        this.concepto='';
        
        
        this.pagosmarcados = this.pagos.filter(function(cc){
            return cc.marcado
        });
        
        this.pagosmarcados.forEach(object => {
            object.saldonuevo = object.saldo;
            object.errormonto = false;
            object.mensajeerror = '';
        });
        
        this.calcularTotales();
        
    }
    
    calcularTotales(){
        this.total_saldo_pagosmarcados=0;
        this.total_saldonuevo_pagosmarcados=0;
        this.total_diferencia_saldo_pagosmarcados=0;
        for (let pp = 0; pp < this.pagosmarcados.length; pp++){
            this.total_saldo_pagosmarcados = this.total_saldo_pagosmarcados + this.pagosmarcados[pp].saldo;
            this.total_saldonuevo_pagosmarcados= this.total_saldonuevo_pagosmarcados + this.pagosmarcados[pp].saldonuevo;
        }
        this.total_diferencia_saldo_pagosmarcados=this.total_saldo_pagosmarcados-this.total_saldonuevo_pagosmarcados;
        
    }
    
    pagar(){
        this.error_fecha=false;
        if (this.fecha==''){
            this.error_fecha=true;
        }
        
        this.error_idcuenta=false;
        if (this.idcuenta==null){
            this.error_idcuenta=true;
        }
        this.error_idtipotransferencia=false;
        if (this.idtipotransferencia==null){
            this.error_idtipotransferencia=true;
        }
        
        let params: Array<any>=[];
        for (let pp = 0; pp < this.pagosmarcados.length; pp++){
            if (this.pagosmarcados[pp].saldonuevo<0){
                this.pagosmarcados[pp].errormonto=true;
                this.pagosmarcados[pp].mensajeerror='Es menor a cero';
                this.errordetalle=true;
            }
            if (this.pagosmarcados[pp].saldonuevo>this.pagosmarcados[pp].saldo){
                this.pagosmarcados[pp].errormonto=true;
                this.pagosmarcados[pp].mensajeerror='Mayor al total';
                this.errordetalle=true;
            }
            params.push({
                'idfacturapago': this.pagosmarcados[pp].idfacturapago,
                'monto': this.pagosmarcados[pp].saldonuevo,
                'iddivisa': this.pagosmarcados[pp].iddivisa
            });
        }
        console.log(this.pagosmarcados);
        
        if (!this.error_fecha && !this.error_idcuenta && !this.error_idtipotransferencia && !this.errordetalle){
            let datosguardar;
            datosguardar={
                fecha: this.fecha,
                idcuenta: this.idcuenta,
                pagoa: this.pagoa,
                idtipotransferencia: this.idtipotransferencia,
                nrotransaccion: this.nrotransaccion,
                alaordende: this.alaordende,
                concepto: this.concepto,
                aplicaciones: params
            };
            
            let identidad_split = this.identidad.split("-");
            let idtipoentidad: number=parseInt(identidad_split[0]);
            let id: number=parseInt(identidad_split[1]);
            
            this._contabilidadService.aplicarpagos(this.token, idtipoentidad, id, datosguardar).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaAplicar").modal('hide');
                        this.consultarDatos();
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
    
    cargarPagados(){
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);
        this._contabilidadService.pagados(this.token, idtipoentidad, id).subscribe(
            response =>{
                this.pagados=response.pagado;
                console.log(this.pagados);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadPago(idpago: number){
        this._contabilidadService.downloadPago(this.token, idpago).subscribe(
            response =>{
                console.log(response);
                
                if(response.codigo==200){
                    const byteCharacters = atob(response.data);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: response.pathinfo});
                    var url = window.URL.createObjectURL(blob);
                    const downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.target = "_blank";
                    downloadLink.click();
                }else{
                    this.toast_mensaje="Ocurrio un error, intente mas tarde";
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

}
