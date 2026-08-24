import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-estadocuentas',
    templateUrl: './reporte-contabilidad-estadocuentas.component.html',
    styleUrls: ['./reporte-contabilidad-estadocuentas.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadEstadocuentasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public identidad: string;
    public fechacorte: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_estado_cuentas: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_estado_cuentas=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 79);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_estado_cuentas=true;
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
                console.log(this.entidades);
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
                
                //console.log(facturas);
                
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
                                            this.reporte.push({
                                                cliente: facturas[ff].entidadcobrar,
                                                fecha: facturas[ff].fecha,
                                                embarque: facturas[ff].embarque,
                                                numero: facturas[ff].nrofactura,
                                                tipo: 'Factura',
                                                monto: facturas[ff].valorfacturado,
                                                diasemision: this.getDayDiff(new Date(facturas[ff].fecha),new Date(fecha)),
                                                cobrado: cobrado,
                                                saldo: facturas[ff].valorfacturado-cobrado,
                                                saldousd: (facturas[ff].valorfacturado-cobrado)*facturas[ff].tipocambio
                                            });
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
                                            this.reporte.push({
                                                cliente: notascobranza[nc].entidadcobrar,
                                                fecha: notascobranza[nc].fecha,
                                                embarque: notascobranza[nc].embarque,
                                                numero: notascobranza[nc].nronotadebito,
                                                tipo: 'Nota de Cobranza',
                                                monto: notascobranza[nc].monto,
                                                diasemision: this.getDayDiff(new Date(notascobranza[nc].fecha),new Date(fecha)),
                                                cobrado: cobrado,
                                                saldo: notascobranza[nc].monto-cobrado,
                                                saldousd: (notascobranza[nc].monto-cobrado)*notascobranza[nc].tipocambio
                                            });
                                        }

                                    }

                                    //let indicefactura = cobrosdetalle.findIndex(x => x.identidad === this.embarque.idexpedidor);


                                }
                                
                                this.reporte.sort((a,b) => b.diasemision - a.diasemision);
                                
                                this.reportexlsx={titulo:"Estado de Cuentas",cabecera:[
                                    {'titulo':'Cliente','tipo':'string','ancho':40},
                                    {'titulo':'Fecha Emision','tipo':'date','ancho':17},
                                    {'titulo':'Embarque','tipo':'string','ancho':20},
                                    {'titulo':'Numero','tipo':'string','ancho':17},
                                    {'titulo':'Tipo','tipo':'string','ancho':20},
                                    {'titulo':'Monto BOB','tipo':'number','ancho':17},
                                    {'titulo':'Dias Emision','tipo':'number','ancho':17},
                                    {'titulo':'Cobrado BOB','tipo':'number','ancho':17},
                                    {'titulo':'Saldo BOB','tipo':'number','ancho':17},
                                    {'titulo':'Saldo USD','tipo':'number','ancho':17}
                                ],
                                data:[]};
                                let data: Array<any>=[];
                                
                                for (let r = 0; r<this.reporte.length; r++){
                                    this.reporte[r].fecha = new Date(this.reporte[r].fecha.replace(/-/g, '\/'))
                                    
                                    
                                    data.push([
                                        {'valor': this.reporte[r].cliente},
                                        {'valor': this.reporte[r].fecha},
                                        {'valor': this.reporte[r].embarque},
                                        {'valor': this.reporte[r].numero},
                                        {'valor': this.reporte[r].tipo},
                                        {'valor': this.reporte[r].monto},
                                        {'valor': this.reporte[r].diasemision},
                                        {'valor': this.reporte[r].cobrado},
                                        {'valor': this.reporte[r].saldo},
                                        {'valor': this.reporte[r].saldousd}
                                    ]);
                                }
                                
                                this.reportexlsx.data=data;
                                //console.log(facturas);
                                //console.log(notascobranza);
                                //console.log(cobrosdetalle);
                                //console.log(this.reporte);
                                
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
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
        /*
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.reportexlsx);
            const workbook = { Sheets: { 'EstadoCuentas': worksheet }, SheetNames: ['EstadoCuentas'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "EstadoCuentas");
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
    
    getDayDiff(startDate: Date, endDate: Date): number {
        const msInDay = 24 * 60 * 60 * 1000;
        return Math.round(
            Math.abs(endDate.getTime() - startDate.getTime()) / msInDay,
        );
    }

}
