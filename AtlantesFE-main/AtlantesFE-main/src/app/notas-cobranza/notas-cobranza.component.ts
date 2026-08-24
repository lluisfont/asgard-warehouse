import { Component, OnInit, ViewChild } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {DatoMaestroService} from '../services/datomaestro.service';

declare var $: any;

@Component({
    selector: 'app-notas-cobranza',
    templateUrl: './notas-cobranza.component.html',
    styleUrls: ['./notas-cobranza.component.css'],
    providers:[UsuarioService,ContabilidadService,DatoMaestroService]
})
export class NotasCobranzaComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public notascobranza: Array<any>;
    
    public idnotadebitoanular: number=0;
    
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
    
    public idnotadebito_anulada: number;
    public ver_motivoanulacion: string;
    public ver_fechaanulacion: string;
    public ver_usuarioanulacion: string;
    public ver_respaldoanulacion: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_notas_cobranza: boolean=false;
    public editar_notas_cobranza: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService,
        private _datomaestroService: DatoMaestroService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_notas_cobranza=true;
            this.editar_notas_cobranza=true;
        }else{
            let indiceVerNotasCobrnza= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 13);
            if (indiceVerNotasCobrnza>=0){
                if (this.tokenDetalle.permisos[indiceVerNotasCobrnza].lectura){
                    this.ver_notas_cobranza=true;
                }
                if (this.tokenDetalle.permisos[indiceVerNotasCobrnza].escritura){
                    this.editar_notas_cobranza=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarNotasCobranza();
        this._datomaestroService.motivosanulacion(this.token).subscribe(
            response =>{
                this.motivosanulacion=response.motivosanulacion;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    cargarNotasCobranza(){
        this._contabilidadService.vernotascobranza(this.token).subscribe(
            response =>{
                this.notascobranza=response.notascobranza;
                this.notascobranza.forEach(
                    notascobranza => (notascobranza.fecha = new Date(notascobranza.fecha.replace(/-/g, '\/')))
                );
                console.log(this.notascobranza);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadNotaCobranza(idnotadebito: number){
        this._contabilidadService.downloadNC(this.token, idnotadebito).subscribe(
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
    
    downloadNotaCobranzaMembretada(idnotadebito: number){
        this._contabilidadService.downloadNCMembretada(this.token, idnotadebito).subscribe(
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
    
    prepararAnular(idnotadebito: number){
        this.idnotadebitoanular=idnotadebito;
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
    
    anularNotaCobranza(){
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
            $("#confirmarAnularNotaCobranza").modal('hide');

            this._contabilidadService.anularNotaCobranza(this.token, this.idnotadebitoanular, this.idmotivoanulacion, this.otro_motivoanulacion, this.uploadFileInput).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.cargarNotasCobranza();

                    }else{
                        this.toast_tipo="Error";

                    }
                    $("#liveToast").toast('show');

                },
                error=>{
                    console.log(<any>error);
                    $('#ventanaLoading').modal('hide');
                }
            );
        }
            
    }
    
    datosAnulacion(idnotadebito: number){
        let indicenotadebito = this.notascobranza.findIndex(x => x.idnotadebito === idnotadebito);
        this.idnotadebito_anulada=idnotadebito;
        this.ver_fechaanulacion = this.notascobranza[indicenotadebito].fecha_anulacion;
        this.ver_usuarioanulacion = this.notascobranza[indicenotadebito].usuario_anulacion;
        this.ver_motivoanulacion = this.notascobranza[indicenotadebito].motivoanulacion+" "+this.notascobranza[indicenotadebito].otro_motivoanulacion;
        this.ver_respaldoanulacion = this.notascobranza[indicenotadebito].resplado_anulacion;
        
    }
    
    descargarResplado(){
        let archivo = 'respaldos_notasdebito_anuladas/' + this.idnotadebito_anulada+'/'+this.ver_respaldoanulacion;
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
