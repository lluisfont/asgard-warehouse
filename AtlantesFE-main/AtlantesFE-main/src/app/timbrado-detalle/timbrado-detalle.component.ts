import { Component, ViewChild, ElementRef } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {TimbradoModel} from '../models/timbrado.model';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import {GLOBAL} from './../global';
declare var $: any;

@Component({
    selector: 'app-timbrado-detalle',
    templateUrl: './timbrado-detalle.component.html',
    styleUrl: './timbrado-detalle.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,ExportExcelService]
})
export class TimbradoDetalleComponent {
    public token: string;
    public tokenDetalle: any;

    public idtimbrado: number;
    public timbrado: TimbradoModel;
    public idtimbradodetalleeliminar: number;
    
    public fecha_inicial: string=null;
    public fecha_final: string=null;
    public fecha_inicial_update: string=null;
    public fecha_final_update: string=null;
    
    public urlFormatoIngreso: string;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    
    @ViewChild('UploadFileInputUpdate')
    myInputVariableUpdate: ElementRef;
    public errorarchivoUpdate: boolean;
    public uploadFileInputUpdate: any;
    public archivocargadoUpdate: boolean;
    
    public mensajes_error: Array<any>=[];
    
    public reportexlsx: ExcelModel;
    public reportexlsx_update: ExcelModel;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_timbrado: boolean=false;
    public editar_timbrado: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _exportexcelService: ExportExcelService,
        private _route: ActivatedRoute
    ) {
        this._route.params.forEach((params: Params)=>{
            this.idtimbrado = params["idtimbrado"];
        });
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_timbrado=true;
            this.editar_timbrado=true;
        }else{
            let indiceVerTimbrado = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 26);
            if (indiceVerTimbrado>=0){
                if (this.tokenDetalle.permisos[indiceVerTimbrado].lectura){
                    this.ver_timbrado=true;
                }
                if (this.tokenDetalle.permisos[indiceVerTimbrado].escritura){
                    this.editar_timbrado=true;
                }
            }
        }
        this.urlFormatoIngreso=GLOBAL.urlFiles+'FormatoTimbrado.xlsx';
    }
    
    ngOnInit(): void {
        this.cargarTimbrado();
    }
    
    cargarTimbrado(){
        this._almacenesService.vertimbrado(this.token, this.idtimbrado, this.fecha_inicial, this.fecha_final).subscribe(
            response =>{
                this.timbrado=response.timbrado;
                /*
                this.timbrado.detalle.filter(reporte => reporte.fecha!=null).forEach(
                    reporte => (reporte.fecha = new Date(reporte.fecha.replace(/-/g, '\/')))
                );
                */
                this.reportexlsx={titulo:"Timbrado",cabecera:[
                    {'titulo':'Turno','tipo':'string','ancho':20},
                    {'titulo':'DÍA','tipo':'string','ancho':20},
                    {'titulo':'FECHA','tipo':'date','ancho':20},
                    {'titulo':'CÓDIGO PRODUCTO','tipo':'string','ancho':20},
                    {'titulo':'SKU','tipo':'string','ancho':20},
                    {'titulo':'NRO TCF','tipo':'string','ancho':20},
                    {'titulo':'Factura Timbrada','tipo':'string','ancho':20},
                    {'titulo':'Q PX Timbrado','tipo':'number','ancho':20},
                    {'titulo':'Q paquetes Timbrado','tipo':'number','ancho':20},
                    {'titulo':'Objetivo timbrado por turno','tipo':'number','ancho':20},
                    {'titulo':'Cumplimiento','tipo':'number','ancho':20},
                    {'titulo':'U/PX','tipo':'number','ancho':20},
                    {'titulo':'U/PQT','tipo':'number','ancho':20},
                    {'titulo':'TOTAL UNIDADES ','tipo':'number','ancho':20},
                    {'titulo':'PRECIO TIMBRADO Y ETIQUETADO X PAQUETE','tipo':'number','ancho':20},
                    {'titulo':'PRECIO  X TIMBRE','tipo':'number','ancho':20},
                    {'titulo':'METODO','tipo':'string','ancho':20},
                    {'titulo':'PRECIO DEL MÉTODO X TIMBRE','tipo':'number','ancho':20},
                    {'titulo':'PRECIO TOTAL POR TIMBRADO + ETIQUETADO + MÉTODO','tipo':'number','ancho':20},
                    {'titulo':'PRECIO (BOB)','tipo':'number','ancho':20},
                    {'titulo':'OBSERVACIONES','tipo':'string','ancho':20},
                    {'titulo':'PERSONAL','tipo':'number','ancho':20},
                    {'titulo':'LOTES DEL PERMISO DE SENASAG','tipo':'string','ancho':20},
                    {'titulo':'FALTANTE SENASAG','tipo':'number','ancho':20},
                    {'titulo':'LOTE SENASAG','tipo':'string','ancho':20},
                    {'titulo':'FALTANTE','tipo':'number','ancho':20},
                    {'titulo':'LOTE FALTANTE','tipo':'string','ancho':20},
                    {'titulo':'DAÑADOS','tipo':'number','ancho':20},
                    {'titulo':'LOTE DAÑADOS','tipo':'string','ancho':20},
                    {'titulo':'QUEBRADOS','tipo':'number','ancho':20},
                    {'titulo':'LOTE QUEBRADOS','tipo':'string','ancho':20},
                    {'titulo':'SIN TAPA','tipo':'number','ancho':20},
                    {'titulo':'LOTE SIN TAPA','tipo':'string','ancho':20},
                    {'titulo':'BORROSOS','tipo':'number','ancho':20},
                    {'titulo':'LOTE BORROSO','tipo':'string','ancho':20},
                    {'titulo':'FILM','tipo':'number','ancho':20},
                    {'titulo':'CINTA','tipo':'number','ancho':20},
                    {'titulo':'SILICONA','tipo':'number','ancho':20},
                    {'titulo':'HORA DE INICIO','tipo':'time','ancho':20},
                    {'titulo':'HORA DE FIN','tipo':'time','ancho':20},
                    {'titulo':'HORAS TRABAJADAS','tipo':'time','ancho':20},
                    {'titulo':'TIMBRES SOBRANTES','tipo':'number','ancho':20}
                ],
                data:[]};


                let data: Array<any>=[];

                for (let r = 0; r < this.timbrado.detalle.length; r++){
                    data.push([
                        {'valor': this.timbrado.detalle[r].timbradoturno},
                        {'valor': this.timbrado.detalle[r].diasemana},
                        {'valor': this.timbrado.detalle[r].fecha},
                        {'valor': this.timbrado.detalle[r].codigo_producto},
                        {'valor': this.timbrado.detalle[r].sku},
                        {'valor': this.timbrado.detalle[r].nro_tcf},
                        {'valor': this.timbrado.detalle[r].factura_timbrada},
                        {'valor': this.timbrado.detalle[r].cantidad_timbrado},
                        {'valor': this.timbrado.detalle[r].cantidad_paquetes_timbrado},
                        {'valor': this.timbrado.detalle[r].meta_timbrado},
                        {'valor': this.timbrado.detalle[r].cumplimiento},
                        {'valor': this.timbrado.detalle[r].umcompra},
                        {'valor': this.timbrado.detalle[r].umalterna},
                        {'valor': this.timbrado.detalle[r].total_unidades},
                        {'valor': this.timbrado.detalle[r].precio_timbrado},
                        {'valor': this.timbrado.detalle[r].precio_por_timbre},
                        {'valor': this.timbrado.detalle[r].metodo},
                        {'valor': this.timbrado.detalle[r].precio_metodo},
                        {'valor': this.timbrado.detalle[r].precio_total},
                        {'valor': this.timbrado.detalle[r].precio},
                        {'valor': this.timbrado.detalle[r].observaciones},
                        {'valor': this.timbrado.detalle[r].personal},
                        {'valor': this.timbrado.detalle[r].lotes_permiso_senasag},
                        {'valor': this.timbrado.detalle[r].faltantes_senasag},
                        {'valor': this.timbrado.detalle[r].lote_senasag},
                        {'valor': this.timbrado.detalle[r].faltante},
                        {'valor': this.timbrado.detalle[r].lote_faltante},
                        {'valor': this.timbrado.detalle[r].danados},
                        {'valor': this.timbrado.detalle[r].lote_danados},
                        {'valor': this.timbrado.detalle[r].quebrados},
                        {'valor': this.timbrado.detalle[r].lote_quebrados},
                        {'valor': this.timbrado.detalle[r].sin_tapa},
                        {'valor': this.timbrado.detalle[r].lote_sin_tapa},
                        {'valor': this.timbrado.detalle[r].borrosos},
                        {'valor': this.timbrado.detalle[r].lote_borrosos},
                        {'valor': this.timbrado.detalle[r].film},
                        {'valor': this.timbrado.detalle[r].cinta},
                        {'valor': this.timbrado.detalle[r].silicona},
                        {'valor': this.timbrado.detalle[r].hora_hinicio},
                        {'valor': this.timbrado.detalle[r].hora_fin},
                        {'valor': this.timbrado.detalle[r].horas_trabajadas},
                        {'valor': this.timbrado.detalle[r].timbres_sobrantes}
                    ]);
                }

                this.reportexlsx.data=data;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }
    
    prepararCarga(){
        this.myInputVariable.nativeElement.value = "";
        this.archivocargado = false;
    }
    
    cargarMasivamente(){
        $("#ventanaCargaMasiva").modal('hide');
        $('#ventanaLoading').modal('show');
        this.mensajes_error=[];
        this._almacenesService.timbradocargamasiva(this.token, this.idtimbrado, this.uploadFileInput).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');

                    this.myInputVariable.nativeElement.value = "";
                    this.archivocargado = false;
                    this.cargarTimbrado();
                    
                }else{
                    this.mensajes_error=response.mensajes_error;
                    this.toast_tipo="Error";
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');
                }

                

                    
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    eliminarDetalle(){
        this._almacenesService.eliminartimbradodetalle(this.token, this.idtimbradodetalleeliminar).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    $('#confirmarEliminarDetalle').modal('hide');
                    $("#liveToast").toast('show');
                    this.cargarTimbrado();
                    
                }else{
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
    }
    
    descargarExcelUpdate(){
        this._almacenesService.vertimbrado(this.token, this.idtimbrado, this.fecha_inicial_update, this.fecha_final_update).subscribe(
            response =>{
                let timbrado=response.timbrado.detalle;
                /*
                this.timbrado.detalle.filter(reporte => reporte.fecha!=null).forEach(
                    reporte => (reporte.fecha = new Date(reporte.fecha.replace(/-/g, '\/')))
                );
                */
                this.reportexlsx_update={titulo:"Actualizar Timbrado",cabecera:[
                    {'titulo':'Turno','tipo':'string','ancho':20},
                    {'titulo':'DÍA','tipo':'string','ancho':20},
                    {'titulo':'FECHA','tipo':'date','ancho':20},
                    {'titulo':'CÓDIGO PRODUCTO','tipo':'string','ancho':20},
                    {'titulo':'NRO TCF','tipo':'string','ancho':20},
                    {'titulo':'Factura Timbrada','tipo':'string','ancho':20},
                    {'titulo':'Q PX Timbrado','tipo':'number','ancho':20},
                    {'titulo':'METODO','tipo':'string','ancho':20},
                    {'titulo':'OBSERVACIONES','tipo':'string','ancho':20},
                    {'titulo':'PERSONAL','tipo':'number','ancho':20},
                    {'titulo':'LOTES DEL PERMISO DE SENASAG','tipo':'string','ancho':20},
                    {'titulo':'FALTANTE SENASAG','tipo':'number','ancho':20},
                    {'titulo':'LOTE SENASAG','tipo':'string','ancho':20},
                    {'titulo':'FALTANTE','tipo':'number','ancho':20},
                    {'titulo':'LOTE FALTANTE','tipo':'string','ancho':20},
                    {'titulo':'DAÑADOS','tipo':'number','ancho':20},
                    {'titulo':'LOTE DAÑADOS','tipo':'string','ancho':20},
                    {'titulo':'QUEBRADOS','tipo':'number','ancho':20},
                    {'titulo':'LOTE QUEBRADOS','tipo':'string','ancho':20},
                    {'titulo':'SIN TAPA','tipo':'number','ancho':20},
                    {'titulo':'LOTE SIN TAPA','tipo':'string','ancho':20},
                    {'titulo':'BORROSOS','tipo':'number','ancho':20},
                    {'titulo':'LOTE BORROSO','tipo':'string','ancho':20},
                    {'titulo':'FILM','tipo':'number','ancho':20},
                    {'titulo':'CINTA','tipo':'number','ancho':20},
                    {'titulo':'SILICONA','tipo':'number','ancho':20},
                    {'titulo':'HORA DE INICIO','tipo':'time','ancho':20},
                    {'titulo':'HORA DE FIN','tipo':'time','ancho':20},
                    {'titulo':'NO SE CUMPLIO POR','tipo':'string','ancho':20},
                    {'titulo':'LOTES VALIDADOS','tipo':'string','ancho':20},
                    {'titulo':'LOTES ADICIONALES','tipo':'string','ancho':20},
                    {'titulo':'TRANSPORTADORA','tipo':'string','ancho':20},
                    {'titulo':' TOTAL UNIDADES TIMBRADAS ','tipo':'string','ancho':20},
                    {'titulo':' TOTAL U FACTURA ','tipo':'string','ancho':20},
                    {'titulo':'CARPICOLA DE BALDE DE 20 L','tipo':'string','ancho':20},
                    {'titulo':'CLASIFICACIÓN','tipo':'string','ancho':20},
                    {'titulo':'ABOLLADAS','tipo':'string','ancho':20},
                    {'titulo':'LOTE ABOLLADAS','tipo':'string','ancho':20},
                    {'titulo':'MOJADAS','tipo':'string','ancho':20},
                    {'titulo':'LOTE MOJADAS','tipo':'string','ancho':20},
                    {'titulo':'MERMADAS','tipo':'string','ancho':20},
                    {'titulo':'LOTE MERMADAS','tipo':'string','ancho':20},
                    {'titulo':'TAPA EXTRA','tipo':'string','ancho':20},
                    {'titulo':'LOTE TAPA EXTRA','tipo':'string','ancho':20},
                    {'titulo':'LEGALES EXTRA O EN PORTUGUES','tipo':'string','ancho':20},
                    {'titulo':'LOTE LEGALES EXTRA','tipo':'string','ancho':20},
                    {'titulo':'CAJA EXTRA','tipo':'string','ancho':20},
                    {'titulo':'LOTE CAJA EXTRA','tipo':'string','ancho':20},
                    {'titulo':'SARRO LEVE','tipo':'string','ancho':20},
                    {'titulo':'LOTE SARRO LEVE','tipo':'string','ancho':20},
                    {'titulo':' SARRO SEVERO','tipo':'string','ancho':20},
                    {'titulo':'LOTE SARRO SEVERO','tipo':'string','ancho':20},
                    {'titulo':'ID','tipo':'string','ancho':20}
                ],
                data:[]};


                let data: Array<any>=[];

                for (let r = 0; r < timbrado.length; r++){
                    data.push([
                        {'valor': timbrado[r].timbradoturno},
                        {'valor': timbrado[r].diasemana},
                        {'valor': timbrado[r].fecha},
                        {'valor': timbrado[r].codigo_producto},
                        {'valor': timbrado[r].nro_tcf},
                        {'valor': timbrado[r].factura_timbrada},
                        {'valor': timbrado[r].cantidad_timbrado},
                        {'valor': timbrado[r].metodo},
                        {'valor': timbrado[r].observaciones},
                        {'valor': timbrado[r].personal},
                        {'valor': timbrado[r].lotes_permiso_senasag},
                        {'valor': timbrado[r].faltantes_senasag},
                        {'valor': timbrado[r].lote_senasag},
                        {'valor': timbrado[r].faltante},
                        {'valor': timbrado[r].lote_faltante},
                        {'valor': timbrado[r].danados},
                        {'valor': timbrado[r].lote_danados},
                        {'valor': timbrado[r].quebrados},
                        {'valor': timbrado[r].lote_quebrados},
                        {'valor': timbrado[r].sin_tapa},
                        {'valor': timbrado[r].lote_sin_tapa},
                        {'valor': timbrado[r].borrosos},
                        {'valor': timbrado[r].lote_borrosos},
                        {'valor': timbrado[r].film},
                        {'valor': timbrado[r].cinta},
                        {'valor': timbrado[r].silicona},
                        {'valor': timbrado[r].hora_hinicio},
                        {'valor': timbrado[r].hora_fin},
                        {'valor': timbrado[r].no_se_cumplio_por},
                        {'valor': timbrado[r].lotes_validos},
                        {'valor': timbrado[r].lotes_adicionales},
                        {'valor': timbrado[r].transportadora},
                        {'valor': timbrado[r].total_unidades_timbradas},
                        {'valor': timbrado[r].total_u_factura},
                        {'valor': timbrado[r].carpicola_de_balde_20},
                        {'valor': timbrado[r].clasificacion},
                        {'valor': timbrado[r].abolladas},
                        {'valor': timbrado[r].lote_abolladas},
                        {'valor': timbrado[r].mojadas},
                        {'valor': timbrado[r].lote_mojadas},
                        {'valor': timbrado[r].mermadas},
                        {'valor': timbrado[r].lote_mermadas},
                        {'valor': timbrado[r].tapa_extra},
                        {'valor': timbrado[r].lote_tapa_extra},
                        {'valor': timbrado[r].legales_extra_portugues},
                        {'valor': timbrado[r].lote_legales_extra},
                        {'valor': timbrado[r].caja_extra},
                        {'valor': timbrado[r].lote_caja_extra},
                        {'valor': timbrado[r].sarro_leve},
                        {'valor': timbrado[r].lote_sarro_leve},
                        {'valor': timbrado[r].sarro_severo},
                        {'valor': timbrado[r].lote_sarro_severo},
                        {'valor': timbrado[r].idtimbradodetalle}
                    ]);
                }

                this.reportexlsx_update.data=data;
                
                this._exportexcelService.exportExcel(this.reportexlsx_update);
                
                
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    fileChangeEventUpdate(fileInput: any) {
        this.errorarchivoUpdate=false;
        if(fileInput.target.files){
            this.uploadFileInputUpdate=<Array<File>>fileInput.target.files;
            this.archivocargadoUpdate=true;
        }else {

        }
    }
    
    prepararCargaUpdate(){
        this.myInputVariableUpdate.nativeElement.value = "";
        this.archivocargadoUpdate = false;
    }
    
    actualizarMasivamente(){
        $("#ventanaActualizacionMasiva").modal('hide');
        $('#ventanaLoading').modal('show');
        this.mensajes_error=[];
        this._almacenesService.timbradoactualizacionmasiva(this.token, this.idtimbrado, this.uploadFileInputUpdate).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');

                    this.myInputVariableUpdate.nativeElement.value = "";
                    this.archivocargadoUpdate = false;
                    this.cargarTimbrado();
                    
                }else{
                    this.mensajes_error=response.mensajes_error;
                    this.toast_tipo="Error";
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');
                }

                

                    
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

}
