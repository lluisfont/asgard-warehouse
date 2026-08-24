import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';

@Component({
    selector: 'app-dashboard-ate-gas',
    templateUrl: './dashboard-ate-gas.component.html',
    styleUrl: './dashboard-ate-gas.component.css',
    providers:[UsuarioService]
})
export class DashboardAteGasComponent {
    public token: string;
    public tokenDetalle: any;
    
    public ver_dashboard_ate_gas: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_dashboard_ate_gas=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 104);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_dashboard_ate_gas=true;
                }
            }
        }
    
    }

}
