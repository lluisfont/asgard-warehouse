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
    selector: 'app-reporte-almacen-capacidad',
    templateUrl: './reporte-almacen-capacidad.component.html',
    styleUrl: './reporte-almacen-capacidad.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService,ExportExcelService]
})
export class ReporteAlmacenCapacidadComponent {
    public token: string;
    public tokenDetalle: any;
    
    public fechacorte: string;
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_capacidad: boolean=false;

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
            this.ver_reporte_almacen_capacidad=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 67);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_capacidad=true;
                }
            }
        }
        this.fechacorte = this._usuarioService.getCurrentDateFilterValue();
    }
    
    ngOnInit(): void {
        
    }
    
    generarReporte(){
        if (this.fechacorte==null || this.fechacorte==''){
            this.fechacorte = this._usuarioService.getCurrentDateFilterValue();
        }
        this.reporte=[];
        this.generado=true;
        this._almacenesService.veralmacen(this.token, this.tokenDetalle['idalmacen'], this.fechacorte).subscribe(
            response =>{
                let detalle=response.almacen.detalle;
                //console.log(response);
                
                for(let dd=0;dd<detalle.length;dd++){
                    for(let cc=0;cc<detalle[dd].length;cc++){
                        if(detalle[dd][cc].tipo==1){
                            if(detalle[dd][cc].items.length==0){
                                this.reporte.push({
                                    ubicacion: detalle[dd][cc].nombre,
                                    tipo: detalle[dd][cc].tipoalmacendetalle,
                                    cliente: '',
                                    rubro: '',
                                    codigo: '',
                                    cantidad: 0,
                                    unidad: ''
                                });
                            }else{
                                for(let ii=0;ii<detalle[dd][cc].items.length;ii++){
                                    this.reporte.push({
                                        ubicacion: detalle[dd][cc].nombre,
                                        tipo: detalle[dd][cc].tipoalmacendetalle,
                                        cliente: detalle[dd][cc].items[ii].cliente,
                                        rubro: detalle[dd][cc].items[ii].categoria,
                                        codigo: detalle[dd][cc].items[ii].codigo,
                                        cantidad: detalle[dd][cc].items[ii].cantidad,
                                        unidad: detalle[dd][cc].items[ii].codigoembalaje
                                    });
                                }
                            }
                        }
                    }
                }
                
                //console.log(this.reporte);
                
                
                
                this.reportexlsx={titulo:"Capacidad",cabecera:[
                    {'titulo':'Ubicacion','tipo':'string','ancho':17},
                    {'titulo':'Tipo','tipo':'string','ancho':17},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Rubro','tipo':'string','ancho':20},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Cantidad','tipo':'number','ancho':17},
                    {'titulo':'Unidad','tipo':'string','ancho':17}
                ],
                data:[]};
                
                
                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].ubicacion},
                        {'valor': this.reporte[r].tipo},
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].rubro},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].cantidad},
                        {'valor': this.reporte[r].unidad}
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
    }
}
