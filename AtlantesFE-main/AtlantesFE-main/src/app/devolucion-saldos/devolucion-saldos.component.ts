import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
declare var $: any;

@Component({
    selector: 'app-devolucion-saldos',
    templateUrl: './devolucion-saldos.component.html',
    styleUrls: ['./devolucion-saldos.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService]
})
export class DevolucionSaldosComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    public identidad: string=null;
    public erroridentidad: boolean=false;
    
    public saldos: Array<any>;
    public devueltos: Array<any>;
    public saldosmarcados: Array<any>;
    public errordetalle: boolean;
    
    public fechadevolucion: string;
    public error_fechadevolucion: boolean;
    public idcuenta: number;
    public error_idcuenta: boolean;
    public numerotransaccion: string;
    public concepto: string;
    public ordende: string;
    
    public cuentas: Array<any>
    
    public totaldevolver: number=0;
    
    public total_monto: number=0;
    public total_devuelto: number=0;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_devolucion_saldos: boolean=false;
    public editar_devolucion_saldos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_devolucion_saldos=true;
            this.editar_devolucion_saldos=true;
        }else{
            let indiceVerDevolucionSaldos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 42);
            if (indiceVerDevolucionSaldos>=0){
                if (this.tokenDetalle.permisos[indiceVerDevolucionSaldos].lectura){
                    this.ver_devolucion_saldos=true;
                }
                if (this.tokenDetalle.permisos[indiceVerDevolucionSaldos].escritura){
                    this.editar_devolucion_saldos=true;
                }
            }
        }
        //this.fecha_historico_inicial=this._usuarioService.getCurrentDateFilterValue();
        //this.fecha_historico_final=this._usuarioService.getCurrentDateFilterValue();
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
        
    }
    
    consultarDatos(){
        this.erroridentidad=false;
        if (this.identidad==null){
            this.erroridentidad=true;
        }
        if (!this.erroridentidad){
            this.cargarSaldos();
            this.cargarDevueltos();
            //this.verHistorial();
        }
    }
    
    cargarSaldos(){
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);
        this._contabilidadService.saldos(this.token, idtipoentidad, id).subscribe(
            response =>{
                this.saldos=response.saldos;
                this.saldos.forEach(object => {
                    object.marcado = false;
                });
                this.getTotal();
                console.log(this.saldos);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    getTotal(){
        this.totaldevolver=0;
        for (let ee = 0; ee < this.saldos.length; ee++){
            if (this.saldos[ee].marcado){
                this.totaldevolver=this.totaldevolver+this.saldos[ee].monto;
            }
        }
    }
    
    prepararDevolucion(){
        this.fechadevolucion=this._usuarioService.getCurrentDateFilterValue();
        this.error_fechadevolucion=false;
        this.idcuenta=null;
        this.error_idcuenta=false;
        this.numerotransaccion='';
        this.concepto='';
        this.ordende='';
        this.errordetalle=false;
        
        this.saldosmarcados = this.saldos.filter(function(cc){
            return cc.marcado
        });
        
        this.saldosmarcados.forEach(object => {
            object.devuelto = object.monto;
            object.errormonto = false;
            object.mensajeerror = '';
        });
        
        this.calcularTotales();
        
    }
    
    calcularTotales(){
        this.total_monto=0;
        this.total_devuelto=0;
        for (let pp = 0; pp < this.saldosmarcados.length; pp++){
            this.total_monto = this.total_monto + this.saldosmarcados[pp].monto;
            this.total_devuelto= this.total_devuelto + this.saldosmarcados[pp].devuelto;
        }
    }
    
    devolver(){
        this.error_fechadevolucion=false;
        if (this.fechadevolucion==''){
            this.error_fechadevolucion=true;
        }
        
        this.error_idcuenta=false;
        if (this.idcuenta==null){
            this.error_idcuenta=true;
        }
        
        let params: Array<any>=[];
        for (let pp = 0; pp < this.saldosmarcados.length; pp++){
            if (this.saldosmarcados[pp].devuelto<0){
                this.saldosmarcados[pp].errormonto=true;
                this.saldosmarcados[pp].mensajeerror='Es menor a cero';
                this.errordetalle=true;
            }
            if (this.saldosmarcados[pp].devuelto>this.saldosmarcados[pp].monto){
                this.saldosmarcados[pp].errormonto=true;
                this.saldosmarcados[pp].mensajeerror='Mayor al total';
                this.errordetalle=true;
            }
            params.push({
                'idanticipo': this.saldosmarcados[pp].idanticipo,
                'monto': this.saldosmarcados[pp].devuelto
            });
        }
        
        if (!this.error_fechadevolucion && !this.error_idcuenta && !this.errordetalle){
            let datosguardar;
            datosguardar={
                fechadevolucion: this.fechadevolucion,
                idcuenta: this.idcuenta,
                numerotransaccion: this.numerotransaccion,
                concepto: this.concepto,
                ordende: this.ordende,
                devoluciones: params
            };
            
            let identidad_split = this.identidad.split("-");
            let idtipoentidad: number=parseInt(identidad_split[0]);
            let id: number=parseInt(identidad_split[1]);
            
            this._contabilidadService.devolversaldos(this.token, idtipoentidad, id, datosguardar).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaDevolver").modal('hide');
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
    
    cargarDevueltos(){
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);
        this._contabilidadService.devueltos(this.token, idtipoentidad, id).subscribe(
            response =>{
                this.devueltos=response.devuelto;
                //console.log(this.pagados);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadDevolucion(iddevolucion: number){
        this._contabilidadService.downloadDevolucion(this.token, iddevolucion).subscribe(
            response =>{
                //console.log(response);
                
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
