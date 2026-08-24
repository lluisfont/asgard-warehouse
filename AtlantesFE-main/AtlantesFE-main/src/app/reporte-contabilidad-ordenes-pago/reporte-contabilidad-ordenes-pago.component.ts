import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-ordenes-pago',
    templateUrl: './reporte-contabilidad-ordenes-pago.component.html',
    styleUrls: ['./reporte-contabilidad-ordenes-pago.component.css'],
    providers:[UsuarioService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadOrdenesPagoComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_ordenes_pago: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
            
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_ordenes_pago=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 77);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_ordenes_pago=true;
                }
            }
        }
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
    }
    
    generarReporte(){
        this.generado=true;
        this.reporte=[];
        //this.reportexlsx=[];
        var fechainicial = this.fechainicial;
        var fechafinal = this.fechafinal;
        this._contabilidadService.verordenespagorango(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                console.log(response);
                this.reporte=response.ordenespago;
                
                this.reporte.forEach(
                    reporte => (reporte.fecha = new Date(reporte.fecha.replace(/-/g, '\/')))
                );
                this.reporte.forEach(
                    reporte => (reporte.saldo = reporte.monto-reporte.pagado)
                );
                
                this.reportexlsx={titulo:"Ordenes de Pago",cabecera:[
                    {'titulo':'Numero','tipo':'string','ancho':17},
                    {'titulo':'Fecha','tipo':'date','ancho':17},
                    {'titulo':'Embarque','tipo':'string','ancho':20},
                    {'titulo':'Proveedor','tipo':'string','ancho':20},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Monto','tipo':'number','ancho':17},
                    {'titulo':'Pagado','tipo':'number','ancho':17},
                    {'titulo':'Saldo','tipo':'number','ancho':17},
                    {'titulo':'Divisa','tipo':'string','ancho':15},
                    {'titulo':'Tipo OP','tipo':'string','ancho':17},
                    {'titulo':'Estado','tipo':'string','ancho':17}
                ],
                data:[]};
                let data: Array<any>=[];
                
                for(let ff=0; ff<this.reporte.length; ff++){
                    //facturas[ff].fecha = new Date(facturas[ff].fecha.replace(/-/g, '\/'))
                    data.push([
                        {'valor': this.reporte[ff].numerofactura},
                        {'valor': this.reporte[ff].fecha},
                        {'valor': this.reporte[ff].embarque},
                        {'valor': this.reporte[ff].proveedor},
                        {'valor': this.reporte[ff].cliente},
                        {'valor': this.reporte[ff].monto},
                        {'valor': this.reporte[ff].pagado},
                        {'valor': this.reporte[ff].saldo},
                        {'valor': this.reporte[ff].divisa},
                        {'valor': this.reporte[ff].tipoop},
                        {'valor': this.reporte[ff].estadonotadebito}
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
        /*
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.reportexlsx);
            const workbook = { Sheets: { 'OrdenesPago': worksheet }, SheetNames: ['OrdenesPago'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "OrdenesPago");
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
