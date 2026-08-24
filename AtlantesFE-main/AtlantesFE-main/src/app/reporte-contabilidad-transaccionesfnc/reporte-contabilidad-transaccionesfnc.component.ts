import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-contabilidad-transaccionesfnc',
    templateUrl: './reporte-contabilidad-transaccionesfnc.component.html',
    styleUrls: ['./reporte-contabilidad-transaccionesfnc.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,ExportExcelService]
})
export class ReporteContabilidadTransaccionesfncComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public identidad: string;
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_list_trans: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _contabilidadService: ContabilidadService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_contabilidad_list_trans=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 73);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_list_trans=true;
                }
            }
        }
        this.identidad=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades = response.entidades;
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    generarReporte(){
        this.generado=true;
        this.reporte=[];
        //this.reportexlsx=[];
        var fechainicial = this.fechainicial;
        var fechafinal = this.fechafinal;
        this._contabilidadService.verrangofacturas(this.token, fechainicial, fechafinal).subscribe(
            response =>{
                
                var facturas=response.facturas.filter(function(cc){
                    return (cc.idestadofactura==1)
                });
                
                facturas.forEach(function(ff) {
                    ff.fecha = new Date(ff.fecha.replace(/-/g, '\/'));
                });
                
                console.log(facturas);
                
                this._contabilidadService.verrangonotascobranza(this.token, fechainicial, fechafinal).subscribe(
                    responsenotascobranza =>{
                        var notascobranza=responsenotascobranza.notascobranza.filter(function(cc){
                            return (cc.idestadonotadebito==1)
                        });
                        
                        notascobranza.forEach(function(ff) {
                            ff.fecha = new Date(ff.fecha.replace(/-/g, '\/'));
                        });
                        
                        //console.log(notascobranza);
                        this._contabilidadService.cobrosdetalle(this.token).subscribe(
                            responsecobros =>{
                                //var cobrosdetalle=responsecobros.cobrosdetalle;
                                
                                var cobrosdetalle=responsecobros.cobrosdetalle.filter(function(cc){
                                    return (cc.fechapago <= fechafinal)
                                });
                                
                                //console.log(cobrosdetalle);
                                
                                var agregarfactura: boolean=true;
                                var agregarnotacobranza: boolean=true;
                                
                                this.reportexlsx={titulo:"Listado de transacciones",cabecera:[
                                    {'titulo':'Numero','tipo':'string','ancho':17},
                                    {'titulo':'Tipo','tipo':'string','ancho':17},
                                    {'titulo':'Aplicado a','tipo':'string','ancho':20},
                                    {'titulo':'Fecha','tipo':'date','ancho':17},
                                    {'titulo':'Generado','tipo':'string','ancho':20},
                                    {'titulo':'Valor BOB','tipo':'number','ancho':17},
                                    {'titulo':'Estado','tipo':'string','ancho':17},
                                    {'titulo':'Monto Pagado BOB','tipo':'number','ancho':17},
                                    {'titulo':'Monto a Pagar BOB','tipo':'number','ancho':17},
                                    {'titulo':'Moneda','tipo':'string','ancho':15}
                                ],
                                data:[]};
                                let data: Array<any>=[];
                                
                                for (let ff = 0; ff < facturas.length; ff++){
                                    agregarfactura=true;
                                    if (this.identidad != null){
                                        if ((facturas[ff].idcobraratipo + "-" + facturas[ff].idcobrara) == this.identidad){
                                            agregarfactura=true;
                                        }else{
                                            agregarfactura=false;
                                        }
                                    }
                                    
                                    if(agregarfactura){
                                        var cobrado=0;
                                        for (let cc = 0; cc < cobrosdetalle.length; cc++){
                                            if(facturas[ff].idfactura==cobrosdetalle[cc].idfacturanotadebito && 1==cobrosdetalle[cc].idtipocobro){
                                                cobrado=cobrado+cobrosdetalle[cc].cobrado;
                                            }
                                        }
                                        let estado='Cerrado';
                                        if(parseFloat(facturas[ff].valorfacturado.toFixed(2))>parseFloat(cobrado.toFixed(2))){
                                            estado='Abierto';
                                        }
                                        
                                        this.reporte.push({
                                            numero: facturas[ff].nrofactura,
                                            tipo: 'Factura',
                                            aplicado: facturas[ff].entidadcobrar,
                                            fecha: facturas[ff].fecha,
                                            usuario: facturas[ff].usuario,
                                            monto: facturas[ff].valorfacturado,
                                            estado: estado,
                                            pagado: cobrado,
                                            apagar: facturas[ff].valorfacturado-cobrado,
                                            moneda: 'BOB'
                                        });
                                        
                                        data.push([
                                            {'valor': facturas[ff].nrofactura,},
                                            {'valor': 'Factura'},
                                            {'valor': facturas[ff].entidadcobrar,},
                                            {'valor': facturas[ff].fecha,},
                                            {'valor': facturas[ff].usuario,},
                                            {'valor': facturas[ff].valorfacturado,},
                                            {'valor': estado,},
                                            {'valor': cobrado,},
                                            {'valor': facturas[ff].valorfacturado-cobrado,},
                                            {'valor': 'BOB'}
                                        ]);
                                        
                                        
                                        
                                    }
                                }
                                
                                for (let nc = 0; nc < notascobranza.length; nc++){
                                    agregarnotacobranza=true;
                                    if (this.identidad != null){
                                        if ((notascobranza[nc].idcobraratipo + "-" + notascobranza[nc].idcobrara) == this.identidad){
                                            agregarnotacobranza=true;
                                        }else{
                                            agregarnotacobranza=false;
                                        }
                                    }
                                    
                                    if(agregarnotacobranza){
                                        var cobrado=0;
                                        for (let cc = 0; cc < cobrosdetalle.length; cc++){
                                            if(notascobranza[nc].idnotadebito==cobrosdetalle[cc].idfacturanotadebito && 2==cobrosdetalle[cc].idtipocobro){
                                                cobrado=cobrado+cobrosdetalle[cc].cobrado;
                                            }
                                        }
                                        let estado='Cerrado';
                                        if(parseFloat(notascobranza[nc].monto.toFixed(2))>parseFloat(cobrado.toFixed(2))){
                                            estado='Abierto';
                                        }
                                        
                                        this.reporte.push({
                                            numero: notascobranza[nc].nronotadebito,
                                            tipo: 'Nota de Cobranza',
                                            aplicado: notascobranza[nc].entidadcobrar,
                                            fecha: notascobranza[nc].fecha,
                                            usuario: notascobranza[nc].usuario,
                                            monto: notascobranza[nc].monto,
                                            estado: estado,
                                            pagado: cobrado,
                                            apagar: notascobranza[nc].monto-cobrado,
                                            moneda: 'BOB'
                                        });
                                        
                                        data.push([
                                            {'valor': notascobranza[nc].nronotadebito,},
                                            {'valor': 'Nota de Cobranza'},
                                            {'valor': notascobranza[nc].entidadcobrar,},
                                            {'valor': notascobranza[nc].fecha,},
                                            {'valor': notascobranza[nc].usuario,},
                                            {'valor': notascobranza[nc].monto,},
                                            {'valor': estado,},
                                            {'valor': cobrado,},
                                            {'valor': notascobranza[nc].monto-cobrado,},
                                            {'valor': 'BOB'}
                                        ]);
                                    }
                                }
                                
                                this.reportexlsx.data=data;
                                
                                this.generado=false;
                                //console.log(this.reporte);
                                
                            },
                            error=>{
                                console.log(<any>error)
                            }
                        );
                        
                        
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            },
            error=>{
                console.log(<any>error)
            }
        );
                            
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
        /*
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.reportexlsx);
            const workbook = { Sheets: { 'TransFCNC': worksheet }, SheetNames: ['TransFCNC'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "TransFCNC");
        });
        */
    }
    
    saveAsExcelFile(buffer: any, fileName: string): void {
        let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
        let EXCEL_EXTENSION = '.xlsx';
        const data: Blob = new Blob([buffer], {
            type: EXCEL_TYPE
        });
        FileSaver.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
    }

}
