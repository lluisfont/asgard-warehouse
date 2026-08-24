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
    selector: 'app-reporte-contabilidad-ordenes-pago-concepto',
    templateUrl: './reporte-contabilidad-ordenes-pago-concepto.component.html',
    styleUrl: './reporte-contabilidad-ordenes-pago-concepto.component.css',
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,AsgardService,ExportExcelService]
})
export class ReporteContabilidadOrdenesPagoConceptoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_ordenes_pago_concepto: boolean=false;
    
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
            this.ver_reporte_contabilidad_ordenes_pago_concepto=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 75);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_ordenes_pago_concepto=true;
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
        
        
        this._contabilidadService.reporteOrdenesPagoConcepto(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                this.generado=true;
                //console.log(response.facturas);
                this.reporte=response.ordenespago;
                
                this.reportexlsx={titulo:"Ordenes de Pago por Concepto",cabecera:[
                    {'titulo':'Mes','tipo':'number','ancho':15},
                    {'titulo':'Cliente','tipo':'string','ancho':40},
                    {'titulo':'Embarque','tipo':'string','ancho':17},
                    {'titulo':'Fecha','tipo':'date','ancho':17},
                    {'titulo':'Numero','tipo':'string','ancho':17},
                    {'titulo':'Estado','tipo':'string','ancho':20},
                    {'titulo':'Concepto','tipo':'string','ancho':30},
                    {'titulo':'Monto BOB','tipo':'number','ancho':20}
                ],
                data:[]};
                let data: Array<any>=[];

                for(let ff=0; ff<this.reporte.length; ff++){
                    data.push([
                        {'valor': this.reporte[ff].mes},
                        {'valor': this.reporte[ff].cliente},
                        {'valor': this.reporte[ff].embarque},
                        {'valor': this.reporte[ff].fecha},
                        {'valor': this.reporte[ff].numero},
                        {'valor': this.reporte[ff].estado},
                        {'valor': this.reporte[ff].concepto},
                        {'valor': this.reporte[ff].monto}
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
