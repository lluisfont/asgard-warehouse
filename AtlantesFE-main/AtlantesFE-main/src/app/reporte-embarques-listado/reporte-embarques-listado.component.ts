import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {EntidadesService} from '../services/entidades.service';
import {ExportExcelService} from '../services/export-excel.service';
import {EmbarqueService} from '../services/embarque.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-embarques-listado',
    templateUrl: './reporte-embarques-listado.component.html',
    styleUrl: './reporte-embarques-listado.component.css',
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,EntidadesService,ExportExcelService,EmbarqueService]
})
export class ReporteEmbarquesListadoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public porcarpeta: boolean=false;
    
    public carpeta:string='';
    public error_carpeta: boolean=false;
    
    public entidades: Array<any>;
    public identidad: string;
    
    public importacion_exportaciones: Array<any>;
    public importacion_exportacion: number;
    
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_embarques_listado: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _entidadesService: EntidadesService,
        private _embarqueService: EmbarqueService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_embarques_listado=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 70);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_embarques_listado=true;
                }
            }
        }
        this.identidad=null;
        this.importacion_exportacion=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }
    
    ngOnInit(): void {
        this._entidadesService.verclientes(this.token).subscribe(
            response =>{
                this.entidades = response.clientes;
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.importacion_exportacion(this.token).subscribe(
            response =>{
                this.importacion_exportaciones = response.importacion_exportacion;
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );

    }
    
    generarReporte(){
        let filtros={
            idcliente: this.identidad,
            importacion_exportacion: this.importacion_exportacion,
            fechainicial: this.fechainicial,
            fechafinal: this.fechafinal
        }
        
        this._embarqueService.embarques(this.token,filtros).subscribe(
            response =>{
                this.reporte=response.embarques;
                this.reporte.forEach(function(ff) {
                    ff.fecharealizacion = new Date(ff.fecharealizacion.replace(/-/g, '\/'));
                    if(ff.finalizado){
                        ff.estado='Finalizado';
                    }else{
                        ff.estado='No Finalizado';
                    }
                });

                this.reportexlsx={titulo:"Embarques",cabecera:[
                    {'titulo':'Embarque','tipo':'string','ancho':17},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Fecha realizacion','tipo':'date','ancho':17},
                    {'titulo':'Descripcion','tipo':'string','ancho':30},
                    {'titulo':'Cargo BOB','tipo':'number','ancho':20},
                    {'titulo':'Costo BOB','tipo':'number','ancho':20},
                    {'titulo':'Balance BOB','tipo':'number','ancho':20},
                    {'titulo':'No DUI/DIM','tipo':'string','ancho':17},
                    {'titulo':'Estado','tipo':'string','ancho':17}
                ],
                data:[]};
                let data: Array<any>=[];
                for (let rr = 0; rr < this.reporte.length; rr++){
                    data.push([
                        {'valor': this.reporte[rr].embarque},
                        {'valor': this.reporte[rr].cliente},
                        {'valor': this.reporte[rr].fecharealizacion},
                        {'valor': this.reporte[rr].descripcioncarga},
                        {'valor': this.reporte[rr].valorcargado},
                        {'valor': this.reporte[rr].valorcosteado},
                        {'valor': this.reporte[rr].balance},
                        {'valor': this.reporte[rr].nodui},
                        {'valor': this.reporte[rr].estado}
                    ]);
                }
                this.reportexlsx.data=data;
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
