import { Component, OnDestroy } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { ConfirmationService, MessageService } from 'primeng/api';

import heic2any from 'heic2any';

import {GLOBAL} from './../global';

declare var $: any;

type CameraTarget =
    | { kind: 'damage'; index: number }
    | { kind: 'main' };

@Component({
    selector: 'app-gestion-movimiento',
    templateUrl: './gestion-movimiento.component.html',
    styleUrl: './gestion-movimiento.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService,ConfirmationService, MessageService]
})
export class GestionMovimientoComponent implements OnDestroy {
    private readonly maxImageSize = 20971520;
    private readonly maxCaptureDimension = 1600;
    private readonly captureQuality = 0.8;

    public token: string;
    public tokenDetalle: any;

    public filtro_chasis: string='';
    
    public gestionmovimientos: Array<any>=[];
    public gestionmovimientos_filtrado: Array<any>=[];
    public etapas: Array<any>=[];
    public almacen_ubicaciones: Array<any>=[];
    public ate_gas_motivos_pausa: Array<any>=[];
    
    public mensajes_error: Array<any>=[];
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_gestion_movimiento: boolean=false;
    public editar_gestion_movimiento: boolean=false;
    
    public visible_ver: boolean=false;
    
    public visible_inventario: boolean=false;
    
    public idate_gas_etapa: number=null;
    public chasis: string='';
    public marca: string='';
    public modelo: string='';
    public ubicacion: string='';
    public tecnicos: Array<any>=[];
    public tecnicos_qa: Array<any>=[];
    public idetapa: number=null;

    public etapa: string='';
    public idalmacendetalle: number=null;
    public observaciones_inventario: string='';
    public idestado_etapa: number=null;
    public inventario: Array<any>=[];
    public imagenes: Array<any>=[];
    
    public total_vin: number=0;
    public total_en_actividad: number=0;
    public total_pausados: number=0;
    public total_con_inventario: number=0;
    
    uploadedFiles: File[][] = [];

    uploadedFilesMain: File[]=[];

    public cameraVisible: boolean=false;
    public cameraTarget: CameraTarget | null=null;
    public videoDevices: MediaDeviceInfo[]=[];
    public selectedDeviceId: string='';
    public cameraLoading: boolean=false;
    public cameraActive: boolean=false;
    public torchSupported: boolean=false;
    public torchOn: boolean=false;
    public capturedFile: File | null=null;
    public capturePreviewVisible: boolean=false;

    private cameraStream: MediaStream | null=null;
    private videoTrack: MediaStreamTrack | null=null;
    private currentVideo: HTMLVideoElement | null=null;
    private captureSequence: number=0;

    public imagenesMain: Array<any>=[];
    
    public visible_iniciar: boolean=false;
    public titulo_iniciar: string='';
    public descripcion_iniciar: string='';
    
    
    public visible_pausar: boolean=false;
    public idate_gas_motivo_pausa: number | null=null;
    public error_idate_gas_motivo_pausa: boolean=false;
    public motivo_pausa: string='';
    public error_motivo_pausa: boolean= false;
    
    private intervalId: number | null = null;
    
    public visible_finalizar: boolean=false;
    
