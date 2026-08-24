import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-inventario-vencimiento',
    templateUrl: './reporte-almacen-inventario-vencimiento.component.html',
    styleUrl: './reporte-almacen-inventario-vencimiento.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService,ExportExcelService]
})
export class ReporteAlmacenInventarioVencimientoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: string;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_inventario_vencimiento: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _entidadService: EntidadesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_inventario_vencimiento=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 65);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_inventario_vencimiento=true;
                }
            }
        }
        this.idcliente=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                
                this.entidades = response.clientes;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    generarReporte(){
        this.generado=true;
        this._almacenesService.reporteinventariovencimiento(this.token, this.idcliente, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                
                this.reporte=response.reporte;
                
                this.reportexlsx={titulo:"Inventario Vencimiento",cabecera:[
                    {'titulo':'Centro Distribución','tipo':'string','ancho':20},
                    {'titulo':'Rubro','tipo':'string','ancho':20},
                    {'titulo':'Almacen','tipo':'string','ancho':25},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Descripcion','tipo':'string','ancho':40},
                    {'titulo':'Cantidad','tipo':'number','ancho':13},
                    {'titulo':'Embalaje','tipo':'string','ancho':10},
                    {'titulo':'Fec Venc','tipo':'date','ancho':20},
                    {'titulo':'Fecha Ingreso','tipo':'date','ancho':17},
                    {'titulo':'Condicion','tipo':'string','ancho':17},
                    {'titulo':'Observaciones','tipo':'string','ancho':30},
                    {'titulo':'Procedencia','tipo':'string','ancho':20},
                    {'titulo':'Ultimo Despacho','tipo':'date','ancho':17}
                ],
                data:[]};
                
                
                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].centro_distribucion},
                        {'valor': this.reporte[r].rubro_producto},
                        {'valor': this.reporte[r].almacen},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].cantidad},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].fechavencimiento},
                        {'valor': this.reporte[r].fechaingreso},
                        {'valor': this.reporte[r].condicion},
                        {'valor': this.reporte[r].observaciones},
                        {'valor': this.reporte[r].procedencia},
                        {'valor': this.reporte[r].fecha_ultima_salida}
                    ]);
                }
                
                this.reportexlsx.data=data;
                this.generado=false;
                
            },
            error=>{
                console.log(<any>error)
                this.generado=false;
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
