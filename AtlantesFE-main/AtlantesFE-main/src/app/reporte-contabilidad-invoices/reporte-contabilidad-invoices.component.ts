import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-invoices',
    templateUrl: './reporte-contabilidad-invoices.component.html',
    styleUrls: ['./reporte-contabilidad-invoices.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadInvoicesComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public identidad: string;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_invoices: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_invoices=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 78);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_invoices=true;
                }
            }
        }
        this.identidad=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
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
        var fechainicial = this.fechainicial;
        var fechafinal = this.fechafinal;
        this._contabilidadService.verrangoinvoices(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                this.reporte=response.invoices;
                
                this.reporte.forEach(function(ff) {
                    ff.fecha = new Date(ff.fecha.replace(/-/g, '\/'));
                });
                
                this.reportexlsx={titulo:"Invoices",cabecera:[
                    {'titulo':'No Invoice','tipo':'string','ancho':17},
                    {'titulo':'Fecha','tipo':'date','ancho':17},
                    {'titulo':'Embarque','tipo':'string','ancho':20},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Monto USD','tipo':'number','ancho':17},
                    {'titulo':'Facturado USD','tipo':'number','ancho':17},
                    {'titulo':'Debitado USD','tipo':'number','ancho':17},
                    {'titulo':'Total F+D USD','tipo':'number','ancho':17},
                    {'titulo':'Diferencia USD','tipo':'number','ancho':17},
                    {'titulo':'Estado','tipo':'string','ancho':17}
                ],
                data:[]};
                let data: Array<any>=[];
                
                for (let ii = 0; ii < this.reporte.length; ii++){
                    data.push([
                        {'valor': this.reporte[ii].nroinvoice},
                        {'valor': this.reporte[ii].fecha},
                        {'valor': this.reporte[ii].embarque},
                        {'valor': this.reporte[ii].cliente},
                        {'valor': this.reporte[ii].montoinvoice},
                        {'valor': this.reporte[ii].valorfacturado},
                        {'valor': this.reporte[ii].valordebitado},
                        {'valor': this.reporte[ii].total},
                        {'valor': this.reporte[ii].diferencia},
                        {'valor': this.reporte[ii].estado}
                    ]);
                }
                this.reportexlsx.data=data;
                this.generado=false;
                //console.log(this.reporte);
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
            const workbook = { Sheets: { 'Invoices': worksheet }, SheetNames: ['Invoices'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "Invoices");
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
