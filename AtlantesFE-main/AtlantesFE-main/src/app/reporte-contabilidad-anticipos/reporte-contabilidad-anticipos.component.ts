import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-anticipos',
    templateUrl: './reporte-contabilidad-anticipos.component.html',
    styleUrl: './reporte-contabilidad-anticipos.component.css',
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadAnticiposComponent {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public identidad: string;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_anticipos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_anticipos=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 82);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_anticipos=true;
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
        let idtipoentidad=0;
        let id=0;
        if(this.identidad){
            let identidad_split = this.identidad.split("-");
            idtipoentidad=parseInt(identidad_split[0]);
            id=parseInt(identidad_split[1]);
        }
        
        if(this.fechainicial==''){
            fechainicial='2000-01-01';
        }
        
        if (this.fechafinal==''){
            fechafinal=this._usuarioService.getCurrentDateFilterValue();
        }
        
        console.log(this.fechainicial);
        
        this._contabilidadService.reporteAnticipos(this.token, idtipoentidad, id, fechainicial, fechafinal).subscribe(
            response =>{
                console.log(response);
                this.reporte=response.anticipos;
                
                this.reportexlsx={titulo:"Anticipos",cabecera:[
                    {'titulo':'Fecha','tipo':'date','ancho':17},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Recibo','tipo':'string','ancho':17},
                    {'titulo':'Banco','tipo':'string','ancho':20},
                    {'titulo':'Monto','tipo':'number','ancho':17},
                    {'titulo':'Embarque','tipo':'string','ancho':40},
                    {'titulo':'Aplicado','tipo':'number','ancho':17},
                    {'titulo':'Devuelto','tipo':'number','ancho':17},
                    {'titulo':'Saldo','tipo':'number','ancho':17}
                ],
                data:[]};
                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    this.reporte[r].fecha = new Date(this.reporte[r].fecha.replace(/-/g, '\/'))
                    data.push([
                        {'valor': this.reporte[r].fecha},
                        {'valor': this.reporte[r].entidad},
                        {'valor': this.reporte[r].recibo},
                        {'valor': this.reporte[r].banco_cuenta},
                        {'valor': this.reporte[r].monto},
                        {'valor': this.reporte[r].embarque},
                        {'valor': this.reporte[r].aplicado},
                        {'valor': this.reporte[r].devuelto},
                        {'valor': this.reporte[r].saldo}
                    ]);
                }
                
                this.reportexlsx.data=data;
                
                this.generado=false;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
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
