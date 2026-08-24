import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-descarga',
    templateUrl: './reporte-almacen-descarga.component.html',
    styleUrls: ['./reporte-almacen-descarga.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenDescargaComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: number;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_descarga: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_descarga=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 58);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_descarga=true;
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
        
        this._almacenesService.reportedescarga(this.token, idclienteenviar, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                this.reporte=response.descarga;
                
                this.reporte.filter(reporte => reporte.fecha!=null).forEach(
                    reporte => (reporte.fecha = new Date(reporte.fecha.replace(/-/g, '\/')))
                );
                
                this.reportexlsx={titulo:"Dscarga",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Ingreso','tipo':'string','ancho':17},
                    {'titulo':'Fecha Ingreso','tipo':'date','ancho':17},
                    {'titulo':'Cant. Manifestada','tipo':'string','ancho':20},
                    {'titulo':'Peso Total (KG)','tipo':'numeric','ancho':17},
                    {'titulo':'Cantidad Pallet','tipo':'numeric','ancho':17},
                    {'titulo':'Cantidad de Cajas','tipo':'numeric','ancho':17},
                    {'titulo':'Hora inicio Maq','tipo':'string','ancho':17},
                    {'titulo':'Hora Fin Maq','tipo':'string','ancho':17},
                    {'titulo':'Tipo Medio Transporte','tipo':'string','ancho':17},
                    {'titulo':'Tipo Contenedor','tipo':'string','ancho':17},
                    {'titulo':'Proveedor','tipo':'string','ancho':30}
                ],
                data:[]};
                
                let data: Array<any>=[];
                
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].numeroingreso},
                        {'valor': this.reporte[r].fecha},
                        {'valor': this.reporte[r].piezas_manifestadas},
                        {'valor': this.reporte[r].peso_total},
                        {'valor': this.reporte[r].cantidad_pallet},
                        {'valor': this.reporte[r].cantidad_cajas},
                        {'valor': this.reporte[r].hora_inicio},
                        {'valor': this.reporte[r].hora_fin},
                        {'valor': this.reporte[r].tipocamion},
                        {'valor': this.reporte[r].tipocontenedor},
                        {'valor': this.reporte[r].proveedor}
                    ]);
                }
                
                this.reportexlsx.data=data;
                
                this.generado=false;
                //this.exportColumns = this.cols.map(col => ({title: col.header, dataKey: col.field}));
                console.log(this.reporte);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
    }

}
