import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {DatoMaestroService} from '../services/datomaestro.service';

declare var $: any;

@Component({
    selector: 'app-facturas',
    templateUrl: './facturas.component.html',
    providers:[UsuarioService,ContabilidadService,DatoMaestroService]
})
export class FacturasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public facturas: Array<any>;
    
    public idfacturaanular: number=0;
    
    public idfacturamigrar: number=0;
    public errormigracion: string='';
    
    public correos_factura: Array<any>;
    public entidades: Array<any>;
    
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
    
    public idfactura_anulada: number;
    public ver_motivoanulacion: string;
    public ver_fechaanulacion: string;
    public ver_usuarioanulacion: string;
    public ver_respaldoanulacion: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_facturas: boolean=false;
    public editar_facturas: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService,
        private _datomaestroService: DatoMaestroService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_facturas=true;
            this.editar_facturas=true;
        }else{
            let indiceVerFacturas= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 12);
            if (indiceVerFacturas>=0){
                if (this.tokenDetalle.permisos[indiceVerFacturas].lectura){
                    this.ver_facturas=true;
                }
                if (this.tokenDetalle.permisos[indiceVerFacturas].escritura){
                    this.editar_facturas=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarFacturas();
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades=response.entidades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.motivosanulacion(this.token).subscribe(
            response =>{
                this.motivosanulacion=response.motivosanulacion;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
    }
    
    cargarFacturas(){
        this._contabilidadService.verfacturas(this.token).subscribe(
            response =>{
                this.facturas=response.facturas;
                console.log(this.facturas);
                this.facturas.forEach(
                    facturas => (facturas.fecha = new Date(facturas.fecha.replace(/-/g, '\/')))
                );
                
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadFactura(idfactura: number){
        this._contabilidadService.downloadFactura(this.token, idfactura).subscribe(
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
    
    downloadFacturaMembretada(idfactura: number){
        this._contabilidadService.downloadFacturaMembretada(this.token, idfactura).subscribe(
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
    
    prepararMigracion(idfactura: number){
        this.idfacturamigrar=idfactura;
        let indicefactura = this.facturas.findIndex(x => x.idfactura === idfactura);
        this.errormigracion = this.facturas[indicefactura].errorOVPFact;
        
        let idcobrara=this.facturas[indicefactura].idcobrara;
        let idcobraratipo=this.facturas[indicefactura].idcobraratipo;
        
        //console.log(idcobrara+"-"+idcobraratipo);
       
        
        
        let indiceCliente = this.entidades.findIndex(x => x.identidad === (idcobraratipo+"-"+idcobrara));
        console.log(this.entidades[indiceCliente]);
        this.correos_factura=this.entidades[indiceCliente].correosfacturacion;
        
    }
    
    
    agregarCorreoFactura(){
        this.correos_factura.push({
            'idcorreofacturacion': 0,
            'correo': '',
            'error': false
        });
        console.log(this.correos_factura);
    }

    eliminarCorreoFactura(indice: number){
        this.correos_factura.splice(indice, 1);
        console.log(this.correos_factura);
    }
    
    trackByFn(index: number, item: any) {
        return item.id;
    }
    

    migrarFactura(){
        $("#confirmarMigracionOVP").modal('hide');
        $('#ventanaLoading').modal('show');
        
        this._contabilidadService.migrarFactura(this.token, this.idfacturamigrar, this.correos_factura).subscribe(
            response =>{
                console.log(response);
                let indicefactura = this.facturas.findIndex(x => x.idfactura === this.idfacturamigrar);
                this.toast_mensaje=response.mensaje;
                this.facturas[indicefactura].errorOVPFact=response.respuesta.mensajeerror;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.facturas[indicefactura].outIdOrdenFactura=response.respuesta.outIdOrdenFactura;
                    this.facturas[indicefactura].outNumeroFactura=response.respuesta.outNumeroFactura;
                    this.facturas[indicefactura].idordenovp=response.respuesta.idordenovp;
                    this.facturas[indicefactura].nrofactura=response.respuesta.outNumeroFactura;
                    
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
    
    prepararAnular(idfactura: number){
        this.idfacturaanular=idfactura;
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

            this._contabilidadService.anularFactura(this.token, this.idfacturaanular, this.idmotivoanulacion, this.otro_motivoanulacion, this.uploadFileInput).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.cargarFacturas();
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
    
    datosAnulacion(idfactura: number){
        let indicefactura = this.facturas.findIndex(x => x.idfactura === idfactura);
        this.idfactura_anulada=idfactura;
        this.ver_fechaanulacion = this.facturas[indicefactura].fecha_anulacion;
        this.ver_usuarioanulacion = this.facturas[indicefactura].usuario_anulacion;
        this.ver_motivoanulacion = this.facturas[indicefactura].motivoanulacion+" "+this.facturas[indicefactura].otro_motivoanulacion;
        this.ver_respaldoanulacion = this.facturas[indicefactura].resplado_anulacion;
        
    }
    
    descargarResplado(){
        let archivo = 'respaldos_facturas_anuladas/' + this.idfactura_anulada+'/'+this.ver_respaldoanulacion;
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
