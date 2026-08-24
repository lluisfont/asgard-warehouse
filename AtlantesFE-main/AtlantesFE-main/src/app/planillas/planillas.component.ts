import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {ContabilidadService} from '../services/contabilidad.service';

declare var $: any;

@Component({
    selector: 'app-planillas',
    templateUrl: './planillas.component.html',
    styleUrls: ['./planillas.component.css'],
    providers:[UsuarioService,ContabilidadService]
})
export class PlanillasComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public planillas: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_planillas: boolean=false;
    public editar_planillas: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _contabilidadService: ContabilidadService
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_planillas=true;
            this.editar_planillas=true;
        }else{
            let indiceVerPlanillas= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 48);
            if (indiceVerPlanillas>=0){
                if (this.tokenDetalle.permisos[indiceVerPlanillas].lectura){
                    this.ver_planillas=true;
                }
                if (this.tokenDetalle.permisos[indiceVerPlanillas].escritura){
                    this.editar_planillas=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._contabilidadService.verplanillas(this.token).subscribe(
            response =>{
                this.planillas=response.planillas;
                this.planillas.forEach(
                    planillas => (planillas.fecha = new Date(planillas.fecha.replace(/-/g, '\/')))
                );
                
                console.log(this.planillas);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    downloadPlanilla(idplanilla: number){
        this._contabilidadService.downloadplanilla(this.token, idplanilla).subscribe(
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
