import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-libroventas',
    templateUrl: './reporte-contabilidad-libroventas.component.html',
    styleUrls: ['./reporte-contabilidad-libroventas.component.css'],
    providers:[UsuarioService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadLibroventasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_libro_ventas: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
            
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_libro_ventas=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 83);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_libro_ventas=true;
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
        this._contabilidadService.verrangofacturas(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                
                response.facturas.forEach(function(ff) {
                    ff.fecha = new Date(ff.fecha.replace(/-/g, '\/'));
                });
                
                //console.log(response.facturas);
                
                var facturas=response.facturas;
                
                this.reportexlsx={titulo:"Libro de Ventas",cabecera:[
                    {'titulo':'ESPECIFICACION','tipo':'string','ancho':17},
                    {'titulo':'No','tipo':'string','ancho':17},
                    {'titulo':'FECHA DE LA FACTURA','tipo':'date','ancho':17},
                    {'titulo':'N° DE LA FACTURA','tipo':'string','ancho':17},
                    {'titulo':'N° DE AUTORIZACION','tipo':'string','ancho':20},
                    {'titulo':'ESTADO','tipo':'string','ancho':17},
                    {'titulo':'NIT/CI CLIENTE','tipo':'string','ancho':17},
                    {'titulo':'NOMBRE O RAZON SOCIAL','tipo':'string','ancho':30},
                    {'titulo':'IMPORTE TOTAL DE LA VENTA','tipo':'number','ancho':20},
                    {'titulo':'IMPORTE ICE/IEHD/IPJ/TASAS/OTROS NO SUJETOS AL IVA','tipo':'number','ancho':20},
                    {'titulo':'EXPORTACIONES Y OPERACIONES EXENTAS','tipo':'number','ancho':20},
                    {'titulo':'VENTAS GRAVADAS A TASA CERO','tipo':'number','ancho':20},
                    {'titulo':'SUBTOTAL','tipo':'number','ancho':20},
                    {'titulo':'REBAJAS SUJETAS AL IVA','tipo':'number','ancho':20},
                    {'titulo':'IMPORTE BASE PARA DEBITO FISCAL','tipo':'number','ancho':20},
                    {'titulo':'DEBITO FISCAL','tipo':'number','ancho':20},
                    {'titulo':'CODIGO DE CONTROL','tipo':'string','ancho':20}
                ],
                data:[]};
                let data: Array<any>=[];
                
                for(let ff=0; ff<facturas.length; ff++){
                    //facturas[ff].fecha = new Date(facturas[ff].fecha.replace(/-/g, '\/'))
                    let codigoestado = facturas[ff].idestadofactura == 1 ? 'V' : 'A';
                    let monto = facturas[ff].idestadofactura == 1 ? facturas[ff].valorfacturado : 0;
                    this.reporte.push({
                        especificacion: 3,
                        correlativo: (ff+1),
                        fecha: facturas[ff].fecha,
                        numerofactutra: facturas[ff].nrofactura,
                        noautorizacion: facturas[ff].nroautorizacion,
                        codigoestado: codigoestado,
                        nitcliente: facturas[ff].nit,
                        nombre: facturas[ff].nombre,
                        monto: monto,
                        importeotros: 0,
                        exportaciones: 0,
                        ventasgravadas: 0,
                        subtotal: monto,
                        descuentos: 0,
                        importebase: monto,
                        debitofiscal: monto*0.13,
                        codigocontrol: facturas[ff].codigocontrol
                    });
                    
                    data.push([
                        {'valor': 3},
                        {'valor': (ff+1)},
                        {'valor': facturas[ff].fecha},
                        {'valor': facturas[ff].nrofactura},
                        {'valor': facturas[ff].nroautorizacion},
                        {'valor': codigoestado},
                        {'valor': facturas[ff].nit},
                        {'valor': facturas[ff].nombre},
                        {'valor': monto},
                        {'valor': 0},
                        {'valor': 0},
                        {'valor': 0},
                        {'valor': monto},
                        {'valor': 0},
                        {'valor': monto},
                        {'valor': monto*0.13},
                        {'valor': facturas[ff].codigocontrol}
                    ]);
                    
                }
                
                this.reportexlsx.data=data;
                //console.log(this.reporte);
                
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
            const workbook = { Sheets: { 'LibroVentas': worksheet }, SheetNames: ['LibroVentas'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "LibroVentas");
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
