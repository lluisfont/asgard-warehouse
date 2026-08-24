import { Component, OnInit, ViewChild, ElementRef, ChangeDetectorRef, AfterContentChecked } from '@angular/core';
import { from, Observable } from 'rxjs';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { Router } from '@angular/router';
import {SalidaModel} from '../models/salida.model';
declare var $: any;

@Component({
  selector: 'app-ingresos',
  templateUrl: './ingresos.component.html',
  styleUrls: ['./ingresos.component.css'],
  providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class IngresosComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public ingresos: Array<any>=[];
    public ingresos_pendientes: Array<any>=[];
    public expandedRows = {};
    public ingreso_pendiente: any={};
    public entidades: Array<any>;

    public tiposingreso: Array<any>=[];
    
    public idcliente: string;
    public erroridcliente: boolean;
    public es_vehiculo: boolean;
    public tiene_inter_company: boolean=false;
    public idcliente_destino: string;
    public erroridcliente_destino: boolean;
    public entidades_destino: Array<any>;
    
    public inter_company: boolean;
    
    public ver_ingresos: boolean=false;
    public editar_ingresos: boolean=false;
    
    public pendientes_visible: boolean=false;
    
    public procesando: boolean=false;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';

    public idsalida: number=0;
    public idsalidadetalle: number=0;
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
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadService: EntidadesService,
        private _router: Router
    ) {
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
            
        //console.log(this.tokenDetalle);
    }

    ngOnInit(): void {
        /*
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades=response.entidades;
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        */
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                this.entidades=response.clientes;
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.tiposingreso(this.token).subscribe(
            response =>{
                this.tiposingreso=response.tiposingreso;
                this.tiposingreso.unshift({
                    idtipoingreso: null,
                    tipoingreso: "(Vacio)"
                });
                console.log(this.tiposingreso);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.veringresos(0);
        this.veringresos_pendientes();
    }
    
    veringresos(filtro: number){
        this._almacenesService.veringresos(this.token, this.tokenDetalle.idcliente_almacen).subscribe(
            response =>{
                switch(filtro){
                    case 0:
                    default:
                        this.ingresos=response.ingresos;
                        break;
                    case 1:
                        this.ingresos=response.ingresos.filter(function (datos){
                            return datos.acomodado;
                        });
                        break;
                    case 2:
                        this.ingresos=response.ingresos.filter(function (datos){
                            return !datos.acomodado;
                        });
                        break;
                }
                this.ingresos.forEach(
                    ingresos => (ingresos.fecha = new Date(ingresos.fecha.replace(/-/g, '\/')))
                );
                //this.ingresos=response.ingresos;
                //console.log(this.ingresos);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.p=1;
    }
    
    veringresos_pendientes(){
        this._almacenesService.veringresos_pendientes(this.token, this.tokenDetalle.idcliente_almacen).subscribe(
            response =>{
                this.ingresos_pendientes=response.ingresos;
                console.log(this.ingresos_pendientes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.p=1;
    }
    
    preperarAgregar(){
        if(this.tokenDetalle.idcliente_almacen!='cfcd208495d565ef66e7dff9f98764da'){
            this.idcliente=this.tokenDetalle.idcliente_almacen;
            this.verificarInerCompany();
        }else{
            this.idcliente=null;
            
        }
        this.es_vehiculo=false;
	this.tiene_inter_company=false;
        this.inter_company=false;
        this.idcliente_destino=null;
        this.erroridcliente_destino=false;
    }
    
    verificarInerCompany(){
        this.erroridcliente=false;
        this.tiene_inter_company=false;
        this.inter_company=false;
        this.idcliente_destino=null;
        this.erroridcliente_destino=false;
        this.entidades_destino=[];
        let indiceCliente = this.entidades.findIndex(x => x.id === this.idcliente);
        if(indiceCliente>=0){
            if (this.entidades[indiceCliente].inter_company.length>0){
                this.tiene_inter_company=true;
                this.entidades_destino=this.entidades[indiceCliente].inter_company;
            }
        }
    }
    
    crearIngreso(){
        let error=false;
        this.erroridcliente=false;
        if (this.idcliente==null){
            this.erroridcliente=true;
            error=true;
        }
        
        this.erroridcliente_destino=false;
        if (this.tiene_inter_company && this.inter_company && this.idcliente_destino==null){
            this.erroridcliente_destino=true;
            error=true;
        }
        
        if(!error){
            let datosingreso = {idcliente: this.idcliente, es_vehiculo: this.es_vehiculo, idcliente_destino: this.idcliente_destino};
            this._almacenesService.crearingreso(this.token, datosingreso).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        $('#nuevoIngreso').modal('hide');
                        this.abrirDetalle(response.idingreso);
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
    }
    
    abrirDetalle(idingreso: number){
        this._router.navigate(['/ingresos-detalle',idingreso])
    }
    
    abrirDetalleNuevo(idingreso:number){
        let newRelativeUrl = this._router.createUrlTree(["/ingresos-detalle",idingreso]);
        let baseUrl = window.location.href.replace(this._router.url, '');

        window.open(baseUrl + newRelativeUrl, '_blank');
        
        //alert("abre en nueva pestaña " + idembarque);
        //event.stopPropagation();
    }
    
    verIngresoPendiente(idsalida: number){
        this.pendientes_visible=true;
        let indiceIngresoPendiente = this.ingresos_pendientes.findIndex(x => x.idsalida === idsalida);
        this.ingreso_pendiente=this.ingresos_pendientes[indiceIngresoPendiente];
        console.log(this.ingreso_pendiente);
    }

    verConstancia(idsalida: string){
        this._almacenesService.downloadConstanciaSalida(this.token, idsalida).subscribe(
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

    verificarAccesorios(idsalida: number, indice: number){
        let indiceIngresoPendiente = this.ingresos_pendientes.findIndex(x => (x.idsalida == idsalida));
        if(this.ingresos_pendientes[indiceIngresoPendiente].chasis[indice].kilometraje || this.ingresos_pendientes[indiceIngresoPendiente].chasis[indice].tiene_danios || this.ingresos_pendientes[indiceIngresoPendiente].chasis[indice].tiene_faltante || this.ingresos_pendientes[indiceIngresoPendiente].chasis[indice].accesorios_vehiculos.length>0){
            return true;
        }
        return false;
    }

    prepararAccesoriosVehciulos(idsalida: number, indiceDetalle: number){
        this.idsalida=idsalida;
        let indiceIngresoPendiente = this.ingresos_pendientes.findIndex(x => (x.idsalida == this.idsalida));
        this.chasis = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].chasis;
        this.modelo = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].modelo;
        this.marca = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].marca;
        this.color = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].color;

        this.kilometraje = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].kilometraje;
        this.tiene_danios = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].tiene_danios;
        this.danios = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].danios;
        this.tiene_faltante = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].tiene_faltante;
        this.faltante = this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].faltante;
        this.indiceDetalle=indiceDetalle;
        this.idsalidadetalle=this.ingresos_pendientes[indiceIngresoPendiente].chasis[indiceDetalle].idsalidadetalle;
        this.images=[];

        this.cargarAccesorios();

        this._almacenesService.verimagenessalidapendientedetalle(this.token, this.idsalidadetalle).subscribe(
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

    cargarAccesorios(){
        this.accesorios_vehiculos=[];
        let indiceIngresoPendiente = this.ingresos_pendientes.findIndex(x => (x.idsalida == this.idsalida));
        if(indiceIngresoPendiente>=0){
            let idcliente: number=this.ingresos_pendientes[indiceIngresoPendiente].idcliente ?? 0;
            if(idcliente>0){
                this._datomaestroService.accesorios_vehiculos(this.token, idcliente).subscribe(
                    response =>{
                        this.accesorios_vehiculos=response.accesorios_vehiculos;
                        for (let aa = 0; aa < this.accesorios_vehiculos.length; aa++){
                            if(this.indiceDetalle>=0){
                                //console.log(this.ingresos_pendientes[indiceIngresoPendiente].chasis[this.indiceDetalle]);
                                let indiceAccesorio = this.ingresos_pendientes[indiceIngresoPendiente].chasis[this.indiceDetalle].accesorios_vehiculos.findIndex(x => (x.idaccesorios_vehiculos == this.accesorios_vehiculos[aa].idaccesorios_vehiculos));
                                
                                if(indiceAccesorio>=0){
                                    this.accesorios_vehiculos[aa].marcado=true;
                                    this.accesorios_vehiculos[aa].cantidad = this.ingresos_pendientes[indiceIngresoPendiente].chasis[this.indiceDetalle].accesorios_vehiculos[indiceAccesorio].cantidad;
                                    this.accesorios_vehiculos[aa].texto = this.ingresos_pendientes[indiceIngresoPendiente].chasis[this.indiceDetalle].accesorios_vehiculos[indiceAccesorio].texto;
                                }else{
                                    this.accesorios_vehiculos[aa].marcado=false;
                                    this.accesorios_vehiculos[aa].cantidad = 0;
                                    this.accesorios_vehiculos[aa].texto = '';
                                }
                                
                            }
                            
                        }

                        console.log(this.accesorios_vehiculos);

                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }
            
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

    aceptarAccesorios(){
        this.detenerCamara();
        const images = this.images.filter(im => im.idsalidadetalleimagen == 0);
        const accesorios_vehiculos = this.accesorios_vehiculos.filter(im => im.marcado);
        
        let payload={
            imagenes: images,
            tiene_danios: this.tiene_danios,
            danios: this.danios,
            tiene_faltante: this.tiene_faltante,
            faltante: this.faltante,
            kilometraje: this.kilometraje,
            accesorios_vehiculos: accesorios_vehiculos
        };

        //console.log(payload);

        this.procesando=true;
        this._almacenesService.guardaraccesoriospendiente(this.token, this.idsalidadetalle, payload).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.accesorios_visible=false;
                    //this.veringresos(0);
                    this.veringresos_pendientes();
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

        //guardaraccesoriospendiente
    }

    rechazarPendiente(){
        
    }
    
    aceptarPendiente(){
        this.procesando=true;
        this._almacenesService.aprobaringresopendiente(this.token, this.ingreso_pendiente.idsalida).subscribe(
            response =>{
                //console.log(response);
                this.procesando=false;
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.pendientes_visible=false;
                    this.abrirDetalle(response.idingreso);
                }else{
                    this.toast_tipo="Error";
                    $("#liveToast").toast('show');
                }
            },
            error=>{
                this.procesando=false;
                console.log(<any>error)
            }
        );
    }

}
