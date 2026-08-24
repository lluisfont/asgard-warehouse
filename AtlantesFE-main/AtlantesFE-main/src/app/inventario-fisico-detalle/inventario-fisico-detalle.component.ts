import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {InventarioFisicoBitacoraComponent} from '../inventario-fisico-bitacora/inventario-fisico-bitacora.component';
import {InventarioFisicoModel} from '../models/inventario-fisico.model';
import { MessageService } from 'primeng/api';
import { FileUploadEvent } from 'primeng/fileupload';
import {GLOBAL} from './../global';
import {HttpHeaders} from '@angular/common/http';
declare var $: any;
@Component({
    selector: 'app-inventario-fisico-detalle',
    templateUrl: './inventario-fisico-detalle.component.html',
    styleUrl: './inventario-fisico-detalle.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,MessageService]
})
export class InventarioFisicoDetalleComponent {
    public token: string;
    public tokenDetalle: any;

    public idinventariofisico: number;    
    public inventarioFisico: InventarioFisicoModel;
    public inventario: Array<any>;
    public inventario_origen: Array<any>;
    
    public status: Array<any>;
    
    public usuarios: Array<any>;
    
    public inventariofisico_etiquetas: Array<any>;
    
    public errorfecha: boolean=false;
    
    public filasagregar: number=1;
    public indicedetalleeliminar: number;
    
    public urlFormatoIngreso: string;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    
    public fotos_visible: boolean=false;
    public images: any[] | undefined;
    responsiveOptions: any[] | undefined;
    
    public archivos_visible: boolean=false;
    uploadedFiles: any[] = [];
    public uploadHeaders: HttpHeaders;
    public url: string='';
    public archivos: any[] | undefined;
    public archivos_no_cargados: Array<any>=[];
    
    public total_cantidad: number=0;
    public total_cantidad_real: number=0;
    public total_diferencia: number=0;
    
    public bitacora_visible: boolean=false;
    public chasis_bitacora: string='';
    public idcliente_bitacora: number=0;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_inventario_fisico_gestion: boolean=false;
    public editar_inventario_fisico_gestion: boolean=false;
    public reabrir_inventario_fisico_conteo: boolean=false;
    public ver_inventario_fisico_bitacora: boolean=false;
    
    public estados_conteo: Array<any>=[
        {idestado_conteo: 0, estado_conteo: "TODOS"},
        {idestado_conteo: 1, estado_conteo: "ENCONTRADO"},
        {idestado_conteo: 2, estado_conteo: "SOBRANTE"},
        {idestado_conteo: 3, estado_conteo: "FALTANTE"},
        {idestado_conteo: 4, estado_conteo: "PENDIENTE"}
    ];
    
