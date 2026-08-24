import { Component, OnInit, ViewChild, ElementRef, ChangeDetectorRef, AfterContentChecked } from '@angular/core';
import {formatDate} from '@angular/common';
import {Router, ActivatedRoute, Params} from '@angular/router';
import { from, Observable } from 'rxjs';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {SalidaModel} from '../models/salida.model';
import {SalidaDetalleModel} from '../models/salidaDetalle.model';
//import {GLOBAL} from './../global';

declare var $: any;

interface AutoCompleteCompleteEvent {
    originalEvent: Event;
    query: string;
}

@Component({
    selector: 'app-salidas-detalle',
    templateUrl: './salidas-detalle.component.html',
    styleUrls: ['./salidas-detalle.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService]
})
export class SalidasDetalleComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public idsalida: string;
    public salida: SalidaModel;
    
    public habilitar_finalizado: boolean=false;
    
    public error_contrato_no: boolean=false;

    public entidades: Array<any>;
    public clientebloqueado: boolean=false;

    public inventario: Array<any>;
    public usuarios: Array<any>;

    public pedidos: Array<any>;
    
    public ciudades: Array<any>;
    public ciudades_mostrar: Array<any>;
    public almacenes: Array<any>;
    public almacenes_mostrar: Array<any>=[];

    public erroridcliente: boolean=false;
    public errorfecha: boolean=false;
    public erroridusuario_entrega: boolean=false;
    public errorfecha_recibido: boolean=false;
    public listaSolicitantes: string[]=[];
    public listaAutorizantes: string[]=[];
    public movilizadores: Array<any>=[];
    
    public erroresfinalizar: Array<any>;
    public itemsexcluidos: Array<any>=[];
    
    public tipospedido:Array<any>;
    public tipospedidoFiltrado:Array<any>;

    public agregarbloqueado: boolean=true;
    public filasagregar: number=1;
    public indicedetalleeliminar: number;

    public cantidadtotal: number= 0;
    public bultostotal: number= 0;
    public pesototal: number= 0;
    public cantidad_no_conftotal: number= 0;
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
    
    public errorpartida: boolean=false;
    
    public accesorios_visible: boolean=false;
    public accesorios_vehiculos: Array<any>=[];
    public indiceDetalle: number;
    public chasis: string='';
    public modelo: string='';
    public marca: string='';
    public color: string='';
    
    public kilometraje: string='';
    public tiene_danios: boolean=false;
    public danios: string='';
    public tiene_faltante: boolean=false;
    public faltante: string='';
    
    videoDevices: MediaDeviceInfo[] = [];
    selectedDeviceId: string = '';
    
    private stream: MediaStream | null = null;
    private currentVideo: HTMLVideoElement | null = null;
    private currentCanvas: HTMLCanvasElement | null = null;
    private currentContext: CanvasRenderingContext2D | null = null;
    
    private videoTrack?: MediaStreamTrack;
    public isTorchSupported = false;
    
    mostrarVideo = false;
    mostrarCanvas = false;
    mostrarGuardarCancelar = false;

    botonIniciarHabilitado = true;
    botonFotoHabilitado = false;
    botonDetenerHabilitado = false;
    
    public torchOn = false;
    
    public images: any[] | undefined=[];
    responsiveOptions: any[] | undefined;
    
    private async ensureVideoReady(video: HTMLVideoElement): Promise<void> {
        if (video.readyState >= 2) return; // HAVE_CURRENT_DATA
        await new Promise<void>((resolve) => {
          const onLoaded = () => {
            video.removeEventListener('loadeddata', onLoaded);
            resolve();
          };
          video.addEventListener('loadeddata', onLoaded, { once: true });
        });
    }
    
    private drawContain(video: HTMLVideoElement, canvas: HTMLCanvasElement) {
        const vw = video.videoWidth || 1280;
        const vh = video.videoHeight || 720;

        const { maxW, maxH } = this.getPreviewTargetSize(1280, 1280);
        const scale = Math.min(maxW / vw, maxH / vh);

        const dw = Math.max(1, Math.round(vw * scale));
        const dh = Math.max(1, Math.round(vh * scale));

        // El buffer del canvas será del tamaño escalado (no gigante)
        canvas.width  = dw;
        canvas.height = dh;

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        // Limpia y dibuja escalado sin recorte
        ctx.clearRect(0, 0, dw, dh);
        ctx.drawImage(
          video,
          /* src */ 0, 0, vw, vh,
          /* dst */ 0, 0, dw, dh
        );
    }
    
    private getPreviewTargetSize(maxLogicalWidth = 1024, maxLogicalHeight = 1024) {
        // Limita por viewport para no pasarte en móviles
        const dpr = window.devicePixelRatio || 1;
        const maxW = Math.min(window.innerWidth * dpr,  maxLogicalWidth);
        const maxH = Math.min(window.innerHeight * dpr, maxLogicalHeight);
        return { maxW: Math.round(maxW), maxH: Math.round(maxH) };
    }
    
    public procesando: boolean=false;

    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_salidas: boolean=false;
    public editar_salidas: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenService: AlmacenesService,
        private _entidadService: EntidadesService,
        private _route: ActivatedRoute,
        private changeDetector: ChangeDetectorRef,
        ) {
            this._route.params.forEach((params: Params)=>{
                this.idsalida = params["idsalida"];
            });
            this.token = this._usuarioService.getToken();
            this.tokenDetalle = this._usuarioService.getTokenDetalle();
            if(this.tokenDetalle.idtipousuario==1){
                this.ver_salidas=true;
                this.editar_salidas=true;
                this.habilitar_finalizado=true;
            }else{
                let indiceVerSalidas = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 22);
                if (indiceVerSalidas>=0){
                    if (this.tokenDetalle.permisos[indiceVerSalidas].lectura){
                        this.ver_salidas=true;
                    }
                    if (this.tokenDetalle.permisos[indiceVerSalidas].escritura){
                        this.editar_salidas=true;
                    }
                }
                let indiceHabilitarFinalizado = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 11);
                if(indiceHabilitarFinalizado>=0){
                    this.habilitar_finalizado=true;
                }
            }
                
            
            this.erroresfinalizar=[];
            this.ubicaciondocumentos = 'almacen/adjuntos_salidas/' + this.idsalida;
    }

    ngOnInit(): void {
        this.responsiveOptions = [
            {
                breakpoint: '1024px',
                numVisible: 5
            },
            {
                breakpoint: '768px',
                numVisible: 3
            },
            {
                breakpoint: '560px',
                numVisible: 1
            }
        ];
        
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                //console.log(response.entidades);

                //this.entidades=response.entidades;
                this.entidades = response.clientes;
                this.verSalida();
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
                this.ciudades_mostrar=response_ciudades.ciudades.map(a => a.ciudad);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.almacenes=[];
        this._almacenService.veralmacenes(this.token).subscribe(
            response =>{
                this.almacenes=response.almacenes;
                this.almacenes.forEach(o => {
                        const partes = [o.codigo_almacen, o.almacen]
                          .filter(v => v != null && String(v).trim() !== "");
                        o.codigo_y_almacen = partes.join(" ") || "";
                });
                //this.setAlmacenes();
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

        
        
        this._datomaestroService.tipospedido(this.token).subscribe(
            response =>{
                this.tipospedido=response.tipospedido;
                //console.log(this.tipospedido);

            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
        
        
        
        
    }
    
    setAlmacenes(){
        this.almacenes_mostrar=[];
        //this.salida.idalmacen_destino=null;
        let indiceCiudad = this.ciudades.findIndex(x => (x.ciudad === this.salida.ciudad));
        
        
        if (indiceCiudad>=0){
            this.almacenes_mostrar = this.almacenes.filter(almacen => (almacen.idciudad == this.ciudades[indiceCiudad].idciudad));
        }else{
            this.almacenes_mostrar = this.almacenes;
        }
        
        //console.log(this.almacenes_mostrar);
        
        
    }
    
    verSalida(){
        this._almacenService.versalida(this.token, this.idsalida).subscribe(
            response =>{
                //console.log(response.salida);
                this.salida=response.salida;
                console.log(this.salida);
                this.verificarDetalle();
                this.getInventario();
                this.gettotales();
                this.cargarPedidos();
                this.cargarSolicitantes();
                this.cargarMovilizadores();
                this.cargarAccesorios();
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    filterTipoPedido(event: AutoCompleteCompleteEvent) {
        let filtered: any[] = [];
        let query = event.query;

        for (let i = 0; i < this.tipospedido.length; i++) {
            let country = this.tipospedido[i];
            if (country.toLowerCase().indexOf(query.toLowerCase()) == 0) {
                filtered.push(country);
            }
        }

        this.tipospedidoFiltrado = filtered;
    }
    
    cargarPedidos(){
        this.pedidos=[];
        if (this.salida.idcliente!=null){
            this._almacenService.verpedidos(this.token, this.salida.idcliente).subscribe(
                response =>{

                    let pedidos=response.pedidos;
                    for(let pp=0;pp<pedidos.length;pp++){
                        let agregar=false;
                        if (pedidos[pp].tipo_pedido != 2){
                            if(pedidos[pp].salidas.length==0){
                                agregar=true;
                            }else{
                                for(let ss=0;ss<pedidos[pp].salidas.length;ss++){
                                    if (pedidos[pp].salidas[ss].idsalida_cif == this.idsalida){
                                        agregar=true;
                                    }
                                }
                            }
                        }
                        if(agregar){
                            this.pedidos.push({
                                idpedido: pedidos[pp].idpedido,
                                idcliente: pedidos[pp].idcliente,
                                numero: pedidos[pp].numero,
                                gestion: pedidos[pp].gestion,
                                salidas: pedidos[pp].salidas,
                                no_pedido: pedidos[pp].no_pedido
                            });
                        }
                    }

                    //this.pedidos=response.pedidos;
                    //console.log(this.pedidos);

                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
            
    }
    
    cambioFechaSalida(){
        this.errorfecha=false;
        this.salida.fecha_recibido = this.salida.fecha;
    }
    
    ngAfterContentChecked(): void {
        this.changeDetector.detectChanges();
    }

    getInventario(){
        this.agregarbloqueado=true;
        this.itemsexcluidos=[];
        this._almacenService.inventario(this.token, this.salida.idcliente,this._usuarioService.getCurrentDateFilterValue(),false).subscribe(
            response =>{
                
                this._entidadService.vernoconfnoconsiderar(this.token, this.salida.idcliente).subscribe(
                    response_no_conf =>{
                        //console.log(response_no_conf);
                        let no_considerar=response_no_conf.no_considerar;
                        if(this.salida.es_no_conf){
                            this.inventario = response.inventario.filter(function(cc){
                                return ((cc.cantidad>0 || cc.peso>0) && no_considerar.includes(cc.idno_conf));
                            });
                        }else{
                            this.inventario = response.inventario.filter(function(cc){
                                return ((cc.cantidad>0 || cc.peso>0) && !no_considerar.includes(cc.idno_conf));
                            });
                        }
                        
                        this.inventario.filter(inventario => inventario.fechavencimiento!=null).forEach(
                            inventario => (inventario.fechavencimiento = new Date(inventario.fechavencimiento.replace(/-/g, '\/')))
                        );
                        this.inventario.forEach(object => {
                            object.inactivo = false;
                            object.fechaingreso = new Date(object.fechaingreso.replace(/-/g, '\/'));
                        });
                        this.verificarDetalle();
                        this.agregarbloqueado=false;
                        console.log(this.inventario);
                    },
                    error_no_conf=>{
                        console.log(<any>error_no_conf)
                    }
                );
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    verificarDetalle(){
        if (this.salida.detalle.length > 0){
            this.clientebloqueado=true;
        }else{
            if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
                this.clientebloqueado=true;
            }else{
                this.clientebloqueado=false;
            }
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

                    let ultimodato = Math.min(copiado.length - 1, this.salida.detalle.length);
                    for(let ii=0; ii<ultimodato; ii++){
                        if(campo=='codigo'){
                            let indiceInventario=-1;
                            switch(copiado[ii].length){
                                case 1:
                                    indiceInventario = this.inventario.findIndex(x => (x.codigo === copiado[ii][0] && !x.inactivo));
                                    break;
                                case 2:
                                    indiceInventario = this.inventario.findIndex(x => (x.codigo === copiado[ii][0] && x.lote === copiado[ii][1] && !x.inactivo));
                                    break;
                                case 3:
                                    indiceInventario = this.inventario.findIndex(x => (x.codigo === copiado[ii][0] && x.lote === copiado[ii][1] && x.ubicacionalmacen === copiado[ii][2] && !x.inactivo));
                                    break;
                            }
                            /*
                            if (copiado[ii].length>1){
                                indiceInventario = this.inventario.findIndex(x => (x.codigo === copiado[ii][0] && x.lote === copiado[ii][1]));
                            }else{
                                indiceInventario = this.inventario.findIndex(x => (x.codigo === copiado[ii][0]));
                            }
                            */
                            //console.log(indiceInventario);
                            if(indiceInventario>=0){
                                this.salida.detalle[ii].idingresodetalle = this.inventario[indiceInventario].idingresodetalle;
                                this.salida.detalle[ii].codigo=this.inventario[indiceInventario].codigo;
                                this.salida.detalle[ii].lote=this.inventario[indiceInventario].lote;
                                this.salida.detalle[ii].no_conf=this.inventario[indiceInventario].no_conf;
                                this.salida.detalle[ii].merma=this.inventario[indiceInventario].merma;
                                this.salida.detalle[ii].relacion_caja=this.inventario[indiceInventario].relacion_caja;
                                this.salida.detalle[ii].ubicacionalmacen=this.inventario[indiceInventario].ubicacionalmacen;
                                this.inventario[indiceInventario].inactivo=true;
                                this.verificarCodigo(ii);
                            }else{
                                copiado[ii][0] = null;
                            }
                        }else{
                            this.salida.detalle[ii][campo]=copiado[ii][0];
                        }
                    }
                }
                //console.log(this.salida.detalle);
            }
        ).catch(error => {
            console.error('Cannot read clipboard text: ', error);
        });
    }

    verificarCliente(){
        this.erroridcliente=false;
        this.salida.idpedido=null;
        if (this.salida.idcliente==null){
            this.salida.cliente='';
            this.inventario=[];
        }else{
            let indiceCliente = this.entidades.findIndex(x => x.id === this.salida.idcliente);
            this.salida.cliente = this.entidades[indiceCliente].entidad;
            this.getInventario();
        }
        this.cargarPedidos();
        this.cargarSolicitantes();
        this.cargarMovilizadores();
    }
    
    cargarSolicitantes(){
        this.listaSolicitantes=[];
        if (this.salida.idcliente){
            this._datomaestroService.solicitantes(this.token, this.salida.idcliente).subscribe(
                response =>{
                    this.listaSolicitantes=response.solicitantes.map(a => a.nombre);
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
            
    }
    
    cargarMovilizadores(){
        //this.salida.idmovilizador=null;
        this.movilizadores=[];
        if (this.salida.idcliente){
            this._datomaestroService.movilizadores(this.token, this.salida.idcliente).subscribe(
                response =>{
                    this.movilizadores=response.movilizadores;
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
            
    }

    cargarAccesorios(){
        this.accesorios_vehiculos=[];
        let indiceCliente = this.entidades.findIndex(x => x.id === this.salida.idcliente);
        console.log(this.salida.idcliente);
        console.log(this.entidades);
        if(indiceCliente>=0){
            this._datomaestroService.accesorios_vehiculos(this.token, this.entidades[indiceCliente].id_num).subscribe(
                response =>{
                    this.accesorios_vehiculos=response.accesorios_vehiculos;
                    console.log(this.accesorios_vehiculos);

                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
        
    }
    
    cambiaPedido(){
        let indicepedido = this.pedidos.findIndex(x => x.idpedido === this.salida.idpedido);
        if(indicepedido>=0){
            this.salida.delivery_note = this.pedidos[indicepedido].no_pedido;
        }
        this.getInventario();
    }
    
    buscarRefrenciaSalida(){
        this.error_contrato_no=false;
        if (this.salida.contrato_no.length<3){
            this.error_contrato_no=true;
        }
        if(!this.error_contrato_no){
            this._datomaestroService.referencia_salida(this.token, this.salida.idcliente, this.salida.contrato_no).subscribe(
                response =>{
                    let referencia_salida=response.referencia_salida;
                    //console.log(referencia_salida.idreferencia_salida);
                    //console.log(referencia_salida);
                    if(referencia_salida.idreferencia_salida>0){
                        this.salida.proyecto_no=referencia_salida.proyecto_no;
                        this.salida.solicitado_por=referencia_salida.solicitado_por;
                        this.salida.autorizado_por=referencia_salida.autorizado_por;
                        this.salida.rubro_producto=referencia_salida.rubro_producto;
                        this.salida.ciudad=referencia_salida.ciudad;
                        this.salida.direccion_entrega=referencia_salida.direccion_entrega;
                        this.salida.transporte=referencia_salida.transporte;
                        this.salida.placa=referencia_salida.placa;
                        this.salida.hora_inicio_a=referencia_salida.hora_inicio_a;
                        this.salida.hora_fin_a=referencia_salida.hora_fin_a;
                        this.salida.hora_inicio_b=referencia_salida.hora_inicio_b;
                        this.salida.hora_fin_b=referencia_salida.hora_fin_b;
                        this.salida.empresa_recibido=referencia_salida.empresa_recibido;
                        this.salida.tipo_pedido=referencia_salida.tipo_pedido;
                        
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
        
            
    }
    
    buscarItemsPartida(){
        this.errorpartida=false;
        if (this.salida.partida.length<4){
            this.errorpartida=true;
        }
        if(!this.errorpartida){
            let indice_detalle=-1;
            for (let ii = 0; ii < this.inventario.length; ii++){
                if (this.inventario[ii].partida == this.salida.partida){
                    this.filasagregar=1;
                    this.agregarFila();
                    
                    this.salida.detalle[this.salida.detalle.length - 1].idingresodetalle = this.inventario[ii].idingresodetalle;
                    this.verificarCodigo(this.salida.detalle.length - 1);
                    
                }
                
                //verificarCodigo(indiceDetalle: number)
            }
        }
    }

    public getDataFromClipBoard(): void {
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
                }

            }
        ).catch(error => {
            console.error('Cannot read clipboard text: ', error);
        });
    }

    gettotales(){
        this.cantidadtotal=0;
        this.bultostotal=0;
        this.pesototal=0;
        this.cantidad_no_conftotal=0;
        this.cantidadsalidatotal=0;
        for (let i = 0; i < this.salida.detalle.length; i++){
            this.cantidadtotal = this.cantidadtotal + this.salida.detalle[i].cantidad;
            this.bultostotal = this.bultostotal + this.salida.detalle[i].bultos;
            this.pesototal = this.pesototal + this.salida.detalle[i].peso;
            this.cantidadsalidatotal=this.cantidadsalidatotal+this.salida.detalle[i].cantidad/this.salida.detalle[i].factor_conversion;
            //this.cantidad_no_conftotal = this.cantidad_no_conftotal + this.salida.detalle[i].cantidad_no_conf;
        }
    }

    agregarFila(){
        this.erroresfinalizar=[];
        for (let fa = 1; fa <= this.filasagregar; fa++){
            this.salida.detalle.push({
                idsalidadetalle: 0,
                idsalida: this.idsalida,
                idingresodetalle: null,
                codigo: '',
                serie: '',
                categoria: '',
                descripcion: '',
                centro_distribucion: '',
                fechavencimiento: null,
                diasavencer: null,
                lote: '',
                costo_un: 0,
                cantidad_no_conf: 0,
                cantidad_no_confactual: 0,
                no_conf: '',
                merma: '',
                relacion_caja: '',
                factor_conversion: 1,
                cantidad: 0,
                cantidadactual: 0,
                idembalaje: 0,
                embalaje: '',
                codigoembalaje: '',
                codigoembalaje_salida: '',
                peso: 0,
                pesoactual: 0,
                bultos: 0,
                bultosactual: 0,
                temperatura: '',
                modelo: '',
                marca: '',
                color_vehiculo: '',
                observaciones: '',
                ubicacionalmacen: '',
                numeroingreso: '',
                centro_rubro: '',
                kilometraje: '',
                tiene_danios: false,
                danios: '',
                tiene_faltante: false,
                faltante: '',
                imagenes: 0,
                con_accesorios: false,
                accesorios_vehiculos: []
            });
        }
        this.verificarDetalle();
    }

    verificarAccesorios(indice: number){
        if(this.salida.detalle[indice].kilometraje || this.salida.detalle[indice].tiene_danios || this.salida.detalle[indice].tiene_faltante || this.salida.detalle[indice].accesorios_vehiculos.length>0 || this.salida.detalle[indice].imagenes>0){
            return true;
        }
        return false;
    }

    cargarMasivamente(){

    }

    eliminarTodos(){
        this.erroresfinalizar=[];
        this.salida.detalle.splice(0, this.salida.detalle.length);
        this.gettotales();
        $('#confirmarEliminarTodos').modal('hide');
        this.verificarDetalle();
    }

    eliminarDetalle(){
        this.erroresfinalizar=[];
        this.salida.detalle.splice(this.indicedetalleeliminar, 1);
        this.gettotales();
        $('#confirmarEliminarDetalle').modal('hide');
        this.verificarDetalle();
    }

    verificarCI(){
        this.erroridusuario_entrega=false;
        if (this.salida.idusuario_entrega>0){
            let indiceUsuario = this.usuarios.findIndex(x => x.idusuario === this.salida.idusuario_entrega);
            this.salida.ci_entrega = this.usuarios[indiceUsuario].ci;
        }else{
            this.salida.ci_entrega='';
        }
    }

    verificarCodigo(indiceDetalle: number){
        this.erroresfinalizar=[];
        let indiceProducto = this.inventario.findIndex(x => x.idingresodetalle === this.salida.detalle[indiceDetalle].idingresodetalle);
        if(indiceProducto>=0){
            this.salida.detalle[indiceDetalle].codigo = this.inventario[indiceProducto].codigo;
            this.salida.detalle[indiceDetalle].serie = this.inventario[indiceProducto].serie;
            this.salida.detalle[indiceDetalle].categoria = this.inventario[indiceProducto].categoria;
            this.salida.detalle[indiceDetalle].descripcion = this.inventario[indiceProducto].descripcion;
            this.salida.detalle[indiceDetalle].centro_distribucion = this.inventario[indiceProducto].centro_distribucion;
            this.salida.detalle[indiceDetalle].cantidad = this.inventario[indiceProducto].cantidad;
            this.salida.detalle[indiceDetalle].cantidadactual = this.inventario[indiceProducto].cantidad;
            this.salida.detalle[indiceDetalle].codigoembalaje = this.inventario[indiceProducto].codigoembalaje;
            this.salida.detalle[indiceDetalle].lote = this.inventario[indiceProducto].lote;
            this.salida.detalle[indiceDetalle].costo_un = this.inventario[indiceProducto].costo_un;
            this.salida.detalle[indiceDetalle].cantidad_no_conf = this.inventario[indiceProducto].cantidad_no_conf;
            this.salida.detalle[indiceDetalle].cantidad_no_confactual = this.inventario[indiceProducto].cantidad_no_conf;
            this.salida.detalle[indiceDetalle].no_conf = this.inventario[indiceProducto].no_conf;
            this.salida.detalle[indiceDetalle].merma = this.inventario[indiceProducto].merma;
            this.salida.detalle[indiceDetalle].relacion_caja = this.inventario[indiceProducto].relacion_caja;
            this.salida.detalle[indiceDetalle].fechavencimiento = this.inventario[indiceProducto].fechavencimiento;
            this.salida.detalle[indiceDetalle].diasavencer = this.inventario[indiceProducto].diasavencer;
            //this.salida.detalle[indiceDetalle].bultos = this.inventario[indiceProducto].bultos;
            //this.salida.detalle[indiceDetalle].bultosactual = this.inventario[indiceProducto].bultos;
            this.salida.detalle[indiceDetalle].bultos = 0;
            this.salida.detalle[indiceDetalle].bultosactual = 0;
            this.salida.detalle[indiceDetalle].peso = this.inventario[indiceProducto].peso;
            this.salida.detalle[indiceDetalle].pesoactual = this.inventario[indiceProducto].peso;
            this.salida.detalle[indiceDetalle].temperatura = this.inventario[indiceProducto].temperatura;
            this.salida.detalle[indiceDetalle].modelo = this.inventario[indiceProducto].modelo;
            this.salida.detalle[indiceDetalle].marca = this.inventario[indiceProducto].marca;
            this.salida.detalle[indiceDetalle].color_vehiculo = this.inventario[indiceProducto].color_vehiculo;
            this.salida.detalle[indiceDetalle].numeroingreso = this.inventario[indiceProducto].numeroingreso;
            this.salida.detalle[indiceDetalle].ubicacionalmacen = this.inventario[indiceProducto].ubicacionalmacen;
            this.salida.detalle[indiceDetalle].factor_conversion = this.inventario[indiceProducto].factor_conversion;
            this.salida.detalle[indiceDetalle].codigoembalaje_salida = this.inventario[indiceProducto].codigoembalaje_salida;

        }else{
            this.salida.detalle[indiceDetalle].serie = '';
            this.salida.detalle[indiceDetalle].descripcion = '';
            this.salida.detalle[indiceDetalle].centro_distribucion = '';
            this.salida.detalle[indiceDetalle].numeroingreso = '';
            this.salida.detalle[indiceDetalle].factor_conversion = 1;
            this.salida.detalle[indiceDetalle].codigoembalaje_salida = '';
        }
        this.gettotales();
    }

    editarSalida(indiceDetalle: number){
        this.inventario.forEach(object => {
            object.inactivo = false;
        });
        for (let sd = 0; sd < this.salida.detalle.length; sd++){
            if(sd!==indiceDetalle){
                let indiceInventario = this.inventario.findIndex(x => (x.idingresodetalle === this.salida.detalle[sd].idingresodetalle));
                if(indiceInventario>=0){
                    this.inventario[indiceInventario].inactivo=true;
                }
            }
        }
    }
    
    prepararAccesoriosVehciulos(indiceDetalle: number){
        this.chasis = this.salida.detalle[indiceDetalle].serie;
        this.modelo = this.salida.detalle[indiceDetalle].modelo;
        this.marca = this.salida.detalle[indiceDetalle].marca;
        this.color = this.salida.detalle[indiceDetalle].color_vehiculo;
        this.kilometraje = this.salida.detalle[indiceDetalle].kilometraje;
        this.tiene_danios = this.salida.detalle[indiceDetalle].tiene_danios;
        this.danios = this.salida.detalle[indiceDetalle].danios;
        this.tiene_faltante = this.salida.detalle[indiceDetalle].tiene_faltante;
        this.faltante = this.salida.detalle[indiceDetalle].faltante;
        this.indiceDetalle=indiceDetalle;
        this.images=[];
        for (let aa = 0; aa < this.accesorios_vehiculos.length; aa++){
            let indiceAccesorio = this.salida.detalle[indiceDetalle].accesorios_vehiculos.findIndex(x => (x.idaccesorios_vehiculos == this.accesorios_vehiculos[aa].idaccesorios_vehiculos));
            if(indiceAccesorio>=0){
                this.accesorios_vehiculos[aa].marcado=true;
                this.accesorios_vehiculos[aa].cantidad = this.salida.detalle[indiceDetalle].accesorios_vehiculos[indiceAccesorio].cantidad;
                this.accesorios_vehiculos[aa].texto = this.salida.detalle[indiceDetalle].accesorios_vehiculos[indiceAccesorio].texto;
            }else{
                this.accesorios_vehiculos[aa].marcado=false;
                this.accesorios_vehiculos[aa].cantidad = 0;
                this.accesorios_vehiculos[aa].texto = '';
            }
        }
        
        this._almacenService.verimagenessalidadetalle(this.token, this.salida.detalle[this.indiceDetalle].idsalidadetalle).subscribe(
            response =>{
                //console.log(response);
                //console.log(response);
                this.images=response.imagenes;
            },
            error=>{
                console.log(<any>error)
                
            }
        );
        
        
        
        this.accesorios_visible=true;
    }

    verificarCamara(){
        if(this.indiceDetalle>=0){
            if(!this.salida.finalizado && this.editar_salidas && this.salida.detalle[this.indiceDetalle].idsalidadetalle>0){
                return true;
            }
        }
        
        return false;
    }
    
    aceptarAccesorios(){
        //console.log(this.images);
        this.salida.detalle[this.indiceDetalle].kilometraje = this.kilometraje;
        this.salida.detalle[this.indiceDetalle].accesorios_vehiculos = this.accesorios_vehiculos.filter(accesorios => accesorios.marcado);
        this.salida.detalle[this.indiceDetalle].tiene_danios=this.tiene_danios;
        if(this.tiene_danios){
            this.salida.detalle[this.indiceDetalle].danios=this.danios;
        }else{
            this.salida.detalle[this.indiceDetalle].danios="";
        }
        this.salida.detalle[this.indiceDetalle].tiene_faltante = this.tiene_faltante;
        if (this.tiene_faltante){
            this.salida.detalle[this.indiceDetalle].faltante = this.faltante;
        }else{
            this.salida.detalle[this.indiceDetalle].faltante="";
        }
        this.accesorios_visible=false;
        
        this.detenerCamara();
        const images = this.images.filter(im => im.idsalidadetalleimagen == 0);
        
        if (images.length>0){
            let payload={
                imagenes: images
            };
            this.procesando=true;
            this._almacenService.agregarimagenessalidaaccesorios(this.token, this.salida.detalle[this.indiceDetalle].idsalidadetalle, payload).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        //this.cargarInventarioFisicoConteo();
                    }else{
                        this.toast_tipo="Error";
                    }
                    this.procesando=false;
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                    this.procesando=false;
                }
            );
        }
            
        
        
    }
    
    cambiarCamara(video: HTMLVideoElement, canvas: HTMLCanvasElement): void {
        if (!this.selectedDeviceId) {
          console.warn('No hay cámara seleccionada.');
          return;
        }

        this.iniciarCamara(video, canvas);
    }
    
    iniciarCamara(video: HTMLVideoElement, canvas: HTMLCanvasElement): void {
        if (this.stream) {
          this.detenerCamara();
        }

        this.currentVideo = video;
        this.currentCanvas = canvas;
        this.currentContext = canvas.getContext('2d');
        
        this.currentVideo.setAttribute('playsinline', 'true');
        this.currentVideo.setAttribute('webkit-playsinline', 'true');
        this.currentVideo.muted = true;

        this.getCameraStream(this.selectedDeviceId).subscribe({
          next: async (stream: MediaStream) => {
            this.stream = stream;
            this.currentVideo!.srcObject = stream;
            await this.ensureVideoReady(this.currentVideo!);
            await this.currentVideo!.play();
            
            //this.currentVideo!.play();
            
            const [track] = stream.getVideoTracks();
            this.videoTrack = track;
            const caps = (track.getCapabilities && track.getCapabilities()) as any;
            this.isTorchSupported = !!(caps && caps.torch);
            
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                this.videoDevices = devices.filter(d => d.kind === 'videoinput');
            } catch {}
            
            this.mostrarVideo = true;
            this.mostrarCanvas = false;
            this.mostrarGuardarCancelar = false;

            this.botonIniciarHabilitado = false;
            this.botonFotoHabilitado = true;
            this.botonDetenerHabilitado = true;
          },
          error: (err) => {
            console.error('Error al acceder a la cámara', err);
          }
        });
    }
    
    detenerCamara(): void {
        if (this.stream) {
            
            if(this.torchOn){
                this.videoTrack.applyConstraints({ advanced: [{ torch: false }] } as any).catch(()=>{});
            }
            
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;

            if (this.currentVideo) {
                this.currentVideo.srcObject = null;
            }
            
            this.mostrarVideo = false;
            this.mostrarCanvas = false;
            this.mostrarGuardarCancelar = false;

            this.botonIniciarHabilitado = true;
            this.botonFotoHabilitado = false;
            this.botonDetenerHabilitado = false;

            this.currentVideo = null;
            this.currentCanvas = null;
            this.currentContext = null;

            console.log('Cámara detenida');
        }
    }
    
    getCameraStream(deviceId?: string): Observable<MediaStream> {
        const baseVideo: MediaTrackConstraints = {
            width:  { ideal: 1280 },
            height: { ideal: 720 }
        };
        
        const primaryVideo: MediaTrackConstraints = deviceId
            ? { ...baseVideo, deviceId: { exact: deviceId } as any }
            : { ...baseVideo, facingMode: { ideal: 'environment' } };
            
        const primary = navigator.mediaDevices.getUserMedia({
            video: primaryVideo,
            audio: false
        });
        
        const fallback1 = () =>
            navigator.mediaDevices.getUserMedia({
              video: { ...baseVideo, facingMode: { ideal: 'environment' } },
              audio: false
        });

          // Fallback #2: si la trasera no aparece, probamos la frontal como último recurso
        const fallback2 = () =>
            navigator.mediaDevices.getUserMedia({
              video: { ...baseVideo, facingMode: { ideal: 'user' } },
              audio: false
        });

        return from(
            primary.catch(() =>
              fallback1().catch(() => fallback2())
            )
        );
        
        /*
        const constraints = deviceId
          ? { video: { deviceId: { exact: deviceId } }, audio: false }
          : { video: true, audio: false };

        return from(navigator.mediaDevices.getUserMedia(constraints));
        */
    }
    
    cancelarFoto(): void {
        // Volver al modo cámara
        this.mostrarVideo = true;
        this.mostrarCanvas = false;
        this.mostrarGuardarCancelar = false;

        this.botonFotoHabilitado = true;
        this.botonDetenerHabilitado = true;
    }
    
    guardarImagen(): void {
        const context = this.currentCanvas?.getContext('2d');
        if (context) {
            const image = this.currentCanvas!.toDataURL('image/png');
            const nuevaImagen = {
                idsalidadetalleimagen: 0,
                itemImageSrc: image,
                thumbnailImageSrc: null,
                alt: 'Imagen nueva',
                title: 'Miniatura'
            };
            this.images = [...this.images, nuevaImagen]; // ahora sí funciona

        }

        // Volver al modo cámara
        this.mostrarVideo = true;
        this.mostrarCanvas = false;
        this.mostrarGuardarCancelar = false;

        this.botonFotoHabilitado = true;
        this.botonDetenerHabilitado = true;
    }
    
    async takePhoto(video: HTMLVideoElement, canvas: HTMLCanvasElement): Promise<void> {
        await this.ensureVideoReady(video);

        this.drawContain(video, canvas);
        
        this.mostrarVideo = false;
        this.mostrarCanvas = true;
        this.mostrarGuardarCancelar = true;

        this.botonFotoHabilitado = false;
        this.botonDetenerHabilitado = false;
    }
    
    async toggleTorch(): Promise<void> {
        if (!this.videoTrack) { return; }
        
        const caps = (this.videoTrack.getCapabilities && this.videoTrack.getCapabilities()) as any;
        if (!caps || !('torch' in caps) || !caps.torch) {
            console.warn('Torch no soportado por esta cámara / dispositivo.');
            return;
        }

        this.torchOn = !this.torchOn;
        
        try {
            // Camino estándar y más compatible
            await (this.videoTrack as any).applyConstraints({ advanced: [{ torch: this.torchOn }] });
            console.log(`Torch ${this.torchOn ? 'encendido' : 'apagado'}`);
        } catch (e1) {
            // Algunos navegadores aceptan la propiedad plana
            try {
              await (this.videoTrack as any).applyConstraints({ torch: this.torchOn } as any);
              console.log(`Torch ${this.torchOn ? 'encendido' : 'apagado'} (fallback)`);
            } catch (e2) {
              // Si falla, revertimos estado y avisamos
              this.torchOn = !this.torchOn;
              console.warn('No se pudo cambiar el estado del flash', e2);
            }
        }
    }

    saveSalida(){
        this.erroresfinalizar=[];

        this.erroridcliente=false;
        if (this.salida.idcliente==null){
            this.erroridcliente=true;
        }

        this.errorfecha=false;
        if (this.salida.fecha == null || this.salida.fecha==''){
            this.errorfecha=true;
        }
        this.erroridusuario_entrega=false;
        if (this.salida.idusuario_entrega==null){
            this.erroridusuario_entrega=true;
        }
        this.errorfecha_recibido=false;
        if (this.salida.fecha_recibido == null || this.salida.fecha_recibido==''){
            this.errorfecha_recibido=true;
        }

        if (!this.erroridcliente && !this.errorfecha && !this.erroridusuario_entrega && !this.errorfecha_recibido){
            //console.log(this.salida);

            this._almacenService.guardarsalida(this.token, this.idsalida, this.salida).subscribe(
                response =>{

                    //console.log(this.salida);
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.salida.detalle=response.detalle;
                        this.verSalida();
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );

        }
    }

    verActaSalida(unidad_salida: number){
        this._almacenService.downloadActaSalida(this.token, this.idsalida, unidad_salida).subscribe(
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
        this._almacenService.downloadConstanciaSalida(this.token, this.idsalida).subscribe(
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

    downloadDocumento(indiceDocumento: number){
        this._datomaestroService.downloaddocumento(this.token, this.ubicaciondocumentos, this.salida.adjuntos[indiceDocumento].documento).subscribe(
            response =>{
                if(response.codigo==200){
                    const linkSource = 'data:'+response.pathinfo+';base64,'+response.data;
                    const downloadLink = document.createElement("a");
                    const fileName = this.salida.adjuntos[indiceDocumento].documento;

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

        let documentoeliminar = this.salida.adjuntos[this.indicedocumentoeliminar].documento;
        this._datomaestroService.eliminardocumento(this.token, this.ubicaciondocumentos, documentoeliminar).subscribe(
            response =>{
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.salida.adjuntos.splice(this.indicedocumentoeliminar, 1);
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

    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
            //console.log(this.uploadFileInput[0].type);
            //this.myfilename=this.uploadFileInput[0].name;
        }else {
            //this.myfilename = 'Seleccione un Archivo';
        }
    }

    validarDocumento(){
        this.errorarchivo=false;
        if (!this.archivocargado){
            this.errorarchivo=true;
        }
        if (!this.errorarchivo){
            let indicedoc = this.salida.adjuntos.findIndex(x => x.documento === this.uploadFileInput[0].name);
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
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    if (!this.existedocumento){
                        this.salida.adjuntos.push({
                            'iddocumento': (this.salida.adjuntos.length+1),
                            'documento': response.file_name
                        });
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

    finalizarSalida(){
        this.erroresfinalizar=[];
        this._almacenService.finalizarSalida(this.token, this.idsalida).subscribe(
            response =>{
                //console.log(response);

                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.salida.finalizado=true;
                    this.verSalida();
                }else{
                    this.toast_tipo="Error";
                    this.erroresfinalizar=response.mensajeserror;
                }
                $('#confirmarFinalizar').modal('hide');
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    habilitarSalida(){
        this._almacenService.habilitarSalida(this.token, this.idsalida).subscribe(
            response =>{
                //console.log(response);

                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.salida.finalizado=false;
                }else{
                    this.toast_tipo="Error";
                    this.erroresfinalizar=response.mensajeserror;
                }
                $('#confirmarHabilitacion').modal('hide');
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error)
            }
        );
    }

}
