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
    selector: 'app-reporte-contabilidad-facturas-concepto',
    templateUrl: './reporte-contabilidad-facturas-concepto.component.html',
    styleUrl: './reporte-contabilidad-facturas-concepto.component.css',
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,AsgardService,ExportExcelService]
})
export class ReporteContabilidadFacturasConceptoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_facturas_concepto: boolean=false;
    
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
            this.ver_reporte_contabilidad_facturas_concepto=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 74);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_facturas_concepto=true;
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
        
        
        this._contabilidadService.reporteFacturasConcepto(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                this.generado=true;
                //console.log(response.facturas);
                this.reporte=response.facturas;
                
                this.reportexlsx={titulo:"Facturas por Concepto",cabecera:[
                    {'titulo':'Mes','tipo':'number','ancho':15},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Embarque','tipo':'string','ancho':17},
                    {'titulo':'NIT','tipo':'string','ancho':17},
                    {'titulo':'Fecha','tipo':'date','ancho':17},
                    {'titulo':'Numero','tipo':'string','ancho':17},
                    {'titulo':'Estado','tipo':'string','ancho':20},
                    {'titulo':'Concepto','tipo':'string','ancho':30},
                    {'titulo':'Monto BOB','tipo':'number','ancho':20},
                    {'titulo':'Monto NetoBOB','tipo':'number','ancho':20}
                ],
                data:[]};
                let data: Array<any>=[];

                for(let ff=0; ff<this.reporte.length; ff++){
                    data.push([
                        {'valor': this.reporte[ff].mes},
                        {'valor': this.reporte[ff].nombre},
                        {'valor': this.reporte[ff].embarque},
                        {'valor': this.reporte[ff].nit},
                        {'valor': this.reporte[ff].fecha},
                        {'valor': this.reporte[ff].nrofactura},
                        {'valor': this.reporte[ff].estadofactura},
                        {'valor': this.reporte[ff].concepto},
                        {'valor': this.reporte[ff].monto},
                        {'valor': this.reporte[ff].montoneto}
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
