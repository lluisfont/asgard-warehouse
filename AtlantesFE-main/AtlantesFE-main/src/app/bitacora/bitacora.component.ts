import { Component } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {AlmacenesService} from '../services/almacenes.service';

@Component({
    selector: 'app-bitacora',
    templateUrl: './bitacora.component.html',
    styleUrl: './bitacora.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService,EntidadesService]
})
export class BitacoraComponent {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;
    
    public idcliente: number;
    public error_idcliente: boolean=false;
    public chasis: string='';
    public error_chasis: boolean=false;
    public generado: boolean=false;
    public bitacora_visible: boolean=false;
    
    public ver_bitacora: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _entidadService: EntidadesService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_bitacora=true;
        }else{
            let indiceVerBitacora = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 46);
            if (indiceVerBitacora>=0){
                if (this.tokenDetalle.permisos[indiceVerBitacora].lectura){
                    this.ver_bitacora=true;
                }
            }
        }
        this.idcliente=null;
    }

    ngOnInit(): void {
        this._entidadService.vercliente(this.token).subscribe(
            response =>{
                this.entidades = response.clientes;
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    verBitacora(){
        this.bitacora_visible=false;
        let error=false;
        if (!this.idcliente){
            error=true;
            this.error_idcliente=true;
        }
        if (!this.chasis){
            error=true;
            this.error_chasis=true;
        }
        if (!error){
            this.bitacora_visible=true;
        }
    }

}
