import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-pedidos',
    templateUrl: './reporte-almacen-pedidos.component.html',
    styleUrl: './reporte-almacen-pedidos.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenPedidosComponent {
    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;

    public idcliente: number;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_pedidos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_pedidos=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 66);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_pedidos=true;
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
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    generarReporte(){
        this.generado=true;
        let idcliente=0;
        if(this.idcliente!=null){
            idcliente=this.idcliente;
        }
        this._almacenesService.reportePedido(this.token, idcliente, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                console.log(response);
                this.reporte=response.pedidos;

                this.reporte.filter(reporte => reporte.fecha!=null).forEach(
                    reporte => (reporte.fecha = new Date(reporte.fecha.replace(/-/g, '\/')))
                );
                this.reporte.filter(reporte => reporte.fecha_entrega!=null).forEach(
                    reporte => (reporte.fecha_entrega = new Date(reporte.fecha_entrega.replace(/-/g, '\/')))
                );

                this.reportexlsx={titulo:"Pedidos",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Fecha Creacion','tipo':'date','ancho':17},
                    {'titulo':'No Pedido','tipo':'string','ancho':17},
                    {'titulo':'Total Bultos','tipo':'numeric','ancho':17},
                    {'titulo':'Total Items','tipo':'numeric','ancho':17},
                    {'titulo':'Cantidad Bultos (Preparador)','numeric':'string','ancho':17},
                    {'titulo':'Cantidad Tiendas','tipo':'numeric','ancho':17},
                    {'titulo':'Rubro','tipo':'string','ancho':17},
                    {'titulo':'Revisado','tipo':'string','ancho':17},
                    {'titulo':'Preparador','tipo':'string','ancho':17},
                    {'titulo':'Hora Inicio','tipo':'string','ancho':17},
                    {'titulo':'Hora Fin','tipo':'date','ancho':17},
                    {'titulo':'PDP Conforme','tipo':'string','ancho':17},
                    {'titulo':'PDP Conforme rev 2','tipo':'string','ancho':17},
                    {'titulo':'PDP Conforme rev 3','tipo':'string','ancho':17},
                    {'titulo':'Notas','tipo':'string','ancho':17},
                    {'titulo':'Documento de Compra','tipo':'string','ancho':30},
                    {'titulo':'Fecha entrega','tipo':'date','ancho':17},
                    {'titulo':'Hora PDP','tipo':'string','ancho':17},
                    {'titulo':'Hora PDP en Nro','tipo':'string','ancho':17},
                    {'titulo':'CT/HR','tipo':'numeric','ancho':17},
                    {'titulo':'CT/MIN','tipo':'numeric','ancho':17},
                    {'titulo':'Sector','tipo':'string','ancho':25},
                    {'titulo':'Nota Adicional','tipo':'string','ancho':40}
                ],
                data:[]};

                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].fecha},
                        {'valor': this.reporte[r].numero},
                        {'valor': this.reporte[r].total_bultos},
                        {'valor': this.reporte[r].total_items},
                        {'valor': this.reporte[r].cantidad_bultos},
                        {'valor': this.reporte[r].cantidad_tiendas},
                        {'valor': this.reporte[r].rubro},
                        {'valor': this.reporte[r].usuario_revisado},
                        {'valor': this.reporte[r].preparador},
                        {'valor': this.reporte[r].hora_inicio},
                        {'valor': this.reporte[r].hora_fin},
                        {'valor': this.reporte[r].conforme},
                        {'valor': this.reporte[r].conforme2},
                        {'valor': this.reporte[r].conforme3},
                        {'valor': this.reporte[r].notas},
                        {'valor': this.reporte[r].no_pedido},
                        {'valor': this.reporte[r].fecha_entrega},
                        {'valor': this.reporte[r].hora},
                        {'valor': this.reporte[r].hora_num},
                        {'valor': this.reporte[r].ct_hr},
                        {'valor': this.reporte[r].ct_min},
                        {'valor': this.reporte[r].sector},
                        {'valor': this.reporte[r].nota_adicional}
                    ]);
                }

                this.reportexlsx.data=data;


                //console.log(this.reporte);

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
}
