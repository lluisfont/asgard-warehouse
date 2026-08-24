import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
declare var $: any; 

@Component({
    selector: 'app-cambiar-cliente-almacen',
    templateUrl: './cambiar-cliente-almacen.component.html',
    styleUrls: ['./cambiar-cliente-almacen.component.css'],
    providers:[UsuarioService,EntidadesService]
})
export class CambiarClienteAlmacenComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public clientes: Array<any>;

    public idcliente: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_cambio_cliente: boolean=false;
    public editar_cambio_cliente: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _entidadesService: EntidadesService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_cambio_cliente=true;
            this.editar_cambio_cliente=true;
        }else{
            let indiceVerCambioCliente = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 23);
            if (indiceVerCambioCliente>=0){
                if (this.tokenDetalle.permisos[indiceVerCambioCliente].lectura){
                    this.ver_cambio_cliente=true;
                }
                if (this.tokenDetalle.permisos[indiceVerCambioCliente].escritura){
                    this.editar_cambio_cliente=true;
                }
            }
        }
        this.idcliente=this.tokenDetalle.idcliente_almacen;
    }

    ngOnInit(): void {
        this._entidadesService.vercliente(this.token).subscribe(
            response =>{

                this.clientes = response.clientes;

                console.log(response.clientes);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
    }
    
    cambiarCliente(){
        let idcliente_almacen='cfcd208495d565ef66e7dff9f98764da';
        if(this.idcliente!=null){
            idcliente_almacen=this.idcliente;
        }
        
        this._usuarioService.cambiarclientealmacen(this.token, {'idcliente_almacen': idcliente_almacen}).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this._usuarioService.setToken(response.token);
                }else{
                    this.toast_tipo="Error";
                }
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        console.log(idcliente_almacen);
    }

}
