import { Component, OnInit, OnDestroy, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';

import {GLOBAL} from './../global';
import { IngresosDetalleComponent } from '../ingresos-detalle/ingresos-detalle.component';

declare var $: any;

@Component({
    selector: 'app-ate-gas-salidas',
    templateUrl: './ate-gas-salidas.component.html',
    styleUrl: './ate-gas-salidas.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class AteGasSalidasComponent implements OnDestroy {
    public token: string;
    public tokenDetalle: any;

    public filtro_chasis: string='';

    public entidades: Array<any>;
    public ategas_salidas: Array<any>=[];
    public ategas_salidas_filtrado: Array<any>=[];

    public mensaje: string='';
    public mensajes_error: Array<any>=[];
    public toast_mensaje: string;
    public toast_tipo: string;

    public ver_ate_gas_salidas: boolean=false;
    public editar_ate_gas_salidas: boolean=false;

    public idcliente: number;
    public erroridcliente: boolean;
    public visible_carga: boolean=false

    public cargando: boolean=false;

    public urlFormatoAteGasSalida: string;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;

    public visible_salida: boolean=false;

    public idate_gas: number=0;
    public chasis: string='';
    public marca: string='';
    public modelo: string='';
    public color: string='';
    public configuracion: string='';
    public fecha_programacion_salida: Date=null;
    public destino_salida: string='';
    public error_destino_salida: boolean=false;
    public transportista_salida: string='';
    public error_transportista_salida: boolean=false;
    public archivo_salida: File=null;
    public nombre_archivo_salida: string='';
    public preview_archivo_salida: string=null;
    public error_archivo_salida: string='';
    public procesando_salida: boolean=false;
    public readonly MAX_FILE_SIZE: number=10*1024*1024;
    public readonly ALLOWED_EXTENSIONS: Array<string>=[
        'jpg','jpeg','png','webp','heic','heif','pdf','xls','xlsx','doc','docx'
    ];
    @ViewChild('fileInput') fileInput: ElementRef<HTMLInputElement>;
    @ViewChild('videoCamaraSalida') videoCamaraSalida: ElementRef<HTMLVideoElement>;
    @ViewChild('canvasCamaraSalida') canvasCamaraSalida: ElementRef<HTMLCanvasElement>;
    public mostrar_camara_salida: boolean=false;
    public iniciando_camara_salida: boolean=false;
    public camara_salida_activa: boolean=false;
    public foto_camara_salida_capturada: boolean=false;
    public error_camara_salida: string='';
    public camaras_salida: MediaDeviceInfo[]=[];
    public id_camara_salida: string='';
    public linterna_salida_soportada: boolean=false;
    public linterna_salida_encendida: boolean=false;
    private stream_camara_salida: MediaStream=null;
    private track_video_salida: MediaStreamTrack=null;
    private version_camara_salida: number=0;


    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        //private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        this.urlFormatoAteGasSalida=GLOBAL.urlFiles+'FormatoAteGasSalidasMasivo.xlsx';

        if(this.tokenDetalle.idtipousuario==1){
            this.ver_ate_gas_salidas=true;
            this.editar_ate_gas_salidas=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 101);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_ate_gas_salidas=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_ate_gas_salidas=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._entidadesService.vercliente(this.token).subscribe(
            response =>{
                this.entidades = response.clientes;
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.getAteGasSalidas();
    }
    
    getAteGasSalidas(){
        this.ategas_salidas=[];
        
        this._almacenesService.verategassalidas(this.token).subscribe(
            response =>{
                this.ategas_salidas = response.ategas;
                this.ategas_salidas.forEach(
                    agas => (agas.fecha_programacion_salida = new Date(agas.fecha_programacion_salida.replace(/-/g, '\/')))
                );

                this.ategas_salidas.forEach(
                    agas => (agas.fecha_salida = agas.fecha_salida ? new Date(agas.fecha_salida.replace(/-/g, '\/')) : null)
                );
                this.filtrarAteGas();
                
                console.log(this.ategas_salidas);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }

    filtrarAteGas(){
        this.ategas_salidas_filtrado=[];
        if(this.filtro_chasis==''){
            this.ategas_salidas_filtrado = this.ategas_salidas;
        }else{
            this.ategas_salidas_filtrado = this.ategas_salidas.filter(product =>
                (product.chasis ?? "").toLowerCase().includes(this.filtro_chasis.toLowerCase())
            );
        }
        /*
        this.total_vin = this.ategas_filtrado.length;
        this.total_pendiente = this.ategas_filtrado.filter(item => item.fecha_recepcion == null).length;
        this.total_recepcion = this.total_vin-this.total_pendiente;
        */
    }


    preperarCarga(){
        this.idcliente=null;
        this.erroridcliente=false;
        this.visible_carga=true;
    }

    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
        }else {

        }
    }

    cargarAteGasSalida(){
        let error=false;
        this.mensajes_error=[];
        this.erroridcliente=false;
        if (this.idcliente==null || this.idcliente==0){
            this.erroridcliente=true;
            error=true;
        }
        
        if (!this.tokenDetalle.idalmacen){
            error=true;
            this.toast_tipo="Error";
            this.toast_mensaje="No esta seleccionado ningun almacen";
            $("#liveToast").toast('show');
        }
        
        if(!error){
            
            this.cargando=true;
            this._almacenesService.crearategassalidascargamasiva(this.token, this.idcliente, this.uploadFileInput).subscribe(
                response =>{
                    console.log(response);
                    this.cargando=false;
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.getAteGasSalidas();
                        this.myInputVariable.nativeElement.value = "";
                        this.archivocargado = false;
                        this.visible_carga=false;
                    }else{
                        this.toast_tipo="Error";
                        this.mensajes_error=response.mensajes_error;
                    }
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                    $('#ventanaLoading').modal('hide');
                    this.cargando=false;
                }
            );
        }
    }

    abrirDespachar(idate_gas: number){
      this.limpiarArchivoSalida();
      this.idate_gas=idate_gas;
      var indice = this.ategas_salidas_filtrado.findIndex(x => x.idate_gas == idate_gas);
      if(indice>=0){
        this.chasis=this.ategas_salidas_filtrado[indice].chasis;
        this.marca=this.ategas_salidas_filtrado[indice].marca;
        this.modelo=this.ategas_salidas_filtrado[indice].modelo;
        this.color=this.ategas_salidas_filtrado[indice].color;
        this.configuracion=this.ategas_salidas_filtrado[indice].configuracion;
        this.fecha_programacion_salida=this.ategas_salidas_filtrado[indice].fecha_programacion_salida;
        this.destino_salida='';
        this.error_destino_salida=false;
        this.transportista_salida='';
        this.error_transportista_salida=false;

        this.visible_salida=true;
      }
    }

    seleccionarArchivoSalida(event: Event): void {
      const input=event.target as HTMLInputElement;
      const file=input.files && input.files.length>0 ? input.files[0] : null;
      if(!file) return;
      if(!this.asignarArchivoSalida(file)){
        input.value='';
        return;
      }
      this.detenerCamaraSalida(true);
    }

    limpiarArchivoSalida(limpiarError: boolean=true): void {
      this.detenerCamaraSalida(true);
      this.revocarPreviewArchivoSalida();
      this.archivo_salida=null;
      this.nombre_archivo_salida='';
      if(limpiarError) this.error_archivo_salida='';
      if(this.fileInput) this.fileInput.nativeElement.value='';
    }

    async abrirCamaraSalida(): Promise<void> {
      this.mostrar_camara_salida=true;
      this.error_camara_salida='';
      if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){
        this.error_camara_salida='La cámara integrada no está disponible en este navegador. Use la opción Elegir archivo.';
        return;
      }
      await this.iniciarCamaraSalida();
    }

    async iniciarCamaraSalida(): Promise<void> {
      if(this.procesando_salida || this.iniciando_camara_salida) return;
      const versionSolicitud=++this.version_camara_salida;
      this.iniciando_camara_salida=true;
      this.error_camara_salida='';
      this.detenerStreamCamaraSalida();
      this.foto_camara_salida_capturada=false;

      try{
        const stream=await this.obtenerStreamCamaraSalida(this.id_camara_salida || undefined);
        if(versionSolicitud!==this.version_camara_salida || !this.mostrar_camara_salida){
          stream.getTracks().forEach(track=>track.stop());
          return;
        }
        this.stream_camara_salida=stream;
        this.track_video_salida=stream.getVideoTracks()[0] || null;

        const video=this.videoCamaraSalida.nativeElement;
        video.setAttribute('playsinline', 'true');
        video.setAttribute('webkit-playsinline', 'true');
        video.muted=true;
        video.srcObject=stream;
        await this.esperarVideoListo(video);
        await video.play();
        if(versionSolicitud!==this.version_camara_salida || !this.mostrar_camara_salida) return;

        const capacidades=this.track_video_salida && this.track_video_salida.getCapabilities
          ? this.track_video_salida.getCapabilities() as any
          : null;
        this.linterna_salida_soportada=!!(capacidades && capacidades.torch);
        this.linterna_salida_encendida=false;
        this.camara_salida_activa=true;
        this.foto_camara_salida_capturada=false;
        this.iniciando_camara_salida=false;

        try{
          const dispositivos=await navigator.mediaDevices.enumerateDevices();
          this.camaras_salida=dispositivos.filter(dispositivo=>dispositivo.kind==='videoinput');
          const deviceIdActual=this.track_video_salida.getSettings().deviceId;
          if(deviceIdActual) this.id_camara_salida=deviceIdActual;
          else if(!this.id_camara_salida && this.camaras_salida.length>0) this.id_camara_salida=this.camaras_salida[0].deviceId;
        }catch{}
      }catch(error){
        if(versionSolicitud!==this.version_camara_salida) return;
        this.detenerStreamCamaraSalida();
        this.iniciando_camara_salida=false;
        const nombre=error && (error as any).name ? (error as any).name : '';
        if(nombre==='NotAllowedError' || nombre==='SecurityError'){
          this.error_camara_salida='No se concedió permiso para usar la cámara. Revise los permisos del navegador.';
        }else if(nombre==='NotFoundError' || nombre==='OverconstrainedError'){
          this.error_camara_salida='No se encontró una cámara disponible.';
        }else if(nombre==='NotReadableError'){
          this.error_camara_salida='La cámara está siendo utilizada por otra aplicación.';
        }else{
          this.error_camara_salida='No se pudo iniciar la cámara. Verifique que el sitio use HTTPS o localhost.';
        }
      }
    }

    async cambiarCamaraSalida(): Promise<void> {
      if(this.id_camara_salida) await this.iniciarCamaraSalida();
    }

    async capturarFotoSalida(): Promise<void> {
      if(!this.camara_salida_activa || !this.videoCamaraSalida || !this.canvasCamaraSalida) return;
      const video=this.videoCamaraSalida.nativeElement;
      await this.esperarVideoListo(video);
      const ancho=video.videoWidth;
      const alto=video.videoHeight;
      if(!ancho || !alto){
        this.error_camara_salida='La cámara todavía no está lista para tomar la fotografía.';
        return;
      }

      const dimensionMaxima=1920;
      const escala=Math.min(1, dimensionMaxima/Math.max(ancho, alto));
      const canvas=this.canvasCamaraSalida.nativeElement;
      canvas.width=Math.max(1, Math.round(ancho*escala));
      canvas.height=Math.max(1, Math.round(alto*escala));
      const context=canvas.getContext('2d');
      if(!context){
        this.error_camara_salida='No se pudo preparar la fotografía.';
        return;
      }
      context.drawImage(video, 0, 0, ancho, alto, 0, 0, canvas.width, canvas.height);
      await this.apagarLinternaSalida();
      this.foto_camara_salida_capturada=true;
    }

    repetirFotoSalida(): void {
      this.foto_camara_salida_capturada=false;
      this.error_camara_salida='';
      if(this.videoCamaraSalida) this.videoCamaraSalida.nativeElement.play().catch(()=>{});
    }

    async usarFotoCapturadaSalida(): Promise<void> {
      if(!this.foto_camara_salida_capturada || !this.canvasCamaraSalida) return;
      const canvas=this.canvasCamaraSalida.nativeElement;
      const blob=await new Promise<Blob|null>(resolve=>canvas.toBlob(resolve, 'image/jpeg', 0.9));
      if(!blob){
        this.error_camara_salida='No se pudo generar el archivo de la fotografía.';
        return;
      }
      const file=new File([blob], 'guia-remision-'+Date.now()+'.jpg', {type:'image/jpeg'});
      if(this.asignarArchivoSalida(file)) this.detenerCamaraSalida(true);
    }

    async alternarLinternaSalida(): Promise<void> {
      if(!this.track_video_salida || !this.linterna_salida_soportada) return;
      this.error_camara_salida='';
      const nuevoEstado=!this.linterna_salida_encendida;
      try{
        await (this.track_video_salida as any).applyConstraints({advanced:[{torch:nuevoEstado}]});
        this.linterna_salida_encendida=nuevoEstado;
      }catch{
        try{
          await (this.track_video_salida as any).applyConstraints({torch:nuevoEstado});
          this.linterna_salida_encendida=nuevoEstado;
        }catch{
          this.error_camara_salida='No se pudo cambiar el estado de la linterna.';
        }
      }
    }

    detenerCamaraSalida(cerrarPanel: boolean=false): void {
      this.version_camara_salida++;
      this.iniciando_camara_salida=false;
      this.detenerStreamCamaraSalida();
      this.camara_salida_activa=false;
      this.foto_camara_salida_capturada=false;
      if(cerrarPanel){
        this.mostrar_camara_salida=false;
        this.error_camara_salida='';
      }
    }

    cerrarDespacho(): void {
      if(this.procesando_salida) return;
      this.visible_salida=false;
      this.limpiarArchivoSalida();
    }

    alCerrarDialogoSalida(): void {
      if(!this.procesando_salida) this.limpiarArchivoSalida();
    }

    previewArchivoNoDisponible(): void {
      this.revocarPreviewArchivoSalida();
    }

    formatoTamanoArchivo(bytes: number): string {
      if(bytes<1024) return bytes+' B';
      if(bytes<1024*1024) return (bytes/1024).toFixed(1)+' KB';
      return (bytes/(1024*1024)).toFixed(1)+' MB';
    }

    tipoDocumentoSalida(): string {
      const extension=this.obtenerExtension(this.nombre_archivo_salida);
      if(['jpg','jpeg','png','webp','heic','heif'].includes(extension)) return 'Imagen';
      if(extension==='pdf') return 'PDF';
      if(['xls','xlsx'].includes(extension)) return 'Excel';
      if(['doc','docx'].includes(extension)) return 'Word';
      return 'Documento';
    }

    despacharVehiculo(){
      if(this.procesando_salida) return;
      let error=false;
      this.error_destino_salida=false;
      this.error_transportista_salida=false;
      this.error_archivo_salida='';
      this.destino_salida=this.destino_salida.trim();
      this.transportista_salida=this.transportista_salida.trim();
      if(this.destino_salida==''){
        error=true;
        this.error_destino_salida=true;
      }
      if(this.transportista_salida==''){
        error=true;
        this.error_transportista_salida=true;
      }
      if(!this.archivo_salida){
        error=true;
        this.error_archivo_salida='La guía de remisión es obligatoria.';
      }

      if(!error){
          const formData=new FormData();
          formData.append('destino_salida', this.destino_salida);
          formData.append('transportista_salida', this.transportista_salida);
          formData.append('file', this.archivo_salida, this.archivo_salida.name);
          this.procesando_salida=true;
          this._almacenesService.sacarategas(this.token, this.idate_gas, formData).subscribe(
              response =>{
                  this.procesando_salida=false;
                  this.toast_mensaje=response.mensaje;
                  if(response.codigo==200){
                      this.toast_tipo="Exito";
                      this.limpiarArchivoSalida();
                      this.visible_salida=false;
                      this.getAteGasSalidas();
                  }else{
                      this.toast_tipo="Error";
                      this.mensaje=response.mensaje;
                  }
                  $('#ventanaLoading').modal('hide');
                  $("#liveToast").toast('show');
                  
              },
              error=>{
                  console.log(<any>error)
                  $('#ventanaLoading').modal('hide');
                  this.procesando_salida=false;
                  this.toast_tipo="Error";
                  this.toast_mensaje="No se pudo registrar la salida. Intente nuevamente.";
                  $("#liveToast").toast('show');
              }
          );
      }
    }

    abrirGuiaRemision(registro: any): void {
      if(!registro || !registro.nombre_original_salida) return;
      const extension=this.obtenerExtension(registro.nombre_original_salida);
      const abrirEnPestana=['jpg','jpeg','png','webp','heic','heif','pdf'].includes(extension);
      let nuevaPestana: Window=null;

      if(abrirEnPestana){
        nuevaPestana=window.open('', '_blank');
        if(!nuevaPestana){
          this.toast_tipo='Error';
          this.toast_mensaje='El navegador bloqueó la nueva pestaña. Habilite las ventanas emergentes e intente nuevamente.';
          $("#liveToast").toast('show');
          return;
        }
        nuevaPestana.opener=null;
      }

      this._almacenesService.descargarGuiaRemisionAteGas(this.token, registro.idate_gas).subscribe(
        response=>{
          const blob=response.body as Blob;
          const objectUrl=URL.createObjectURL(blob);
          if(abrirEnPestana && nuevaPestana){
            nuevaPestana.location.href=objectUrl;
            window.setTimeout(()=>URL.revokeObjectURL(objectUrl), 60000);
          }else{
            const downloadLink=document.createElement('a');
            downloadLink.href=objectUrl;
            downloadLink.download=registro.nombre_original_salida;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            window.setTimeout(()=>URL.revokeObjectURL(objectUrl), 1000);
          }
        },
        error=>{
          if(nuevaPestana) nuevaPestana.close();
          this.toast_tipo='Error';
          this.toast_mensaje='No se pudo obtener la guía de remisión.';
          $("#liveToast").toast('show');
        }
      );
    }

    ngOnDestroy(): void {
      this.limpiarArchivoSalida();
    }

    private asignarArchivoSalida(file: File): boolean {
      this.error_archivo_salida='';
      const extension=this.obtenerExtension(file.name);
      if(!this.ALLOWED_EXTENSIONS.includes(extension)){
        this.error_archivo_salida='Formato no permitido. Seleccione una imagen, PDF, Word o Excel válido.';
        return false;
      }
      if(file.size<=0){
        this.error_archivo_salida='El archivo seleccionado está vacío.';
        return false;
      }
      if(file.size>this.MAX_FILE_SIZE){
        this.error_archivo_salida='El archivo no puede superar 10 MB.';
        return false;
      }

      this.revocarPreviewArchivoSalida();
      this.archivo_salida=file;
      this.nombre_archivo_salida=file.name;
      if(file.type.startsWith('image/')) this.preview_archivo_salida=URL.createObjectURL(file);
      return true;
    }

    private async obtenerStreamCamaraSalida(deviceId?: string): Promise<MediaStream> {
      const baseVideo: MediaTrackConstraints={
        width:{ideal:1920},
        height:{ideal:1080}
      };
      const primaryVideo: MediaTrackConstraints=deviceId
        ? {...baseVideo, deviceId:{exact:deviceId}}
        : {...baseVideo, facingMode:{ideal:'environment'}};

      try{
        return await navigator.mediaDevices.getUserMedia({video:primaryVideo, audio:false});
      }catch(error){
        if(this.esErrorPermisoCamara(error)) throw error;
      }

      try{
        return await navigator.mediaDevices.getUserMedia({
          video:{...baseVideo, facingMode:{ideal:'environment'}},
          audio:false
        });
      }catch(error){
        if(this.esErrorPermisoCamara(error)) throw error;
      }

      return navigator.mediaDevices.getUserMedia({
        video:{...baseVideo, facingMode:{ideal:'user'}},
        audio:false
      });
    }

    private esErrorPermisoCamara(error: any): boolean {
      return !!error && (error.name==='NotAllowedError' || error.name==='SecurityError');
    }

    private esperarVideoListo(video: HTMLVideoElement): Promise<void> {
      if(video.readyState>=2 && video.videoWidth>0) return Promise.resolve();
      return new Promise<void>((resolve, reject)=>{
        const timeout=window.setTimeout(()=>{
          video.removeEventListener('loadeddata', listo);
          reject(new Error('camera-timeout'));
        }, 8000);
        const listo=()=>{
          window.clearTimeout(timeout);
          video.removeEventListener('loadeddata', listo);
          resolve();
        };
        video.addEventListener('loadeddata', listo, {once:true});
      });
    }

    private async apagarLinternaSalida(): Promise<void> {
      if(!this.track_video_salida || !this.linterna_salida_encendida) return;
      try{
        await (this.track_video_salida as any).applyConstraints({advanced:[{torch:false}]});
        this.linterna_salida_encendida=false;
      }catch{
        try{
          await (this.track_video_salida as any).applyConstraints({torch:false});
          this.linterna_salida_encendida=false;
        }catch{}
      }
    }

    private detenerStreamCamaraSalida(): void {
      if(this.track_video_salida){
        (this.track_video_salida as any).applyConstraints({advanced:[{torch:false}]}).catch(()=>{});
      }
      if(this.stream_camara_salida) this.stream_camara_salida.getTracks().forEach(track=>track.stop());
      if(this.videoCamaraSalida) this.videoCamaraSalida.nativeElement.srcObject=null;
      this.stream_camara_salida=null;
      this.track_video_salida=null;
      this.camara_salida_activa=false;
      this.linterna_salida_encendida=false;
      this.linterna_salida_soportada=false;
    }

    private obtenerExtension(nombre: string): string {
      const partes=(nombre || '').toLowerCase().split('.');
      return partes.length>1 ? partes.pop() : '';
    }

    private revocarPreviewArchivoSalida(): void {
      if(this.preview_archivo_salida){
        URL.revokeObjectURL(this.preview_archivo_salida);
        this.preview_archivo_salida=null;
      }
    }


}
