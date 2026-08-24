import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-control-inventario-fisico',
    templateUrl: './reporte-almacen-control-inventario-fisico.component.html',
    styleUrl: './reporte-almacen-control-inventario-fisico.component.css',
    providers:[UsuarioService,EntidadesService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenControlInventarioFisicoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public ver_reporte_control_general_inventario_fisico: boolean=false;
    
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
            this.ver_reporte_control_general_inventario_fisico=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 100);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_control_general_inventario_fisico=true;
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
            //this.generado=true;
            let ajuste=false;
            if(this.idalmacen.length==0){
                this.idalmacen=this.almacenes.map(item => item.idalmacen);
                ajuste=true;
            }
            this.reporte=[];
            let payload={
                idcliente: this.idcliente,
                fecha_inicial: this.fechainicial,
                fecha_final: this.fechafinal,
                ciudades: this.idciudad,
                almacenes: this.idalmacen
            };
            console.log(payload);
            
            this._almacenesService.verinventariosfisico(this.token, payload).subscribe(
                response => {
                    this.generado=false;
                    this.reporte=response.inventariosfisico;

                    this.reporte.forEach(
                        rep => (rep.fecha = new Date(rep.fecha.replace(/-/g, '\/')))
                    );
                  
                    this.reporte.forEach(o => {
                        const partes = [o.codigo_almacen, o.almacen]
                          .filter(v => v != null && String(v).trim() !== "");
                        o.codigo_y_almacen = partes.join(" ") || "";
                    });
                    
                    
                    this.reportexlsx={titulo:"Total de Conteo",cabecera:[
                        {'titulo':'No Inventario','tipo':'string','ancho':20},
                        {'titulo':'Ciudad','tipo':'string','ancho':20},
                        {'titulo':'Almacen','tipo':'string','ancho':30},
                        {'titulo':'Encontrados','tipo':'number','ancho':20},
                        {'titulo':'Sobrantes','tipo':'number','ancho':20},
                        {'titulo':'Faltantes','tipo':'number','ancho':20},
                        {'titulo':'Pendientes','tipo':'number','ancho':20},
                        {'titulo':'Cliente','tipo':'string','ancho':35},
                        {'titulo':'Fecha de Inventario','tipo':'date','ancho':20},
                        {'titulo':'Diferencia ','tipo':'string','ancho':20},
                        {'titulo':'Status ','tipo':'string','ancho':20},
                    ],
                    data:[]};

                    let data: Array<any>=[];
                    for (let r = 0; r<this.reporte.length; r++){
                        data.push([
                            {'valor': this.reporte[r].idinventariofisico},
                            {'valor': this.reporte[r].ciudad},
                            {'valor': this.reporte[r].codigo_y_almacen},
                            {'valor': this.reporte[r].encontrados},
                            {'valor': this.reporte[r].sobrantes},
                            {'valor': this.reporte[r].faltantes},
                            {'valor': this.reporte[r].pendientes},
                            {'valor': this.reporte[r].cliente},
                            {'valor': this.reporte[r].fecha},
                            {'valor': this.reporte[r].diferencia_texto},
                            {'valor': this.reporte[r].status},
                        ]);
                    }


                    this.reportexlsx.data=data;
                    
                    
                    //console.log(this.reporte);
                    
                    if(ajuste){
                       this.idalmacen=[];
                    }
                    
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
