import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import * as FileSaver from 'file-saver';

@Component({
    selector: 'app-dashboard-monitoreo-centros',
    templateUrl: './dashboard-monitoreo-centros.component.html',
    styleUrl: './dashboard-monitoreo-centros.component.css',
    providers:[UsuarioService,EntidadesService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class DashboardMonitoreoCentrosComponent {
    public token: string;
    public tokenDetalle: any;
    
    public ver_dashboard_monitoreo_centros: boolean=false;
    
    public entidades: Array<any>;
    public ciudades: Array<any>;
    public almacenes: Array<any>;
    public almacenes_mostrar: Array<any>;
    
    public idcliente: number=null;
    public error_idcliente: boolean=false;
    public idinventariofisico: number=null;
    public error_idinventariofisico: boolean=false;
    public fechainicial: string;
    public fechafinal: string;
    public error_fechainicial: boolean=false;
    public error_fechafinal: boolean=false;
    public idciudad: Array<any>=[];
    public idalmacen: Array<any>=[];
    public generado: boolean=false;
    
    public cuadros: Array<any>=[];
    public inventariofisico_detalle: Array<any>=[];
    public detalle: Array<any>=[];
    
    public detalle_visible: boolean=false;
    public detalle_cabecera: string='';
    public reportexlsx: ExcelModel;
    
    public tiposFiltro: any[] = [{ label: 'Por Inventario', value: 1 },{ label: 'Por Fechas', value: 2 }];
    public tipo_filtro=1;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _entidadService: EntidadesService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService,
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_dashboard_monitoreo_centros=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 86);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_dashboard_monitoreo_centros=true;
                }
            }
        }
    
    }
    
    ngOnInit(): void {
        this.entidades=[];
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                
                this.entidades = response.clientes;
                
                //console.log(response.entidades);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.ciudades=[];
        this._datomaestroService.ciudades(this.token).subscribe(
            response_ciudades =>{
                this.ciudades=response_ciudades.ciudades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.almacenes=[];
        this._almacenesService.veralmacenes(this.token).subscribe(
            response =>{
                this.almacenes=response.almacenes;
                this.almacenes.forEach(o => {
                        const partes = [o.codigo_almacen, o.almacen]
                          .filter(v => v != null && String(v).trim() !== "");
                        o.codigo_y_almacen = partes.join(" ") || "";
                });
                this.setAlmacenes();
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.resetCuadro();
    }
    
    setAlmacenes(){
        this.idalmacen=[];
        if (this.idciudad.length==0){
            this.almacenes_mostrar = this.almacenes;
        }else{
            this.almacenes_mostrar = this.almacenes.filter(almacen => this.idciudad.includes(almacen.idciudad));
        }
        
        
    }
    
    resetCuadro(){
        this.cuadros=[
            {
                id: 1,
                nombre: 'Encontrados',
                icono: 'bi-check2-circle',
                cantidad: 0,
                colores: ['bg-success','text-white'],
            },
            {
                id: 4,
                nombre: 'Pendientes',
                icono: 'bi-plus-circle-dotted',
                cantidad: 0,
                colores: ['bg-warning','text-dark'],
            },
            {
                id: 2,
                nombre: 'Sobrantes',
                icono: 'bi-plus-circle',
                cantidad: 0,
                colores: ['bg-info','text-dark'],
            },
            {
                id: 5,
                nombre: 'Reportados',
                icono: 'bi-eye',
                cantidad: 0,
                colores: ['bg-secondary','text-white'],
            },
            {
                id: 3,
                nombre: 'Faltantes',
                icono: 'bi-x-circle',
                cantidad: 0,
                colores: ['bg-danger','text-white'],
            },
            {
                id: 6,
                nombre: 'Total Vehiculos Cargados',
                icono: 'bi-car-front',
                cantidad: 0,
                colores: ['bg-primary','text-white'],
            },
        ];
    }
    
    generarDashBoard(){
        let error=false;
        if (!this.idcliente){
            error=true;
            this.error_idcliente=true;
        }
        if (this.tipo_filtro == 1 && !this.idinventariofisico){
            error=true;
            this.error_idinventariofisico=true;
        }
        if (this.tipo_filtro == 2 && !this.fechainicial){
            error=true;
            this.error_fechainicial=true;
        }
        if (this.tipo_filtro == 2 && !this.fechafinal){
            error=true;
            this.error_fechafinal=true;
        }
        
        if(!error){
            this.resetCuadro();
            
            let request$;

            if(this.tipo_filtro == 1){
              
            }else{
                this.idinventariofisico=null;
            }
            
            let payload={
                ciudades: this.idciudad,
                almacenes: this.idalmacen
            };
            
            request$ = this._almacenesService.vermonitoreocentros(this.token, this.idcliente, this.idinventariofisico, this.fechainicial, this.fechafinal, payload);
            
            request$.subscribe(
                response => {
                    this.cuadros[0].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '1' ? 1 : 0), 0);
                    this.cuadros[1].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '4' ? 1 : 0), 0);
                    this.cuadros[2].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '2' ? 1 : 0), 0);
                    this.cuadros[3].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '2' ? 1 : 0), 0);
                    this.cuadros[4].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '3' ? 1 : 0), 0);
                    this.cuadros[5].cantidad = response.detalle.reduce((acum, item) => acum + (item.cantidad === 1 ? 1 : 0), 0);

                    this.inventariofisico_detalle = response.detalle;
                },
                error => {
                    console.log(<any>error)
                }
            );
        }
            
    }
    
    verDetalle(idestado: number){
        let indiceCuadro = this.cuadros.findIndex(x => x.id === idestado);
        this.detalle_cabecera = "Detalle " + this.cuadros[indiceCuadro].nombre;
        
        this.detalle=[];
        let agregar=false;
        
        for (let dd = 0; dd < this.inventariofisico_detalle.length; dd++){
            agregar=false;
            switch(idestado){
                case 1:
                case 2:
                case 3:
                case 4:
                    agregar=(this.inventariofisico_detalle[dd].idestado_conteo==idestado);
                    break;
                case 5:
                    agregar=(this.inventariofisico_detalle[dd].idestado_conteo==2);
                    break;
                case 6:
                    agregar=(this.inventariofisico_detalle[dd].cantidad==1);
                    break;
            }
            
            if(agregar){
                this.detalle.push({
                    almacen: this.inventariofisico_detalle[dd].almacen,
                    almacen_conteo: this.inventariofisico_detalle[dd].almacen_conteo,
                    usuario_conteo: this.inventariofisico_detalle[dd].usuario_conteo,
                    serie: this.inventariofisico_detalle[dd].serie,
                    descripcion: this.inventariofisico_detalle[dd].descripcion,
                    idinventariofisico: this.inventariofisico_detalle[dd].idinventariofisico,
                });
            }
        }
        
        this.reportexlsx={titulo:"Detalle "+this.cuadros[indiceCuadro].nombre,cabecera:[
            {'titulo':'Almacen','tipo':'string','ancho':20},
            {'titulo':'Almacen Conteo','tipo':'string','ancho':20},
            {'titulo':'Usuario Conteo','tipo':'string','ancho':17},
            {'titulo':'Chasis','tipo':'string','ancho':20},
            {'titulo':'Descripción','tipo':'string','ancho':20},
            {'titulo':'No Inventario','tipo':'string','ancho':17},
        ],
        data:[]};

        let data: Array<any>=[];
        for (let r = 0; r<this.detalle.length; r++){
            data.push([
                {'valor': this.detalle[r].almacen},
                {'valor': this.detalle[r].almacen_conteo},
                {'valor': this.detalle[r].usuario_conteo},
                {'valor': this.detalle[r].serie},
                {'valor': this.detalle[r].descripcion},
                {'valor': this.detalle[r].idinventariofisico},
            ]);
        }


         this.reportexlsx.data=data;
        
        this.detalle_visible=true;
        
        
        
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
    }
    
    verMarcados(){
        console.log(this.idciudad);
    }

}
