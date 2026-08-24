import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';

declare var $: any;

@Component({
    selector: 'app-invoices',
    templateUrl: './invoices.component.html',
    styleUrls: ['./invoices.component.css'],
    providers:[UsuarioService,ContabilidadService]
})
export class InvoicesComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public invoices: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_invoices: boolean=false;
    public editar_invoices: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_invoices=true;
            this.editar_invoices=true;
        }else{
            let indiceVerInvoices= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 49);
            if (indiceVerInvoices>=0){
                if (this.tokenDetalle.permisos[indiceVerInvoices].lectura){
                    this.ver_invoices=true;
                }
                if (this.tokenDetalle.permisos[indiceVerInvoices].escritura){
                    this.editar_invoices=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._contabilidadService.verinvoices(this.token).subscribe(
            response =>{
                this.invoices=response.invoices;
                this.invoices.forEach(
                    invoices => (invoices.fecha = new Date(invoices.fecha.replace(/-/g, '\/')))
                );
                
                console.log(this.invoices);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadInvoice(idinvoice: number){
        this._contabilidadService.downloadInvoice(this.token, idinvoice).subscribe(
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
    
    downloadInvoiceMembretada(idinvoice: number){
        this._contabilidadService.downloadInvoiceMembretada(this.token, idinvoice).subscribe(
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

}
