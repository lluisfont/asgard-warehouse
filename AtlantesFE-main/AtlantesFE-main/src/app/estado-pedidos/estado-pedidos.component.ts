import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import { FileUploadEvent } from 'primeng/fileupload';

import {GLOBAL} from './../global';

declare var $: any;

@Component({
    selector: 'app-estado-pedidos',
    templateUrl: './estado-pedidos.component.html',
    styleUrl: './estado-pedidos.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class EstadoPedidosComponent {
    public token: string;
    public tokenDetalle: any;
    
    public estadopedidos: Array<any>=[];
    
    public mensajes_error: Array<any>=[];
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_estado_pedidos: boolean=false;
    public editar_estado_pedidos: boolean=false;
    
    public cargando: boolean=false;
    
    public visible_historial: boolean=false;

    public filtro_chasis: string='';
    
    public tecnicos: Array<any>=[];
    public filtro_tecnico: number=null;
    public etapas: Array<any>=[];
    public filtro_etapa: number=null;
    public filtro_marca: string='';
    
    public estadopedido: Array<any>=[];
    
    public chasis: string='';
    public marca: string='';
    public modelo: string='';
    public color: string='';
    public configuracion: string='';
    public ubicacion: string='';

    
    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        //private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_estado_pedidos=true;
            this.editar_estado_pedidos=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 98);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_estado_pedidos=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_estado_pedidos=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this._almacenesService.verasignaciontrabajotecnicos(this.token).subscribe(
            response =>{
                this.tecnicos = response.tecnicos;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.etapas(this.token).subscribe(
            response =>{
                this.etapas = response.etapas;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    getEstadoPedidos(){
        this.estadopedidos=[];
        let payload={
            chasis: this.filtro_chasis,
            idtecnico: this.filtro_tecnico,
            idetapa: this.filtro_etapa,
            marca: this.filtro_marca
        };
        this._almacenesService.verestadopedidos(this.token, payload).subscribe(
            response =>{
                this.estadopedidos = response.estadopedidos;
                console.log(this.estadopedidos);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    verDetalle(idate_gas: number){
        this.estadopedido=[];
        
        let indiceAteGas = this.estadopedidos.findIndex(x => x.idate_gas === idate_gas);
        if(indiceAteGas>=0){
            this.chasis = this.estadopedidos[indiceAteGas].chasis;
            this.marca = this.estadopedidos[indiceAteGas].marca;
            this.modelo = this.estadopedidos[indiceAteGas].modelo;
            this.color = this.estadopedidos[indiceAteGas].color;
            this.configuracion = this.estadopedidos[indiceAteGas].configuracion;
            this.ubicacion = this.estadopedidos[indiceAteGas].ubicacion;
        }
        
        
        this._almacenesService.verdetalleestadopedidos(this.token, idate_gas).subscribe(
            response =>{
                this.estadopedido = response.estadopedido;
                console.log(this.estadopedido);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
        this.visible_historial=true;
    }
    
    tiempoFormateado(segundos: number): string {
        const total = Math.max(0, Math.floor(segundos)); // por si viene decimal/negativo
        const h = Math.floor(total / 3600);
        const m = Math.floor((total % 3600) / 60);
        const s = total % 60;
        return `${h}h ${m}m ${s}s`;
    }

}
