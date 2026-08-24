import { Component, OnInit, ViewChild } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any;

@Component({
    selector: 'app-ordenes-pago',
    templateUrl: './ordenes-pago.component.html',
    styleUrls: ['./ordenes-pago.component.css'],
    providers:[UsuarioService,ContabilidadService,DatoMaestroService]
})
export class OrdenesPagoComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public ordenespago: Array<any>;
    
    public idfacturapagoanular: number=0;
    
    public idfacturapagomigrar: number=0;
    public errormigracion: string='';
    
    
    public motivosanulacion: Array<any>;
    public idmotivoanulacion: number=null;
    public error_idmotivoanulacion: boolean=false;
    public otro_motivoanulacion: string='';
    public error_otro_motivoanulacion: boolean=false;
    @ViewChild('UploadFileInput')
    //myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean=false;
    
    public idfacturapago_anulada: number;
    public ver_motivoanulacion: string;
    public ver_fechaanulacion: string;
    public ver_usuarioanulacion: string;
    public ver_respaldoanulacion: string;
    
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_ordenes_pago: boolean=false;
    public editar_ordenes_pago: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService,
        private _datomaestroService: DatoMaestroService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_ordenes_pago=true;
            this.editar_ordenes_pago=true;
        }else{
            let indiceVerOrdenesPago= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 14);
            if (indiceVerOrdenesPago>=0){
                if (this.tokenDetalle.permisos[indiceVerOrdenesPago].lectura){
                    this.ver_ordenes_pago=true;
                }
                if (this.tokenDetalle.permisos[indiceVerOrdenesPago].escritura){
                    this.editar_ordenes_pago=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarOrdenesPago();
        this._datomaestroService.motivosanulacion(this.token).subscribe(
            response =>{
                this.motivosanulacion=response.motivosanulacion;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    cargarOrdenesPago(){
        this._contabilidadService.verordenespago(this.token).subscribe(
            response =>{
                this.ordenespago=response.ordenespago;
                this.ordenespago.forEach(
                    ordenespago => (ordenespago.fecha = new Date(ordenespago.fecha.replace(/-/g, '\/')))
                );
                this.ordenespago.forEach(
                    ordenespago => (ordenespago.saldo = ordenespago.monto-ordenespago.pagado)
                );
                
                console.log(this.ordenespago);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadOrdenPago(idfacturapago: number){
        this._contabilidadService.downloadordenpago(this.token, idfacturapago).subscribe(
            response =>{
                //console.log(response);
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
    
    prepararMigracion(idfacturapago: number){
        this.idfacturapagomigrar=idfacturapago;
        let indicefacturapago = this.ordenespago.findIndex(x => x.idfacturapago === idfacturapago);
        this.errormigracion = this.ordenespago[indicefacturapago].errorOVP;
    }
    
    migrarFacturaPago(){
        $("#confirmarMigracionOVP").modal('hide');
        $('#ventanaLoading').modal('show');
        
        this._contabilidadService.migrarOrdenPago(this.token, this.idfacturapagomigrar).subscribe(
            response =>{
                console.log(response);
                
                let indicefactura = this.ordenespago.findIndex(x => x.idfacturapago === this.idfacturapagomigrar);
                this.toast_mensaje=response.mensaje;
                console.log(indicefactura);
                this.ordenespago[indicefactura].errorOVP=response.respuesta.mensajeerror;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.ordenespago[indicefactura].outNroAsignacion=response.respuesta.outNroAsignacion;
                    
                }else{
                    this.toast_tipo="Error";
                    
                }
                $('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
                
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararAnular(idfacturapago: number){
        this.idfacturapagoanular=idfacturapago;
        this.idmotivoanulacion=null;
        this.error_idmotivoanulacion=false;
        this.otro_motivoanulacion='';
        this.error_otro_motivoanulacion=false;
        //this.uploadFileInput=null;
        //this.uploadFileInput.nativeElement.value = "";
        this.errorarchivo=false;
        //console.log(this.uploadFileInput);
    }
    
    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
            console.log(this.uploadFileInput);
            //this.myfilename=this.uploadFileInput[0].name;
        }else {
            //this.myfilename = 'Seleccione un Archivo';
        }
    }
    
    anularFactura(){
        this.error_idmotivoanulacion=false;
        if (this.idmotivoanulacion==null){
            this.error_idmotivoanulacion=true;
        }
        this.error_otro_motivoanulacion=false;
        if (this.idmotivoanulacion==5 && this.otro_motivoanulacion==''){
            this.error_otro_motivoanulacion=true;
        }
        this.errorarchivo=false;
        if(!this.archivocargado){
            this.errorarchivo=true;
        }
        
        if(!this.error_idmotivoanulacion && !this.error_otro_motivoanulacion && !this.errorarchivo){
            $("#confirmarAnularFactura").modal('hide');
            $('#ventanaLoading').modal('show');

            this._contabilidadService.anularOrdenPago(this.token, this.idfacturapagoanular, this.idmotivoanulacion, this.otro_motivoanulacion, this.uploadFileInput).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.cargarOrdenesPago();
                    }else{
                        this.toast_tipo="Error";

                    }
                    $('#ventanaLoading').modal('hide');
                    $("#liveToast").toast('show');

                },
                error=>{
                    console.log(<any>error);
                    $('#ventanaLoading').modal('hide');
                }
            );
        }
            
    }
    
    datosAnulacion(idfacturapago: number){
        let indicefacturapago = this.ordenespago.findIndex(x => x.idfacturapago === idfacturapago);
        this.idfacturapago_anulada=idfacturapago;
        this.ver_fechaanulacion = this.ordenespago[indicefacturapago].fecha_anulacion;
        this.ver_usuarioanulacion = this.ordenespago[indicefacturapago].usuario_anulacion;
        this.ver_motivoanulacion = this.ordenespago[indicefacturapago].motivoanulacion+" "+this.ordenespago[indicefacturapago].otro_motivoanulacion;
        this.ver_respaldoanulacion = this.ordenespago[indicefacturapago].resplado_anulacion;
        
    }
    
    descargarResplado(){
        let archivo = 'respaldos_facturaspago_anuladas/' + this.idfacturapago_anulada+'/'+this.ver_respaldoanulacion;
        this._contabilidadService.downloadresplado(this.token, archivo).subscribe(
            response =>{
                if(response.codigo==200){
                    const linkSource = 'data:'+response.pathinfo+';base64,'+response.data;
                    const downloadLink = document.createElement("a");
                    const fileName = this.ver_respaldoanulacion;

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

}
