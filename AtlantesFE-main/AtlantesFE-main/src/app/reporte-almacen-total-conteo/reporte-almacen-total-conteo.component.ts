import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';


@Component({
    selector: 'app-reporte-almacen-total-conteo',
    templateUrl: './reporte-almacen-total-conteo.component.html',
    styleUrl: './reporte-almacen-total-conteo.component.css',
    providers:[UsuarioService,EntidadesService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenTotalConteoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public ver_reporte_almacen_total_conteo: boolean=false;
    
    public entidades: Array<any>;
    public ciudades: Array<any>;
    public almacenes: Array<any>;
    public almacenes_mostrar: Array<any>;
    
    public idcliente: number=null;
    public error_idcliente: boolean=false;
    
    public fechainicial: string;
    public fechafinal: string;
    public error_fechainicial: boolean=false;
    public error_fechafinal: boolean=false;
    public idciudad: Array<any>=[];
    public idalmacen: Array<any>=[];
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _entidadService: EntidadesService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService,
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_total_conteo=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 90);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_total_conteo=true;
                }
            }
        }
    
    }
    
    ngOnInit(): void {
        this.entidades=[];
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                
                this.entidades = response.clientes;
                
                //console.log(response.entidades);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.ciudades=[];
        this._datomaestroService.ciudades(this.token).subscribe(
            response_ciudades =>{
                this.ciudades=response_ciudades.ciudades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.almacenes=[];
        this._almacenesService.veralmacenes(this.token).subscribe(
            response =>{
                this.almacenes=response.almacenes;
                this.almacenes.forEach(o => {
                        const partes = [o.codigo_almacen, o.almacen]
                          .filter(v => v != null && String(v).trim() !== "");
                        o.codigo_y_almacen = partes.join(" ") || "";
                });
                this.setAlmacenes();
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }
    
    setAlmacenes(){
        this.idalmacen=[];
        if (this.idciudad.length==0){
            this.almacenes_mostrar = this.almacenes;
        }else{
            this.almacenes_mostrar = this.almacenes.filter(almacen => this.idciudad.includes(almacen.idciudad));
        }
        
        
    }
    
    generarReporte(){
        let error=false;
        if (!this.idcliente){
            error=true;
            this.error_idcliente=true;
        }
        
        if (!this.fechainicial){
            error=true;
            this.error_fechainicial=true;
        }
        if (!this.fechafinal){
            error=true;
            this.error_fechafinal=true;
        }
        
        if(!error){
            this.generado=true;
            this.reporte=[];
            let payload={
                ciudades: this.idciudad,
                almacenes: this.idalmacen
            };
            
            this._almacenesService.vermonitoreocentros(this.token, this.idcliente, 0, this.fechainicial, this.fechafinal, payload).subscribe(
                response => {
                    this.generado=false;
                    this.reporte=response.detalle;
                    
                    this.reportexlsx={titulo:"Total de Conteo",cabecera:[
                        {'titulo':'No Inventario','tipo':'string','ancho':20},
                        {'titulo':'Centro','tipo':'string','ancho':17},
                        {'titulo':'Ubicación','tipo':'string','ancho':30},
                        {'titulo':'Centro Encontrado','tipo':'string','ancho':17},
                        {'titulo':'Ubicación Encontrado','tipo':'string','ancho':30},
                        {'titulo':'Marca del Vehiculo ','tipo':'string','ancho':20},
                        {'titulo':'Número de Chasis ','tipo':'string','ancho':20},
                        {'titulo':'Modelo del Vehiculo','tipo':'string','ancho':40},
                        {'titulo':'Descripción del Color','tipo':'string','ancho':30},
                        {'titulo':'Categoría','tipo':'string','ancho':20},
                        {'titulo':'Estado Conteo','tipo':'string','ancho':20},
                        {'titulo':'Etiqueta ','tipo':'string','ancho':20},
                    ],
                    data:[]};

                    let data: Array<any>=[];
                    for (let r = 0; r<this.reporte.length; r++){
                        data.push([
                            {'valor': this.reporte[r].idinventariofisico},
                            {'valor': this.reporte[r].codigo_almacen},
                            {'valor': this.reporte[r].almacen},
                            {'valor': this.reporte[r].codigo_almacen_conteo},
                            {'valor': this.reporte[r].almacen_conteo},
                            {'valor': this.reporte[r].codigo},
                            {'valor': this.reporte[r].serie},
                            {'valor': this.reporte[r].descripcion},
                            {'valor': this.reporte[r].categoria},
                            {'valor': this.reporte[r].lote},
                            {'valor': this.reporte[r].estado_conteo},
                            {'valor': this.reporte[r].inventariofisicoetiqueta},
                        ]);
                    }


                     this.reportexlsx.data=data;
                    
                    
                    console.log(this.reporte);
                    
                    
                    
                },
                error => {
                    this.generado=false;
                    console.log(<any>error)
                }
            );
        }
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
    }
    
    

}
