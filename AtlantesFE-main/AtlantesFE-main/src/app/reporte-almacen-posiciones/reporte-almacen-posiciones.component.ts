import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';
@Component({
    selector: 'app-reporte-almacen-posiciones',
    templateUrl: './reporte-almacen-posiciones.component.html',
    styleUrls: ['./reporte-almacen-posiciones.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService,ExportExcelService]
})
export class ReporteAlmacenPosicionesComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    //public entidades: Array<any>;

    //public idcliente: string;
    //public fechacorte: string;
    public generado: boolean=false;

    public almacen: Array<any>=[];
    public reporte: Array<any>=[];
    public reportexlsx: ExcelModel;
    
    public ver_reporte_almacen_posiciones: boolean=false;

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
            this.ver_reporte_almacen_posiciones=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 57);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_reporte_almacen_posiciones=true;
                }
            }
        }
        //this.idcliente=null;
        //this.fechacorte = this._usuarioService.getCurrentDateFilterValue();
    }

    ngOnInit(): void {
        this.generarReporte();
    }

    generarReporte(){
      let idalmacen=this.tokenDetalle.idalmacen;
      this._almacenesService.veralmacen(this.token, idalmacen, this._usuarioService.getCurrentDateFilterValue()).subscribe(
            response =>{
                this.almacen=response.almacen.detalle;
                //console.log(this.almacen);
              //this.reportexlsx=[];
                this.reportexlsx={titulo:"Reporte de Posiciones",cabecera:[
                    {'titulo':'Cliente','tipo':'string'},
                    {'titulo':'Ingreso','tipo':'string'},
                    {'titulo':'Fecha Ingreso','tipo':'string'},
                    {'titulo':'Ubicacion','tipo':'string'},
                    {'titulo':'Codigo','tipo':'string'},
                    {'titulo':'Descripcion','tipo':'string'},
                    {'titulo':'Categoria','tipo':'string'},
                    {'titulo':'Centro Distribucion','tipo':'string'},
                    {'titulo':'Saldo','tipo':'number'},
                    {'titulo':'Embalaje','tipo':'string'},
                    {'titulo':'Lote','tipo':'string'}
                ],
                data:[]};
                //this.reportexlsx.cabecera=;
                let data: Array<any>=[];
                for(let aa=0;aa<this.almacen.length;aa++){
                    for(let bb=0;bb<this.almacen[aa].length;bb++){
                        //console.log(this.almacen[aa][bb].tipo);
                        if(this.almacen[aa][bb].tipo==1){
                            let total=0;
                            //console.log(this.almacen[aa][bb].items);
                            let items = this.almacen[aa][bb].items.filter(function (el) {
                                return el.cantidad>=0.01;
                            });
                            //console.log(items);

                            for(let ii=0;ii<items.length;ii++){
                                total=total+items[ii].cantidad;
                            }
                            let cliente='';
                            let numeroingreso='';
                            let fechaingreso='';
                            let codigo='';
                            let categoria='';
                            let centro_distribucion='';
                            let descripcion='';
                            let codigoembalaje='';
                            let lote='';

                            if(total>=0.01){
                                const clientes = items.map(item => item.cliente);
                                if(this.array_unique(clientes).length==1){
                                    cliente=items[0].cliente;
                                }else{
                                    cliente=this.array_unique(clientes).join(", ");
                                    //cliente="VARIOS";
                                }

                                const ingresos = items.map(item => item.numeroingreso);
                                if(this.array_unique(ingresos).length==1){
                                    numeroingreso=items[0].numeroingreso;
                                    fechaingreso=items[0].fechaingreso;
                                }else{
                                    numeroingreso="VARIOS";
                                    fechaingreso="VARIOS";
                                }

                                const codigos = items.map(item => item.codigo);
                                if(this.array_unique(codigos).length==1){
                                    codigo=items[0].codigo;
                                    categoria=items[0].categoria;
                                    centro_distribucion=items[0].centro_distribucion;
                                    descripcion=items[0].descripcion;
                                    codigoembalaje=items[0].codigoembalaje;
                                    lote=items[0].lote;
                                }else{
                                    codigo="VARIOS";
                                    categoria="VARIOS";
                                    centro_distribucion="VARIOS";
                                    descripcion="VARIOS";
                                    codigoembalaje="VARIOS";
                                    lote="VARIOS";
                                }
                                //console.log(this.array_unique(colores));
                            }else{

                            }

                            this.reporte.push({
                                'cliente': cliente,
                                'color': this.almacen[aa][bb].color,
                                'ubicacion': this.almacen[aa][bb].nombre,
                                'numeroingreso': numeroingreso,
                                'fechaingreso': fechaingreso,
                                'categoria': categoria,
                                'centro_distribucion': centro_distribucion,
                                'codigo': codigo,
                                'descripcion': descripcion,
                                'saldo': total,
                                'codigoembalaje': codigoembalaje,
                                'lote': lote
                            });

                            data.push(
                                [{'valor': cliente},
                                //{'valor': this.almacen[aa][bb].color},
                                {'valor': numeroingreso},
                                {'valor': fechaingreso},
                                {'valor': this.almacen[aa][bb].nombre, color: this.almacen[aa][bb].color},
                                {'valor': codigo},
                                {'valor': descripcion},
                                {'valor': categoria},
                                {'valor': centro_distribucion},
                                {'valor': total},
                                {'valor': codigoembalaje},
                                {'valor': lote}]
                                );

                            //console.log("entra");
                        }
                    }

                }
                this.reportexlsx.data=data;
                //console.log(this.reportexlsx);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx)
    }

    array_unique(arr) {
        return [...new Set(arr)];
    }

}
