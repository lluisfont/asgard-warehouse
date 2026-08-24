import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';

declare var $: any;

@Component({
    selector: 'app-pagos-agente-exterior',
    templateUrl: './pagos-agente-exterior.component.html',
    styleUrls: ['./pagos-agente-exterior.component.css'],
    providers:[UsuarioService,ContabilidadService]
})
export class PagosAgenteExteriorComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public pagosagenteexterior: Array<any>;
    
    public idfacturapagomigrar: number=0;
    public errormigracion: string='';
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_pagos_agente_exterior: boolean=false;
    public editar_pagos_agente_exterior: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_pagos_agente_exterior=true;
            this.editar_pagos_agente_exterior=true;
        }else{
            let indiceVerPagosAgenteExterior= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 47);
            if (indiceVerPagosAgenteExterior>=0){
                if (this.tokenDetalle.permisos[indiceVerPagosAgenteExterior].lectura){
                    this.ver_pagos_agente_exterior=true;
                }
                if (this.tokenDetalle.permisos[indiceVerPagosAgenteExterior].escritura){
                    this.editar_pagos_agente_exterior=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._contabilidadService.verpagosagenteexterior(this.token).subscribe(
            response =>{
                this.pagosagenteexterior=response.pagosagenteexterior;
                this.pagosagenteexterior.forEach(
                    pagosagenteexterior => (pagosagenteexterior.fecha = new Date(pagosagenteexterior.fecha.replace(/-/g, '\/')))
                );
                
                console.log(this.pagosagenteexterior);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadPagoAgenteExterior(idfacturapago: number){
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
        let indicefacturapago = this.pagosagenteexterior.findIndex(x => x.idfacturapago === idfacturapago);
        this.errormigracion = this.pagosagenteexterior[indicefacturapago].errorOVP;
    }
    
    migrarPagoAgenteExterior(){
        $("#confirmarMigracionOVP").modal('hide');
        $('#ventanaLoading').modal('show');
        
        this._contabilidadService.migrarOrdenPago(this.token, this.idfacturapagomigrar).subscribe(
            response =>{
                console.log(response);
                
                let indicefactura = this.pagosagenteexterior.findIndex(x => x.idfacturapago === this.idfacturapagomigrar);
                this.toast_mensaje=response.mensaje;
                console.log(indicefactura);
                this.pagosagenteexterior[indicefactura].errorOVP=response.respuesta.mensajeerror;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.pagosagenteexterior[indicefactura].outNroAsignacion=response.respuesta.outNroAsignacion;
                    
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

}
