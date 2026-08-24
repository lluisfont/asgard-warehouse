import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-movimiento-detalle',
    templateUrl: './reporte-almacen-movimiento-detalle.component.html',
    styleUrl: './reporte-almacen-movimiento-detalle.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenMovimientoDetalleComponent {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: number;
    public lote: string='';
    public codigo: string='';
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_movimiento_detalle: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_movimiento_detalle=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 88);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_movimiento_detalle=true;
                }
            }
        }
        this.idcliente=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }
    
    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades = response.entidades.filter(function (entidad) {
                    return entidad.idtipoentidad==1;
                });
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
        var idcliente: number=0;
        if (this.idcliente!=null){
            idcliente=this.idcliente;
        }
        
        this._almacenesService.reportemovimientodetalle(this.token, idcliente, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                //
                this.reporte=response.movimientos;
                
                if(this.lote!=''){
                    this.reporte = this.reporte.filter(reporte => reporte.lote === this.lote);
                }
                if(this.codigo!=''){
                    this.reporte = this.reporte.filter(reporte => reporte.codigo === this.codigo);
                }
                
                console.log(this.reporte);
                
                this.reportexlsx={titulo:"Movimientos Detalle",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Fecha Movimiento','date':'string','ancho':15},
                    {'titulo':'Codigo','tipo':'string','ancho':20},
                    {'titulo':'Descripción','tipo':'string','ancho':30},
                    {'titulo':'Lote','tipo':'string','ancho':20},
                    {'titulo':'Tipo','tipo':'string','ancho':20},
                    {'titulo':'Proyecto No','tipo':'string','ancho':20},
                    {'titulo':'Referecnia Cliente','tipo':'string','ancho':20},
                    {'titulo':'Correlativo','tipo':'string','ancho':17},
                    {'titulo':'No Orden Pedido','tipo':'string','ancho':20},
                    {'titulo':'Embalaje','tipo':'string','ancho':20},
                    {'titulo':'Merma','tipo':'string','ancho':20},
                    {'titulo':'Clasificacion','tipo':'string','ancho':20},
                    {'titulo':'No Conf.','tipo':'string','ancho':20},
                    {'titulo':'Cantidad','tipo':'number','ancho':20},
                ],
                data:[]};
                let data: Array<any>=[];
                
                for (let r = 0; r<this.reporte.length; r++){
                    this.reporte[r].fechamovimiento = new Date(this.reporte[r].fechamovimiento.replace(/-/g, '\/'));
                    
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].fechamovimiento},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].lote},
                        {'valor': this.reporte[r].tipomovimiento},
                        {'valor': this.reporte[r].proyecto_no},
                        {'valor': this.reporte[r].referencia_cliente},
                        {'valor': this.reporte[r].correlativo},
                        {'valor': this.reporte[r].orden_pedido},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].merma},
                        {'valor': this.reporte[r].clasificacion},
                        {'valor': this.reporte[r].no_conf},
                        {'valor': this.reporte[r].cantidad},
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
