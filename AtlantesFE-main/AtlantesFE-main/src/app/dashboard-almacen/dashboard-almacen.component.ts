import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';

@Component({
    selector: 'app-dashboard-almacen',
    templateUrl: './dashboard-almacen.component.html',
    styleUrls: ['./dashboard-almacen.component.css'],
    providers:[UsuarioService]
})
export class DashboardAlmacenComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public ver_dashboard_interno_almacen: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_dashboard_interno_almacen=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 85);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_dashboard_interno_almacen=true;
                }
            }
        }
    
    }

  ngOnInit(): void {
  }

}
