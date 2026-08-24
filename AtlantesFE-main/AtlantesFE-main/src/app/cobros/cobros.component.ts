import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import * as FileSaver from 'file-saver';

declare var $: any;

@Component({
    selector: 'app-cobros',
    templateUrl: './cobros.component.html',
    styleUrls: ['./cobros.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService]
})
export class CobrosComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    public identidad: string=null;
    public erroridentidad: boolean=false;
    
    public cobros: Array<any>;
    public anticipos: Array<any>;
    public anticiposExcel: Array<any>;
    public anticiposcobros: Array<any>;
    public cobrosmarcados: Array<any>;
    public total_saldo_cobrosmarcados: number=0;
    public total_saldonuevo_cobrosmarcados: number=0;
    public total_afavor_cliente: number=0;
    public anticiposcobros_marcados: number=0;
    
    public cuentas: Array<any>
    public tipostransferencia: Array<any>;
    
    public fecha_anticipos: string;
    public error_fecha_anticipos: boolean;
    public recibo_anticipos: string;
    public error_recibo_anticipos: boolean;
    public idcuenta_anticipos: number;
    public error_idcuenta_anticipos: boolean;
    public idtipotransferencia_anticipos: number;
    public error_idtipotransferencia_anticipos: boolean;
    public glosa_anticipos: string;
    public monto_anticipos: number;
    public error_monto_anticipos: boolean;
    public anticiporeal_anticipos: boolean;
    
    public totalcobrar: number=0;
    public totalanticiposmonto: number=0;
    public totalanticiposaplicado: number=0;
    public totalanticipossaldo: number=0;
    
    public individuales: boolean;
    public multiplesrecibos: boolean;
    
    public fecha_pagos: string;
    public error_fecha_pagos: boolean;
    public usar_cobranza: boolean;
    public idcuenta_pagos: number;
    public error_idcuenta_pagos: boolean;
    public idtipotransferencia_pagos: number;
    public error_idtipotransferencia_pagos: boolean;
    public glosa_pagos: string;
    public anticiporeal_pagos: boolean;
    
    public mensajeserror: Array<any>=[];
    
    public fecha_historico_inicial: string;
    public fecha_historico_final: string;
    public historico_cobros: Array<any>=[];
    
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public p_h: number=1;
    public items_h: number=10;
    public filtro_h: string= '';
    
    public ver_cobros: boolean=false;
    public editar_cobros: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_cobros=true;
            this.editar_cobros=true;
        }else{
            let indiceVerCobros = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 40);
            if (indiceVerCobros>=0){
                if (this.tokenDetalle.permisos[indiceVerCobros].lectura){
                    this.ver_cobros=true;
                }
                if (this.tokenDetalle.permisos[indiceVerCobros].escritura){
                    this.editar_cobros=true;
                }
            }
        }
        this.fecha_historico_inicial=this._usuarioService.getCurrentDateFilterValue();
        this.fecha_historico_final=this._usuarioService.getCurrentDateFilterValue();
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
            this.cargarCobros();
            this.cargarAnticipos();
            this.verHistorial();
        }
    }
    
    cargarCobros(){
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);
        this._contabilidadService.cobros(this.token, idtipoentidad, id).subscribe(
            response =>{
                this.cobros=response.cobros;
                this.cobros.sort((a, b) => {
                    return b.dias - a.dias;
                });

                this.cobros.forEach(object => {
                    object.marcado = false;
                });

                console.log(this.cobros);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    cargarAnticipos(){
        this.anticiposExcel=[];
        this.totalanticiposmonto = 0;
        this.totalanticiposaplicado = 0;
        this.totalanticipossaldo = 0;
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);;
        this._contabilidadService.anticipos(this.token, idtipoentidad, id).subscribe(
            response =>{
                this.anticipos=response.anticipos;
                for (let aa = 0; aa < this.anticipos.length; aa++){
                    this.totalanticiposmonto = this.totalanticiposmonto + this.anticipos[aa].monto;
                    this.totalanticiposaplicado = this.totalanticiposaplicado + this.anticipos[aa].aplicado;
                    this.totalanticipossaldo = this.totalanticipossaldo + this.anticipos[aa].saldo;
                    this.anticipos[aa].fecha = new Date(this.anticipos[aa].fecha.replace(/-/g, '\/'));
                    this.anticiposExcel.push({
                        'Fecha': this.anticipos[aa].fecha,
                        'Recibo': this.anticipos[aa].recibo,
                        'Cuenta': this.anticipos[aa].banco+' '+this.anticipos[aa].cuenta,
                        'Glosa': this.anticipos[aa].glosa,
                        'Monto BOB': this.anticipos[aa].monto,
                        'Aplicado BOB': this.anticipos[aa].aplicado,
                        'Saldo BOB': this.anticipos[aa].saldo
                    });
                }
                this.anticipos.sort(function(a,b){
                    // Turn your strings into dates, and then subtract them
                    // to get a value that is either negative, positive, or zero.
                    return b.fecha - a.fecha;
                  });
                  
                
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    getTotal(){
        this.totalcobrar=0;
        for (let ee = 0; ee < this.cobros.length; ee++){
            if(this.cobros[ee].marcado){
                this.totalcobrar=this.totalcobrar+this.cobros[ee].saldo;
            }
        }
        
    }
    
    exportarAnticiposExcel(){
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.anticiposExcel);
            const workbook = { Sheets: { 'Anticipos': worksheet }, SheetNames: ['Anticipos'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "Anticipos");
        });
    }
    
    saveAsExcelFile(buffer: any, fileName: string): void {
        let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
        let EXCEL_EXTENSION = '.xlsx';
        const data: Blob = new Blob([buffer], {
            type: EXCEL_TYPE
        });
        FileSaver.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
    }
    
    prepararNuevoAnticipo(){
        this.fecha_anticipos=this._usuarioService.getCurrentDateFilterValue();
        this.error_fecha_anticipos=false;
        this.recibo_anticipos='';
        this.error_recibo_anticipos=false;
        this.idcuenta_anticipos=null;
        this.error_idcuenta_anticipos=false;
        this.idtipotransferencia_anticipos=null;
        this.error_idtipotransferencia_anticipos=false;
        this.glosa_anticipos='';
        this.monto_anticipos=0;
        this.error_monto_anticipos=false;
        this.anticiporeal_anticipos=false;
    }
    
    guardarAnticipo(){
        this.error_fecha_anticipos=false;
        this.error_recibo_anticipos=false;
        this.error_idcuenta_anticipos=false;
        this.error_idtipotransferencia_anticipos=false;
        this.error_monto_anticipos=false;
        
        if (this.fecha_anticipos==null){
            this.error_fecha_anticipos=true;
        }
        if (this.recibo_anticipos==''){
            this.error_recibo_anticipos=true;
        }
        if (this.idcuenta_anticipos==null){
            this.error_idcuenta_anticipos=true;
        }
        if (this.idtipotransferencia_anticipos==null){
            this.error_idtipotransferencia_anticipos=true;
        }
        if (this.monto_anticipos<=0){
            this.error_monto_anticipos=true;
        }
        
        if(!this.error_fecha_anticipos && !this.error_recibo_anticipos && !this.error_idcuenta_anticipos && !this.error_idtipotransferencia_anticipos && !this.error_monto_anticipos){
            let identidad_split = this.identidad.split("-");
            let idtipoentidad: number=parseInt(identidad_split[0]);
            let id: number=parseInt(identidad_split[1]);;
            
            let datosguardar;
            datosguardar={
                fecha: this.fecha_anticipos,
                recibo: this.recibo_anticipos,
                idcuenta: this.idcuenta_anticipos,
                idtipotransferencia: this.idtipotransferencia_anticipos,
                glosa: this.glosa_anticipos,
                monto: this.monto_anticipos,
                anticiporeal: this.anticiporeal_anticipos
            };
            
            this._contabilidadService.saveAnticipo(this.token, idtipoentidad, id, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaAnticipo").modal('hide');
                        this.cargarAnticipos();
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
    
    prepararCobro(){
        this.fecha_pagos=this._usuarioService.getCurrentDateFilterValue();
        this.idcuenta_pagos=null;
        this.idtipotransferencia_pagos=null;
        this.glosa_pagos='';
        this.anticiporeal_pagos=false;
        
        this.usar_cobranza=false;
        this.individuales=false;
        this.multiplesrecibos=false;
        
        this.mensajeserror=[];
        
        
        this.anticiposcobros=[];
        this.anticiposcobros.push({
            'idanticipo': 0,
            'marcado': false,
            'glosa': 'Cobranza',
            'recibo': '',
            'individuales': false,
            'multiplesrecibos': false,
            'monto': 0,
            'saldo': 0,
            'totalaplicado': 0
        });
        
        for (let aa = 0; aa < this.anticipos.length; aa++){
            if (this.anticipos[aa].saldo>0){
                this.anticiposcobros.push({
                    'idanticipo': this.anticipos[aa].idanticipo,
                    'marcado': false,
                    'glosa': this.anticipos[aa].glosa,
                    'recibo': this.anticipos[aa].recibo,
                    'individuales': false,
                    'multiplesrecibos': false,
                    'monto': this.anticipos[aa].saldo,
                    'saldo': 0,
                    'totalaplicado': 0
                });
            }
        }
        
        this.cobrosmarcados = this.cobros.filter(function(cc){
            return cc.marcado
        });
        
        this.cobrosmarcados.forEach(object => {
            object.reciboindividual = '';
            object.saldonuevo = object.saldo;
        });
        
        for (let cc = 0; cc < this.cobrosmarcados.length; cc++){
            let cobros: Array<any>=[];
            for (let aa = 0; aa < this.anticiposcobros.length; aa++){
                cobros.push(0);
            }
            this.cobrosmarcados[cc].cobros=cobros;
            this.cobrosmarcados[cc].totalcobros=0;
        }
        
        this.calcularTotales();
            
        
        console.log(this.cobrosmarcados);
        console.log(this.anticiposcobros);
        
    }
    
    marcaranticipocobros(indiceanticipocobros: number){
        this.anticiposcobros[indiceanticipocobros].marcado = !this.anticiposcobros[indiceanticipocobros].marcado;
        if(indiceanticipocobros==0){
            this.usar_cobranza=!this.usar_cobranza;
            if(!this.usar_cobranza){
                this.individuales=false;
                this.multiplesrecibos=false;
                this.idcuenta_pagos=null;
                this.idtipotransferencia_pagos=null;
                this.glosa_pagos='';
                this.anticiporeal_pagos=false;
            }
            if(!this.anticiposcobros[indiceanticipocobros].marcado){
                this.anticiposcobros[indiceanticipocobros].monto=0;
                this.anticiposcobros[indiceanticipocobros].recibo='';
            }
        }
        if(!this.anticiposcobros[indiceanticipocobros].marcado){
            this.cobrosmarcados.forEach(object => {
                object.cobros[indiceanticipocobros] = 0;
            });
        }
        this.calcularTotales();
        
    }
    
    calcularTotales(){
        this.total_saldo_cobrosmarcados=0;
        this.total_saldonuevo_cobrosmarcados=0;
        this.total_afavor_cliente=0;
        this.anticiposcobros_marcados=0;
        
        this.anticiposcobros.forEach(object => {
            object.totalaplicado = 0;
        });
        
        for (let cm = 0; cm < this.cobrosmarcados.length; cm++){
            this.cobrosmarcados[cm].saldonuevo=0;
            let totalcobrado=0;
            for (let cmcc = 0; cmcc < this.cobrosmarcados[cm].cobros.length; cmcc++){
                totalcobrado=totalcobrado+parseFloat(this.cobrosmarcados[cm].cobros[cmcc]);
                this.anticiposcobros[cmcc].totalaplicado=parseFloat(this.anticiposcobros[cmcc].totalaplicado)+parseFloat(this.cobrosmarcados[cm].cobros[cmcc]);
            }
            this.cobrosmarcados[cm].totalcobros=totalcobrado;
            this.cobrosmarcados[cm].saldonuevo=parseFloat(this.cobrosmarcados[cm].saldo)-totalcobrado;
            this.total_saldo_cobrosmarcados = this.total_saldo_cobrosmarcados + parseFloat(this.cobrosmarcados[cm].saldo);
            this.total_saldonuevo_cobrosmarcados = this.total_saldonuevo_cobrosmarcados + parseFloat(this.cobrosmarcados[cm].saldonuevo);
        }
        
        if (this.multiplesrecibos){
            this.anticiposcobros[0].monto=this.anticiposcobros[0].totalaplicado;
        }
        
        this.anticiposcobros.forEach(object => {
            object.saldo = object.monto-object.totalaplicado;
            this.total_afavor_cliente=this.total_afavor_cliente+object.saldo;
        });
        
        for (let am = 0; am < this.anticiposcobros.length; am++){
            if (this.anticiposcobros[am].marcado){
                this.anticiposcobros_marcados++;
            }
        }
        
        
        
        
        
    }
    
    calcularMontos(indiceanticipocobros: number){
        let montoaplicar = parseFloat(this.anticiposcobros[indiceanticipocobros].monto);
        for (let cc = 0; cc < this.cobrosmarcados.length; cc++){
            if (montoaplicar >= this.cobrosmarcados[cc].saldo){
                this.cobrosmarcados[cc].cobros[indiceanticipocobros]=parseFloat(this.cobrosmarcados[cc].saldo).toFixed(2);
            }else{
                this.cobrosmarcados[cc].cobros[indiceanticipocobros]=montoaplicar.toFixed(2);
            }
            montoaplicar=montoaplicar-parseFloat(this.cobrosmarcados[cc].cobros[indiceanticipocobros]);
        }
        this.calcularTotales();
    }
    
    recibosDistintos(){
        this.multiplesrecibos=!this.multiplesrecibos;
        if(this.multiplesrecibos){
            this.anticiposcobros[0].recibo='';
        }else{
            this.cobrosmarcados.forEach(object => {
                object.reciboindividual = '';
            });
        }
        this.calcularTotales();
    }
    
    aplicar(){
        this.mensajeserror=[];
        if (this.fecha_pagos==null || this.fecha_pagos==''){
            this.mensajeserror.push(
                'El campo Fecha de Pago es obligatorio'
            );
        }
        for (let aa = 0; aa < this.anticiposcobros.length; aa++){
            if(this.anticiposcobros[aa].marcado){
                if(this.anticiposcobros[aa].saldo.toFixed(2)<0){
                    this.mensajeserror.push(
                        '<strong>'+this.anticiposcobros[aa].glosa+'</strong> El monto aplicado ('+parseFloat(this.anticiposcobros[aa].totalaplicado).toFixed(2)+') del ingreso no puede ser mayor al disponible ('+parseFloat(this.anticiposcobros[aa].monto).toFixed(2)+')'
                    );
                }
                if(aa==0){
                    if (!this.multiplesrecibos && this.anticiposcobros[aa].recibo==''){
                        this.mensajeserror.push(
                            'El campo Recibo para el ingreso <strong>'+this.anticiposcobros[aa].glosa+'</strong> es obligatorio'
                        );
                    }
                    if (this.idcuenta_pagos==null){
                        this.mensajeserror.push(
                            'El campo Banco/Cuenta es obligatorio'
                        );
                    }
                    if (this.idtipotransferencia_pagos==null){
                        this.mensajeserror.push(
                            'El campo Tipo Transferencia es obligatorio'
                        );
                    }
                }
            }
        }
        
        for (let cc = 0; cc < this.cobrosmarcados.length; cc++){
            if (this.cobrosmarcados[cc].saldonuevo<0){
                this.mensajeserror.push(
                    '<strong>'+this.cobrosmarcados[cc].tipodocumento+' '+this.cobrosmarcados[cc].numerodocumento+'</strong>: El monto total aplicado ('+parseFloat(this.cobrosmarcados[cc].totalcobros)+') no puede ser mayor al monto actual ('+parseFloat(this.cobrosmarcados[cc].saldo)+')'
                );
            }
            if (this.multiplesrecibos && this.cobrosmarcados[cc].reciboindividual=='' && parseFloat(this.cobrosmarcados[cc].cobros[0])>0){
                this.mensajeserror.push(
                    '<strong>'+this.cobrosmarcados[cc].tipodocumento+' '+this.cobrosmarcados[cc].numerodocumento+'</strong>: El numero de recibo individual no puede estar vacio'
                );
            }
        }
        
        if (this.mensajeserror.length==0){
            let params: Array<any>=[];
            let cobros: Array<any>=[];
            for (let aa = 0; aa < this.anticiposcobros.length; aa++){
                if(this.anticiposcobros[aa].marcado){
                    cobros=[];
                    let idcuenta=null;
                    let idtipotransferencia=null;
                    let glosa=null;
                    let anticiporeal=null;
                    if(aa==0){
                        idcuenta = this.idcuenta_pagos;
                        idtipotransferencia=this.idtipotransferencia_pagos;
                        glosa=this.glosa_pagos;
                        anticiporeal=this.anticiporeal_pagos;
                    }
                    if (this.multiplesrecibos && aa==0){
                        for (let cc = 0; cc < this.cobrosmarcados.length; cc++){
                            cobros=[];
                            cobros.push({
                                'iddocumento': this.cobrosmarcados[cc].iddocumento,
                                'idtipodocumento': this.cobrosmarcados[cc].idtipodocumento,
                                'monto': this.cobrosmarcados[cc].cobros[aa]
                            });
                            if(parseFloat(this.cobrosmarcados[cc].cobros[aa])>0){
                                params.push({
                                    'idanticipo': this.anticiposcobros[aa].idanticipo,
                                    'recibo': this.cobrosmarcados[cc].reciboindividual,
                                    'idcuenta': idcuenta,
                                    'idtipotransferencia': idtipotransferencia,
                                    'glosa': glosa,
                                    'anticiporeal': anticiporeal,
                                    'monto': this.cobrosmarcados[cc].cobros[aa],
                                    'cobros': cobros
                                });
                            }
                                
                        }
                    }else{
                        for (let cc = 0; cc < this.cobrosmarcados.length; cc++){
                            if(parseFloat(this.cobrosmarcados[cc].cobros[aa])>0){
                                cobros.push({
                                    'iddocumento': this.cobrosmarcados[cc].iddocumento,
                                    'idtipodocumento': this.cobrosmarcados[cc].idtipodocumento,
                                    'monto': this.cobrosmarcados[cc].cobros[aa]
                                });
                            }
                        }
                        if (parseFloat(this.anticiposcobros[aa].totalaplicado)>0){
                            params.push({
                                'idanticipo': this.anticiposcobros[aa].idanticipo,
                                'recibo': this.anticiposcobros[aa].recibo,
                                'idcuenta': idcuenta,
                                'idtipotransferencia': idtipotransferencia,
                                'glosa': glosa,
                                'anticiporeal': anticiporeal,
                                'monto': this.anticiposcobros[aa].monto,
                                'cobros': cobros
                            });
                        }
                    }
                }
            }
            
            let datosguardar;
            datosguardar={
                fechapago: this.fecha_pagos,
                aplicaciones: params
            };
            
            let identidad_split = this.identidad.split("-");
            let idtipoentidad: number=parseInt(identidad_split[0]);
            let id: number=parseInt(identidad_split[1]);;
            
            this._contabilidadService.aplicarcobros(this.token, idtipoentidad, id, datosguardar).subscribe(
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
            
            console.log(params);
            
        }
        
        console.log(this.anticiposcobros);
        console.log(this.cobrosmarcados);
        
    }
    
    verHistorial(){
        let identidad_split = this.identidad.split("-");
        let idtipoentidad: number=parseInt(identidad_split[0]);
        let id: number=parseInt(identidad_split[1]);
        this._contabilidadService.historicopagos(this.token, idtipoentidad, id, this.fecha_historico_inicial, this.fecha_historico_final).subscribe(
            response =>{
                this.historico_cobros=response.historico;
                

                console.log(this.historico_cobros);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadCobro(numero: number){
        this._contabilidadService.downloadCobro(this.token, numero).subscribe(
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
    
    downloadAnticipo(idanticipo: number){
        //console.log(this.token);
        this._contabilidadService.downloadAnticipo(this.token, idanticipo).subscribe(
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