    public cargando: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        private _confirmationService: ConfirmationService, 
        private _messageService: MessageService,
        //private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_gestion_movimiento=true;
            this.editar_gestion_movimiento=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 95);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_gestion_movimiento=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_gestion_movimiento=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this._datomaestroService.etapas(this.token).subscribe(
            response =>{
                this.etapas = response.etapas;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.ate_gas_motivos_pausa(this.token).subscribe(
            response =>{
                this.ate_gas_motivos_pausa = response.ate_gas_motivos_pausa;
            },
            error=>{
                console.log(<any>error)
            }
        );

        
        
        this.getGestionMovimiento();
    }


    
    getGestionMovimiento(){
        this.gestionmovimientos=[];
        this._almacenesService.vergestionmovimiento(this.token).subscribe(
            response =>{
                this.gestionmovimientos = response.gestionmovimientos;
                //this.contadorTiempo();
                //this.getTotales();
                this.filtrarGestionMovimiento();
                /*
                this.ategas.forEach(
                    agas => (agas.created_at = new Date(agas.created_at.replace(/-/g, '\/')))
                );
                this.total_vin = this.ategas.length;
                this.total_pendiente = this.ategas.filter(item => item.fecha_recepcion == null).length;
                this.total_recepcion = this.total_vin-this.total_pendiente;
                */
                console.log(this.gestionmovimientos);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    filtrarGestionMovimiento(){
        this.gestionmovimientos_filtrado=[];
        if(this.filtro_chasis==''){
            this.gestionmovimientos_filtrado = this.gestionmovimientos;
        }else{
            this.gestionmovimientos_filtrado = this.gestionmovimientos.filter(product =>
                (product.chasis ?? "").toLowerCase().includes(this.filtro_chasis.toLowerCase())
            );
        }
        this.contadorTiempo();
        this.getTotales();
    }
    
    getTotales(){
        this.total_vin = this.gestionmovimientos_filtrado.length;
        this.total_en_actividad = this.gestionmovimientos_filtrado.filter(item => item.idestado_etapa == 2).length;
        this.total_pausados = this.gestionmovimientos_filtrado.filter(item => item.idestado_etapa == 3).length;
        this.total_con_inventario = this.gestionmovimientos_filtrado.filter(item => item.fecha_inventario != null).length;
    }
    
    getDetalleAlmacen(idalmacen: number){
        this._almacenesService.veralmacenubicaciones(this.token, idalmacen).subscribe(
            response =>{
                //this.almacen_ubicaciones = response.almacen_ubicaciones;
                let ubicaciones=response.almacen_ubicaciones;

                this.almacen_ubicaciones = ubicaciones.filter(pr => {
                    if(!pr.ubicacion_unica){
                        return true;
                    }else{
                        if(pr.idalmacendetalle==this.idalmacendetalle){
                            return true;
                        }else{
                            if(pr.items.length==0){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    }
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararVer(indice: number){
        this.idate_gas_etapa = this.gestionmovimientos_filtrado[indice].idate_gas_etapa;
        this.chasis = this.gestionmovimientos_filtrado[indice].chasis;
        this.marca = this.gestionmovimientos_filtrado[indice].marca;
        this.modelo = this.gestionmovimientos_filtrado[indice].modelo;
        this.tecnicos = this.gestionmovimientos_filtrado[indice].tecnicos;
        this.tecnicos_qa = this.gestionmovimientos_filtrado[indice].tecnicos_qa;
        this.idetapa = this.gestionmovimientos_filtrado[indice].idetapa;
        this.idalmacendetalle = this.gestionmovimientos_filtrado[indice].idalmacendetalle;
        this.getDetalleAlmacen(this.gestionmovimientos_filtrado[indice].idalmacen);
        
        this.visible_ver=true;
    }
    
    guardarVer(){
        let payload={
            idalmacendetalle: this.idalmacendetalle,
            idetapa: this.idetapa
        };
        this.cargando=true;
        this._almacenesService.guardargestionmovimientovista(this.token, this.idate_gas_etapa, payload).subscribe(
            response =>{
                //console.log(response);
                this.cargando=false;
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getGestionMovimiento();
                    this.visible_ver=false;
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
                this.cargando=false;
            }
        );
    }
    
    prepararInventario(indice: number){
        void this.stopCamera();
        this.cameraVisible=false;
        this.cameraTarget=null;
        this.uploadedFiles=[];
        this.uploadedFilesMain=[];
        this.imagenesMain=[];

        this.idate_gas_etapa = this.gestionmovimientos_filtrado[indice].idate_gas_etapa;
        this.chasis = this.gestionmovimientos_filtrado[indice].chasis;
        this.marca = this.gestionmovimientos_filtrado[indice].marca;
        this.modelo = this.gestionmovimientos_filtrado[indice].modelo;
        this.ubicacion = this.gestionmovimientos_filtrado[indice].ubicacion;
        this.observaciones_inventario = this.gestionmovimientos_filtrado[indice].observaciones_inventario;
        //this.inventario = this.gestionmovimientos[indice].inventario;
        
        
        this._almacenesService.vergestionmovimientoinventario(this.token, this.idate_gas_etapa).subscribe(
            response =>{
                
                this.inventario = response.inventario;
                this.uploadedFiles = this.inventario.map(() => []);
                //this.uploadedFilesMain=[];
                //console.log(this.uploadedFiles);
                //this.imagenes =response.inventario.imagenes;
                this._almacenesService.vergestionmovimientoimagenes(this.token, this.idate_gas_etapa).subscribe(
                    response_imagenes =>{
                        //console.log(response.imagenes);
                        this.imagenesMain=response_imagenes.imagenes;
                        //this.visible_inventario=true;
                        //this.inventario = response.inventario;
                        this.visible_inventario=true;
                        //console.log(this.uploadedFilesMain);
                        //this.imagenes =response.inventario.imagenes;
                        
                    },
                    error_imagenes=>{
                        console.log(<any>error_imagenes)
                    }
                );
                
            },
            error=>{
                console.log(<any>error)
            }
        );

        
        
        
        
    }

    async onGallerySelected(event: Event, index: number): Promise<void> {
        const input = event.target as HTMLInputElement;
        const selectedFiles = Array.from(input.files ?? []);
        const validFiles = selectedFiles.filter(file => file.size <= this.maxImageSize);

        if (validFiles.length !== selectedFiles.length) {
            this.showToast('Error', 'Cada imagen debe tener un tamaño máximo de 20 MB.');
        }

        if (validFiles.length > 0) {
            await this.onSelectFiles({ files: validFiles }, index);
        }

        input.value = '';
    }

    async onSelectFiles(event: any, index: number): Promise<void> {
        const files: File[] = event.files ?? [];
        const processedFiles: File[] = [];

        for (const file of files) {
            const fileName = file.name.toLowerCase();
            const fileType = (file.type || '').toLowerCase();

            const isHeic =
                fileName.endsWith('.heic') ||
                fileName.endsWith('.heif') ||
                fileType === 'image/heic' ||
                fileType === 'image/heif';

            if (isHeic) {
                try {
                    const conversionResult = await heic2any({
                        blob: file,
                        toType: 'image/jpeg',
                        quality: 0.9
                    });
                    const convertedBlob = Array.isArray(conversionResult)
                        ? conversionResult[0]
                        : conversionResult;

                    const newFileName = file.name.replace(/\.(heic|heif)$/i, '.jpg');

                    const convertedFile = new File(
                        [convertedBlob],
                        newFileName,
                        { type: 'image/jpeg' }
                    );

                    processedFiles.push(convertedFile);
                } catch (error) {
                    console.error(`Error convirtiendo ${file.name}:`, error);
                    this.showToast('Error', `No se pudo convertir ${file.name} a JPEG.`);
                }
            } else {
                processedFiles.push(file);
            }
        }

        const filesWithinLimit = processedFiles.filter(file => file.size <= this.maxImageSize);
        if (filesWithinLimit.length !== processedFiles.length) {
            this.showToast('Error', 'Una imagen procesada supera el límite de 20 MB y no fue agregada.');
        }

        if (index === -1) {
            this.uploadedFilesMain = [
                ...(this.uploadedFilesMain ?? []),
                ...filesWithinLimit
            ];
        } else {
            this.uploadedFiles[index] = [
                ...(this.uploadedFiles[index] ?? []),
                ...filesWithinLimit
            ];
        }
    }
    removePendingFile(index: number, fileIndex: number): void {
        if (index === -1) {
            this.uploadedFilesMain = this.uploadedFilesMain.filter((_, currentIndex) => currentIndex !== fileIndex);
            return;
        }

        this.uploadedFiles[index] = (this.uploadedFiles[index] ?? [])
            .filter((_, currentIndex) => currentIndex !== fileIndex);
    }

    openCameraForDamage(index: number): void {
        this.openCamera({ kind: 'damage', index });
    }

    openCameraForMain(): void {
        this.openCamera({ kind: 'main' });
    }

    private openCamera(target: CameraTarget): void {
        this.cameraTarget=target;
        this.capturedFile=null;
        this.capturePreviewVisible=false;
        this.cameraVisible=true;
    }

    async startCamera(video: HTMLVideoElement, canvas: HTMLCanvasElement): Promise<void> {
        if (!navigator.mediaDevices?.getUserMedia) {
            this.showToast('Error', 'La cámara no está disponible en este navegador. Puede elegir imágenes desde la galería.');
            return;
        }

        this.cameraLoading=true;
        this.capturedFile=null;
        this.capturePreviewVisible=false;
        await this.stopCamera();
        this.currentVideo=video;

        try {
            const stream = await this.getCameraStream(this.selectedDeviceId);
            if (!this.cameraVisible) {
                stream.getTracks().forEach(track => track.stop());
                return;
            }

            this.cameraStream=stream;
            video.srcObject=stream;
            video.muted=true;
            video.setAttribute('playsinline', 'true');
            video.setAttribute('webkit-playsinline', 'true');
            await this.ensureVideoReady(video);
            await video.play();

            const [track] = stream.getVideoTracks();
            if (!track) {
                throw new Error('No se encontró una pista de video activa.');
            }

            this.configureVideoTrack(track);
            this.cameraActive=true;

            const activeDeviceId=track.getSettings?.().deviceId;
            if (activeDeviceId) {
                this.selectedDeviceId=activeDeviceId;
            }

            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                this.videoDevices=devices.filter(device => device.kind === 'videoinput');
                if (!this.selectedDeviceId && this.videoDevices.length > 0) {
                    this.selectedDeviceId=this.videoDevices[0].deviceId;
                }
            } catch (error) {
                this.videoDevices=[];
            }

            canvas.width=0;
            canvas.height=0;
        } catch (error) {
            await this.stopCamera();
            if (this.cameraVisible) {
                this.showCameraError(error);
            }
        } finally {
            this.cameraLoading=false;
        }
    }

    async changeCamera(video: HTMLVideoElement, canvas: HTMLCanvasElement): Promise<void> {
        await this.stopCamera();
        await this.startCamera(video, canvas);
    }

    async capturePhoto(video: HTMLVideoElement, canvas: HTMLCanvasElement): Promise<void> {
        if (!this.cameraActive) {
            return;
        }

        try {
            await this.ensureVideoReady(video);
            const sourceWidth=video.videoWidth;
            const sourceHeight=video.videoHeight;
            if (!sourceWidth || !sourceHeight) {
                throw new Error('La vista previa de la cámara todavía no está lista.');
            }

            const scale=Math.min(1, this.maxCaptureDimension / Math.max(sourceWidth, sourceHeight));
            canvas.width=Math.round(sourceWidth * scale);
            canvas.height=Math.round(sourceHeight * scale);
            const context=canvas.getContext('2d');
            if (!context) {
                throw new Error('No se pudo preparar la captura.');
            }

            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const blob=await this.canvasToBlob(canvas);
            if (blob.size > this.maxImageSize) {
                throw new Error('La fotografía supera el límite de 20 MB.');
            }

            this.captureSequence++;
            this.capturedFile=new File(
                [blob],
                `foto-inventario-${Date.now()}-${this.captureSequence}.jpg`,
                { type: 'image/jpeg', lastModified: Date.now() }
            );
            this.capturePreviewVisible=true;
        } catch (error) {
            const message=error instanceof Error ? error.message : 'No se pudo generar la fotografía.';
            this.showToast('Error', message);
        }
    }

    confirmCapturedPhoto(): void {
        if (!this.capturedFile || !this.cameraTarget) {
            return;
        }

        if (this.cameraTarget.kind === 'main') {
            this.uploadedFilesMain=[...this.uploadedFilesMain, this.capturedFile];
        } else {
            const index=this.cameraTarget.index;
            this.uploadedFiles[index]=[...(this.uploadedFiles[index] ?? []), this.capturedFile];
        }

        this.capturedFile=null;
        this.capturePreviewVisible=false;
    }

    cancelCapturedPhoto(): void {
        this.capturedFile=null;
        this.capturePreviewVisible=false;
    }

    get cameraTargetFileCount(): number {
        if (!this.cameraTarget) {
            return 0;
        }

        return this.cameraTarget.kind === 'main'
            ? this.uploadedFilesMain.length
            : (this.uploadedFiles[this.cameraTarget.index]?.length ?? 0);
    }

    async toggleTorch(): Promise<void> {
        if (!this.videoTrack || !this.torchSupported) {
            return;
        }

        const nextState=!this.torchOn;
        try {
            await (this.videoTrack as any).applyConstraints({ advanced: [{ torch: nextState }] });
            this.torchOn=nextState;
        } catch (firstError) {
            try {
                await (this.videoTrack as any).applyConstraints({ torch: nextState });
                this.torchOn=nextState;
            } catch (error) {
                this.showToast('Error', 'No se pudo cambiar el estado de la linterna.');
            }
        }
    }

    async closeCameraDialog(): Promise<void> {
        await this.stopCamera();
        this.cameraVisible=false;
        this.cameraTarget=null;
        this.capturedFile=null;
        this.capturePreviewVisible=false;
    }

    async onCameraDialogHide(): Promise<void> {
        await this.stopCamera();
        this.cameraTarget=null;
        this.capturedFile=null;
        this.capturePreviewVisible=false;
    }

    async stopCamera(): Promise<void> {
        const track=this.videoTrack;
        if (track && this.torchOn) {
            try {
                await (track as any).applyConstraints({ advanced: [{ torch: false }] });
            } catch (error) {
                // La pista se detendrá igualmente para liberar la cámara y apagar la linterna.
            }
        }

        this.cameraStream?.getTracks().forEach(streamTrack => streamTrack.stop());
        if (this.currentVideo) {
            this.currentVideo.pause();
            this.currentVideo.srcObject=null;
        }

        this.cameraStream=null;
        this.videoTrack=null;
        this.currentVideo=null;
        this.cameraActive=false;
        this.torchSupported=false;
        this.torchOn=false;
    }

    private async getCameraStream(deviceId?: string): Promise<MediaStream> {
        const videoBase: MediaTrackConstraints={
            width: { ideal: 1280 },
            height: { ideal: 720 }
        };
        const attempts: MediaTrackConstraints[]=[];

        if (deviceId) {
            attempts.push({ ...videoBase, deviceId: { exact: deviceId } });
        }
        attempts.push({ ...videoBase, facingMode: { ideal: 'environment' } });
        attempts.push({ ...videoBase, facingMode: { ideal: 'user' } });

        let lastError: unknown=null;
        for (const video of attempts) {
            try {
                return await navigator.mediaDevices.getUserMedia({ video, audio: false });
            } catch (error) {
                lastError=error;
            }
        }

        throw lastError ?? new Error('No se encontró una cámara disponible.');
    }

    private async ensureVideoReady(video: HTMLVideoElement): Promise<void> {
        if (video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA) {
            return;
        }

        await new Promise<void>((resolve, reject) => {
            const timeoutId=window.setTimeout(() => finish(new Error('La cámara tardó demasiado en iniciar.')), 10000);
            const finish=(error?: Error) => {
                window.clearTimeout(timeoutId);
                video.removeEventListener('loadeddata', onLoaded);
                video.removeEventListener('error', onError);
                error ? reject(error) : resolve();
            };
            const onLoaded=() => finish();
            const onError=() => finish(new Error('No se pudo iniciar la vista previa de la cámara.'));
            video.addEventListener('loadeddata', onLoaded, { once: true });
            video.addEventListener('error', onError, { once: true });
        });
    }

    private canvasToBlob(canvas: HTMLCanvasElement): Promise<Blob> {
        return new Promise<Blob>((resolve, reject) => {
            canvas.toBlob(blob => {
                if (blob) {
                    resolve(blob);
                } else {
                    reject(new Error('No se pudo generar el archivo JPEG.'));
                }
            }, 'image/jpeg', this.captureQuality);
        });
    }

    private configureVideoTrack(track: MediaStreamTrack): void {
        this.videoTrack=track;
        const capabilities=track.getCapabilities ? track.getCapabilities() as any : null;
        this.torchSupported=!!capabilities?.torch;
        this.torchOn=false;
    }

    private showCameraError(error: unknown): void {
        const errorName=error instanceof DOMException ? error.name : '';
        if (errorName === 'NotAllowedError' || errorName === 'SecurityError') {
            this.showToast('Error', 'No se otorgó permiso para usar la cámara. Puede continuar desde la galería.');
            return;
        }
        if (errorName === 'NotFoundError' || errorName === 'DevicesNotFoundError') {
            this.showToast('Error', 'No se encontró una cámara disponible. Puede continuar desde la galería.');
            return;
        }

        const detail=error instanceof Error ? ` ${error.message}` : '';
        this.showToast('Error', `No se pudo iniciar la cámara.${detail}`);
    }

    private showToast(type: string, message: string): void {
        this.toast_tipo=type;
        this.toast_mensaje=message;
        $('#liveToast').toast('show');
    }

    private async closeInventoryModal(): Promise<void> {
        await this.stopCamera();
        this.cameraVisible=false;
        this.cameraTarget=null;
        this.capturedFile=null;
        this.capturePreviewVisible=false;
        this.visible_inventario=false;
    }

    cerrarModal(event: Event){
        if(this.hasPendingFiles()){
            this.cerrarVentana(event);
        }else{
            void this.closeInventoryModal();
        }
    }

    hasPendingFiles(): boolean {
        return this.uploadedFilesMain.length > 0 ||
            this.uploadedFiles.some(files => (files?.length ?? 0) > 0);
    }

    cerrarVentana(event: Event) {
        this._confirmationService.confirm({
            target: event.target as EventTarget,
            message: '¿Está seguro que desea cerrar la ventana?, Existen imagenes cargadas y no se guardó la información',
            header: 'Confirmar',
            acceptLabel: 'Si',
            rejectLabel: 'No',
            icon: 'pi pi-exclamation-triangle',
            acceptIcon:"none",
            rejectIcon:"none",
            rejectButtonStyleClass:"p-button-text",
            accept: () => {
                void this.closeInventoryModal();
            },
            reject: () => {
                
            }
        });
    }

    

    guardarInventario(){
        this.cargando=true;
        this._almacenesService.guardargestionmovimientoinventario(this.token, this.idate_gas_etapa, this.observaciones_inventario, this.inventario, this.uploadedFiles, this.uploadedFilesMain).subscribe(
            (response) => {
                this.cargando=false;
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getGestionMovimiento();
                    void this.closeInventoryModal();
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
            },
            (error) => {
                this.cargando=false;
                console.log(error);
                //this.toast_tipo = 'Error';
            }
        );
    }

    ngOnDestroy(): void {
        if (this.intervalId !== null) {
            window.clearInterval(this.intervalId);
        }
        void this.stopCamera();
    }
    
    prepararInicio(indice: number){
        this.idate_gas_etapa = this.gestionmovimientos_filtrado[indice].idate_gas_etapa;
        this.chasis = this.gestionmovimientos_filtrado[indice].chasis;
        this.etapa = this.gestionmovimientos_filtrado[indice].etapa;
        this.ubicacion = this.gestionmovimientos_filtrado[indice].ubicacion;
        this.idestado_etapa = this.gestionmovimientos_filtrado[indice].idestado_etapa;
        
        if(this.idestado_etapa==1){
            this.titulo_iniciar="Iniciar";
            this.descripcion_iniciar="Inicio";
        }
        if(this.idestado_etapa==3){
            this.titulo_iniciar="Reanudar";
            this.descripcion_iniciar="Reanudacion";
        }
        
        this.visible_iniciar=true;
    }
    
    iniciar(){
        this._almacenesService.iniciargestionmovimiento(this.token, this.idate_gas_etapa).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getGestionMovimiento();
                    this.visible_iniciar=false;
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
            }
        );
    }
    
    prepararPausa(indice: number){
        this.idate_gas_etapa = this.gestionmovimientos_filtrado[indice].idate_gas_etapa;
        this.chasis = this.gestionmovimientos_filtrado[indice].chasis;
        this.etapa = this.gestionmovimientos_filtrado[indice].etapa;
        this.ubicacion = this.gestionmovimientos_filtrado[indice].ubicacion;
        this.idate_gas_motivo_pausa=null;
        this.error_idate_gas_motivo_pausa=false;
        this.motivo_pausa='';
        this.error_motivo_pausa=false;
        this.visible_pausar=true;
    }
    
    pausar(){
        let error=false;
        this.error_idate_gas_motivo_pausa=false;
        if(!this.idate_gas_motivo_pausa){
            error=true;
            this.error_idate_gas_motivo_pausa=true;
        }
        this.error_motivo_pausa=false;
        if (this.idate_gas_motivo_pausa==6 && this.motivo_pausa==''){
            error=true;
            this.error_motivo_pausa=true;
        }
        if(!error){
            this._almacenesService.pausargestionmovimiento(this.token, this.idate_gas_etapa, this.idate_gas_motivo_pausa, this.motivo_pausa).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.getGestionMovimiento();
                        this.visible_pausar=false;
                    }else{
                        this.toast_tipo="Error";
                    }
                    //$('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');

                },
                error=>{
                    console.log(<any>error);
                }
            );
        }
    }
    
    tiempoFormateado(segundos: number): string {
        const total = Math.max(0, Math.floor(segundos)); // por si viene decimal/negativo
        const h = Math.floor(total / 3600);
        const m = Math.floor((total % 3600) / 60);
        const s = total % 60;
        return `${h}h ${m}m ${s}s`;
    }
    
    contadorTiempo(): void {
        if (this.intervalId !== null) return;

        this.intervalId = window.setInterval(() => {
            for (const item of this.gestionmovimientos_filtrado) {
                if (item.idestado_etapa==2) item.tiempo++;
            }
        }, 1000);
    }
    
    prepararFinalizacion(indice: number){
        this.idate_gas_etapa = this.gestionmovimientos_filtrado[indice].idate_gas_etapa;
        this.chasis = this.gestionmovimientos_filtrado[indice].chasis;
        this.etapa = this.gestionmovimientos_filtrado[indice].etapa;
        this.ubicacion = this.gestionmovimientos_filtrado[indice].ubicacion;
        
        this.visible_finalizar=true;
    }
    
    finalizar(){
        this._almacenesService.finalizargestionmovimiento(this.token, this.idate_gas_etapa).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getGestionMovimiento();
                    this.visible_finalizar=false;
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error);
            }
        );
    }
}
