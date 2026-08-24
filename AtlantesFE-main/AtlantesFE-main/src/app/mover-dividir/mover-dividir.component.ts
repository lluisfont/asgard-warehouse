import { Component, OnInit, Input, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {InventarioModel} from '../models/inventario.model';
import {AlmacenModel} from '../models/almacen.model';
import {ExportExcelService} from '../services/export-excel.service';
import {ExcelModel} from "../models/excel.model";
import {GLOBAL} from './../global';
import * as FileSaver from 'file-saver';
declare var $: any;

@Component({
    selector: 'app-mover-dividir',
    templateUrl: './mover-dividir.component.html',
    styleUrls: ['./mover-dividir.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,ExportExcelService]
})
export class MoverDividirComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public inventario: Array<any>=[InventarioModel];
    public cantidad_inicial: number;
    public cantidad_total: number;
    public error_cantidad: boolean;
    public peso_inicial: number;
    public peso_total: number;
    public error_peso: boolean;
    public division: Array<any>=[];
    public inventarioinicial: Array<any>=[];
    public errordetalle: Array<any>=[];
    public embalajes: Array<any>=[];
    public no_confs: Array<any>=[];
    public clasificaciones: Array<any>=[];
    public mermas: Array<any>=[];
    public salidaspendientes: Array<any>=[];
    public bloqueado: boolean=false;
    public bloqueado_es_vehiculo: boolean=false;
    
    public visible_mover_dividir: boolean=false;
    public cargando: boolean=false;
    
    public historial: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public idingresodetalleeditar: number;
    
    public carga_almacen: boolean=false;
    public almacen: AlmacenModel;
    public marcarTodosAlmacen: boolean=false;
    public marcadoAlmacen: Array<any>=[];
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public cols: Array<any>;
    public _selectedColumns: Array<any>;
    
    public reportexlsx: ExcelModel;
    
    public ver_mover_dividir: boolean=false;
    public editar_mover_dividir: boolean=false;

    public visible_actualizar: boolean=false;

    public urlFormatoActualziacion: string;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;

    public mensajes_error: Array<any>=[];
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _exportexcelService: ExportExcelService
        ) { 
        this.urlFormatoActualziacion=GLOBAL.urlFiles+'FormatoActualizacionMasiva.xlsx';
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        this._datomaestroService.columnas_mover_dividir(this.token).subscribe(
            response =>{
                this.cols=response.columnas_mover_dividir;
            },
            error=>{
                console.log(<any>error)
            }
        );
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_mover_dividir=true;
            this.editar_mover_dividir=true;
        }else{
            let indiceVerMoverDividir = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 20);
            if (indiceVerMoverDividir>=0){
                if (this.tokenDetalle.permisos[indiceVerMoverDividir].lectura){
                    this.ver_mover_dividir=true;
                }
                if (this.tokenDetalle.permisos[indiceVerMoverDividir].escritura){
                    this.editar_mover_dividir=true;
                }
            }
        }
        
        this._usuarioService.verusuario(this.token,this.tokenDetalle["idusuario"]).subscribe(
            response =>{
                this._selectedColumns=response.usuario.columnas_moverdividir;
                if(this._selectedColumns.length==0){
                    this._selectedColumns = this.cols;
                }
                //console.log(this._selectedColumns);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }

    ngOnInit(): void {
        this._datomaestroService.embalajes(this.token).subscribe(
            response =>{
                this.embalajes=response.embalajes;
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.no_confs(this.token).subscribe(
            response =>{
                this.no_confs=response.no_confs;
                this.no_confs.unshift({
                    idno_conf: null,
                    no_conf: "(Vacio)"
                });
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.clasificaciones(this.token).subscribe(
            response =>{
                this.clasificaciones=response.clasificaciones;
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.mermas(this.token).subscribe(
            response =>{
                this.mermas=response.mermas;
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
        //console.log(this.tokenDetalle)
        /*
        
        */
        /*
        this.cols = [
            { field: 'cliente', header: 'Cliente', type: 'text' },
            { field: 'numeroingreso', header: 'Ingreso', type: 'text' },
            { field: 'fechaingreso', header: 'Fecha Ingreso', type: 'date' },
            { field: 'categoria', header: 'Categoria', type: 'text' },
            { field: 'ubicacionalmacen', header: 'Ubicacion', type: 'text' },
            { field: 'codigo', header: 'Codigo', type: 'text' },
            { field: 'serie', header: 'Serie', type: 'text' },
            { field: 'descripcion', header: 'Descripción', type: 'text' },
            { field: 'cantidad', header: 'Cantidad', type: 'numeric' },
            { field: 'codigoembalaje', header: 'Embalaje', type: 'text' },
            { field: 'lote', header: 'Lote', type: 'text' },
            { field: 'no_conf', header: 'No Conf', type: 'text' },
            { field: 'fechavencimiento', header: 'Fec. Venc', type: 'date' },
            { field: 'relacion_caja', header: 'PPT', type: 'text' },
            { field: 'bultos', header: 'Bultos', type: 'numeric' },
            { field: 'peso', header: 'Peso', type: 'numeric' },
            { field: 'observaciones', header: 'Observaciones', type: 'text' }
        ];
        */
        //this._selectedColumns = this.cols;
        /*
        this._selectedColumns = [
            { field: 'cliente', header: 'Cliente', type: 'text' },
            { field: 'cantidad', header: 'Cantidad', type: 'numeric' }
        ];
        */
        this.getInventario();
        
    }
    
    @Input() get selectedColumns(): Array<any> {
        //console.log(this._selectedColumns);
        return this._selectedColumns;
    }

    set selectedColumns(val: Array<any>) {
        //console.log("entra a set");
        //console.log(val);
        //restore original order
        //this._selectedColumns = this.cols.filter(col => val.includes(col));
        this._selectedColumns = val;
        //console.log(this._selectedColumns);
        this._usuarioService.savecolumnas_mover_dividir(this.token, this._selectedColumns, this.tokenDetalle["idusuario"]).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    //this.getInventario();
                }else{
                    this.toast_tipo="Error";
                }
                //$("#ventanaDetalleItem").modal('hide');
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        //console.log(this._selectedColumns);
    }
    
    cargarAlmacen(){
        this._almacenesService.veralmacen(this.token, this.tokenDetalle['idalmacen'], this._usuarioService.getCurrentDateFilterValue()).subscribe(
            response =>{
                this.almacen=response.almacen;
                this.carga_almacen=true;
                //console.log(this.almacen);
                //console.log(this.almacen.direccion);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    getInventario(){
        this._almacenesService.inventario(this.token, this.tokenDetalle.idcliente_almacen,this._usuarioService.getCurrentDateTimeValue(),false).subscribe(
            response =>{
                this.inventario=response.inventario;
                //console.log(response.inventario);
                this.inventario.filter(inventario => inventario.fechavencimiento!=null).forEach(
                    inventario => (inventario.fechavencimiento = new Date(inventario.fechavencimiento.replace(/-/g, '\/')))
                );
                
                this.inventario.forEach(
                    inventario => (inventario.fechaingreso = new Date(inventario.fechaingreso.replace(/-/g, '\/')))
                );
                
                
                this.reportexlsx={titulo:"InventarioActual",cabecera:[
                    {'titulo':'Id','tipo':'string','ancho':40},
                    {'titulo':'Codigo','tipo':'string','ancho':17},
                    {'titulo':'Cantidad','tipo':'number','ancho':17}
                ],
                data:[]};


                let data: Array<any>=[];

                for (let r = 0; r < this.inventario.length; r++){
                    data.push([
                        {'valor': this.inventario[r].idingresodetalle},
                        {'valor': this.inventario[r].codigo},
                        {'valor': this.inventario[r].cantidad}
                    ]);
                }

                this.reportexlsx.data=data;
                
                //console.log(this.inventario);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    
    prepararVentana(idingresodetalle: number){
        if (!this.carga_almacen){
            this.cargarAlmacen();
        }
        
        this.visible_mover_dividir=true;
        
        this.bloqueado=false;
        this.bloqueado_es_vehiculo=false;
        this.errordetalle=[];
        this.division=[];
        this.inventarioinicial=[];
        this.marcadoAlmacen=[];
        this.marcarTodosAlmacen=false;
        this.idingresodetalleeditar=idingresodetalle;
        let indicedetalle = this.inventario.findIndex(x => x.idingresodetalle === this.idingresodetalleeditar);
        
        if (this.inventario[indicedetalle].salidaspendientes.length>0){
            this.bloqueado=true;
        }
        if (this.inventario[indicedetalle].es_vehiculo){
            this.bloqueado_es_vehiculo=true;
        }
        this.salidaspendientes=this.inventario[indicedetalle].salidaspendientes;
        
        this.errordetalle.push({
            'errorcodigo': false
        });
        
        this.cantidad_inicial=this.inventario[indicedetalle].cantidad;
        this.peso_inicial=this.inventario[indicedetalle].peso;
        
        this.division.push({
            idingresodetalle: this.idingresodetalleeditar,
            idingreso: this.inventario[indicedetalle].idingreso,
            idalmacendetalle: this.inventario[indicedetalle].idalmacendetalle,
            ubicacionalmacen: this.inventario[indicedetalle].ubicacionalmacen,
            codigo: this.inventario[indicedetalle].codigo,
            serie: this.inventario[indicedetalle].serie,
            descripcion: this.inventario[indicedetalle].descripcion,
            categoria: this.inventario[indicedetalle].categoria,
            cantidad: this.inventario[indicedetalle].cantidad,
            idembalaje: this.inventario[indicedetalle].idembalaje,
            codigoembalaje: this.inventario[indicedetalle].codigoembalaje,
            lote: this.inventario[indicedetalle].lote,
            costo_un: this.inventario[indicedetalle].costo_un,
            cantidad_no_conf: this.inventario[indicedetalle].cantidad_no_conf,
            idno_conf: this.inventario[indicedetalle].idno_conf,
            no_conf: this.inventario[indicedetalle].no_conf,
            idclasificacion: this.inventario[indicedetalle].idclasificacion,
            clasificacion: this.inventario[indicedetalle].clasificacion,
            idmerma: this.inventario[indicedetalle].idmerma,
            merma: this.inventario[indicedetalle].merma,
            //fechaproduccion: this.inventario[indicedetalle].fechaproduccion,
            fechavencimiento: this.inventario[indicedetalle].fechavencimiento,
            relacion_caja: this.inventario[indicedetalle].relacion_caja,
            volumen: this.inventario[indicedetalle].volumen,
            bultos: this.inventario[indicedetalle].bultos,
            peso: this.inventario[indicedetalle].peso,
            pallet: this.inventario[indicedetalle].pallet,
            temperatura: this.inventario[indicedetalle].temperatura,
            observaciones: this.inventario[indicedetalle].observaciones,
            centro_distribucion: this.inventario[indicedetalle].centro_distribucion
        });
        
        this.inventarioinicial.push({
            idingresodetalle: this.idingresodetalleeditar,
            idingreso: this.inventario[indicedetalle].idingreso,
            idalmacendetalle: this.inventario[indicedetalle].idalmacendetalle,
            ubicacionalmacen: this.inventario[indicedetalle].ubicacionalmacen,
            codigo: this.inventario[indicedetalle].codigo,
            serie: this.inventario[indicedetalle].serie,
            descripcion: this.inventario[indicedetalle].descripcion,
            categoria: this.inventario[indicedetalle].categoria,
            cantidad: this.inventario[indicedetalle].cantidad,
            idembalaje: this.inventario[indicedetalle].idembalaje,
            lote: this.inventario[indicedetalle].lote,
            costo_un: this.inventario[indicedetalle].costo_un,
            cantidad_no_conf: this.inventario[indicedetalle].cantidad_no_conf,
            idno_conf: this.inventario[indicedetalle].idno_conf,
            no_conf: this.inventario[indicedetalle].no_conf,
            idclasificacion: this.inventario[indicedetalle].idclasificacion,
            clasificacion: this.inventario[indicedetalle].clasificacion,
            idmerma: this.inventario[indicedetalle].idmerma,
            merma: this.inventario[indicedetalle].merma,
            //fechaproduccion: this.inventario[indicedetalle].fechaproduccion,
            fechavencimiento: this.inventario[indicedetalle].fechavencimiento,
            relacion_caja: this.inventario[indicedetalle].relacion_caja,
            volumen: this.inventario[indicedetalle].volumen,
            bultos: this.inventario[indicedetalle].bultos,
            peso: this.inventario[indicedetalle].peso,
            pallet: this.inventario[indicedetalle].pallet,
            temperatura: this.inventario[indicedetalle].temperatura,
            observaciones: this.inventario[indicedetalle].observaciones,
            centro_distribucion: this.inventario[indicedetalle].centro_distribucion
        });
        
        this.marcadoAlmacen.push({
            'ubicarenalmacen': false
        });
        
        this.calcularTotal();
        
        this.historial=[];
        
        
        this._almacenesService.historial(this.token,idingresodetalle).subscribe(
            response =>{
                this.historial=response.historial;
                //console.log(this.historial);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
    }
    
    crearDivision(){
        let indicedetalle = this.inventario.findIndex(x => x.idingresodetalle === this.idingresodetalleeditar);
        this.errordetalle.push({
            'errorcodigo': false
        });
        //console.log(this.division);
        this.division.push({
            idingresodetalle: 0,
            idingreso: this.inventario[indicedetalle].idingreso,
            idalmacendetalle: this.inventario[indicedetalle].idalmacendetalle,
            ubicacionalmacen: this.inventario[indicedetalle].ubicacionalmacen,
            codigo: this.inventario[indicedetalle].codigo,
            serie: this.inventario[indicedetalle].serie,
            descripcion: this.inventario[indicedetalle].descripcion,
            categoria: this.inventario[indicedetalle].categoria,
            cantidad: this.inventario[indicedetalle].cantidad,
            idembalaje: this.inventario[indicedetalle].idembalaje,
            codigoembalaje: this.inventario[indicedetalle].codigoembalaje,
            lote: this.inventario[indicedetalle].lote,
            costo_un: this.inventario[indicedetalle].costo_un,
            cantidad_no_conf: this.inventario[indicedetalle].cantidad_no_conf,
            idno_conf: this.inventario[indicedetalle].idno_conf,
            no_conf: this.inventario[indicedetalle].no_conf,
            idclasificacion: this.inventario[indicedetalle].idclasificacion,
            clasificacion: this.inventario[indicedetalle].clasificacion,
            idmerma: this.inventario[indicedetalle].idmerma,
            merma: this.inventario[indicedetalle].merma,
            //fechaproduccion: this.inventario[indicedetalle].fechaproduccion,
            fechavencimiento: this.inventario[indicedetalle].fechavencimiento,
            relacion_caja: this.inventario[indicedetalle].relacion_caja,
            volumen: this.inventario[indicedetalle].volumen,
            bultos: this.inventario[indicedetalle].bultos,
            peso: this.inventario[indicedetalle].peso,
            pallet: this.inventario[indicedetalle].pallet,
            temperatura: this.inventario[indicedetalle].temperatura,
            observaciones: this.inventario[indicedetalle].observaciones,
            centro_distribucion: this.inventario[indicedetalle].centro_distribucion
        });
        
        this.marcadoAlmacen.push({
            'ubicarenalmacen': false
        });
        
        this.calcularValores();
        this.calcularTotal();
        
    }
    
    eliminarDivision(indiceeliminar: number){
        this.division.splice(indiceeliminar, 1);
        this.errordetalle.splice(indiceeliminar, 1);
        this.marcadoAlmacen.splice(indiceeliminar, 1);
        this.calcularValores();
    }
    
    calcularValores(){
        let indicedetalle = this.inventario.findIndex(x => x.idingresodetalle === this.idingresodetalleeditar);
        let cantidadfilas = this.division.length;
        let cantidadividida=this.inventario[indicedetalle].cantidad/cantidadfilas;
        let bultodividido=this.inventario[indicedetalle].bultos/cantidadfilas;
        let pesodividido=this.inventario[indicedetalle].peso/cantidadfilas;
        let cantidad_no_confdividido=this.inventario[indicedetalle].cantidad_no_conf/cantidadfilas;
        Object.keys(this.division).forEach(key => {
            this.division[key].cantidad = cantidadividida.toFixed(2);
            this.division[key].bultos = bultodividido.toFixed(2);
            this.division[key].peso = pesodividido.toFixed(2);
            this.division[key].cantidad_no_conf = cantidad_no_confdividido.toFixed(2);
        });
    }
    
    calcularTotal(){
        this.error_cantidad=false;
        this.error_peso=false;
        this.cantidad_total=0;
        this.peso_total=0
        for (let dd = 0; dd < this.division.length; dd++){
            this.cantidad_total=this.cantidad_total+parseFloat(this.division[dd].cantidad);
            this.peso_total=this.peso_total+parseFloat(this.division[dd].peso);
        }
    }
    
    guardarCambios(){
        //console.log(this.division);
        //console.log(this.inventarioinicial);
        //let mismosdatos=false;
        //let mismaubicacion=false;
        /*
        if (this.division.length==1){
            if (this.division[0].codigo == this.inventarioinicial[0].codigo 
            && this.division[0].idalmacendetalle == this.inventarioinicial[0].idalmacendetalle
            && this.division[0].ubicacionalmacen == this.inventarioinicial[0].ubicacionalmacen
            && this.division[0].serie == this.inventarioinicial[0].serie
            && this.division[0].descripcion == this.inventarioinicial[0].descripcion
            && this.division[0].categoria == this.inventarioinicial[0].categoria
            && this.division[0].cantidad == this.inventarioinicial[0].cantidad
            && this.division[0].idembalaje == this.inventarioinicial[0].idembalaje
            && this.division[0].lote == this.inventarioinicial[0].lote
            && this.division[0].fechaproduccion == this.inventarioinicial[0].fechaproduccion
            && this.division[0].fechavencimiento == this.inventarioinicial[0].fechavencimiento
            && this.division[0].volumen == this.inventarioinicial[0].volumen
            && this.division[0].bultos == this.inventarioinicial[0].bultos
            && this.division[0].peso == this.inventarioinicial[0].peso
            && this.division[0].temperatura == this.inventarioinicial[0].temperatura
            && this.division[0].observaciones == this.inventarioinicial[0].observaciones){
                mismosdatos=true;
            }
        }
        
        
        
        if (mismosdatos){
            $("#ventanaDetalleItem").modal('hide');
        }else{
            let erroresdetalle: boolean=false;
            for (let dd = 0; dd < this.division.length; dd++){
                if (this.division[dd].codigo == null || this.division[dd].codigo==''){
                    erroresdetalle=true;
                    this.errordetalle[dd].errorcodigo=true;
                }
            }

            if (!erroresdetalle){
                //console.log(this.division);
                this._almacenesService.moverdividir(this.token, this.division).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            this.getInventario();
                        }else{
                            this.toast_tipo="Error";
                        }
                        $("#ventanaDetalleItem").modal('hide');
                        $("#liveToast").toast('show');
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }
        }
        */
        let erroresdetalle: boolean=false;
        this.error_cantidad=false;
        this.error_peso=false;
        for (let dd = 0; dd < this.division.length; dd++){
            if (this.division[dd].codigo == null || this.division[dd].codigo==''){
                erroresdetalle=true;
                this.errordetalle[dd].errorcodigo=true;
            }
        }
        
        this.calcularTotal();
        if (this.cantidad_total != this.cantidad_inicial){
            this.error_cantidad=true;
        }
        if (this.peso_total != this.peso_inicial){
            this.error_peso=true;
        }

        if (!erroresdetalle && !this.error_cantidad && !this.error_peso){
            //console.log(this.division);
            this.cargando=true;
            this._almacenesService.moverdividir(this.token, this.division).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.getInventario();
                    }else{
                        this.toast_tipo="Error";
                    }
                    //$("#ventanaDetalleItem").modal('hide');
                    this.visible_mover_dividir=false;
                    this.cargando=false;
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error);
                    this.cargando=false;
                }
            );
        }
        
            
        
    }
    
    ubicarEnAlmacen(idalmacendetalle: number, nombreUbicacion: string){
        
        for (let aa = 0; aa < this.marcadoAlmacen.length; aa++){
            if (this.marcadoAlmacen[aa].ubicarenalmacen){
                this.division[aa].idalmacendetalle=idalmacendetalle;
                this.division[aa].ubicacionalmacen=nombreUbicacion;
                this.marcadoAlmacen[aa].ubicarenalmacen=false;
            }
        }
        this.marcarTodosAlmacen=false;
        
    }
    
    
    public pegarUbicacion(): void {
        var copiado: Array<any>;
        navigator.clipboard.readText().then(
            text => {
                //console.log(text);
                if (text.length>0){
                    
                    copiado = text.split(/\r?\n/);
                    for (let xx = 0; xx < (copiado.length-1); xx++){
                        copiado[xx]=copiado[xx].split('\t');
                    }
                    //console.log(copiado);
                    //console.log(this.almacen.detalle);
                    let ultimodato = Math.min(copiado.length - 1, this.division.length);
                    for(let ii=0; ii<ultimodato; ii++){
                        var nombreubicacion='';
                        var idubicacion=null;
                        for (let aa = 0; aa < this.almacen.detalle.length;aa++){
                            let indiceUbicacion = this.almacen.detalle[aa].findIndex(x => (x.nombre === copiado[ii][0] && x.tipo===1));
                            //console.log("para el "+copiado[ii][0]+" el id es "+aa+" "+indiceUbicacion);
                            if(indiceUbicacion>=0){
                                nombreubicacion=copiado[ii][0];
                                idubicacion = this.almacen.detalle[aa][indiceUbicacion].idalmacendetalle;
                                break;
                            }
                        }
                        
                        this.division[ii].ubicacionalmacen=nombreubicacion;
                        this.division[ii].idalmacendetalle=idubicacion;
                        
                    }
                }
            }
        ).catch(error => {
            console.error('Cannot read clipboard text: ', error);
        });
    }

    
    marcarTodos(){
        this.marcarTodosAlmacen = !this.marcarTodosAlmacen;
        for (let ma = 0; ma < this.marcadoAlmacen.length; ma++){
            this.marcadoAlmacen[ma].ubicarenalmacen=this.marcarTodosAlmacen;
        }
        
    }
    
    guardarUbicacion(){
        //console.log(this.division);
        let ubicacion: Array<any>=[];
        for (let dd = 0; dd < this.division.length;dd++){
            ubicacion.push({
                idingresodetalle: this.division[dd].idingresodetalle,
                idalmacendetalle: this.division[dd].idalmacendetalle
            });
        }
        
        this._almacenesService.ubicaralmacen(this.token, ubicacion).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getInventario();
                }else{
                    this.toast_tipo="Error";
                }
                //$("#ventanaDetalleItem").modal('hide');
                this.visible_mover_dividir=false;
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
    }

    prepararActualizar(){
        this.visible_actualizar=true;
        this.mensajes_error=[];
        this.myInputVariable.nativeElement.value = "";
    }

    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }

    actualizarInformacion(){
        this.cargando=true;
        this._almacenesService.inventariocatualizacionmasiva(this.token, this.uploadFileInput).subscribe(
            response =>{
                console.log(response);
                
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    //this.verInventarioFisico(0);
                    this.myInputVariable.nativeElement.value = "";
                    this.archivocargado = false;
                    this.visible_actualizar=false;
                    this.cargando=false;
                    this.getInventario();
                    //$('#nuevoInventarioFisico').modal('hide');
                }else{
                    this.toast_tipo="Error";
                    this.myInputVariable.nativeElement.value = "";
                    this.mensajes_error=response.mensajes_error;
                    this.cargando=false;
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error)
                $('#ventanaLoading').modal('hide');
            }
        );
    }
    
    exportExcel(){
        this._exportexcelService.exportExcel(this.reportexlsx);
    }

}
