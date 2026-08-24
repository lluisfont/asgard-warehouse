import { GestionMovimientoComponent } from './gestion-movimiento.component';

describe('GestionMovimientoComponent - inventario visual', () => {
    let component: GestionMovimientoComponent;
    let confirmationService: { confirm: jasmine.Spy };

    beforeEach(() => {
        const usuarioService = {
            getToken: () => 'token-prueba',
            getTokenDetalle: () => ({ idtipousuario: 1, permisos: [] })
        };
        confirmationService = { confirm: jasmine.createSpy('confirm') };

        component = new GestionMovimientoComponent(
            usuarioService as any,
            {} as any,
            {} as any,
            {} as any,
            confirmationService as any,
            {} as any
        );
        component.inventario = [
            { iddanios_vehiculos: 10, marcado: true },
            { iddanios_vehiculos: 20, marcado: true }
        ];
        component.uploadedFiles = [[], []];
        component.uploadedFilesMain = [];
    });

    it('agrega una captura solamente al daño activo', () => {
        const photo = createImageFile('captura-danio.jpg');
        component.cameraTarget = { kind: 'damage', index: 1 };
        component.capturedFile = photo;

        component.confirmCapturedPhoto();

        expect(component.uploadedFiles[0]).toEqual([]);
        expect(component.uploadedFiles[1]).toEqual([photo]);
        expect(component.uploadedFilesMain).toEqual([]);
    });

    it('agrega una captura al destino general', () => {
        const photo = createImageFile('captura-general.jpg');
        component.cameraTarget = { kind: 'main' };
        component.capturedFile = photo;

        component.confirmCapturedPhoto();

        expect(component.uploadedFilesMain).toEqual([photo]);
        expect(component.uploadedFiles[0]).toEqual([]);
    });

    it('descarta una captura no confirmada', () => {
        component.cameraTarget = { kind: 'damage', index: 0 };
        component.capturedFile = createImageFile('descartada.jpg');
        component.capturePreviewVisible = true;

        component.cancelCapturedPhoto();

        expect(component.capturedFile).toBeNull();
        expect(component.capturePreviewVisible).toBeFalse();
        expect(component.uploadedFiles[0]).toEqual([]);
    });

    it('acumula archivos de galería y cámara en el mismo destino', async () => {
        const galleryFile = createImageFile('galeria.png', 'image/png');
        const cameraFile = createImageFile('camara.jpg');

        await component.onSelectFiles({ files: [galleryFile] }, 0);
        component.cameraTarget = { kind: 'damage', index: 0 };
        component.capturedFile = cameraFile;
        component.confirmCapturedPhoto();

        expect(component.uploadedFiles[0]).toEqual([galleryFile, cameraFile]);
    });

    it('elimina el archivo indicado del destino correcto', () => {
        const first = createImageFile('primera.jpg');
        const second = createImageFile('segunda.jpg');
        const main = createImageFile('general.jpg');
        component.uploadedFiles = [[first, second], []];
        component.uploadedFilesMain = [main];

        component.removePendingFile(0, 0);

        expect(component.uploadedFiles[0]).toEqual([second]);
        expect(component.uploadedFilesMain).toEqual([main]);
    });

    it('detiene todas las pistas y limpia el estado de cámara', async () => {
        const videoTrack = createTrack(true);
        const audioTrack = createTrack(false);
        const video = document.createElement('video');
        spyOn(video, 'pause');
        (component as any).cameraStream = {
            getTracks: () => [videoTrack, audioTrack]
        } as any;
        (component as any).videoTrack = videoTrack;
        (component as any).currentVideo = video;
        component.cameraActive = true;
        component.torchSupported = true;
        component.torchOn = true;

        await component.stopCamera();

        expect(videoTrack.stop).toHaveBeenCalled();
        expect(audioTrack.stop).toHaveBeenCalled();
        expect(videoTrack.applyConstraints).toHaveBeenCalledWith({ advanced: [{ torch: false }] });
        expect(component.cameraActive).toBeFalse();
        expect(component.torchSupported).toBeFalse();
        expect(component.torchOn).toBeFalse();
        expect(video.srcObject).toBeNull();
    });

    it('solo alterna la linterna cuando está soportada', async () => {
        const track = createTrack(true);
        (component as any).videoTrack = track;

        component.torchSupported = false;
        await component.toggleTorch();
        expect(track.applyConstraints).not.toHaveBeenCalled();

        component.torchSupported = true;
        await component.toggleTorch();
        expect(track.applyConstraints).toHaveBeenCalledWith({ advanced: [{ torch: true }] });
        expect(component.torchOn).toBeTrue();
    });

    it('detecta la capacidad de linterna en la pista activa', () => {
        (component as any).configureVideoTrack(createTrack(true));
        expect(component.torchSupported).toBeTrue();

        (component as any).configureVideoTrack(createTrack(false));
        expect(component.torchSupported).toBeFalse();
    });

    it('advierte al cerrar cuando hay fotografías pendientes', () => {
        component.uploadedFilesMain = [createImageFile('pendiente.jpg')];

        component.cerrarModal(new Event('click'));

        expect(confirmationService.confirm).toHaveBeenCalled();
    });

    function createImageFile(name: string, type: string='image/jpeg'): File {
        return new File(['imagen'], name, { type, lastModified: 1 });
    }

    function createTrack(withTorch: boolean): any {
        return {
            stop: jasmine.createSpy('stop'),
            applyConstraints: jasmine.createSpy('applyConstraints').and.resolveTo(),
            getCapabilities: () => withTorch ? { torch: true } : {}
        };
    }
});
