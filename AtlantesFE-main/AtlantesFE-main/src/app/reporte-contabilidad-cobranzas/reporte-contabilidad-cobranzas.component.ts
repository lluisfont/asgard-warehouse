import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-cobranzas',
    templateUrl: './reporte-contabilidad-cobranzas.component.html',
    styleUrls: ['./reporte-contabilidad-cobranzas.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadCobranzasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public identidad: string;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_cobranza: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_cobranza=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 81);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_cobranza=true;
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
        
        this._contabilidadService.reporteCobranzas(this.token, idtipoentidad, id, fechainicial, fechafinal).subscribe(
            response =>{
                console.log(response);
                this.reporte=response.cobranzas;
                
                this.reportexlsx={titulo:"Cobranzas",cabecera:[
                    {'titulo':'Fecha Pago','tipo':'date','ancho':17},
                    {'titulo':'Recibo','tipo':'string','ancho':17},
                    {'titulo':'Embarque','tipo':'string','ancho':20},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'No. F/NC','tipo':'string','ancho':17},
                    {'titulo':'Tipo','tipo':'string','ancho':17},
                    {'titulo':'Monto F/NC','tipo':'number','ancho':17},
                    {'titulo':'Fecha F/NC ','tipo':'date','ancho':17},
                    {'titulo':'Días','tipo':'number','ancho':17},
                    {'titulo':'Monto Cobrado','tipo':'number','ancho':17},
                    {'titulo':'Banco','tipo':'string','ancho':20}
                ],
                data:[]};
                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    this.reporte[r].fecha = new Date(this.reporte[r].fecha.replace(/-/g, '\/'))
                    this.reporte[r].fechapago = new Date(this.reporte[r].fechapago.replace(/-/g, '\/'))


                    data.push([
                        {'valor': this.reporte[r].fechapago},
                        {'valor': this.reporte[r].recibo},
                        {'valor': this.reporte[r].embarque},
                        {'valor': this.reporte[r].entidad},
                        {'valor': this.reporte[r].numero},
                        {'valor': this.reporte[r].tipo},
                        {'valor': this.reporte[r].monto},
                        {'valor': this.reporte[r].fecha},
                        {'valor': this.reporte[r].dias},
                        {'valor': this.reporte[r].cobrado},
                        {'valor': this.reporte[r].cuenta}
                    ]);
                }
                
                this.reportexlsx.data=data;
                
                this.generado=false;
            },
            error=>{
                console.log(<any>error)
            }
        );
        /*
        this._contabilidadService.verrangofacturas(this.token,'2000-01-01',this._usuarioService.getCurrentDateFilterValue()).subscribe(
            response =>{
                
                var facturas=response.facturas.filter(function(cc){
                    return (cc.idestadofactura==1)
                });
                
                this._contabilidadService.verrangonotascobranza(this.token,'2000-01-01',this._usuarioService.getCurrentDateFilterValue()).subscribe(
                    responsenotascobranza =>{
                        var notascobranza=responsenotascobranza.notascobranza.filter(function(cc){
                            return (cc.idestadonotadebito==1)
                        });
                        
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
        */
    }
    
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
        /*
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.reportexlsx);
            const workbook = { Sheets: { 'Cobranzas': worksheet }, SheetNames: ['Cobranzas'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "Cobranzas");
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
