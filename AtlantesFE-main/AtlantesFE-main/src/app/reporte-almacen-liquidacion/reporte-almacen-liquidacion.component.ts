import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';
@Component({
    selector: 'app-reporte-almacen-liquidacion',
    templateUrl: './reporte-almacen-liquidacion.component.html',
    styleUrls: ['./reporte-almacen-liquidacion.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenLiquidacionComponent implements OnInit {

    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;

    public idcliente: number;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_liquidacion: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_liquidacion=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 63);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_liquidacion=true;
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

                this.entidades = response.entidades.filter(function (el) {
                    return el.idtipoentidad==1;
                });

                //console.log(response.entidades);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    generarReporte(){
        this.generado=true;
        let idclienteenviar=0;
        if (this.idcliente>0){
            idclienteenviar=this.idcliente;
        }

        if (this.fechafinal==null || this.fechafinal=='' || this.fechainicial==null || this.fechainicial==''){
            this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
            this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        }

        this._almacenesService.reporteliquidacion(this.token, idclienteenviar, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                
                this.reporte=response.reporte;
                this.reporte.filter(reporte => reporte.fecha!=null).forEach(
                    reporte => (reporte.fecha = new Date(reporte.fecha.replace(/-/g, '\/')))
                );
                
                this.reporte.forEach(
                    reporte => (reporte.numeromovimiento = reporte.numero+'/'+reporte.gestion)
                );
                
                
                this.reportexlsx={titulo:"Liquidacion",cabecera:[
                    {'titulo':'Fecha','tipo':'date','ancho':17},
                    {'titulo':'Nota','tipo':'string','ancho':17},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Servicio','tipo':'string','ancho':17},
                    {'titulo':'Cantidad','tipo':'number','ancho':17},
                    {'titulo':'Embalaje','tipo':'string','ancho':17},
                    {'titulo':'Descripcion','tipo':'string','ancho':40},
                    {'titulo':'Codigo','tipo':'string','ancho':40},
                    {'titulo':'Cargío de camiones 3 a 7 TN 25 USD 9 a 35 TN 70 USD','tipo':'number','ancho':20}
                ],
                data:[]};


                let data: Array<any>=[];

                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].fecha},
                        {'valor': this.reporte[r].numeromovimiento},
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].servicio},
                        {'valor': this.reporte[r].cantidad_pallet},
                        {'valor': this.reporte[r].embalaje},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].codigos},
                        {'valor': this.reporte[r].peso_total}
                    ]);
                }

                this.reportexlsx.data=data;
                
                console.log(this.reporte);
                
                
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
            const workbook = { Sheets: { 'Ingresos': worksheet }, SheetNames: ['Ingresos'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "Ingresos");
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
