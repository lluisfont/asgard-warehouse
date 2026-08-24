import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
//import { SelectEvent } from 'primeng/api';
import * as FileSaver from 'file-saver';

@Component({
  selector: 'app-reporte-almacen-posiciones-dia',
  templateUrl: './reporte-almacen-posiciones-dia.component.html',
  styleUrl: './reporte-almacen-posiciones-dia.component.css',
  providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService,EntidadesService]
})
export class ReporteAlmacenPosicionesDiaComponent {
    public token: string;
    public tokenDetalle: any;

    public entidades: Array<any>;

    public idcliente: number;
    public error_idcliente: boolean=false;
    public rangeDates: Date[] | undefined;
    public fechainicial: string;
    public fechafinal: string;
    public fechainicial_min: string;
    public fechafinal_max: string;
    public generado: boolean=false;
    
    public columnas: Array<any>;

    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_posicion_por_dia: boolean=false;

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
            this.ver_reporte_almacen_posicion_por_dia=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 64);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_posicion_por_dia=true;
                }
            }
        }
        this.idcliente=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
        this.rangeDates = [new Date(),new Date()];
    }

    ngOnInit(): void {
        this.obtenerLimitesFechas();
        this._entidadService.vercliente(this.token).subscribe(
            response =>{

                this.entidades = response.clientes;

                //console.log(this.entidades);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    obtenerLimitesFechas(){
        const fechainicial_min: Date = new Date(this.fechafinal+" 09:00:00");
        fechainicial_min.setMonth(fechainicial_min.getMonth() - 1);
        this.fechainicial_min=formatDate(fechainicial_min, 'yyyy-MM-dd', 'en');
        
        //console.log(fechainicial_min);
        
        console.log(this.fechainicial);
        
        
        const fechafinal_max: Date = new Date(this.fechainicial+" 09:00:00");
        console.log(fechafinal_max);
        fechafinal_max.setMonth(fechafinal_max.getMonth() + 1);
        console.log(fechafinal_max);
        this.fechafinal_max=formatDate(fechafinal_max, 'yyyy-MM-dd', 'en');
        
        //console.log(fechafinal_max);
        
        
    }
    
    onSelect(event: any) {
        if (this.rangeDates && this.rangeDates[0]!=null && this.rangeDates[1]!=null) {
            const startDate = this.rangeDates[0];
            const endDate = this.rangeDates[1];
            const differenceInTime = endDate.getTime() - startDate.getTime();
            const differenceInDays = differenceInTime / (1000 * 3600 * 24);
            const maxRangeDays = 30; // Máximo de días permitidos

            if (differenceInDays > maxRangeDays) {
                // Si el rango seleccionado excede el límite, ajustar la fecha de finalización
                const adjustedEndDate = new Date(startDate);
                adjustedEndDate.setDate(adjustedEndDate.getDate() + maxRangeDays);
                this.rangeDates = [startDate, adjustedEndDate];

              // Aquí podrías mostrar un mensaje al usuario indicando que el rango ha sido ajustado
              console.log('El rango de selección se ha ajustado a un máximo de un mes.');
            }
        }
      }

    generarReporte(){
        this.error_idcliente=false;
        if (this.idcliente==null){
            this.error_idcliente=true;
        }
        if (!this.error_idcliente){
            this.generado=true;
            let fechainicial=formatDate(this.rangeDates[0], 'yyyy-MM-dd', 'en');
            let fechafinal=formatDate(this.rangeDates[1], 'yyyy-MM-dd', 'en');
            this.columnas=[
                { field: 'fecha', header: 'Fecha', type: 'date' },
                { field: 'categoriaalmacendetalle', header: 'Tipo', type: 'text' }
            ];
            this.reporte=[];
            console.log(this.idcliente);
            this._almacenesService.reporteposicionesdia(this.token, this.idcliente, fechainicial, fechafinal).subscribe(
                response =>{
                    console.log(response);
                    let data: Array<any>=[];
                    let cabecera=[
                        {'titulo':'Fecha','tipo':'string'},
                        {'titulo':'Tipo','tipo':'string'}
                    ];
                    for(let cc=0; cc<response.columnas.length;cc++){
                        cabecera.push({
                            'titulo': response.columnas[cc].header,
                            'tipo': 'number'
                        });
                        this.columnas.push({
                            field: response.columnas[cc].field,
                            header: response.columnas[cc].header,
                            type: 'numeric'
                        });
                    }
                    
                    this.reportexlsx={
                        titulo:"Reporte de Posiciones por Dia",
                        cabecera:cabecera,
                        data:[]
                    };
                    
                    this.reporte=response.reporte;
                    for(let rr=0;rr<response.reporte.length;rr++){
                        let campos_columna=[{valor: response.reporte[rr]['fecha']},{valor: response.reporte[rr]['categoriaalmacendetalle']}];
                        //let campos_reporte={fecha: response.reporte[rr]['fecha'], categoriaalmacendetalle: response.reporte[rr]['categoriaalmacendetalle']};
                        for(let cc=0; cc<response.columnas.length;cc++){
                            campos_columna.push({
                                valor: response.reporte[rr][response.columnas[cc]['field']]
                            });
                        }
                        data.push(campos_columna);
                    }
                    this.reportexlsx.data=data;
                    
                    console.log(this.reportexlsx);
                    this.generado=false;
                    
                },
                error=>{
                    console.log(<any>error)
                    this.generado=false;
                }
            );
        }
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
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
