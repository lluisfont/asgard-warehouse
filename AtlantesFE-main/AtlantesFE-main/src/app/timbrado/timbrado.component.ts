import { Component } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { Router } from '@angular/router';
declare var $: any;

@Component({
    selector: 'app-timbrado',
    templateUrl: './timbrado.component.html',
    styleUrl: './timbrado.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class TimbradoComponent {
    public token: string;
    public tokenDetalle: any;
    
    public timbrados: Array<any>;
    public entidades: Array<any>;
    
    public idcliente: number;
    public erroridcliente: boolean;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_timbrado: boolean=false;
    public editar_timbrado: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_timbrado=true;
            this.editar_timbrado=true;
        }else{
            let indiceVerTimbrado = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 26);
            if (indiceVerTimbrado>=0){
                if (this.tokenDetalle.permisos[indiceVerTimbrado].lectura){
                    this.ver_timbrado=true;
                }
                if (this.tokenDetalle.permisos[indiceVerTimbrado].escritura){
                    this.editar_timbrado=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._entidadesService.vercliente(this.token).subscribe(
            response =>{
                this.entidades = response.clientes;
                console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.verTimbrado();
    }
    
    verTimbrado(){
        this.timbrados=[];
        this._almacenesService.vertimbrados(this.token).subscribe(
            response =>{
                this.timbrados=response.timbrados;

                console.log(this.timbrados);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    preperarAgregar(){
        this.idcliente=null;
        this.erroridcliente=false;
    }
    
    crearTimbrado(){
        this.erroridcliente=false;
        if (this.idcliente==null || this.idcliente==0){
            this.erroridcliente=true;
        }
        
        if(!this.erroridcliente){
            let datostimbrado = {
                idcliente: this.idcliente
            };
            this._almacenesService.creartimbrado(this.token, datostimbrado).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        $('#nuevoTimbrado').modal('hide');
                        this.abrirDetalle(response.idtimbrado);
                    }else{
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
    
    abrirDetalle(idtimbrado: number){
        this._router.navigate(['/timbrado-detalle',idtimbrado])
    }
    
    abrirDetalleNuevo(idtimbrado:number){
        let newRelativeUrl = this._router.createUrlTree(["/timbrado-detalle",idtimbrado]);
        let baseUrl = window.location.href.replace(this._router.url, '');
        window.open(baseUrl + newRelativeUrl, '_blank');
    }
}
