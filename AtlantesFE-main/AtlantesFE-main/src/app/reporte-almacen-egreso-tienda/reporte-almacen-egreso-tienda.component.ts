import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-almacen-egreso-tienda',
    templateUrl: './reporte-almacen-egreso-tienda.component.html',
    styleUrls: ['./reporte-almacen-egreso-tienda.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenEgresoTiendaComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: number;
    public error_idcliente: boolean=false;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public columnas: Array<any>=[];
    public filtros: Array<any>=['codigo','serie','descripcion','categoria','centro_distribucion','codigoembalaje','ingresos','inicial','saldo','salidas'];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_egreso_por_tiendas: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_almacen_egreso_por_tiendas=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 61);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_egreso_por_tiendas=true;
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
                this.entidades = response.entidades.filter(function (entidad) {
                    return entidad.idtipoentidad==1;
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    generarReporte(){
        this.generado=true;
        this.reporte=[];
        
        if (this.fechafinal==null || this.fechafinal=='' || this.fechainicial==null || this.fechainicial==''){
            this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
            this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        }
        
        this.error_idcliente=false;
        if (this.idcliente==null){
            this.error_idcliente=true;
            this.generado=false;
        }
        
        if (!this.error_idcliente){
            this._almacenesService.reportemovimientotienda(this.token, this.idcliente, this.fechainicial, this.fechafinal).subscribe(
                response =>{
                    //console.log(response);
                    this.reporte=response.movimientos;
                    let cabeceraxls=[
                        {'titulo':'Codigo','tipo':'string','ancho':17},
                        {'titulo':'Serie','tipo':'string','ancho':17},
                        {'titulo':'Descripción','tipo':'string','ancho':40},
                        {'titulo':'Categoría','tipo':'string','ancho':17},
                        {'titulo':'Centro distribucion','tipo':'string','ancho':17},
                        {'titulo':'Embalaje','tipo':'numeric','ancho':17},
                        {'titulo':'Inicial','tipo':'numeric','ancho':17},
                        {'titulo':'Ingresos','tipo':'numeric','ancho':17}
                    ];
                    if (this.reporte.length>0){
                        if (this.reporte[0].tiendas.length>0){
                            this.columnas=this.reporte[0].tiendas;
                            for(let tt=0;tt<this.reporte[0].tiendas.length;tt++){
                                this.filtros.push(this.reporte[0].tiendas[tt].columna);
                                cabeceraxls.push({
                                    'titulo': this.reporte[0].tiendas[tt].proyecto,
                                    'tipo': 'numeric',
                                    'ancho': 17
                                });
                            }
                            
                            cabeceraxls.push({
                                'titulo': 'Total Desp.',
                                'tipo': 'numeric',
                                'ancho': 17
                            },{
                                'titulo': 'Saldo',
                                'tipo': 'numeric',
                                'ancho': 17
                            });
                            
                            this.reportexlsx={
                                titulo:"Egreso por Tienda",
                                cabecera: cabeceraxls,
                                data:[]
                            };

                            let data: Array<any>=[];
                            
                            for (let rr = 0; rr < this.reporte.length; rr++){
                                if(this.reporte[rr].saldo<0){
                                    this.reporte[rr].color='ff0000';
                                }else{
                                    this.reporte[rr].color='ffffff';
                                }
                                let dataxls=[
                                    {'valor': this.reporte[rr].codigo, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].serie, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].descripcion, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].categoria, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].centro_distribucion, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].codigoembalaje, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].inicial, 'color': this.reporte[rr].color},
                                    {'valor': this.reporte[rr].ingresos, 'color': this.reporte[rr].color}
                                ]
                                
                                for(let tt=0;tt<this.reporte[rr].tiendas.length;tt++){
                                    this.reporte[rr] = { ...this.reporte[rr], [this.reporte[rr].tiendas[tt].columna]: this.reporte[rr].tiendas[tt].salida };
                                    dataxls.push({
                                        'valor': this.reporte[rr].tiendas[tt].salida, 'color': this.reporte[rr].color
                                    })
                                }
                                
                                dataxls.push({
                                    'valor': this.reporte[rr].salidas, 'color': this.reporte[rr].color
                                },{
                                    'valor': this.reporte[rr].saldo, 'color': this.reporte[rr].color
                                });
                                
                                data.push(dataxls);
                                
                                
                            }
                            
                            this.reportexlsx.data=data;
                        }
                    }
                    console.log(this.reportexlsx);
                    console.log(this.reporte);
                    
                    this.generado=false;
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
        
            
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
    }

}
