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
    selector: 'app-reporte-almacen-inventario-fisico',
    templateUrl: './reporte-almacen-inventario-fisico.component.html',
    styleUrl: './reporte-almacen-inventario-fisico.component.css',
    providers:[UsuarioService,DatoMaestroService,EntidadesService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenInventarioFisicoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: number;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_inventario_fisico: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_inventario_fisico=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 68);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_inventario_fisico=true;
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
                this.entidades = response.entidades.filter(function(cc){
                    return (cc.idtipoentidad==1)
                });
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    generarReporte(){
        let idcliente=0;
        if (this.idcliente!=null){
            idcliente = this.idcliente;
        }
        
        if (this.fechafinal==null || this.fechafinal=='' || this.fechainicial==null || this.fechainicial==''){
            this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
            this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        }
        
        this.reporte=[];
        
        
        this._almacenesService.reporteInventarioFisico(this.token, idcliente, this.fechainicial, this.fechafinal).subscribe(
            response =>{
                console.log(response.inventariosfisico);
                
                
                this.reportexlsx={titulo:"Inventario Fisico",cabecera:[
                    {'titulo':'Numero','tipo':'string','ancho':17},
                    {'titulo':'Fecha de Inventario','tipo':'date','ancho':17},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Diferencia Items','tipo':'string','ancho':17},
                    {'titulo':'Total Items','tipo':'numeric','ancho':17},
                    {'titulo':'Total Cantidad','tipo':'numeric','ancho':17},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Serie','tipo':'string','ancho':17},
                    {'titulo':'Descripción','tipo':'string','ancho':25},
                    {'titulo':'Ubicacion','tipo':'string','ancho':17},
                    {'titulo':'UM','tipo':'string','ancho':17},
                    {'titulo':'Cantidad Sistema','tipo':'numeric','ancho':17},
                    {'titulo':'Cantidad Real','tipo':'numeric','ancho':17},
                    {'titulo':'Dif Cant','tipo':'numeric','ancho':17},
                    {'titulo':'ERI','tipo':'numeric','ancho':17},
                    {'titulo':'Asignado','tipo':'string','ancho':17},
                    {'titulo':'Apoya','tipo':'string','ancho':17},
                    {'titulo':'Fecha y Hora de Inicio','tipo':'date','ancho':17},
                    {'titulo':'Fecha y Hora de Fin','tipo':'date','ancho':17},
                    {'titulo':'Status','tipo':'string','ancho':17},
                    {'titulo':'Categoría','tipo':'string','ancho':17}
                ],
                data:[]};
                
                let data: Array<any>=[];
                
                for(let ii=0;ii<response.inventariosfisico.length;ii++){
                    for(let dd=0;dd<response.inventariosfisico[ii].detalle.length;dd++){
                        this.reporte.push({
                            numero: response.inventariosfisico[ii].idinventariofisico,
                            fecha: response.inventariosfisico[ii].fecha,
                            cliente: response.inventariosfisico[ii].cliente,
                            diferencia: response.inventariosfisico[ii].diferencia_texto,
                            total_items: response.inventariosfisico[ii].detalle.length,
                            total_cantidad: response.inventariosfisico[ii].cantidad_total,
                            codigo: response.inventariosfisico[ii].detalle[dd].codigo,
                            serie: response.inventariosfisico[ii].detalle[dd].serie,
                            descripcion: response.inventariosfisico[ii].detalle[dd].descripcion,
                            ubicacion: response.inventariosfisico[ii].detalle[dd].ubicacion,
                            embalaje: response.inventariosfisico[ii].detalle[dd].embalaje,
                            cantidad: response.inventariosfisico[ii].detalle[dd].cantidad,
                            cantidad_real: response.inventariosfisico[ii].detalle[dd].cantidad_real,
                            diferencia_cantidad: response.inventariosfisico[ii].detalle[dd].diferencia_cantidad,
                            diferencia_porcentaje: response.inventariosfisico[ii].detalle[dd].diferencia_porcentaje,
                            asignado: response.inventariosfisico[ii].asignado,
                            apoyo: response.inventariosfisico[ii].apoyo,
                            fecha_inicio: response.inventariosfisico[ii].fecha_inicio,
                            fecha_fin: response.inventariosfisico[ii].fecha_fin,
                            status: response.inventariosfisico[ii].status,
                            categoria: response.inventariosfisico[ii].categoria,
                        });
                        
                        
                        data.push([
                            {'valor': response.inventariosfisico[ii].idinventariofisico},
                            {'valor': response.inventariosfisico[ii].fecha},
                            {'valor': response.inventariosfisico[ii].cliente},
                            {'valor': response.inventariosfisico[ii].diferencia_texto},
                            {'valor': response.inventariosfisico[ii].detalle.length},
                            {'valor': response.inventariosfisico[ii].cantidad_total},
                            {'valor': response.inventariosfisico[ii].detalle[dd].codigo},
                            {'valor': response.inventariosfisico[ii].detalle[dd].serie},
                            {'valor': response.inventariosfisico[ii].detalle[dd].descripcion},
                            {'valor': response.inventariosfisico[ii].detalle[dd].ubicacion},
                            {'valor': response.inventariosfisico[ii].detalle[dd].embalaje},
                            {'valor': response.inventariosfisico[ii].detalle[dd].cantidad},
                            {'valor': response.inventariosfisico[ii].detalle[dd].cantidad_real},
                            {'valor': response.inventariosfisico[ii].detalle[dd].diferencia_cantidad},
                            {'valor': response.inventariosfisico[ii].detalle[dd].diferencia_porcentaje},
                            {'valor': response.inventariosfisico[ii].asignado},
                            {'valor': response.inventariosfisico[ii].apoyo},
                            {'valor': response.inventariosfisico[ii].fecha_inicio},
                            {'valor': response.inventariosfisico[ii].fecha_fin},
                            {'valor': response.inventariosfisico[ii].status},
                            {'valor': response.inventariosfisico[ii].categoria},
                        ]);
                        
                    }
                }
                
                this.reportexlsx.data=data;
                
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
