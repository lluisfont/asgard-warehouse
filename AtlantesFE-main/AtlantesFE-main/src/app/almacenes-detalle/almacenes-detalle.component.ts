import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {AlmacenModel} from '../models/almacen.model';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import {Router, ActivatedRoute, Params} from '@angular/router';

@Component({
  selector: 'app-almacenes-detalle',
  templateUrl: './almacenes-detalle.component.html',
  styleUrls: ['./almacenes-detalle.component.css'],
  providers:[UsuarioService,AlmacenesService,ExportExcelService]
})
export class AlmacenesDetalleComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public idalmacen: number;
    public almacen: AlmacenModel;
    public tipoalmacendetalle: Array<any>;
    //public filas
    public reportexlsx: ExcelModel;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_almacenes: boolean=false;
    public editar_almacenes: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService,
        private _route: ActivatedRoute
    ) { 
        this._route.params.forEach((params: Params)=>{
            this.idalmacen = params["idalmacen"];
        });
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_almacenes=true;
            this.editar_almacenes=true;
        }else{
            let indiceVerAlmacenes = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 8);
            if (indiceVerAlmacenes>=0){
                if (this.tokenDetalle.permisos[indiceVerAlmacenes].lectura){
                    this.ver_almacenes=true;
                }
                if (this.tokenDetalle.permisos[indiceVerAlmacenes].escritura){
                    this.editar_almacenes=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._almacenesService.vertipoalmacendetalle(this.token).subscribe(
            responsetipoalmacen =>{
                
                this.tipoalmacendetalle=responsetipoalmacen.tipoalmacenesdetalle;
                for(let ta=0;ta<this.tipoalmacendetalle.length;ta++){
                    this.tipoalmacendetalle[ta].capacidad=0;
                    this.tipoalmacendetalle[ta].utilizado=0;
                }
                
                this._almacenesService.veralmacen(this.token, this.idalmacen, this._usuarioService.getCurrentDateFilterValue()).subscribe(
                    response =>{
                        this.almacen=response.almacen;
                        let cabecera=[];
                        for (let dd = 0; dd < this.almacen.detalle[0].length; dd++){
                            cabecera.push(
                                {'titulo':'','tipo':'string','ancho':4}
                            );
                        }

                        this.reportexlsx={titulo:"Layout Almacen",
                            cabecera: cabecera,
                            data:[]};

                        let data: Array<any>=[];
                        for (let dd = 0; dd < this.almacen.detalle.length; dd++){
                            let datainicial=[];
                            for (let ii = 0; ii < this.almacen.detalle[dd].length; ii++){
                                let indicetipo = this.tipoalmacendetalle.findIndex(x => x.idtipoalmacendetalle == this.almacen.detalle[dd][ii].idtipoalmacendetalle);
                                if(indicetipo>=0){
                                    this.tipoalmacendetalle[indicetipo].capacidad++;
                                    if(this.almacen.detalle[dd][ii].items.length>0){
                                        this.tipoalmacendetalle[indicetipo].utilizado++;
                                    }
                                }
                                
                                
                                
                                let borde='none';
                                if(this.almacen.detalle[dd][ii].tipo==1){
                                    borde='';
                                }
                                datainicial.push(
                                    {'valor': this.almacen.detalle[dd][ii].texto+this.almacen.detalle[dd][ii].columna, 'borde': borde, 'color': this.almacen.detalle[dd][ii].color}
                                );
                            }
                            data.push(datainicial);
                        }

                        this.reportexlsx.data=data;

                        console.log(this.tipoalmacendetalle);
                        console.log(this.almacen);
                        //console.log(this.reportexlsx);
                        //console.log(this.almacen.direccion);
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
    }

}
