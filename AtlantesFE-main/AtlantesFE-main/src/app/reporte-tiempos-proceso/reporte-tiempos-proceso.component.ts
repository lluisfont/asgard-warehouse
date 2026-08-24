import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import { FilterService } from 'primeng/api';
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-reporte-tiempos-proceso',
    templateUrl: './reporte-tiempos-proceso.component.html',
    styleUrl: './reporte-tiempos-proceso.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteTiemposProcesoComponent {
    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;
    public etapas: Array<any>;
    public tecnicos: Array<any>;

    public idcliente: number;
    public error_idcliente: boolean=false;
    public tipo_filtro: number=1;
    public fechainicial: string;
    public fechafinal: string;
    public error_fechainicial: boolean=false;
    public error_fechafinal: boolean=false;
    public idetapa: number;
    public marca: string='';
    public tecnico: string;
    public ubicacion: string='';
    public sede: string='';

    public generado: boolean=false;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_tiempos_proceso: boolean=false;

    filtroHora: { exact?: string; desde?: string; hasta?: string } = {};

    modoSeleccionado: 'exacto' | 'rango' = 'exacto';

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService,
        private _filterService: FilterService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_tiempos_proceso=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 103);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_tiempos_proceso=true;
                }
            }
        }
        this.idcliente=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
        this._filterService.register('custom', (value: Date, filter: any) => {
          if (!filter) return true;
          if (!value) return false;

          const hora = value.toTimeString().substring(0,5); // HH:mm

          // exacto
          if (filter.exact) {
            return hora === filter.exact;
          }

          // rango
          if (filter.desde && filter.hasta) {
            return hora >= filter.desde && hora <= filter.hasta;
          }

          // solo desde
          if (filter.desde) {
            return hora >= filter.desde;
          }

          // solo hasta
          if (filter.hasta) {
            return hora <= filter.hasta;
          }

          return true;
        });
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{

                this.entidades = response.entidades.filter(function (el) {
                    return el.idtipoentidad==1;
                });

                //console.log(response.entidades);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.etapas(this.token).subscribe(
            response =>{

                this.etapas = response.etapas;

                //console.log(response.entidades);
                console.log(this.etapas);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._almacenesService.verasignaciontrabajotecnicos(this.token).subscribe(
            response =>{

                this.tecnicos = response.tecnicos;

                //console.log(response.entidades);
                console.log(this.tecnicos);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    onModoChange(event: Event) {
      const value = (event.target as HTMLSelectElement).value as 'exacto' | 'rango';
      this.modoSeleccionado = value;
    }

    onExactTimeChange(value: string, filterCallback: Function) {
      this.filtroHora = { exact: value };
      filterCallback(this.filtroHora);
    }

    onRangeTimeChange(desde: string, hasta: string, filterCallback: Function) {
      this.filtroHora = { desde, hasta };
      filterCallback(this.filtroHora);
    }

    generarReporte(){
        let error:boolean=false;
        if(!this.idcliente){
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
            this.generado=true;
            this._almacenesService.reportetiemposproceso(this.token, this.idcliente, this.tipo_filtro, this.fechainicial, this.fechafinal).subscribe(
                response => {
                    this.generado=false;
                    this.reporte=response.reporte_tiempos_proceso;

                    //const camposFecha = ['created_at', 'fecha_recepcion', 'inicio', 'fin'];

                    const camposFechaHora = ['created_at', 'fecha_recepcion', 'inicio', 'fin'];

                    this.reporte.forEach(rep => {
                      camposFechaHora.forEach(campo => {
                        const valor = rep[campo];

                        if (valor && typeof valor === 'string') {
                          const fechaObj = new Date(valor.replace(/-/g, '/'));
                          rep[campo] = fechaObj;

                          // fecha
                          rep[`${campo}_fecha`] = new Date(
                            fechaObj.getFullYear(),
                            fechaObj.getMonth(),
                            fechaObj.getDate()
                          );

                          // hora
                          const partes = valor.split(' ');
                          if (partes.length > 1) {
                            const [h, m, s] = partes[1].split(':').map(Number);
                            rep[`${campo}_hora`] = new Date(0, 0, 0, h, m, s);
                            rep[`${campo}_hora_string`] = valor.split(' ')[1]; // "11:36:01"
                          } else {
                            rep[`${campo}_hora`] = null;
                            rep[`${campo}_hora_string`] = null;
                          }

                        } else {
                          rep[campo] = null;
                          rep[`${campo}_fecha`] = null;
                          rep[`${campo}_hora`] = null;
                          rep[`${campo}_hora_string`] = null;
                        }
                      });
                    });
                    

                    /*
                    this.reporte.forEach(o => {
                        const partes = [o.codigo_almacen, o.almacen]
                          .filter(v => v != null && String(v).trim() !== "");
                        o.codigo_y_almacen = partes.join(" ") || "";
                    });
                    */
                    
                    this.reportexlsx={titulo:"Tiempos de Proceso",cabecera:[
                        {'titulo':'fecha de Registro','tipo':'datetime','ancho':20},
                        {'titulo':'fecha de Registro VIN','tipo':'date','ancho':20},
                        {'titulo':'Hora de Registro VIN','tipo':'string','ancho':20},
                        {'titulo':'fecha de recepcion','tipo':'datetime','ancho':20},
                        {'titulo':'fecha de recepcion VIN','tipo':'date','ancho':20},
                        {'titulo':'Hora de recepcion VIN','tipo':'string','ancho':20},
                        {'titulo':'Sede','tipo':'string','ancho':20},
                        {'titulo':'Chasis','tipo':'string','ancho':20},
                        {'titulo':'Marca','tipo':'string','ancho':20},
                        {'titulo':'Modelo','tipo':'string','ancho':20},
                        {'titulo':'Color','tipo':'string','ancho':20},
                        {'titulo':'Configuracion','tipo':'string','ancho':20},
                        {'titulo':'Tipo Tanque','tipo':'string','ancho':20},
                        {'titulo':'Tecnico','tipo':'string','ancho':20},
                        {'titulo':'Proceso','tipo':'string','ancho':20},
                        {'titulo':'Ubicación (ultima)','tipo':'string','ancho':20},
                        {'titulo':'Fecha Inicio','tipo':'datetime','ancho':20},
                        {'titulo':'Fecha Fin','tipo':'datetime','ancho':20},
                        {'titulo':'Tiempo Total (en segundos)','tipo':'string','ancho':20},
                        {'titulo':'Tiemo Total Horas / min','tipo':'string','ancho':20},
                        {'titulo':'Tiempo en Pausa (en segundos)','tipo':'string','ancho':20},
                        {'titulo':'Tiemo en Pausa Horas / min','tipo':'string','ancho':20},
                        
                    ],
                    data:[]};

                    let data: Array<any>=[];
                    for (let r = 0; r<this.reporte.length; r++){
                        data.push([
                            {'valor': this.reporte[r].created_at},
                            {'valor': this.reporte[r].created_at_fecha},
                            {'valor': this.reporte[r].created_at_hora_string},
                            {'valor': this.reporte[r].fecha_recepcion},
                            {'valor': this.reporte[r].fecha_recepcion_fecha},
                            {'valor': this.reporte[r].fecha_recepcion_hora_string},
                            {'valor': this.reporte[r].sede},
                            {'valor': this.reporte[r].chasis},
                            {'valor': this.reporte[r].marca},
                            {'valor': this.reporte[r].modelo},
                            {'valor': this.reporte[r].color},
                            {'valor': this.reporte[r].configuracion},
                            {'valor': this.reporte[r].tipo_tanque},
                            {'valor': this.reporte[r].tecnico},
                            {'valor': this.reporte[r].etapa},
                            {'valor': this.reporte[r].ubicacion},
                            {'valor': this.reporte[r].inicio},
                            {'valor': this.reporte[r].fin},
                            {'valor': this.reporte[r].tiempo_total},
                            {'valor': this.reporte[r].tiempo_total_formato},
                            {'valor': this.reporte[r].tiempo_en_pausa},
                            {'valor': this.reporte[r].tiempo_en_pausa_formato},
                        ]);
                    }


                    this.reportexlsx.data=data;
                    
                    
                    console.log(this.reporte);
                    
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
