import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import { FilterService } from 'primeng/api';

@Component({
    selector: 'app-reporte-ate-gas-demanda',
    templateUrl: './reporte-ate-gas-demanda.component.html',
    styleUrl: './reporte-ate-gas-demanda.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAteGasDemandaComponent {
    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;

    public idcliente: number;
    public error_idcliente: boolean=false;
    public fechainicial: string;
    public fechafinal: string;
    public error_fechainicial: boolean=false;
    public error_fechafinal: boolean=false;

    public generado: boolean=false;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_ate_gas_demanda: boolean=false;

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
            this.ver_reporte_ate_gas_demanda=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 105);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_ate_gas_demanda=true;
                }
            }
        }
        
        this.idcliente=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
        /*
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
        */
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
            this._almacenesService.reporteategasdemanda(this.token, this.idcliente, this.fechainicial, this.fechafinal).subscribe(
                response => {
                    this.generado=false;
                    this.reporte=response.reporte_demanda;

                    /*
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
                    */

                    
                    
                    this.reportexlsx={titulo:"Reporte Demanda",cabecera:[
                        {'titulo':'Cliente','tipo':'string','ancho':20},
                        {'titulo':'Chasis','tipo':'string','ancho':20},
                        {'titulo':'Marca','tipo':'string','ancho':20},
                        {'titulo':'Modelo','tipo':'string','ancho':20},
                        {'titulo':'Configuracion','tipo':'string','ancho':20},
                        {'titulo':'Tipo Tanque','tipo':'string','ancho':20},
                        {'titulo':'Sede','tipo':'string','ancho':20},
                        {'titulo':'fecha de Registro','tipo':'datetime','ancho':20},
                        {'titulo':'fecha asignación','tipo':'datetime','ancho':20},
                        {'titulo':'fecha de recepcion','tipo':'datetime','ancho':20},
                        {'titulo':'Etapa Actual','tipo':'string','ancho':20},
                        {'titulo':'Fecha OT','tipo':'date','ancho':20},
                        
                    ],
                    data:[]};

                    let data: Array<any>=[];
                    for (let r = 0; r<this.reporte.length; r++){
                        data.push([
                            {'valor': this.reporte[r].cliente},
                            {'valor': this.reporte[r].chasis},
                            {'valor': this.reporte[r].marca},
                            {'valor': this.reporte[r].modelo},
                            {'valor': this.reporte[r].configuracion},
                            {'valor': this.reporte[r].tipo_tanque},
                            {'valor': this.reporte[r].sede},
                            {'valor': this.reporte[r].created_at},
                            {'valor': this.reporte[r].fecha_creacion_etapa},
                            {'valor': this.reporte[r].fecha_recepcion},
                            {'valor': this.reporte[r].etapa},
                            {'valor': this.reporte[r].fecha_ot},
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