    public idestado_conteo: number=0;

    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _messageService: MessageService,
        private _route: ActivatedRoute
    ) {
        this._route.params.forEach((params: Params)=>{
            this.idinventariofisico = params["idinventariofisico"];
        });
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        this.urlFormatoIngreso=GLOBAL.urlFiles+'FormatoInventarioFisico.xlsx';
        
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_inventario_fisico_gestion=true;
            this.editar_inventario_fisico_gestion=true;
            this.reabrir_inventario_fisico_conteo=true;
            this.ver_inventario_fisico_bitacora=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 43);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_inventario_fisico_gestion=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_inventario_fisico_gestion=true;
                }
            }
            
            let indiceVerInventarioFisicoBitacora = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 46);
            if(indiceVerInventarioFisicoBitacora>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoBitacora].lectura){
                    this.ver_inventario_fisico_bitacora=true;
                }
            }
            
            let indiceReabrirInventarioFisicoConteo = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 89);
            if(indiceReabrirInventarioFisicoConteo>=0){
                if(this.tokenDetalle.permisos[indiceReabrirInventarioFisicoConteo].lectura){
                    this.reabrir_inventario_fisico_conteo=true;
                }
            }
        }
        
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
    }
    
    ngOnInit(): void {
        this.uploadHeaders = new HttpHeaders({'Authorization':this.token});
        
        
        this._datomaestroService.status(this.token).subscribe(
            response =>{
                this.status=response.status;
                //console.log(this.usuarios);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.inventariofisico_etiquetas(this.token).subscribe(
            response =>{
                this.inventariofisico_etiquetas=response.inventariofisico_etiquetas;
                //console.log(this.usuarios);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.cargarInventarioFisico();
    }
    
    cargarInventarioFisico(){
        this._almacenesService.verinventariofisico(this.token, this.idinventariofisico).subscribe(
            response =>{
                
                this._almacenesService.inventario(this.token, response.inventariofisico.idcliente_cifrado, response.inventariofisico.fecha, true).subscribe(
                    response_inventario =>{
                        this.inventario=response_inventario.inventario;
                        this.inventarioFisico=response.inventariofisico;

                        this._usuarioService.usuarios(this.token).subscribe(
                            response =>{
                                this.usuarios=response.usuarios.filter(u => (u.almacen || u.idusuario == this.inventarioFisico.idapoyo || u.idusuario == this.inventarioFisico.idasignado));
                                //console.log(this.usuarios);
                            },
                            error=>{
                                console.log(<any>error)
                            }
                        );

                        this.verificarInventario();
                        console.log(this.inventario_origen);
                        console.log(this.inventarioFisico);
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
    
    verificarInventario(){
        let fechacomparar=null;
        this.total_cantidad=0;
        this.total_cantidad_real=0;
        this.total_diferencia=0;
        console.log(this.inventarioFisico.detalle);
        for (let iif = 0; iif < this.inventarioFisico.detalle.length; iif++){
            this.total_cantidad = this.total_cantidad + this.inventarioFisico.detalle[iif].cantidad;
            this.total_cantidad_real = this.total_cantidad_real + this.inventarioFisico.detalle[iif].cantidad_real;
            this.total_diferencia = this.total_cantidad_real-this.total_cantidad;
            this.inventarioFisico.detalle[iif].existe=false;
            if(this.inventarioFisico.detalle[iif].ubicacion==''){
                this.inventarioFisico.detalle[iif].ubicacion=null;
            }
            for (let ii = 0; ii < this.inventario.length; ii++){
                fechacomparar=null;
                if(this.inventario[ii].fechavencimiento!=null){
                    fechacomparar=(this.inventario[ii].fechavencimiento).substr(0, 10);
                }
                if (this.inventario[ii].codigo == this.inventarioFisico.detalle[iif].codigo 
                    && this.inventario[ii].ubicacionalmacen==this.inventarioFisico.detalle[iif].ubicacion 
                    && this.inventario[ii].categoria==this.inventarioFisico.detalle[iif].categoria 
                    
                    && this.inventario[ii].cantidad==this.inventarioFisico.detalle[iif].cantidad 
                    && this.inventario[ii].lote==this.inventarioFisico.detalle[iif].lote 
                    && fechacomparar==this.inventarioFisico.detalle[iif].fecha_vencimiento
                    ){
                    this.inventarioFisico.detalle[iif].existe=true;
                    break;
                }
            }
        }
        
        console.log(this.total_cantidad);
            
    }
    
    agregarFila(){
        for (let fa = 1; fa <= this.filasagregar; fa++){
            this.inventarioFisico.detalle.push({
                idinventariofisicodetalle: 0,
                codigo: '',
                serie: '',
                descripcion: '',
                ubicacion: '',
                categoria: '',
                cantidad: 0,
                cantidad_real: 0,
                diferencia: 0,
                embalaje: '',
                lote: '',
                fecha_vencimiento: null,
                observaciones: '',
                existe: false,
                idestado_conteo: null,
                estado_conteo: '',
                idinventariofisicoetiqueta: null,
                idinventariofisicoconteo: 0,
                inventariofisicoetiqueta: '',
                cantidad_imagenes: 0,
                archivos: [{}]
            });
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
    
    cargarMasivamente(){
        $("#ventanaCargaMasiva").modal('hide');
        $('#ventanaLoading').modal('show');
        this._almacenesService.inventariofisicocargamasiva(this.token, this.idinventariofisico, this.uploadFileInput).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    for(let dd=0;dd<response.xls_data.length;dd++){
                        this.inventarioFisico.detalle.push({
                            idinventariofisicodetalle: 0,
                            codigo: response.xls_data[dd][0],
                            serie: response.xls_data[dd][1],
                            descripcion: response.xls_data[dd][2],
                            ubicacion: response.xls_data[dd][3],
                            categoria: response.xls_data[dd][4],
                            cantidad: response.xls_data[dd][5],
                            cantidad_real: 0,
                            diferencia: response.xls_data[dd][5]*(-1),
                            embalaje: response.xls_data[dd][6],
                            lote: response.xls_data[dd][7],
                            fecha_vencimiento: response.xls_data[dd][8],
                            observaciones: '',
                            existe: false,
                            idestado_conteo: null,
                            estado_conteo: '',
                            idinventariofisicoetiqueta: null,
                            idinventariofisicoconteo: 0,
                            inventariofisicoetiqueta: '',
                            cantidad_imagenes: 0,
                            archivos: [{}]
                        });
                    }

                    this.verificarInventario();
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');

                    this.myInputVariable.nativeElement.value = "";
                    this.archivocargado = false;
                    
                }else{
                    this.toast_tipo="Error";
                }

                

                    
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    eliminarDetalle(){
        this.inventarioFisico.detalle.splice(this.indicedetalleeliminar, 1);
        $('#confirmarEliminarDetalle').modal('hide');
    }
    
    eliminarTodos(){
        this.inventarioFisico.detalle.splice(0, this.inventarioFisico.detalle.length);
        $('#confirmarEliminarTodos').modal('hide');
    }
    
    
    public pegarDatos(campo: string): void {
        var copiado: Array<any>;
        navigator.clipboard.readText().then(
            text => {
                if (text.length>0){

                    copiado = text.split(/\r?\n/);
                    for (let xx = 0; xx < (copiado.length-1); xx++){
                        copiado[xx]=copiado[xx].split('\t');
                    }
                    let ultimodato = Math.min(copiado.length - 1, this.inventarioFisico.detalle.length);
                    for(let ii=0; ii<ultimodato; ii++){
                        switch(campo){
                            case 'fecha_vencimiento':
                                let fechasplit=copiado[ii][0].split("/");
                                if(fechasplit.length==3){
                                    copiado[ii][0]=fechasplit[2]+"-"+fechasplit[1]+"-"+fechasplit[0];
                                }
                                break;
                            case 'cantidad':
                            case 'cantidad_real':
                                copiado[ii][0]=parseFloat(copiado[ii][0]);
                                break;
                            default:
                                //this.ingreso.detalle[ii][campo]=copiado[ii][0];
                                break;
                        }
                        this.inventarioFisico.detalle[ii][campo]=copiado[ii][0];
                        if(campo=='codigo'){
                            this.verificarCodigo(ii);
                        }
                        if(campo=='cantidad' || campo=='cantidad_real'){
                            this.calcularDiferencia(ii);
                        }
                    }
                    this.verificarInventario();
                }

            }
        ).catch(error => {
            console.error('Cannot read clipboard text: ', error);
        });
        

    }
    
    
    verificarCodigo(indiceDetalle: number){
        let indiceCodigo = this.inventario.findIndex(x => x.codigo === this.inventarioFisico.detalle[indiceDetalle].codigo);
        if(indiceCodigo>=0){
            this.inventarioFisico.detalle[indiceDetalle].serie = this.inventario[indiceCodigo].serie;
            this.inventarioFisico.detalle[indiceDetalle].descripcion = this.inventario[indiceCodigo].descripcion;
            this.inventarioFisico.detalle[indiceDetalle].embalaje = this.inventario[indiceCodigo].codigoembalaje;
        }else{
            this.inventarioFisico.detalle[indiceDetalle].serie = '';
            this.inventarioFisico.detalle[indiceDetalle].descripcion = '';
            this.inventarioFisico.detalle[indiceDetalle].embalaje = '';
        }
        
        this.verificarInventario();
    }
    
    calcularDiferencia(indiceDetalle: number){
        this.inventarioFisico.detalle[indiceDetalle].diferencia = this.inventarioFisico.detalle[indiceDetalle].cantidad_real - this.inventarioFisico.detalle[indiceDetalle].cantidad;
        this.verificarInventario();
    }
    
    prepararImagenes(indice: number){
        this.fotos_visible=true;
        this.images=[];
        //this.images = JSON.parse(JSON.stringify(this.inventarioFisico.detalle[indice].imagenes));
        let idinventariofisicodetallearchivo = this.inventarioFisico.detalle[indice].idinventariofisicoconteo;
        this._almacenesService.verimagenesinventariofisicoconteo(this.token, idinventariofisicodetallearchivo).subscribe(
            response =>{
                if(response.codigo==200){
                    this.images=response.imagenes;
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
    
    prepararArchivos(indice: number){
        let idinventariofisicodetalle = this.inventarioFisico.detalle[indice].idinventariofisicodetalle;
        this.url = GLOBAL.url+'inventariosfisico/detalle/'+idinventariofisicodetalle+'/cargararchivo';
        this.archivos_visible=true;
        this.archivos = JSON.parse(JSON.stringify(this.inventarioFisico.detalle[indice].archivos));
        this.archivos_no_cargados=[];
    }
    
    onUpload(event:any) {
        const respuesta = event.originalEvent.body;
        
        let indiceDetalle = this.inventarioFisico.detalle.findIndex(x => x.idinventariofisicodetalle == respuesta.idinventariofisicodetalle);
        
        for(let ac=0; ac<respuesta.cargados.length; ac++){
            this.archivos.push({
                idinventariofisicodetallearchivo: respuesta.cargados[ac].id,
                inventariofisicodetallearchivo: respuesta.cargados[ac].original,
            });
            this.inventarioFisico.detalle[indiceDetalle].archivos.push({
                idinventariofisicodetallearchivo: respuesta.cargados[ac].id,
                inventariofisicodetallearchivo: respuesta.cargados[ac].original
            });
        }
        
        this.archivos_no_cargados=respuesta.errores;
        
        this.toast_mensaje=respuesta.mensaje;
        if(respuesta.codigo==200){
            this.toast_tipo="Exito";
        }else{
            this.toast_tipo="Error";
        }
        $("#liveToast").toast('show');
        
        //this._messageService.add({severity: 'info', summary: 'Archivcos cargados', detail: ''});
    }
    
    downloadArchivo(idinventariofisicodetallearchivo: number){
        this._almacenesService.downloadinventariofisicodetallearchivo(this.token, idinventariofisicodetallearchivo).subscribe(
            response =>{
                if(response.codigo==200){
                    const linkSource = 'data:'+response.pathinfo+';base64,'+response.data;
                    const downloadLink = document.createElement("a");
                    const fileName = response.inventariofisicodetallearchivo;

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
    
    saveInventarioFisico(){
        this.errorfecha=false;
        if (this.inventarioFisico.fecha == null || this.inventarioFisico.fecha==''){
            this.errorfecha=true;
        }
        
        if (!this.errorfecha){
            //console.log(this.ingreso);
            this._almacenesService.guardarinventariofisico(this.token, this.idinventariofisico, this.inventarioFisico).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.inventarioFisico.detalle=response.detalle;
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#liveToast").toast('show');
                    this.verificarInventario();
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
    
    reabrirConteo(){
        $('#confirmarReabrirConteo').modal('hide');
        $('#ventanaLoading').modal('show');
        this._almacenesService.reabrirconteoinventariofisico(this.token, this.idinventariofisico).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.cargarInventarioFisico();
                }else{
                    this.toast_tipo="Error";
                }
                $('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
            }
        );
    }
    
    finalizarInventarioFisico(){
        $('#confirmarFinalizarInventarioFisico').modal('hide');
        $('#ventanaLoading').modal('show');
        this._almacenesService.finalizarinventariofisico(this.token, this.idinventariofisico).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.cargarInventarioFisico();
                }else{
                    this.toast_tipo="Error";
                }
                $('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
            }
        );
    }
    
    
    verTomaInventarioFisico(){
        this._almacenesService.downloadTomaInventarioFisico(this.token, this.idinventariofisico).subscribe(
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
    
    prepararBitacora(idcliente: number, chasis: string){
        this.idcliente_bitacora=idcliente;
        this.chasis_bitacora=chasis;
        this.bitacora_visible=true;
        
    }
}
