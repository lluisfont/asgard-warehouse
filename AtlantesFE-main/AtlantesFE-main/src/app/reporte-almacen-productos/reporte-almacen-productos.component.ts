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
    selector: 'app-reporte-almacen-productos',
    templateUrl: './reporte-almacen-productos.component.html',
    styleUrls: ['./reporte-almacen-productos.component.css'],
    providers:[UsuarioService,DatoMaestroService,EntidadesService,AlmacenesService,ExportExcelService]
})
export class ReporteAlmacenProductosComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: string;
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_productos: boolean=false;
    
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
            this.ver_reporte_almacen_productos=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 62);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_productos=true;
                }
            }
        }
        this.idcliente=null;
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
        this.generado=true;
        let idcliente = this.idcliente;
        
        this._datomaestroService.productoscliente(this.token).subscribe(
            response =>{
                //this.productos_cliente=response.productos_cliente;
                this.reporte = response.productos_cliente.filter(function(item){
                    let data=true;
                    if(idcliente!=null){
                        if(item.idcliente!=idcliente){
                            data=false;
                        }
                    }
                    return data;
                });
                console.log(this.reporte);
                
                this.reportexlsx={titulo:"Productos",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Rubro','tipo':'string','ancho':17},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Serie','tipo':'string','ancho':17},
                    {'titulo':'Descripción','tipo':'string','ancho':25},
                    {'titulo':'U/M Inventario','tipo':'string','ancho':17},
                    {'titulo':'U/M Compra','tipo':'string','ancho':17},
                    {'titulo':'U/M Alterna','tipo':'string','ancho':17},
                    {'titulo':'Alto (m)','tipo':'numeric','ancho':17},
                    {'titulo':'Ancho (m)','tipo':'numeric','ancho':17},
                    {'titulo':'Largo (m)','tipo':'numeric','ancho':17},
                    {'titulo':'Volumen (m3)','tipo':'numeric','ancho':17},
                    {'titulo':'Centro de Distribución','tipo':'string','ancho':20}
                ],
                data:[]};
                
                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].rubro},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].serie},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].umcompra},
                        {'valor': this.reporte[r].umalterna},
                        {'valor': this.reporte[r].alto},
                        {'valor': this.reporte[r].ancho},
                        {'valor': this.reporte[r].largo},
                        {'valor': this.reporte[r].alto*this.reporte[r].ancho*this.reporte[r].largo},
                        {'valor': this.reporte[r].centro_distribucion}
                    ]);
                }
                
                this.reportexlsx.data=data;

                
                console.log(this.reporte);
                
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
