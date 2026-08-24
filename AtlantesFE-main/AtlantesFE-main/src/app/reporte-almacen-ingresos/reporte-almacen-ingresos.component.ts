import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-ingresos',
    templateUrl: './reporte-almacen-ingresos.component.html',
    styleUrls: ['./reporte-almacen-ingresos.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenIngresosComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;
    public tiposingreso: Array<any>=[];
    public no_confs: Array<any>=[];

    public idcliente: number;
    public idtipoingreso: Array<any>=[];
    public idno_conf: Array<any>=[];
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_ingresos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_ingresos=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 54);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_ingresos=true;
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

        this._datomaestroService.no_confs(this.token).subscribe(
            response =>{
                this.no_confs=response.no_confs;
                this.no_confs.unshift({
                    idno_conf: null,
                    no_conf: "(Vacio)"
                });
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.tiposingreso(this.token).subscribe(
            response =>{
                this.tiposingreso=response.tiposingreso;
                this.tiposingreso.unshift({
                    idtipoingreso: null,
                    tipoingreso: "(Vacio)"
                });
                console.log(this.tiposingreso);
            },
            error=>{
                console.log(<any>error)
            }
        );

    }

    generarReporte(){
        //this.reportexlsx=[];
        this.generado=true;
        let idclienteenviar=0;
        if (this.idcliente>0){
            idclienteenviar=this.idcliente;
        }

        if (this.fechafinal==null || this.fechafinal=='' || this.fechainicial==null || this.fechainicial==''){
            this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
            this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        }

        this._almacenesService.reporteingresos(this.token, idclienteenviar, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                this.reporte=response.ingresos;

                this.reporte =
                    this.idtipoingreso.length === 0
                        ? this.reporte
                        : this.reporte.filter(r => this.idtipoingreso.includes(r.idtipoingreso));

                this.reporte =
                    this.idno_conf.length === 0
                        ? this.reporte
                        : this.reporte.filter(r => this.idno_conf.includes(r.idno_conf));
                        
                this.reporte.filter(reporte => reporte.fechavencimiento!=null).forEach(
                    reporte => (reporte.fechavencimiento = new Date(reporte.fechavencimiento.replace(/-/g, '\/')))
                );
                this.reporte.filter(reporte => reporte.fechaingreso!=null).forEach(
                    reporte => (reporte.fechaingreso = new Date(reporte.fechaingreso.replace(/-/g, '\/')))
                );
                /*
                this.reporte.filter(reporte => reporte.fechaproduccion!=null).forEach(
                    reporte => (reporte.fechaproduccion = new Date(reporte.fechaproduccion.replace(/-/g, '\/')))
                );
                */
                this.reporte.filter(reporte => reporte.fechasistema!=null).forEach(
                    reporte => (reporte.fechasistema = new Date(reporte.fechasistema.replace(/-/g, '\/')))
                );
                
                this.reporte.filter(reporte => reporte.fecha_cierre_transito!=null).forEach(
                    reporte => (reporte.fecha_cierre_transito = new Date(reporte.fecha_cierre_transito.replace(/-/g, '\/')))
                );
                
                this.reporte.filter(reporte => reporte.fecha_emision_parte!=null).forEach(
                    reporte => (reporte.fecha_emision_parte = new Date(reporte.fecha_emision_parte.replace(/-/g, '\/')))
                );

                this.reportexlsx={titulo:"Ingresos",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Ingreso','tipo':'string','ancho':17},
                    {'titulo':'Fecha Ingreso','tipo':'date','ancho':17},
                    {'titulo':'Ubicacion','tipo':'string','ancho':20},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Serie','tipo':'string','ancho':17},
                    {'titulo':'Descripcion','tipo':'string','ancho':40},
                    {'titulo':'Centro Distribución','tipo':'string','ancho':17},
                    {'titulo':'Categoria','tipo':'string','ancho':17},
                    {'titulo':'Cantidad','tipo':'number','ancho':13},
                    {'titulo':'Embalaje','tipo':'string','ancho':10},
                    {'titulo':'Lote','tipo':'string','ancho':20},
                    {'titulo':'Precio Un (USD)','tipo':'number','ancho':20},
                    {'titulo':'No Conf.','tipo':'string','ancho':20},
                    {'titulo':'Clasificacion','tipo':'string','ancho':20},
                    {'titulo':'MERMA','tipo':'string','ancho':20},
                    {'titulo':'Fec Venc','tipo':'date','ancho':20},
                    {'titulo':'PPT','tipo':'string','ancho':20},
                    //{'titulo':'Volumen','tipo':'number','ancho':20},
                    {'titulo':'Bultos','tipo':'number','ancho':20},
                    {'titulo':'Peso','tipo':'number','ancho':20},
                    {'titulo':'Pallet','tipo':'string','ancho':20},
                    {'titulo':'Temperatura','tipo':'string','ancho':20},
                    {'titulo':'Placa','tipo':'string','ancho':20},
                    //{'titulo':'No Contenedor','tipo':'string','ancho':20},
                    {'titulo':'Tipo Ingreso','tipo':'string','ancho':20},
                    {'titulo':'Tipo Descarga','tipo':'string','ancho':20},
                    {'titulo':'Medio Transporte','tipo':'string','ancho':20},
                    {'titulo':'Tipo Producto','tipo':'string','ancho':20},
                    {'titulo':'Cant. Manifestada','tipo':'string','ancho':20},
                    {'titulo':'Peso Total (KG)','tipo':'number','ancho':20},
                    {'titulo':'Proveedor','tipo':'string','ancho':30},
                    {'titulo':'Ref. Cliente','tipo':'string','ancho':20},
                    {'titulo':'No Lote','tipo':'string','ancho':20},
                    {'titulo':'Rubro','tipo':'string','ancho':20},
                    {'titulo':'Proyecto','tipo':'string','ancho':20},
                    {'titulo':'Invoice','tipo':'string','ancho':20},
                    {'titulo':'DUI','tipo':'string','ancho':20},
                    {'titulo':'Cantidad Pallet','tipo':'number','ancho':20},
                    {'titulo':'Cantidad de Cajas','tipo':'number','ancho':20},
                    {'titulo':'Hora inicio Maq','tipo':'string','ancho':20},
                    {'titulo':'Hora Fin Maq','tipo':'string','ancho':20},
                    {'titulo':'Estibadores','tipo':'number','ancho':20},
                    {'titulo':'Notas','tipo':'string','ancho':20},
                    {'titulo':'Recibido por','tipo':'string','ancho':20},
                    {'titulo':'Fecha Sistema','tipo':'date','ancho':17},
                    {'titulo':'Entregado Por','tipo':'string','ancho':20},
                    {'titulo':'CI','tipo':'string','ancho':15},
                    {'titulo':'Empresa','tipo':'string','ancho':20},
                    {'titulo':'Observaciones','tipo':'string','ancho':30},
                    {'titulo':'FC','tipo':'number','ancho':20},
                    {'titulo':'QTY','tipo':'number','ancho':20},
                    {'titulo':'U.M','tipo':'string','ancho':20},
                    {'titulo':'Fecha Cierre de Tránsito','tipo':'datetime','ancho':20},
                    {'titulo':'Fecha emisión del parte de recepción','tipo':'datetime','ancho':20}
                ],
                data:[]};


                let data: Array<any>=[];

                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].numeroingreso},
                        {'valor': this.reporte[r].fechaingreso},
                        {'valor': this.reporte[r].ubicacionalmacen},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].serie},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].centro_distribucion},
                        {'valor': this.reporte[r].categoria},
                        {'valor': this.reporte[r].cantidad},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].lote},
                        {'valor': this.reporte[r].costo_un},
                        {'valor': this.reporte[r].no_conf},
                        {'valor': this.reporte[r].clasificacion},
                        {'valor': this.reporte[r].merma},
                        //'Fec Prod': this.reporte[r].fechaproduccion},
                        {'valor': this.reporte[r].fechavencimiento},
                        {'valor': this.reporte[r].relacion_caja},
                        //{'valor': this.reporte[r].volumen},
                        {'valor': this.reporte[r].bultos},
                        {'valor': this.reporte[r].peso},
                        {'valor': this.reporte[r].pallet},
                        {'valor': this.reporte[r].temperatura},
                        {'valor': this.reporte[r].placa},
                        //{'valor': this.reporte[r].contenedor},
                        {'valor': this.reporte[r].tipoingreso},
                        {'valor': this.reporte[r].tipodescarga},
                        {'valor': this.reporte[r].tipocamion},
                        {'valor': this.reporte[r].tipoproducto},
                        {'valor': this.reporte[r].piezas_manifestadas},
                        {'valor': this.reporte[r].peso_total},
                        {'valor': this.reporte[r].proveedor},
                        {'valor': this.reporte[r].no_contrato},
                        {'valor': this.reporte[r].delivery_batch},
                        {'valor': this.reporte[r].rubro_producto},
                        {'valor': this.reporte[r].project},
                        {'valor': this.reporte[r].invoice},
                        {'valor': this.reporte[r].dui},
                        {'valor': this.reporte[r].cantidad_pallet},
                        {'valor': this.reporte[r].cantidad_cajas},
                        {'valor': this.reporte[r].hora_inicio},
                        {'valor': this.reporte[r].hora_fin},
                        {'valor': this.reporte[r].cantidad_estibadores},
                        {'valor': this.reporte[r].nota_adicional},
                        {'valor': this.reporte[r].usuario_recibido},
                        {'valor': this.reporte[r].fechasistema},
                        {'valor': this.reporte[r].nombre_entrega},
                        {'valor': this.reporte[r].ci_entrega},
                        {'valor': this.reporte[r].empresa_entrega},
                        {'valor': this.reporte[r].observaciones},
                        {'valor': this.reporte[r].factor_conversion},
                        {'valor': this.reporte[r].cantidad/this.reporte[r].factor_conversion},
                        {'valor': this.reporte[r].codigoembalaje_salida},
                        {'valor': this.reporte[r].fecha_cierre_transito},
                        {'valor': this.reporte[r].fecha_emision_parte}
                    ]);
                }

                this.reportexlsx.data=data;
                console.log(this.reportexlsx);


                this.generado=false;
                //this.exportColumns = this.cols.map(col => ({title: col.header, dataKey: col.field}));
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
