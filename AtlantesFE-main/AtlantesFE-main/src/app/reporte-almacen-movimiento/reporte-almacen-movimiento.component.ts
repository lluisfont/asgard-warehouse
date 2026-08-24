import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-movimiento',
    templateUrl: './reporte-almacen-movimiento.component.html',
    styleUrls: ['./reporte-almacen-movimiento.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenMovimientoComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: number;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_movimiento: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_movimiento=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 56);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_movimiento=true;
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
        //var fecha = this.fechacorte;
        this._almacenesService.reportemovimiento(this.token, idcliente, 0, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                //
                this.reporte=response.movimientos;
                
                //console.log(response.movimientos);
                this.reportexlsx={titulo:"Movimientos",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Tipo','tipo':'string','ancho':20},
                    {'titulo':'No. Ingreso','tipo':'string','ancho':17},
                    {'titulo':'No. Salida','tipo':'string','ancho':17},
                    {'titulo':'Fecha Movimiento','date':'string','ancho':15},
                    //{'titulo':'Ubicación','tipo':'string','ancho':20},
                    {'titulo':'Codigo','tipo':'string','ancho':20},
                    //{'titulo':'Serie','tipo':'string','ancho':20},
                    {'titulo':'Descripción','tipo':'string','ancho':30},
                    //{'titulo':'Lote','tipo':'string','ancho':20},
                    //{'titulo':'Fecha Venc.','tipo':'date','ancho':17},
                    //{'titulo':'Categoría','tipo':'string','ancho':20},
                    {'titulo':'Inv. Inicial','tipo':'number','ancho':20},
                    {'titulo':'Ingreso','tipo':'number','ancho':20},
                    {'titulo':'Salida','tipo':'number','ancho':20},
                    {'titulo':'Inv. Final','tipo':'number','ancho':20},
                    {'titulo':'Embalaje','tipo':'string','ancho':20},
                    {'titulo':'Días permanencia','tipo':'number','ancho':20},
                    {'titulo':'m3','tipo':'number','ancho':20},
                    {'titulo':'Días liquidación','tipo':'number','ancho':20},
                    {'titulo':'Inv. Inicial m3','tipo':'number','ancho':20},
                    {'titulo':'Ingreso m3','tipo':'number','ancho':20},
                    {'titulo':'Salida m3','tipo':'number','ancho':20},
                    {'titulo':'Inv. Final m3','tipo':'number','ancho':20},
                    {'titulo':'Acumulado','tipo':'number','ancho':20},
		    {'titulo':'Adicional','tipo':'number','ancho':20},
		    {'titulo':'Posiciones adicionales','tipo':'number','ancho':20},
                    {'titulo':'max posiciones por m3','tipo':'number','ancho':20},
                    {'titulo':'adicional real posiciones por m3','tipo':'number','ancho':20},
                    {'titulo':'Inbound ad','tipo':'number','ancho':20},
                    {'titulo':'Outbound ad','tipo':'number','ancho':20},
                    {'titulo':'Fee adicional','tipo':'number','ancho':20},
                    {'titulo':'Ad inbound','tipo':'number','ancho':20},
                    {'titulo':'Ad outbound','tipo':'number','ancho':20},
                    {'titulo':'Total adicional','tipo':'number','ancho':20}
                ],
                data:[]};
                let data: Array<any>=[];
                
                for (let r = 0; r<this.reporte.length; r++){
                    this.reporte[r].fechamovimiento = new Date(this.reporte[r].fechamovimiento.replace(/-/g, '\/'));
                    if(this.reporte[r].fechavencimiento!=null){
                        this.reporte[r].fechavencimiento = new Date(this.reporte[r].fechavencimiento.replace(/-/g, '\/'));
                    }
                    
                    

                    
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].tipomovimiento},
                        {'valor': this.reporte[r].codigoingreso},
                        {'valor': this.reporte[r].codigosalida},
                        {'valor': this.reporte[r].fechamovimiento},
                        //{'valor': this.reporte[r].ubicacionalmacen},
                        {'valor': this.reporte[r].codigo},
                        //{'valor': this.reporte[r].serie},
                        {'valor': this.reporte[r].descripcion},
                        //{'valor': this.reporte[r].lote},
                        //{'valor': this.reporte[r].fechavencimiento},
                        //{'valor': this.reporte[r].categoria},
                        {'valor': this.reporte[r].inicial},
                        {'valor': this.reporte[r].ingreso},
                        {'valor': this.reporte[r].salida},
                        {'valor': this.reporte[r].inventariofinal},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].diaspermanencia},
                        {'valor': 0},
                        {'valor': this.reporte[r].diasliquidacion},
                        {'valor': this.reporte[r].inicialmq},
                        {'valor': this.reporte[r].ingresomq},
                        {'valor': this.reporte[r].salidamq},
                        {'valor': this.reporte[r].inventariofinalmq},
                        {'valor': this.reporte[r].acumulado},
			{'valor': this.reporte[r].adicional},
			{'valor': this.reporte[r].posiciones_adicionales},
                        {'valor': this.reporte[r].max_posiciones},
                        {'valor': this.reporte[r].posiciones_adicionales_real},
                        {'valor': this.reporte[r].inbound},
                        {'valor': this.reporte[r].outbound},
                        {'valor': this.reporte[r].fee_adicional},
                        {'valor': this.reporte[r].ad_inbound},
                        {'valor': this.reporte[r].ad_outbound},
                        {'valor': this.reporte[r].total_adicional}
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

}
