import { Component } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";

@Component({
    selector: 'app-reporte-ate-gas-ingresos',
    templateUrl: './reporte-ate-gas-ingresos.component.html',
    styleUrl: './reporte-ate-gas-ingresos.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class ReporteAteGasIngresosComponent {
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

    public columnas: Array<{field: string; header: string; tipo: string}> = [];
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;

    public ver_reporte_ate_gas_ingresos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_reporte_ate_gas_ingresos=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 106);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_ate_gas_ingresos=true;
                }
            }
        }

        this.idcliente=null;
        this.fechainicial = this._usuarioService.getCurrentDateFilterValue();
        this.fechafinal = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{

                this.entidades = response.entidades.filter(function (el) {
                    return el.idtipoentidad==1;
                });
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
            this._almacenesService.reporteategasingresos(this.token, this.idcliente, this.fechainicial, this.fechafinal).subscribe(
                response => {
                    this.generado=false;
                    this.reporte=this.normalizarReporte(this.resolverDataReporte(response, 'reporte_ingresos'));
                    this.columnas=this.generarColumnas(this.reporte);
                    this.reportexlsx=this.generarExcel("Reporte de Ingresos");
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

    private resolverDataReporte(response: any, propiedadEsperada: string): Array<any> {
        if (response && Array.isArray(response[propiedadEsperada])) {
            return response[propiedadEsperada];
        }

        if (Array.isArray(response)) {
            return response;
        }

        if (response && typeof response === 'object') {
            const key = Object.keys(response).find(k => Array.isArray(response[k]));
            return key ? response[key] : [];
        }

        return [];
    }

    private generarColumnas(reporte: Array<any>): Array<{field: string; header: string; tipo: string}> {
        if (!reporte || reporte.length === 0) {
            return [];
        }

        return Object.keys(reporte[0]).map(key => ({
            field: key,
            header: this.tituloColumna(key),
            tipo: this.esCampoFecha(key) ? 'date' : 'text'
        }));
    }

    private normalizarReporte(reporte: Array<any>): Array<any> {
        return reporte.map(item => {
            let fila = {...item};
            Object.keys(fila).forEach(key => {
                if (this.esCampoFecha(key) && this.esFechaParseable(fila[key])) {
                    fila[key] = this.convertirFecha(fila[key]);
                }
            });
            return fila;
        });
    }

    private generarExcel(titulo: string): ExcelModel {
        return {
            titulo: titulo,
            cabecera: this.columnas.map(col => ({
                titulo: col.header,
                tipo: col.tipo === 'date' ? 'datetime' : 'string',
                ancho: 20
            })),
            data: this.reporte.map(row => this.columnas.map(col => ({
                valor: row[col.field]
            })))
        };
    }

    private tituloColumna(field: string): string {
        return field
            .replace(/_/g, ' ')
            .replace(/\w\S*/g, texto => texto.charAt(0).toUpperCase() + texto.substring(1).toLowerCase());
    }

    private esCampoFecha(field: string): boolean {
        const campo = field.toLowerCase();
        return campo.includes('fecha') ||
            campo.includes('created_at') ||
            campo.includes('updated_at') ||
            campo === 'inicio' ||
            campo === 'fin';
    }

    private esFechaParseable(valor: any): boolean {
        if (!valor) {
            return false;
        }

        if (valor instanceof Date) {
            return !isNaN(valor.getTime());
        }

        if (typeof valor !== 'string' && typeof valor !== 'number') {
            return false;
        }

        return !isNaN(new Date(String(valor).replace(/-/g, '/')).getTime());
    }

    private convertirFecha(valor: any): Date {
        if (valor instanceof Date) {
            return valor;
        }

        return new Date(String(valor).replace(/-/g, '/'));
    }
}
