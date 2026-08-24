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
    selector: 'app-reporte-almacen-noconforme',
    templateUrl: './reporte-almacen-noconforme.component.html',
    styleUrls: ['./reporte-almacen-noconforme.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService,ExportExcelService]
})
export class ReporteAlmacenNoconformeComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: string;
    public fechacorte: string;
    public generado: boolean=false;
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_no_conforme: boolean=false;

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
            this.ver_reporte_almacen_no_conforme=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 60);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_no_conforme=true;
                }
            }
        }
        this.idcliente=null;
        this.fechacorte = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
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
    }
    
    generarReporte(){
        //this.reportexlsx=[];
        this.generado=true;
        let idclienteenviar=null;
        if (this.idcliente!=null){
            idclienteenviar=this.idcliente;
        }
        
        if (this.fechacorte==null || this.fechacorte==''){
            this.fechacorte = this._usuarioService.getCurrentDateFilterValue();
        }
        
        this._almacenesService.inventario(this.token, idclienteenviar, this.fechacorte,false).subscribe(
            response =>{
                
                this.reporte = response.inventario.filter(function(item){
                    return (item.cantidad>0 && item.idno_conf!=null);
                });
                
                this.reporte.filter(reporte => reporte.fechavencimiento!=null).forEach(
                    reporte => (reporte.fechavencimiento = new Date(reporte.fechavencimiento.replace(/-/g, '\/')))
                );
                
                this.reporte.filter(reporte => reporte.fechaingreso!=null).forEach(
                    reporte => (reporte.fechaingreso = new Date(reporte.fechaingreso.replace(/-/g, '\/')))
                );
                
                this.reportexlsx={titulo:"No Conforme",cabecera:[
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Serie','tipo':'string','ancho':17},
                    {'titulo':'Descripción','tipo':'string','ancho':25},
                    {'titulo':'Cantidad','tipo':'numeric','ancho':17},
                    {'titulo':'Embalaje','tipo':'string','ancho':17},
                    {'titulo':'Categoría','tipo':'string','ancho':17},
                    {'titulo':'Centro Distribucion','tipo':'string','ancho':17},
                    //{'titulo':'CT No Conf','tipo':'numeric','ancho':17},
                    {'titulo':'No Conf','tipo':'string','ancho':17},
                    {'titulo':'MERMA','tipo':'string','ancho':17},
                    {'titulo':'Lote','tipo':'string','ancho':17},
                    {'titulo':'Fecha Vencimiento','tipo':'date','ancho':17},
                    {'titulo':'Ubicación','tipo':'string','ancho':17},
                    {'titulo':'Fecha Ingreso','tipo':'date','ancho':17},
                    {'titulo':'Nro Ingreso','tipo':'string','ancho':17},
                    {'titulo':'DIM','tipo':'string','ancho':17},
                    {'titulo':'Observaciones','tipo':'string','ancho':25}
                ],
                data:[]};
                
                let data: Array<any>=[];
                for (let r = 0; r<this.reporte.length; r++){
                    data.push([
                        {'valor': this.reporte[r].cliente},
                        {'valor': this.reporte[r].codigo},
                        {'valor': this.reporte[r].serie},
                        {'valor': this.reporte[r].descripcion},
                        {'valor': this.reporte[r].cantidad},
                        {'valor': this.reporte[r].codigoembalaje},
                        {'valor': this.reporte[r].categoria},
                        {'valor': this.reporte[r].centro_distribucion},
                        //{'valor': this.reporte[r].cantidad_no_conf},
                        {'valor': this.reporte[r].no_conf},
                        {'valor': this.reporte[r].merma},
                        {'valor': this.reporte[r].lote},
                        {'valor': this.reporte[r].fechavencimiento},
                        {'valor': this.reporte[r].ubicacionalmacen},
                        {'valor': this.reporte[r].fechaingreso},
                        {'valor': this.reporte[r].numeroingreso},
                        {'valor': this.reporte[r].dui},
                        {'valor': this.reporte[r].observaciones}
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
