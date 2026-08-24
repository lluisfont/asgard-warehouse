import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-salidas',
    templateUrl: './reporte-almacen-salidas.component.html',
    styleUrls: ['./reporte-almacen-salidas.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenSalidasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;
    public no_confs: Array<any>=[];

    public idcliente: number;
    public idno_conf: Array<any>=[];
    public es_no_conf: number=2;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_salidas: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_salidas=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 55);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_salidas=true;
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

        this._almacenesService.reportesalidas(this.token, idclienteenviar, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                this.reporte=response.salidas;

                this.reporte.forEach(
                    reporte => (reporte.es_no_conf_texto = (reporte.es_no_conf == '1' ? 'Si' : 'No'))
                );

                this.reporte =
                    this.idno_conf.length === 0
                        ? this.reporte
                        : this.reporte.filter(r => this.idno_conf.includes(r.idno_conf));

                if(this.es_no_conf==1 || this.es_no_conf==0){
                    this.reporte=this.reporte.filter(r => this.es_no_conf==r.es_no_conf);
                }

                this.reporte.filter(reporte => reporte.fechavencimiento!=null).forEach(
                    reporte => (reporte.fechavencimiento = new Date(reporte.fechavencimiento.replace(/-/g, '\/')))
                );
                this.reporte.filter(reporte => reporte.fechaingreso!=null).forEach(
                    reporte => (reporte.fechaingreso = new Date(reporte.fechaingreso.replace(/-/g, '\/')))
                );
                this.reporte.filter(reporte => reporte.fechasalida!=null).forEach(
                    reporte => (reporte.fechasalida = new Date(reporte.fechasalida.replace(/-/g, '\/')))
                );
                this.reporte.filter(reporte => reporte.fecha_recibido!=null).forEach(
                    reporte => (reporte.fecha_recibido = new Date(reporte.fecha_recibido.replace(/-/g, '\/')))
                );

                this.reportexlsx={titulo:"Salidas",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Salida','tipo':'string','ancho':17},
                    {'titulo':'Fecha salida','tipo':'date','ancho':17},
                    {'titulo':'Es No Conf','tipo':'string','ancho':20},
                    {'titulo':'Ubicacion','tipo':'string','ancho':20},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Serie','tipo':'string','ancho':17},
                    {'titulo':'Decripcion','tipo':'string','ancho':40},
                    {'titulo':'Centro Distribución','tipo':'string','ancho':20},
                    {'titulo':'Categoria','tipo':'string','ancho':17},
                    {'titulo':'Cantidad','tipo':'number','ancho':13},
                    {'titulo':'Embalaje','tipo':'string','ancho':10},
                    {'titulo':'Lote','tipo':'string','ancho':20},
                    {'titulo':'MERMA','tipo':'string','ancho':20},
                    {'titulo':'PPT','tipo':'string','ancho':20},
                    {'titulo':'Precio Un (USD)','tipo':'number','ancho':20},
                    {'titulo':'No Conf.','tipo':'string','ancho':20},
                    {'titulo':'Clasificacion','tipo':'string','ancho':20},
                    {'titulo':'Fec Venc','tipo':'date','ancho':20},
                    {'titulo':'Bultos','tipo':'number','ancho':20},
                    {'titulo':'Peso','tipo':'number','ancho':20},
                    {'titulo':'Pallet','tipo':'string','ancho':20},
                    {'titulo':'Temperatura','tipo':'string','ancho':20},
                    {'titulo':'Solicitado Por','tipo':'string','ancho':20},
                    {'titulo':'Autorizado Por','tipo':'string','ancho':20},
                    {'titulo':'No Orden de Pedido','tipo':'string','ancho':20},
                    {'titulo':'Proyecto No','tipo':'string','ancho':20},
                    {'titulo':'Referencia Cliente','tipo':'string','ancho':20},
                    {'titulo':'Rubro de Producto','tipo':'string','ancho':20},
                    {'titulo':'Ciudad Destino','tipo':'string','ancho':20},
                    {'titulo':'Dirección de entrega','tipo':'string','ancho':20},
                    {'titulo':'Transporte','tipo':'string','ancho':20},
                    {'titulo':'Placa','tipo':'string','ancho':20},
                    {'titulo':'Cantidad Pallet','tipo':'number','ancho':20},
                    {'titulo':'Cantidad de Cajas','tipo':'number','ancho':20},
                    {'titulo':'No Autor. Compra Local','tipo':'string','ancho':20},
                    {'titulo':'Hora Inicio Maquina A','tipo':'string','ancho':20},
                    {'titulo':'Hora Fin Maquina A','tipo':'string','ancho':20},
                    {'titulo':'Cantidad Estibadores A','tipo':'number','ancho':20},
                    {'titulo':'Notas Adicionales','tipo':'string','ancho':20},
                    {'titulo':'Hora Inicio Maquina B','tipo':'string','ancho':20},
                    {'titulo':'Hora Fin Maquina B','tipo':'string','ancho':20},
                    {'titulo':'Cantidad Estibadores B','tipo':'number','ancho':20},
                    {'titulo':'Entregado por','tipo':'string','ancho':20},
                    {'titulo':'CI Entregado','tipo':'string','ancho':20},
                    {'titulo':'Recibido por','tipo':'string','ancho':20},
                    {'titulo':'CI Recibido','tipo':'string','ancho':20},
                    {'titulo':'Empresa Recibido','tipo':'string','ancho':20},
                    {'titulo':'Fecha Recibido','tipo':'date','ancho':20},
                    {'titulo':'Entrega a Tiempo','tipo':'string','ancho':20},
                    {'titulo':'Entrega Completa y Conforme','tipo':'string','ancho':20},
                    {'titulo':'Ingreso','tipo':'string','ancho':20},
                    {'titulo':'Fecha Ingreso','tipo':'date','ancho':20},
                    {'titulo':'Tipo Ingreso','tipo':'string','ancho':20},
                    {'titulo':'Observaciones','tipo':'string','ancho':20},
                    {'titulo':'FC','tipo':'number','ancho':20},
                    {'titulo':'QTY','tipo':'number','ancho':20},
                    {'titulo':'U.M','tipo':'string','ancho':20},
                    {'titulo':'No Invoice','tipo':'string','ancho':20}
                ],
                data:[]};

                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].numerosalida},
                        {'valor': this.reporte[r].fechasalida},
                        {'valor': this.reporte[r].es_no_conf_texto},
                        {'valor': this.reporte[r].ubicacionalmacen},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].serie},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].centro_distribucion},
                        {'valor': this.reporte[r].categoria},
                        {'valor': this.reporte[r].cantidad},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].lote},
                        {'valor': this.reporte[r].merma},
                        {'valor': this.reporte[r].relacion_caja},
                        {'valor': this.reporte[r].costo_un},
                        {'valor': this.reporte[r].no_conf},
                        {'valor': this.reporte[r].clasificacion},
                        {'valor': this.reporte[r].fechavencimiento},
                        {'valor': this.reporte[r].bultos},
                        {'valor': this.reporte[r].peso},
                        {'valor': this.reporte[r].pallet},
                        {'valor': this.reporte[r].temperatura},
                        {'valor': this.reporte[r].solicitado_por},
                        {'valor': this.reporte[r].autorizado_por},
                        {'valor': this.reporte[r].delivery_note},
                        {'valor': this.reporte[r].proyecto_no},
                        {'valor': this.reporte[r].contrato_no},
                        {'valor': this.reporte[r].rubro_producto},
                        {'valor': this.reporte[r].ciudad},
                        {'valor': this.reporte[r].direccion_entrega},
                        {'valor': this.reporte[r].transporte},
                        {'valor': this.reporte[r].placa},
                        {'valor': this.reporte[r].cantidad_pallet},
                        {'valor': this.reporte[r].cantidad_cajas},
                        {'valor': this.reporte[r].autorizacion_compra},
                        {'valor': this.reporte[r].hora_inicio_a},
                        {'valor': this.reporte[r].hora_fin_a},
                        {'valor': this.reporte[r].cantidad_estibadores_a},
                        {'valor': this.reporte[r].nota_adicional},
                        {'valor': this.reporte[r].hora_inicio_b},
                        {'valor': this.reporte[r].hora_fin_b},
                        {'valor': this.reporte[r].cantidad_estibadores_b},
                        {'valor': this.reporte[r].nombre_entrega},
                        {'valor': this.reporte[r].ci_entrega},
                        {'valor': this.reporte[r].nombre_recibido},
                        {'valor': this.reporte[r].ci_recibido},
                        {'valor': this.reporte[r].empresa_recibido},
                        {'valor': this.reporte[r].fecha_recibido},
                        {'valor': this.reporte[r].entrega_a_tiempo},
                        {'valor': this.reporte[r].entrega_completa_conforme},
                        {'valor': this.reporte[r].numeroingreso},
                        {'valor': this.reporte[r].fechaingreso},
                        {'valor': this.reporte[r].tipoingreso},
                        {'valor': this.reporte[r].observaciones},
                        {'valor': this.reporte[r].factor_conversion},
                        {'valor': this.reporte[r].cantidad/this.reporte[r].factor_conversion},
                        {'valor': this.reporte[r].codigoembalaje_salida},
                        {'valor': this.reporte[r].invoice}
                        
                    ]);
                }


                 this.reportexlsx.data=data;


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
            const workbook = { Sheets: { 'Salidas': worksheet }, SheetNames: ['Salidas'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "Salidas");
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
