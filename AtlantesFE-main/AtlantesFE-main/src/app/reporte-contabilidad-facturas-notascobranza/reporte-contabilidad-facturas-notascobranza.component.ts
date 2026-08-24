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
    selector: 'app-reporte-contabilidad-facturas-notascobranza',
    templateUrl: './reporte-contabilidad-facturas-notascobranza.component.html',
    styleUrls: ['./reporte-contabilidad-facturas-notascobranza.component.css'],
    providers:[UsuarioService,DatoMaestroService,ContabilidadService,AsgardService,ExportExcelService]
})
export class ReporteContabilidadFacturasNotascobranzaComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public porcarpeta: boolean=false;
    
    public carpeta:string='';
    public error_carpeta: boolean=false;
    
    public entidades: Array<any>;
    public identidad: string;
    
    public importacion_exportaciones: Array<any>;
    public importacion_exportacion: number;
    
    public transportesAgard: Array<any>;
    
    public fechainicial: string;
    public fechafinal: string;
    public generado: boolean=false;
    
    
    
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_contabilidad_facturas_notas_cobranza: boolean=false;
    
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
            this.ver_reporte_contabilidad_facturas_notas_cobranza=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 72);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_contabilidad_facturas_notas_cobranza=true;
                }
            }
        }
        this.identidad=null;
        this.importacion_exportacion=null;
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
        
        this._datomaestroService.importacion_exportacion(this.token).subscribe(
            response =>{
                this.importacion_exportaciones = response.importacion_exportacion;
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._asgardService.transporteAsgard(this.token).subscribe(
            response =>{
                this.transportesAgard = response.transporteAsgard;
                //console.log(this.transportesAgard);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }
    
    generarReporte(){
        //this.reportexlsx=[];
        this.reporte=[];
        var fechainicial = this.fechainicial;
        var fechafinal = this.fechafinal;
        var identidad=this.identidad;
        var importacion_exportacion=this.importacion_exportacion;
        var carpeta=this.carpeta;
        if (this.porcarpeta){
            this.error_carpeta=false;
            if (this.carpeta.length<4){
                this.error_carpeta=true;
            }
            if(!this.error_carpeta){
                this._contabilidadService.verfacturas(this.token).subscribe(
                    response =>{
                        this.generado=true;
                        var facturas=[];
                        facturas=response.facturas.filter(function(cc){
                            let filtrocarpeta=false;
                            if(cc.carpetapacena.includes(carpeta)){
                                filtrocarpeta=true;
                            }
                            return filtrocarpeta;
                        });
                        
                        this._contabilidadService.vernotascobranza(this.token).subscribe(
                            responsenc =>{
                                var notascobranza=[];
                                notascobranza=responsenc.facturas.filter(function(cc){
                                    let filtrocarpeta=false;
                                    if(cc.carpetapacena.includes(carpeta)){
                                        filtrocarpeta=true;
                                    }
                                    return filtrocarpeta;
                                });
                                //console.log(facturas);
                                this.generado=false;
                                this.armarReporte(facturas,notascobranza);
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
        }else{
            this._contabilidadService.verrangofacturas(this.token, fechainicial, fechafinal).subscribe(
                response =>{
                    this.generado=true;
                    //console.log(response.facturas);
                    var facturas=[];
                    facturas=response.facturas.filter(function(cc){
                        let filtroentidad=true;
                        let filtroimportacion_exportacion=true;
                        if(identidad!=null){
                            if(identidad!=cc.idcobraratipo+"-"+cc.idcobrara){
                                filtroentidad=false;
                            }
                        }
                        if(importacion_exportacion!=null){
                            if(importacion_exportacion!=cc.importacion_exportacion){
                                filtroimportacion_exportacion=false;
                            }
                            
                        }
                        return (filtroentidad && filtroimportacion_exportacion);
                    });
                    this._contabilidadService.verrangonotascobranza(this.token, fechainicial, fechafinal).subscribe(
                        responsenc =>{
                            var notascobranza=[];
                            //console.log(responsenc);
                            
                            notascobranza=responsenc.notascobranza.filter(function(cc){
                                let filtroentidad=true;
                                let filtroimportacion_exportacion=true;
                                if(identidad!=null){
                                    if(identidad!=cc.idcobraratipo+"-"+cc.idcobrara){
                                        filtroentidad=false;
                                    }
                                }
                                if(importacion_exportacion!=null){
                                    if(importacion_exportacion!=cc.importacion_exportacion){
                                        filtroimportacion_exportacion=false;
                                    }

                                }
                                return (filtroentidad && filtroimportacion_exportacion);
                            });
                            
                            this.generado=false;
                            this.armarReporte(facturas,notascobranza);
                            
                            //console.log(notascobranza);
                        },
                        error=>{
                            console.log(<any>error)
                        }
                    );
                    
                    
                    //console.log(facturas);
                    
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
        
    }
    
    armarReporte(facturas: Array<any>, notascobranza: Array<any>){
        console.log(facturas);
        console.log(notascobranza);
        console.log(this.transportesAgard);
        this.reporte=[];
        
        facturas.forEach(function(ff) {
            ff.fecha = new Date(ff.fecha.replace(/-/g, '\/'));
        });
        
        notascobranza.forEach(function(ff) {
            ff.fecha = new Date(ff.fecha.replace(/-/g, '\/'));
        });
        
        this.reportexlsx={titulo:"Facturas Notas de Cobranza",cabecera:[
            {'titulo':'Tipo Documento','tipo':'string','ancho':15},
            {'titulo':'Fecha','tipo':'date','ancho':17},
            {'titulo':'Numero','tipo':'string','ancho':17},
            {'titulo':'No DUI/DIM','tipo':'string','ancho':17},
            {'titulo':'Embarque','tipo':'string','ancho':17},
            {'titulo':'Cliente','tipo':'string','ancho':40},
            {'titulo':'Tipo','tipo':'string','ancho':17},
            {'titulo':'Usuario','tipo':'string','ancho':20},
            {'titulo':'Carpeta Paceña','tipo':'string','ancho':17},
            {'titulo':'Monto BOB','tipo':'number','ancho':20},
            {'titulo':'Estado','tipo':'string','ancho':20},
            {'titulo':'Transportista','tipo':'string','ancho':20},
            {'titulo':'Arribo a','tipo':'string','ancho':20},
            {'titulo':'Regional','tipo':'string','ancho':20},
            {'titulo':'Esta en planilla','tipo':'string','ancho':15}
        ],
        data:[]};
        let data: Array<any>=[];
        
        for(let ff=0; ff<facturas.length; ff++){
            let estaplanilla='NO';
            let indicecarpeta = this.transportesAgard.findIndex(x => (parseInt(x.carpeta) === parseInt(facturas[ff].carpetapacena) && parseInt(x.nro) === parseInt(facturas[ff].nrofactura)));
            if(indicecarpeta>=0){
                estaplanilla='SI';
            }
            this.reporte.push({
                tipodocumento: 'Factura',
                fecha: facturas[ff].fecha,
                numero: facturas[ff].nrofactura,
                nodui: facturas[ff].nodui,
                embarque: facturas[ff].embarque,
                nombre: facturas[ff].nombre,
                tipo: facturas[ff].importacion_exportacion_codigo,
                usuario: facturas[ff].usuario,
                carpetapacena: facturas[ff].carpetapacena,
                monto: facturas[ff].valorfacturado,
                estado: facturas[ff].estadofactura,
                transportista: facturas[ff].transportista,
                arribo: facturas[ff].ciudad,
                regional: facturas[ff].ciudadembarque,
                estaplanilla: estaplanilla
            });
            
            data.push([
                {'valor': 'Factura'},
                {'valor': facturas[ff].fecha},
                {'valor': facturas[ff].nrofactura},
                {'valor': facturas[ff].nodui},
                {'valor': facturas[ff].embarque},
                {'valor': facturas[ff].nombre},
                {'valor': facturas[ff].importacion_exportacion_codigo},
                {'valor': facturas[ff].usuario},
                {'valor': facturas[ff].carpetapacena},
                {'valor': facturas[ff].valorfacturado},
                {'valor': facturas[ff].estadofactura},
                {'valor': facturas[ff].transportista},
                {'valor': facturas[ff].ciudad},
                {'valor': facturas[ff].ciudadembarque},
                {'valor': estaplanilla}
            ]);
        }
        
        
        
        for (let ff = 0; ff < notascobranza.length; ff++){
            let estaplanilla='NO';
            let indicecarpeta = this.transportesAgard.findIndex(x => (parseInt(x.carpeta) === parseInt(notascobranza[ff].carpetapacena) && x.nro === notascobranza[ff].nronotadebito));
            if(indicecarpeta>=0){
                estaplanilla='SI';
            }
            
            this.reporte.push({
                tipodocumento: 'Nota de Cobranza',
                fecha: notascobranza[ff].fecha,
                numero: notascobranza[ff].nronotadebito,
                nodui: notascobranza[ff].nodui,
                embarque: notascobranza[ff].embarque,
                nombre: notascobranza[ff].cliente,
                tipo: notascobranza[ff].importacion_exportacion_codigo,
                usuario: notascobranza[ff].usuario,
                carpetapacena: notascobranza[ff].carpetapacena,
                monto: notascobranza[ff].monto,
                estado: notascobranza[ff].estadonotadebito,
                transportista: notascobranza[ff].transportista,
                arribo: notascobranza[ff].ciudad,
                regional: notascobranza[ff].ciudadembarque,
                estaplanilla: estaplanilla
            });
            
            data.push([
                {'valor': 'Nota de Cobranza'},
                {'valor': notascobranza[ff].fecha},
                {'valor': notascobranza[ff].nronotadebito},
                {'valor': notascobranza[ff].nodui},
                {'valor': notascobranza[ff].embarque},
                {'valor': notascobranza[ff].cliente},
                {'valor': notascobranza[ff].importacion_exportacion_codigo},
                {'valor': notascobranza[ff].usuario},
                {'valor': notascobranza[ff].carpetapacena},
                {'valor': notascobranza[ff].monto},
                {'valor': notascobranza[ff].estadonotadebito},
                {'valor': notascobranza[ff].transportista},
                {'valor': notascobranza[ff].ciudad},
                {'valor': notascobranza[ff].ciudadembarque},
                {'valor': estaplanilla},
            ]);
            
        }
        
        this.reportexlsx.data=data;
        
        
        
    }
    
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
        /*
        import("xlsx").then(xlsx => {
            const worksheet = xlsx.utils.json_to_sheet(this.reportexlsx);
            const workbook = { Sheets: { 'FacturasNotasCobranza': worksheet }, SheetNames: ['FacturasNotasCobranza'] };
            const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
            this.saveAsExcelFile(excelBuffer, "FacturasNotasCobranza");
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
