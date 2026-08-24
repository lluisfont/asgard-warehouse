import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-saldos',
    templateUrl: './reporte-contabilidad-saldos.component.html',
    styleUrls: ['./reporte-contabilidad-saldos.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadSaldosComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public identidad: string;
    public fechacorte: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_saldos: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_saldos=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 80);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_saldos=true;
                }
            }
        }
        this.identidad=null;
        this.fechacorte = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades = response.entidades;
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    generarReporte(){
        this.generado=true;
        this.reporte=[];
        //this.reportexlsx=[];
        var fecha = this.fechacorte;
        this._contabilidadService.verrangofacturas(this.token,'2000-01-01',fecha).subscribe(
            response =>{
                
                var facturas=response.facturas.filter(function(cc){
                    return (cc.idestadofactura==1)
                });
                
                this._contabilidadService.verrangonotascobranza(this.token,'2000-01-01',fecha).subscribe(
                    responsenotascobranza =>{
                        var notascobranza=responsenotascobranza.notascobranza.filter(function(cc){
                            return (cc.idestadonotadebito==1)
                        });
                        
                        
                        this._contabilidadService.cobrosdetalle(this.token).subscribe(
                            responsecobros =>{
                                //var cobrosdetalle=responsecobros.cobrosdetalle;
                                
                                var cobrosdetalle=responsecobros.cobrosdetalle.filter(function(cc){
                                    return (cc.fechapago<=fecha)
                                });
                                
                                var agregarfactura: boolean=true;
                                var agregarnotacobranza: boolean=true;
                                
                                
                                
                                for (let ff = 0; ff < facturas.length; ff++){
                                    agregarfactura=true;
                                    if (this.identidad != null){
                                        if ((facturas[ff].idcobraratipo + "-" + facturas[ff].idcobrara) == this.identidad){
                                            agregarfactura=true;
                                        }else{
                                            agregarfactura=false;
                                        }
                                    }
                                    
                                    if(agregarfactura){
                                        var cobrado=0;
                                        for (let cc = 0; cc < cobrosdetalle.length; cc++){
                                            if(facturas[ff].idfactura==cobrosdetalle[cc].idfacturanotadebito && 1==cobrosdetalle[cc].idtipocobro){
                                                cobrado=cobrado+cobrosdetalle[cc].cobrado;
                                            }
                                        }

                                        if(parseFloat(facturas[ff].valorfacturado.toFixed(2))>parseFloat(cobrado.toFixed(2))){
                                            let indiceentidad = this.entidades.findIndex(x => x.identidad === (facturas[ff].idcobraratipo + "-" + facturas[ff].idcobrara));
                                            let plazo: number = this.entidades[indiceentidad].plazo;
                                            
                                            let v: number=0;
                                            let s_1_5: number=0;
                                            let s_5_30: number=0;
                                            let s_3_60: number=0;
                                            let s_60: number=0;
                                            
                                            let ubicacionmonto=this.tiempomora(plazo,facturas[ff].fecha);
                                            
                                            switch(ubicacionmonto){
                                                case 'v':
                                                    v=facturas[ff].valorfacturado-cobrado;
                                                    break;
                                                case 's_1_5':
                                                    s_1_5=facturas[ff].valorfacturado-cobrado;
                                                    break;
                                                case 's_5_30':
                                                    s_5_30=facturas[ff].valorfacturado-cobrado;
                                                    break;
                                                case 's_3_60':
                                                    s_3_60=facturas[ff].valorfacturado-cobrado;
                                                    break;
                                                case 's_60':
                                                    s_60=facturas[ff].valorfacturado-cobrado;
                                                    break;
                                            }

                                            let indicecliente = this.reporte.findIndex(x => x.identidad === (facturas[ff].idcobraratipo + "-" + facturas[ff].idcobrara));
                                            
                                            if(indicecliente>=0){
                                                this.reporte[indicecliente][ubicacionmonto]=this.reporte[indicecliente][ubicacionmonto]+facturas[ff].valorfacturado-cobrado;
                                                this.reporte[indicecliente].saldo=this.reporte[indicecliente].saldo+facturas[ff].valorfacturado-cobrado;
                                            }else{
                                                this.reporte.push({
                                                    identidad: facturas[ff].idcobraratipo + "-" + facturas[ff].idcobrara,
                                                    cliente: facturas[ff].entidadcobrar,
                                                    saldo: facturas[ff].valorfacturado-cobrado,
                                                    plazo: plazo,
                                                    v: v,
                                                    s_1_5: s_1_5,
                                                    s_5_30: s_5_30,
                                                    s_3_60: s_3_60,
                                                    s_60: s_60
                                                });
                                            }
                                            
                                            
                                                
                                        }

                                    }

                                    //let indicefactura = cobrosdetalle.findIndex(x => x.identidad === this.embarque.idexpedidor);


                                }
                                
                                
                                for (let nc = 0; nc < notascobranza.length; nc++){
                                    agregarnotacobranza=true;
                                    if (this.identidad != null){
                                        if ((notascobranza[nc].idcobraratipo + "-" + notascobranza[nc].idcobrara) == this.identidad){
                                            agregarnotacobranza=true;
                                        }else{
                                            agregarnotacobranza=false;
                                        }
                                    }
                                    
                                    if(agregarnotacobranza){
                                        var cobrado=0;
                                        for (let cc = 0; cc < cobrosdetalle.length; cc++){
                                            if(notascobranza[nc].idnotadebito==cobrosdetalle[cc].idfacturanotadebito && 2==cobrosdetalle[cc].idtipocobro){
                                                cobrado=cobrado+cobrosdetalle[cc].cobrado;
                                            }
                                        }

                                        if(parseFloat(notascobranza[nc].monto.toFixed(2))>parseFloat(cobrado.toFixed(2))){
                                            
                                            let indiceentidad = this.entidades.findIndex(x => x.identidad === (notascobranza[nc].idcobraratipo + "-" + notascobranza[nc].idcobrara));
                                            let plazo = this.entidades[indiceentidad].plazo;
                                            
                                            let v: number=0;
                                            let s_1_5: number=0;
                                            let s_5_30: number=0;
                                            let s_3_60: number=0;
                                            let s_60: number=0;
                                            
                                            let ubicacionmonto=this.tiempomora(plazo,notascobranza[nc].fecha);
                                            
                                            switch(ubicacionmonto){
                                                case 'v':
                                                    v=notascobranza[nc].monto-cobrado;
                                                    break;
                                                case 's_1_5':
                                                    s_1_5=notascobranza[nc].monto-cobrado;
                                                    break;
                                                case 's_5_30':
                                                    s_5_30=notascobranza[nc].monto-cobrado;
                                                    break;
                                                case 's_3_60':
                                                    s_3_60=notascobranza[nc].monto-cobrado;
                                                    break;
                                                case 's_60':
                                                    s_60=notascobranza[nc].monto-cobrado;
                                                    break;
                                            }

                                            let indicecliente = this.reporte.findIndex(x => x.identidad === (notascobranza[nc].idcobraratipo + "-" + notascobranza[nc].idcobrara));
                                            
                                            if(indicecliente>=0){
                                                this.reporte[indicecliente][ubicacionmonto]=this.reporte[indicecliente][ubicacionmonto]+notascobranza[nc].monto-cobrado;
                                                this.reporte[indicecliente].saldo=this.reporte[indicecliente].saldo+notascobranza[nc].monto-cobrado;
                                            }else{
                                                this.reporte.push({
                                                    identidad: notascobranza[nc].idcobraratipo + "-" + notascobranza[nc].idcobrara,
                                                    cliente: notascobranza[nc].entidadcobrar,
                                                    saldo: notascobranza[nc].monto-cobrado,
                                                    plazo: plazo,
                                                    v: v,
                                                    s_1_5: s_1_5,
                                                    s_5_30: s_5_30,
                                                    s_3_60: s_3_60,
                                                    s_60: s_60
                                                });
                                            }
                                            
                                            /*
                                            this.reporte.push({
                                                identidad: notascobranza[nc].idcobraratipo + "-" + notascobranza[nc].idcobrara,
                                                cliente: notascobranza[nc].entidadcobrar,
                                                fecha: notascobranza[nc].fecha,
                                                embarque: notascobranza[nc].embarque,
                                                numero: notascobranza[nc].nronotadebito,
                                                tipo: 'Nota de Cobranza',
                                                monto: notascobranza[nc].monto,
                                                //diasemision: this.getDayDiff(new Date(notascobranza[nc].fecha),new Date(fecha)),
                                                cobrado: cobrado,
                                                plazo: plazo,
                                                fecha_add: this.tiempomora(plazo,notascobranza[nc].fecha),
                                                saldo: notascobranza[nc].monto-cobrado
                                            });
                                            */
                                        }

                                    }

                                    //let indicefactura = cobrosdetalle.findIndex(x => x.identidad === this.embarque.idexpedidor);


                                }
                                
                                this.reporte.sort((a,b) => b.cliente - a.cliente);
                                
                                
                                this.reportexlsx={titulo:"Saldos",cabecera:[
                                    {'titulo':'Cliente','tipo':'string','ancho':40},
                                    {'titulo':'Saldo','tipo':'number','ancho':20},
                                    {'titulo':'Tiempo financ.','tipo':'number','ancho':20},
                                    {'titulo':'Vigente','tipo':'number','ancho':20},
                                    {'titulo':'1 a 5 días','tipo':'number','ancho':20},
                                    {'titulo':'5 a 30 días','tipo':'number','ancho':20},
                                    {'titulo':'30 a 60 días','tipo':'number','ancho':20},
                                    {'titulo':'mas de 60 días','tipo':'number','ancho':20}
                                ],
                                data:[]};
                                let data: Array<any>=[];
                                for (let r = 0; r<this.reporte.length; r++){
                                    data.push([
                                        {'valor': this.reporte[r].cliente},
                                        {'valor': this.reporte[r].saldo},
                                        {'valor': this.reporte[r].plazo},
                                        {'valor': this.reporte[r].v},
                                        {'valor': this.reporte[r].s_1_5},
                                        {'valor': this.reporte[r].s_5_30},
                                        {'valor': this.reporte[r].s_3_60},
                                        {'valor': this.reporte[r].s_60}
                                    ]);
                                }
                                this.reportexlsx.data=data;
                                /*
                                console.log(facturas);
                                console.log(notascobranza);
                                console.log(cobrosdetalle);
                                console.log(this.reporte);
                                */
                                this.generado=false;


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
                
                
                
                
                        
                /*
                facturas.forEach(
                    factura => (factura.fecha = new Date(factura.fecha.replace(/-/g, '\/')))
                );
                */

                

            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    tiempomora(dias: number, fecha: string){
        //let fechasplit=fecha.split("-");
        const fechafactura = new Date(fecha.replace(/-/g, '\/'));
        fechafactura.setDate(fechafactura.getDate() + dias);
        let diferenciafechas=this.getDayDiff(fechafactura, new Date())-1;
        let mora='';
        switch (true) {
            case (diferenciafechas <= 0):
                mora='v';
                break;
            case (diferenciafechas <= 5):
                mora='s_1_5';
                break;
            case (diferenciafechas <= 30):
                mora='s_5_30';
                break;
            case (diferenciafechas <= 60):
                mora='s_3_60';
                break;
            default:
                mora='s_60';
                break;
        }
        
        
        return mora;
        
    }
    
    getDayDiff(startDate: Date, endDate: Date): number {
        const msInDay = 24 * 60 * 60 * 1000;
        return Math.round(
            Math.abs(endDate.getTime() - startDate.getTime()) / msInDay,
        );
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
        /*
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.reportexlsx);
            const workbook = { Sheets: { 'Saldos': worksheet }, SheetNames: ['Saldos'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "Saldos");
        });
        */
    }
    
    saveAsExcelFile(buffer: any, fileName: string): void {
        let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
        let EXCEL_EXTENSION = '.xlsx';
        const data: Blob = new Blob([buffer], {
            type: EXCEL_TYPE
        });
        FileSaver.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
    }
    

}
