import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {formatDate} from '@angular/common';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {IngresoModel} from '../models/ingreso.model';
import {AlmacenModel} from '../models/almacen.model';
import {AsgardService} from '../services/asgard.service';
import {GLOBAL} from './../global';

declare var $: any;

@Component({
    selector: 'app-ingresos-detalle',
    templateUrl: './ingresos-detalle.component.html',
    styleUrls: ['./ingresos-detalle.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,AsgardService,EntidadesService]
})
export class IngresosDetalleComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public idingreso: number;
    public ingreso: IngresoModel;

    public entidades: Array<any>;
    public clientebloqueado: boolean=false;
    public borrar_todo_bloqueado: boolean=false;
    public tiene_inter_company: boolean=false;
    public inter_company: boolean=false;
    public erroridcliente_destino: boolean;
    public entidades_destino: Array<any>;

    public mediostransporte: Array<any>;
    public tiposdescarga: Array<any>;
    public tiposingreso: Array<any>;
    public tiposcontenedor: Array<any>;
    public tiposproducto: Array<any>;
    public embalajes: Array<any>;
    public no_confs: Array<any>;
    public clasificaciones: Array<any>;
    public mermas: Array<any>;
    public usuarios: Array<any>;
    public docs_errada: Array<any>;

    public filasagregar: number=1;
    public indicedetalleeliminar: number;

    public urlFormatoIngreso: string;

    public erroridcliente: boolean=false;
    public errorfecha: boolean=false;
    public erroridusuario_recibido: boolean=false;
    public errorfechasistema: boolean=false;
    public errordetalle: Array<any>=[];

    public editar_descripcion: number=-1;

    public cantidadtotal: number= 0;
    public bultostotal: number= 0;
    public cantidad_no_conftotal: number= 0;
    public volumentotal: number= 0;
    public pesototal: number= 0;
    public cantidadsalidatotal: number= 0;

    public ubicaciondocumentos: string;
    public indicedocumentoeliminar: number;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    public nombredocumentocargar: string;
    public existedocumento: boolean;

    public almacen: AlmacenModel;
    public marcarTodosAlmacen: boolean=false;
    public marcadoAlmacen: Array<any>=[];

    public toast_mensaje: string;
    public toast_tipo: string;
    
    public erroresmovimiento: Array<any>=[];
    
    public error_partida: boolean=false;
    
    public chasis: string;
    public modelo: string;
    public color: string 
    public accesorios_vehiculos: Array<any>;
    public ingresodetalles_accesorios_vehiculos: Array<any>;
    
    public importar_data: boolean=true;
    public actualizar_data: boolean=true;
    
    public ver_ingresos: boolean=false;
    public editar_ingresos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenService: AlmacenesService,
        private _asgardService: AsgardService,
        private _entidadService: EntidadesService,
        private _route: ActivatedRoute,
        private _router: Router
        ){
            this._route.params.forEach((params: Params)=>{
                this.idingreso = params["idingreso"];
            });
            this.token = this._usuarioService.getToken();
            this.tokenDetalle = this._usuarioService.getTokenDetalle();
            if(this.tokenDetalle.idtipousuario==1){
                this.ver_ingresos=true;
                this.editar_ingresos=true;
            }else{
                let indiceVerIngresos = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 1);
                if(indiceVerIngresos>=0){
                    if(this.tokenDetalle.permisos[indiceVerIngresos].lectura){
                        this.ver_ingresos=true;
                    }
                    if(this.tokenDetalle.permisos[indiceVerIngresos].escritura){
                        this.editar_ingresos=true;
                    }
                }
            }
            this.urlFormatoIngreso=GLOBAL.urlFiles+'FormatoIngreso.xlsx';
            this.ubicaciondocumentos = 'almacen/adjuntos_ingresos/' + this.idingreso;
        }

    ngOnInit(): void {
        
        
        
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                this.entidades = response.clientes;
                this.cargarDetalle();
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.mediostransporte(this.token).subscribe(
            response =>{
                this.mediostransporte=response.mediostransporte;
                //console.log(this.mediostransporte);

            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tiposdescarga(this.token).subscribe(
            response =>{
                this.tiposdescarga=response.tiposdescarga;
                //console.log(this.tiposcontenedor);

            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.tiposcontenedor(this.token).subscribe(
            response =>{
                this.tiposcontenedor=response.tiposcontenedor;
                //console.log(this.tiposcontenedor);

            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tiposproducto(this.token).subscribe(
            response =>{
                this.tiposproducto=response.tiposproducto;
                //console.log(this.tiposcontenedor);

            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tiposingreso(this.token).subscribe(
            response =>{
                this.tiposingreso=response.tiposingreso;
                //console.log(this.tiposcontenedor);

            },
            error=>{
                console.log(<any>error)
            }
        );

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

        this._datomaestroService.docs_errada(this.token).subscribe(
            response =>{
                this.docs_errada=response.docs_errada;
                //console.log(this.embalajes);

            },
            error=>{
                console.log(<any>error)
            }
        );
        
        

        this._usuarioService.usuarios(this.token).subscribe(
            response =>{
                this.usuarios=response.usuarios;
                //console.log(this.tiposcontenedor);

            },
            error=>{
                console.log(<any>error)
            }
        );

        
        this.cargarAlmacen();
    }
    
    cargarDetalle(){
        this.importar_data=true;
        this.clientebloqueado=false;
        this.borrar_todo_bloqueado=false;
        console.log(this.tokenDetalle);
        if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
            this.clientebloqueado=true;
        }
        this._almacenService.veringreso(this.token, this.idingreso).subscribe(
            response =>{
                this.ingreso=response.ingreso;
                this.errordetalle=[];
                this.marcadoAlmacen=[];

                for (let dd = 0; dd < this.ingreso.detalle.length; dd++){
                    if (this.ingreso.detalle[dd].salidas.length>0){
                        this.importar_data=false;
                    }
                    this.errordetalle.push({
                        'errorcodigo': false,
                        'errorserie': false,
                        'errormodelo': false,
                        'errormarca': false,
                        'errorcolor': false,
                        'errorcantidad': false,
                        'erroridembalaje': false,
                        'errorpeso': false,
                        'errorbultos': false
                    });

                    this.marcadoAlmacen.push({
                        'ubicarenalmacen': false
                    });
                }
                this.cargarAccesorios()
                this.gettotales();
                this.verificarCliente();
                if (this.ingreso.idcliente_destino>0){
                    this.inter_company=true;
                }

                for (let ii = 0; ii < this.ingreso.detalle.length; ii++){
                    if (this.ingreso.detalle[ii].salidas.length>0){
                        this.clientebloqueado=true;
                        this.borrar_todo_bloqueado=true;
                    }
                }
                
                
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
                
    }

    cargarAccesorios(){
        this._datomaestroService.accesorios_vehiculos(this.token, this.ingreso.idcliente ?? 0).subscribe(
            response =>{
                this.accesorios_vehiculos=response.accesorios_vehiculos;
                //console.log(this.embalajes);

            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    cargarAlmacen(){
        this._almacenService.veralmacen(this.token, this.tokenDetalle['idalmacen'], this._usuarioService.getCurrentDateFilterValue()).subscribe(
            response =>{
                this.almacen=response.almacen;
                console.log(this.almacen);
                //console.log(this.almacen.direccion);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    verificarCliente(){
        this.entidades_destino=[];
        this.erroridcliente=false;
        this.tiene_inter_company=false;
        if(this.ingreso.idcliente==null){
            this.ingreso.cliente='';
        }else{
            let indiceCliente = this.entidades.findIndex(x => x.id_num === this.ingreso.idcliente);
            this.ingreso.cliente = this.entidades[indiceCliente].entidad;
            if (this.entidades[indiceCliente].inter_company.length>0){
                this.tiene_inter_company=true;
                this.entidades_destino=this.entidades[indiceCliente].inter_company;
                //console.log(this.entidades_destino);
            }
        }
    }
    
    cambioCliente(){
        this.inter_company=false;
        this.ingreso.idcliente_destino=null;
        this.erroridcliente_destino=false;
        this.verificarCliente();
        this.cargarAccesorios();
    }
    
    getAccesoriosVehiculos(){
        let accesorios_vehiculos: Array<any>=[];
        for (let av = 0; av < this.accesorios_vehiculos.length; av++){
            accesorios_vehiculos.push({
                idingresodetalle_accesorios: 0,
                idaccesorios_vehiculos: this.accesorios_vehiculos[av].idaccesorios_vehiculos,
                accesorios_vehiculos: this.accesorios_vehiculos[av].accesorios_vehiculos,
                requiere_cantidad: this.accesorios_vehiculos[av].requiere_cantidad,
                marcado: false,
                cantidad: this.accesorios_vehiculos[av].cantidad
            });
        }
        
        return accesorios_vehiculos;
    }
    
    buscarAsgard(){
        this.error_partida=false;
        if (this.ingreso.partida.length==0){
            this.error_partida=true;
        }

        if(!this.error_partida){
            this._asgardService.vehiculosAsgard(this.token, this.ingreso.partida, this.ingreso.idcliente).subscribe(
                response =>{
                    if(response.codigo==200){
                        console.log(response);
                        this.ingreso.proveedor=response.data.proveedor;
                        this.ingreso.detalle.splice(0, this.ingreso.detalle.length);
                        this.errordetalle.splice(0, this.errordetalle.length);
                        this.marcadoAlmacen.splice(0, this.marcadoAlmacen.length);
                        
                        
                        
                        
                        for(let ii=0;ii<response.data.items.length;ii++){
                            this.errordetalle.push({
                                'errorcodigo': false,
                                'errorserie': false,
                                'errormodelo': false,
                                'errormarca': false,
                                'errorcolor': false,
                                'errorcantidad': false,
                                'erroridembalaje': false,
                                'errorpeso': false,
                                'errorbultos': false
                            });
                            this.marcadoAlmacen.push({
                                'ubicarenalmacen': false
                            });
                            
                            let accesorios_vehiculos=this.getAccesoriosVehiculos();
                            for(let aa=0;aa<accesorios_vehiculos.length;aa++){
                                if(aa==0){
                                    console.log(accesorios_vehiculos[aa].idaccesorios_vehiculos);
                                    console.log(response.data.items[ii].accesorios);
                                }
                                let indiceAccesorio = response.data.items[ii].accesorios.findIndex(x => x.accesorio_id == accesorios_vehiculos[aa].idaccesorios_vehiculos);
                                if(indiceAccesorio>=0){
                                    accesorios_vehiculos[aa].marcado=true;
                                    accesorios_vehiculos[aa].cantidad=response.data.items[ii].accesorios[indiceAccesorio].cantidad;
                                    accesorios_vehiculos[aa].observaciones=response.data.items[ii].accesorios[indiceAccesorio].observaciones;
                                }
                            }
                            
                            
                            
                            this.ingreso.detalle.push({
                                'idingresodetalle': 0,
                                'codigo': response.data.items[ii].codigoproducto,
                                'serie': response.data.items[ii].chasis,
                                'descripcion': '',
                                'centro_distribucion': '',
                                'categoria': '',
                                'cantidad': 1,
                                'cantidad_saldo': 0,
                                'idembalaje': 13,
                                'codigoembalaje': 'UND',
                                'lote': '',
                                'costo_un': 0,
                                //'cantidad_no_conf': 0,
                                'idno_conf': null,
                                'idclasificacion': null,
                                'idmerma': null,
                                //'fechaproduccion': null,
                                'fechavencimiento': null,
                                'relacion_caja': '',
                                'volumen': 0,
                                'peso': 0,
                                'pallet': '',
                                'peso_saldo': 0,
                                'temperatura': '',
                                'iddoc_errada': null,
                                'doc_errada': '',
                                'observaciones': '',
                                'idalmacendetalle': 0,
                                'ubicacionalmacen': '',
                                'dividido': 0,
                                'factor_conversion': 1,
                                'codigoembalaje_salida': '',
                                'salidas':[],
                                'cantidadextraida': 0,
                                'pesoextraido': 0,
                                'bultosextraidos': 0,
                                'cantidad_no_confextraidos': 0,
                                'modelo': response.data.items[ii].modelo,
                                'marca': response.data.items[ii].marca,
                                'color': response.data.items[ii].color,
                                'partida_especifica': response.data.items[ii].pedido,
                                'invoice': response.data.items[ii].nofactura,
                                'no_dim': response.data.items[ii].nodui,
                                'fecha_pase_salida': response.data.items[ii].fecha_pase_salida,
                                'accesorios_vehiculos': accesorios_vehiculos
                            });
                        }
                        this.ingreso.piezas_manifestadas = this.ingreso.detalle.length.toString();
                        this.gettotales();
                    }else{
                        this.toast_mensaje=response.mensaje;
                        this.toast_tipo="Error";
                        $("#liveToast").toast('show');
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }
    
    actualizarData(){
        this.error_partida=false;
        if (this.ingreso.partida.length==0){
            this.error_partida=true;
        }

        if(!this.error_partida){
            this._asgardService.vehiculosAsgard(this.token, this.ingreso.partida, this.ingreso.idcliente).subscribe(
                response =>{
                    if(response.codigo==200){
                        for (let dd = 0; dd < this.ingreso.detalle.length; dd++){
                            let indiceAsgard = response.data.items.findIndex(x => x.chasis === this.ingreso.detalle[dd].serie);
                            if(indiceAsgard>=0){
                                let accesorios_vehiculos=this.getAccesoriosVehiculos();
                                for(let aa=0;aa<accesorios_vehiculos.length;aa++){
                                    let indiceAccesorio = response.data.items[indiceAsgard].accesorios.findIndex(x => x.accesorio_id == accesorios_vehiculos[aa].idaccesorios_vehiculos);
                                    if(indiceAccesorio>=0){
                                        accesorios_vehiculos[aa].marcado=true;
                                        accesorios_vehiculos[aa].cantidad=response.data.items[indiceAsgard].accesorios[indiceAccesorio].cantidad;
                                    }
                                }
                                
                                this.ingreso.detalle[dd].modelo = response.data.items[indiceAsgard].modelo;
                                this.ingreso.detalle[dd].marca = response.data.items[indiceAsgard].marca;
                                this.ingreso.detalle[dd].color = response.data.items[indiceAsgard].color;
                                this.ingreso.detalle[dd].partida_especifica = response.data.items[indiceAsgard].pedido;
                                this.ingreso.detalle[dd].invoice = response.data.items[indiceAsgard].nofactura;
                                this.ingreso.detalle[dd].no_dim = response.data.items[indiceAsgard].nodui;
                                this.ingreso.detalle[dd].fecha_pase_salida = response.data.items[indiceAsgard].fecha_pase_salida;
                                this.ingreso.detalle[dd].accesorios_vehiculos= accesorios_vehiculos;
                                
                                
                            }
                        }
                    }else{
                        this.toast_mensaje=response.mensaje;
                        this.toast_tipo="Error";
                        $("#liveToast").toast('show');
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    agregarFila(){
        //console.log("entra");
        let cantidad=0;
        let idembalaje=0;
        let codigoembalaje='';
        if (this.ingreso.es_vehiculo){
            cantidad=1;
            idembalaje=13;
            codigoembalaje='UND';
        }

        for (let fa = 1; fa <= this.filasagregar; fa++){
            this.errordetalle.push({
                'errorcodigo': false,
                'errorserie': false,
                'errormodelo': false,
                'errormarca': false,
                'errorcolor': false,
                'errorcantidad': false,
                'erroridembalaje': false,
                'errorpeso': false,
                'errorbultos': false
            });
            this.marcadoAlmacen.push({
                'ubicarenalmacen': false
            });
            this.ingreso.detalle.push({
                'idingresodetalle': 0,
                'codigo': '',
                'serie': '',
                'descripcion': '',
                'centro_distribucion': '',
                'categoria': '',
                'cantidad': cantidad,
                'cantidad_saldo': cantidad,
                'idembalaje': idembalaje,
                'codigoembalaje': codigoembalaje,
                'lote': '',
                'costo_un': 0,
                //'cantidad_no_conf': 0,
                'idno_conf': null,
                'idclasificacion': null,
                'idmerma': null,
                //'fechaproduccion': null,
                'fechavencimiento': null,
                'relacion_caja': '',
                'volumen': 0,
                'peso': 0,
                'pallet': '',
                'peso_saldo': 0,
                'temperatura': '',
                'iddoc_errada': null,
                'doc_errada': '',
                'observaciones': '',
                'idalmacendetalle': 0,
                'ubicacionalmacen': '',
                'dividido': 0,
                'factor_conversion': 1,
                'codigoembalaje_salida': '',
                'salidas':[],
                'cantidadextraida': 0,
                'pesoextraido': 0,
                'bultosextraidos': 0,
                'cantidad_no_confextraidos': 0,
                'modelo': '',
                'marca': '',
                'color': '',
                'partida_especifica': '',
                'invoice': '',
                'no_dim': '',
                'fecha_pase_salida': null,
                'accesorios_vehiculos': this.getAccesoriosVehiculos()
            });
        }
    }

    public pegarDatos(campo: string): void {
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
                    let ultimodato = Math.min(copiado.length - 1, this.ingreso.detalle.length);
                    for(let ii=0; ii<ultimodato; ii++){
                        switch(campo){
                            case 'fechaproduccion':
                            case 'fechavencimiento':
                                let fechasplit=copiado[ii][0].split("/");
                                if(fechasplit.length==3){
                                    copiado[ii][0]=fechasplit[2]+"-"+fechasplit[1]+"-"+fechasplit[0];
                                }
                                break;
                            case 'idembalaje':
                                let indiceEmbalaje = this.embalajes.findIndex(x => x.codigoembalaje === copiado[ii][0]);
                                if(indiceEmbalaje>=0){
                                    copiado[ii][0] = this.embalajes[indiceEmbalaje].idembalaje;
                                }
                                break;
                            case 'idno_conf':
                                let indiceNoConf = this.no_confs.findIndex(x => x.no_conf === copiado[ii][0]);
                                if(indiceNoConf>=0){
                                    copiado[ii][0] = this.no_confs[indiceNoConf].idno_conf;
                                }
                                break;
                            case 'idclasificacion':
                                let indiceClasificacion = this.clasificaciones.findIndex(x => x.clasificacion === copiado[ii][0]);
                                if(indiceClasificacion>=0){
                                    copiado[ii][0] = this.clasificaciones[indiceClasificacion].idclasificacion;
                                }
                                break;
                            case 'idmerma':
                                let indiceMerma = this.mermas.findIndex(x => x.merma === copiado[ii][0]);
                                if(indiceMerma>=0){
                                    copiado[ii][0] = this.mermas[indiceMerma].idmerma;
                                }
                                break;
                            case 'iddoc_errada':
                                let indiceDocErrada = this.docs_errada.findIndex(x => x.doc_errada === copiado[ii][0]);
                                if(indiceDocErrada>=0){
                                    copiado[ii][0] = this.docs_errada[indiceDocErrada].iddoc_errada;
                                }
                                break;
                            case 'cantidad':
                            case 'costo_un':
                            case 'cantidad_no_conf':
                            case 'volumen':
                            case 'peso':
                                copiado[ii][0]=parseFloat(copiado[ii][0]);
                                break;
                            case 'idalmacendetalle':
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



                                break;
                            default:
                                //this.ingreso.detalle[ii][campo]=copiado[ii][0];
                                break;
                        }

                        if(campo=='idalmacendetalle'){
                            this.ingreso.detalle[ii].ubicacionalmacen=nombreubicacion;
                            this.ingreso.detalle[ii].idalmacendetalle=idubicacion;
                        }else{
                            this.ingreso.detalle[ii][campo]=copiado[ii][0];
                            if(campo=='codigo'){
                                this.verificarCodigo(ii);
                            }
                        }
                    }
                    this.gettotales();
                    console.log(this.ingreso.detalle);

                }

            }
        ).catch(error => {
            console.error('Cannot read clipboard text: ', error);
        });


    }

    public getDataFromClipBoard(): void {
        var copiado: Array<any>;
        navigator.clipboard.readText().then(
            text => {
                console.log(text);
                if (text.length>0){
                    copiado = text.split(/\r?\n/);
                    for (let xx = 0; xx < (copiado.length-1); xx++){
                        copiado[xx]=copiado[xx].split('\t');
                    }
                    console.log(copiado);
                }

            }
        ).catch(error => {
            console.error('Cannot read clipboard text: ', error);
        });
    }

    verificarCodigo(indiceDetalle: number){
        console.log("entra");
        this._almacenService.verproducto(this.token, this.ingreso.idcliente, this.ingreso.detalle[indiceDetalle].codigo).subscribe(
            response =>{
                //console.log(response.producto.length);
                if(response.producto.length!==0){
                    this.ingreso.detalle[indiceDetalle].serie=response.producto.serie;
                    this.ingreso.detalle[indiceDetalle].descripcion=response.producto.descripcion;
                    this.ingreso.detalle[indiceDetalle].centro_distribucion=response.producto.centro_distribucion;
                    this.ingreso.detalle[indiceDetalle].categoria=response.producto.categoria;
                    this.ingreso.detalle[indiceDetalle].idembalaje=response.producto.idembalaje;
                    this.ingreso.detalle[indiceDetalle].codigoembalaje=response.producto.codigoembalaje;
                    this.ingreso.detalle[indiceDetalle].relacion_caja=response.producto.umcompra;
                    this.ingreso.detalle[indiceDetalle].factor_conversion=response.producto.factor_conversion;
                    this.ingreso.detalle[indiceDetalle].codigoembalaje_salida=response.producto.codigoembalaje_salida;
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.errordetalle[indiceDetalle].errorcodigo=false;
        this.errordetalle[indiceDetalle].errorserie=false;
        this.errordetalle[indiceDetalle].errormodelo=false;
        this.errordetalle[indiceDetalle].errormarca=false;
    }

    gettotales(){
        this.cantidadtotal=0;
        this.bultostotal=0;
        this.cantidad_no_conftotal=0;
        this.volumentotal=0;
        this.pesototal=0;
        this.cantidadsalidatotal=0;
        for (let i = 0; i < this.ingreso.detalle.length; i++){
            this.cantidadtotal = this.cantidadtotal + this.ingreso.detalle[i].cantidad;
            //this.bultostotal = this.bultostotal + this.ingreso.detalle[i].bultos;
            //this.cantidad_no_conftotal = this.cantidad_no_conftotal + this.ingreso.detalle[i].cantidad_no_conf;
            this.volumentotal = this.volumentotal + this.ingreso.detalle[i].volumen;
            this.pesototal = this.pesototal + this.ingreso.detalle[i].peso;
            this.cantidadsalidatotal = this.cantidadsalidatotal + (this.ingreso.detalle[i].cantidad / this.ingreso.detalle[i].factor_conversion);
        }
    }

    eliminarDetalle(){
        this.ingreso.detalle.splice(this.indicedetalleeliminar, 1);
        this.errordetalle.splice(this.indicedetalleeliminar, 1);
        this.marcadoAlmacen.splice(this.indicedetalleeliminar, 1);
        this.gettotales();
        $('#confirmarEliminarDetalle').modal('hide');
    }

    eliminarTodos(){
        this.ingreso.detalle.splice(0, this.ingreso.detalle.length);
        this.errordetalle.splice(0, this.errordetalle.length);
        this.marcadoAlmacen.splice(0, this.marcadoAlmacen.length);
        this.gettotales();
        $('#confirmarEliminarTodos').modal('hide');
    }

    verificarCI(){
        this.erroridusuario_recibido=false;
        if(this.ingreso.idusuario_recibido>0){
            let indiceUsuario = this.usuarios.findIndex(x => x.idusuario === this.ingreso.idusuario_recibido);
            this.ingreso.ci_recibido = this.usuarios[indiceUsuario].ci;
        }else{
            this.ingreso.ci_recibido='';
        }
    }

    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }

    validarDocumento(){
        this.errorarchivo=false;
        if (!this.archivocargado){
            this.errorarchivo=true;
        }
        if (!this.errorarchivo){
            let indicedoc = this.ingreso.adjuntos.findIndex(x => x.documento === this.uploadFileInput[0].name);
            if(indicedoc>=0){
                this.nombredocumentocargar=this.uploadFileInput[0].name;
                this.existedocumento=true;
                $("#confirmarSobreescribirDocumento").modal('show');
                //console.log("existe");
            }else{
                this.existedocumento=false;
                this.cargarDocumento();
            }
        }
    }

    cargarDocumento(){
        this._datomaestroService.cargardocumento(this.token, this.ubicaciondocumentos, this.uploadFileInput).subscribe(
            response =>{
                console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    if (!this.existedocumento){
                        this.cargarDetalle();
                    }else{
                        $("#confirmarSobreescribirDocumento").modal('hide');
                    }
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                $("#liveToast").toast('show');

                this.myInputVariable.nativeElement.value = "";
                this.archivocargado = false;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    downloadDocumento(indiceDocumento: number){
        this._datomaestroService.downloaddocumento(this.token, this.ubicaciondocumentos, this.ingreso.adjuntos[indiceDocumento].documento).subscribe(
            response =>{
                if(response.codigo==200){
                    const linkSource = 'data:'+response.pathinfo+';base64,'+response.data;
                    const downloadLink = document.createElement("a");
                    const fileName = this.ingreso.adjuntos[indiceDocumento].documento;

                    downloadLink.href = linkSource;
                    downloadLink.download = fileName;
                    downloadLink.click();
                }else{
                    this.toast_mensaje="Ocurrio un error, intente mas tarde";
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    ventanaEliminarDocumento(indiceDocumento: number, event: any){
        this.indicedocumentoeliminar=indiceDocumento;
        $("#confirmarEliminarDocumento").modal('show');
        event.stopPropagation();
    }

    eliminarDocumento(){

        let documentoeliminar = this.ingreso.adjuntos[this.indicedocumentoeliminar].documento;
        this._datomaestroService.eliminardocumento(this.token, this.ubicaciondocumentos, documentoeliminar).subscribe(
            response =>{
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.cargarDetalle();
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );

        $('#confirmarEliminarDocumento').modal('hide');

    }

    cargarMasivamente(){
        $("#ventanaCargaMasiva").modal('hide');
        $('#ventanaLoading').modal('show');
        this._almacenService.ingresocargamasiva(this.token, this.idingreso, this.uploadFileInput).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    for(let dd=0;dd<response.xls_data.length;dd++){
                        this.errordetalle.push({
                            'errorcodigo': false,
                            'errorserie': false,
                            'errormodelo': false,
                            'errormarca': false,
                            'errorcolor': false,
                            'errorcantidad': false,
                            'erroridembalaje': false,
                            'errorpeso': false,
                            'errorbultos': false
                        });

                        this.marcadoAlmacen.push({
                            'ubicarenalmacen': false
                        });

                        this.ingreso.detalle.push({
                            'idingresodetalle': 0,
                            'codigo': response.xls_data[dd][0],
                            'serie': response.xls_data[dd][1],
                            'descripcion': response.xls_data[dd][2],
                            'centro_distribucion': response.xls_data[dd][3],
                            'categoria': response.xls_data[dd][4],
                            'cantidad': response.xls_data[dd][4],
                            'cantidad_saldo': response.xls_data[dd][5],
                            'idembalaje': response.xls_data[dd][6],
                            'codigoembalaje': '',
                            'lote': response.xls_data[dd][7],
                            'costo_un': response.xls_data[dd][8],
                            'idno_conf': response.xls_data[dd][9],
                            'idclasificacion': response.xls_data[dd][10],
                            'idmerma': response.xls_data[dd][11],
                            //'fechaproduccion': response.xls_data[dd][8],
                            'fechavencimiento': response.xls_data[dd][12],
                            'relacion_caja': response.xls_data[dd][13],
                            'volumen': 0,
                            'peso': response.xls_data[dd][14],
                            'pallet': '',
                            'peso_saldo': response.xls_data[dd][14],
                            'temperatura': response.xls_data[dd][15],
                            'iddoc_errada': null,
                            'doc_errada': '',
                            'observaciones': response.xls_data[dd][16],
                            'idalmacendetalle': 0,
                            'ubicacionalmacen': '',
                            'dividido': 0,
                            'factor_conversion': 1,
                            'codigoembalaje_salida': '',
                            'salidas': [],
                            'cantidadextraida': 0,
                            'pesoextraido': 0,
                            'bultosextraidos': 0,
                            'cantidad_no_confextraidos': 0,
                            'modelo': '',
                            'marca': '',
                            'color': '',
                            'partida_especifica': '',
                            'invoice': '',
                            'no_dim': '',
                            'fecha_pase_salida': null,
                            'accesorios_vehiculos': this.getAccesoriosVehiculos()
                        });
                    }

                    //this.ingreso.detalle=response.detalle;
                }else{
                    this.toast_tipo="Error";
                }

                this.gettotales();

                $('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');

                this.myInputVariable.nativeElement.value = "";
                this.archivocargado = false;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararAccesoriosVehciulos(indice: number){
        this.chasis = this.ingreso.detalle[indice].serie;
        this.modelo = this.ingreso.detalle[indice].modelo;
        this.color = this.ingreso.detalle[indice].color;
        this.ingresodetalles_accesorios_vehiculos = this.ingreso.detalle[indice].accesorios_vehiculos;
    }

    saveIngreso(reportrar: boolean){
        let error=false;
        this.erroridcliente=false;
        if (this.ingreso.idcliente==null || this.ingreso.idcliente==0){
            this.erroridcliente=true;
            error=true;
        }

        this.errorfecha=false;
        if (this.ingreso.fecha==null || this.ingreso.fecha==''){
            this.errorfecha=true;
            error=true;
        }
        this.errorfechasistema=false;
        if (this.ingreso.fechasistema==null || this.ingreso.fechasistema==''){
            this.errorfechasistema=true;
            error=true;
        }
        this.erroridusuario_recibido=false;
        if (this.ingreso.idusuario_recibido==null){
            this.erroridusuario_recibido=true;
            error=true;
        }
        this.erroridcliente_destino=false;
        if (this.inter_company && this.ingreso.idcliente_destino==null){
            this.erroridcliente_destino=true;
            error=true;
        }

        let erroresdetalle: boolean=false;
        for (let dd = 0; dd < this.ingreso.detalle.length; dd++){
            if ((this.ingreso.detalle[dd].codigo == null || this.ingreso.detalle[dd].codigo == '') && !this.ingreso.es_vehiculo){
                erroresdetalle=true;
                this.errordetalle[dd].errorcodigo=true;
            }
            if(this.ingreso.es_vehiculo){
                if (this.ingreso.detalle[dd].serie == null || this.ingreso.detalle[dd].serie == ''){
                    erroresdetalle=true;
                    this.errordetalle[dd].errorserie=true;
                }
                if (this.ingreso.detalle[dd].modelo == null || this.ingreso.detalle[dd].modelo == ''){
                    erroresdetalle=true;
                    this.errordetalle[dd].errormodelo=true;
                }
                if (this.ingreso.detalle[dd].marca == null || this.ingreso.detalle[dd].marca == ''){
                    erroresdetalle=true;
                    this.errordetalle[dd].errormarca=true;
                }
                if (this.ingreso.detalle[dd].color == null || this.ingreso.detalle[dd].color == ''){
                    erroresdetalle=true;
                    this.errordetalle[dd].errorcolor=true;
                }
            }
                
            if (this.ingreso.detalle[dd].cantidad < this.ingreso.detalle[dd].cantidadextraida){
                erroresdetalle=true;
                this.errordetalle[dd].errorcantidad=true;
            }
            if (this.ingreso.detalle[dd].idembalaje==null || this.ingreso.detalle[dd].idembalaje==0){
                erroresdetalle=true;
                this.errordetalle[dd].erroridembalaje=true;
            }
            if (this.ingreso.detalle[dd].peso < this.ingreso.detalle[dd].pesoextraido){
                erroresdetalle=true;
                this.errordetalle[dd].errorpeso=true;
            }
            /*
            if (this.ingreso.detalle[dd].bultos < this.ingreso.detalle[dd].bultosextraidos){
                erroresdetalle=true;
                this.errordetalle[dd].errorbultos=true;
            }
            if (this.ingreso.detalle[dd].cantidad_no_conf < this.ingreso.detalle[dd].cantidad_no_confextraidos){
                erroresdetalle=true;
                this.errordetalle[dd].errorcantidad_no_conf=true;
            }
            */
        }
        
        this.erroresmovimiento=[];

        if (!error && !this.erroridusuario_recibido && !erroresdetalle){
            console.log(this.ingreso);
            this._almacenService.guardaringreso(this.token, this.idingreso, this.ingreso).subscribe(
                response =>{
                    console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        
                        if (reportrar){
                            this._almacenService.reportarDelosiAPI(this.token, this.idingreso).subscribe(
                                response_api =>{
                                    //console.log(response_api);
                                    //respuesta=response_api;
                                    if(response_api['codigo']==200){
                                        this.toast_mensaje=response_api['response_array'].Data.Mensaje;
                                        if(response_api['response_array'].Data.OK){
                                            this.toast_tipo="Exito";
                                        }else{
                                            this.toast_tipo="Error";
                                        }
                                    }else{
                                        this.toast_tipo="Error";
                                        this.toast_mensaje=response_api['mensaje'];
                                    }

                                    $('#ventanaLoading').modal('hide');
                                    $("#liveToast").toast('show');

                                    this.cargarDetalle();
                                    this.cargarAlmacen();
                                },
                                error=>{
                                    console.log(<any>error);
                                    this.toast_tipo="Error";
                                    this.toast_mensaje="Ocurrio un problema";
                                    $("#liveToast").toast('show');
                                    $('#ventanaLoading').modal('hide');
                                }
                            );
                        }else{
                            this.toast_tipo="Exito";
                            this.cargarDetalle();
                            this.cargarAlmacen();
                            $("#liveToast").toast('show');
                        }
                    }else{
                        this.toast_tipo="Error";
                        this.erroresmovimiento=response.conmovimiento;
                        $("#liveToast").toast('show');
                        //this.cargarDetalle();
                    }
                    //this.ingreso.detalle=response.detalle;
                    
                    
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }else{
            this.toast_tipo="Error";
            this.toast_mensaje="Existen errores en el formulario";
            $("#liveToast").toast('show');
        }
    }
    
    duplicarIngreso(){
        this._almacenService.duplicaringreso(this.token, this.idingreso).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    //$('#nuevoIngreso').modal('hide');
                    this.abrirDetalleNuevo(response.idingreso);
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
    
    abrirDetalleNuevo(idingreso:number){
        let newRelativeUrl = this._router.createUrlTree(["/ingresos-detalle",idingreso]);
        let baseUrl = window.location.href.replace(this._router.url, '');

        window.open(baseUrl + newRelativeUrl, '_blank');
        
        //alert("abre en nueva pestaña " + idembarque);
        //event.stopPropagation();
    }
    
    confirmarAPI(){
        
        $("#confirmarAPI").modal('hide');
        $('#ventanaLoading').modal('show');
        
        this.saveIngreso(true);
        
        
        
    }

    verActaIngreso(){
        this._almacenService.downloadActaIngreso(this.token, this.idingreso).subscribe(
            response =>{
                if(response.codigo==200){
                    const byteCharacters = atob(response.data);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: response.pathinfo});
                    var url = window.URL.createObjectURL(blob);
                    const downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.target = "_blank";
                    downloadLink.click();
                }else{
                    this.toast_mensaje="Ocurrio un error, intente mas tarde";
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    verConstancia(){
        this._almacenService.downloadConstanciaIngreso(this.token, this.idingreso).subscribe(
            response =>{
                if(response.codigo==200){
                    const byteCharacters = atob(response.data);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: response.pathinfo});
                    var url = window.URL.createObjectURL(blob);
                    const downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.target = "_blank";
                    downloadLink.click();
                }else{
                    this.toast_mensaje="Ocurrio un error, intente mas tarde";
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    marcarItem(indice: number){
        this.marcadoAlmacen[indice].ubicarenalmacen=!this.marcadoAlmacen[indice].ubicarenalmacen;
    }

    ubicarEnAlmacen(idalmacendetalle: number, nombreUbicacion: string){
        let fila=null;
        let columna=null;
        for (let ff = 0; ff < this.almacen.detalle.length; ff++){
            for (let cc = 0; cc < this.almacen.detalle[ff].length; cc++){
                if(this.almacen.detalle[ff][cc].idalmacendetalle==idalmacendetalle){
                    fila=ff;
                    columna=cc;
                    break;
                }
            }
        }
        
        let ubicacion_unica = this.almacen.detalle[fila][columna].ubicacion_unica;
        
        let primer_ubicado=false;
        //console.log(this.marcadoAlmacen);
        for (let aa = 0; aa < this.marcadoAlmacen.length; aa++){
            if (this.marcadoAlmacen[aa].ubicarenalmacen){
                let ubicar=true;
                let idalmacendetalle_actual = this.ingreso.detalle[aa].idalmacendetalle;
                if(ubicacion_unica){
                    if(primer_ubicado){
                        ubicar=false;
                    }
                }
                if(ubicar){
                    this.ingreso.detalle[aa].idalmacendetalle=idalmacendetalle;
                    this.ingreso.detalle[aa].ubicacionalmacen=nombreUbicacion;
                    primer_ubicado=true;
                    if(ubicacion_unica){
                        this.almacen.detalle[fila][columna].habilitado=false;
                    }
                    this.almacen.detalle[fila][columna].color='#CCCCCC';
                }
                this.marcadoAlmacen[aa].ubicarenalmacen=false;
            }
        }
        this.marcarTodosAlmacen=false;
    }

    marcarTodos(){
        this.marcarTodosAlmacen = !this.marcarTodosAlmacen;
        for (let ma = 0; ma < this.marcadoAlmacen.length; ma++){
            this.marcadoAlmacen[ma].ubicarenalmacen=this.marcarTodosAlmacen;
        }

    }
}
