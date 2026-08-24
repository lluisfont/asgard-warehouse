import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';

declare var $: any;

@Component({
    selector: 'app-devoluciones',
    templateUrl: './devoluciones.component.html',
    styleUrls: ['./devoluciones.component.css'],
    providers:[UsuarioService,ContabilidadService]
})
export class DevolucionesComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public devoluciones: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_devoluciones: boolean=false;
    public editar_devoluciones: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_devoluciones=true;
            this.editar_devoluciones=true;
        }else{
            let indiceVerDevoluciones= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 50);
            if (indiceVerDevoluciones>=0){
                if (this.tokenDetalle.permisos[indiceVerDevoluciones].lectura){
                    this.ver_devoluciones=true;
                }
                if (this.tokenDetalle.permisos[indiceVerDevoluciones].escritura){
                    this.editar_devoluciones=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._contabilidadService.verdevoluciones(this.token).subscribe(
            response =>{
                this.devoluciones=response.devoluciones;
                this.devoluciones.forEach(
                    invoices => (invoices.fechadevolucion = new Date(invoices.fechadevolucion.replace(/-/g, '\/')))
                );
                
                console.log(this.devoluciones);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadDevolucion(iddevolucion: number){
        this._contabilidadService.downloadDevolucion(this.token, iddevolucion).subscribe(
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
