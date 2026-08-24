import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {AsgardService} from '../services/asgard.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-conceptos',
    templateUrl: './reporte-contabilidad-conceptos.component.html',
    styleUrl: './reporte-contabilidad-conceptos.component.css',
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,AsgardService,ExportExcelService]
})
export class ReporteContabilidadConceptosComponent {
    public token: string;
    public tokenDetalle: any;
    
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_montos_concepto: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _asgardService: AsgardService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_montos_concepto=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 76);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_montos_concepto=true;
                }
            }
        }
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {

        
    }
    
    generarReporte(){
        this.reporte=[];
        var fechainicial = this.fechainicial;
        var fechafinal = this.fechafinal;
        
        
        this._contabilidadService.reporteMontosConcepto(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                this.generado=true;
                //console.log(response.facturas);
                this.reporte=response.conceptos;
                console.log(this.reporte);
                
                
                this.reportexlsx={titulo:"Montos por Concepto",cabecera:[
                    {'titulo':'Embarque','tipo':'string','ancho':17},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Fecha Realización','tipo':'date','ancho':17},
                    {'titulo':'Fecha Emisión','tipo':'date','ancho':17},
                    {'titulo':'Concepto','tipo':'string','ancho':30},
                    {'titulo':'Orden de Ingreso','tipo':'number','ancho':20},
                    {'titulo':'Factura SLG','tipo':'number','ancho':20},
                    {'titulo':'Nota de Cobranza','tipo':'number','ancho':20},
                    {'titulo':'Invoice','tipo':'number','ancho':20},
                    {'titulo':'Monto Cargo','tipo':'number','ancho':20},
                    {'titulo':'Orden de Egreso','tipo':'number','ancho':20},
                    {'titulo':'Costo (OP)','tipo':'number','ancho':20},
                    {'titulo':'Monto Costo','tipo':'number','ancho':20},
                    {'titulo':'DUI/DIM','tipo':'string','ancho':17},
                    {'titulo':'Estado','tipo':'string','ancho':17},
                    {'titulo':'Proveedor/Agente','tipo':'string','ancho':40},
                    {'titulo':'Transportista','tipo':'string','ancho':40},
                ],
                data:[]};
                let data: Array<any>=[];

                for(let ff=0; ff<this.reporte.length; ff++){
                    data.push([
                        {'valor': this.reporte[ff].embarque},
                        {'valor': this.reporte[ff].cliente},
                        {'valor': this.reporte[ff].fecharealizacion},
                        {'valor': this.reporte[ff].fecha},
                        {'valor': this.reporte[ff].concepto},
                        {'valor': this.reporte[ff].montoi},
                        {'valor': this.reporte[ff].montofactura},
                        {'valor': this.reporte[ff].montonotadebito},
                        {'valor': this.reporte[ff].montoinvoice},
                        {'valor': this.reporte[ff].montocargo},
                        {'valor': this.reporte[ff].montoe},
                        {'valor': this.reporte[ff].montoop},
                        {'valor': this.reporte[ff].montocosto},
                        {'valor': this.reporte[ff].nodui},
                        {'valor': this.reporte[ff].estado},
                        {'valor': this.reporte[ff].proveedor},
                        {'valor': this.reporte[ff].transportista},
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
