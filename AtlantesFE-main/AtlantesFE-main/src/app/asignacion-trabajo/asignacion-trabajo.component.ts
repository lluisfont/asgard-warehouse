import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';

import {GLOBAL} from './../global';

declare var $: any;

@Component({
    selector: 'app-asignacion-trabajo',
    templateUrl: './asignacion-trabajo.component.html',
    styleUrl: './asignacion-trabajo.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService]
})
export class AsignacionTrabajoComponent {
    public token: string;
    public tokenDetalle: any;

    public filtro_chasis: string='';
    
    public asignaciontrabajo: Array<any>=[];
    public asignaciontrabajo_filtrado: Array<any>=[];
    public filtro_tecnico: string='';
    public tecnicos: Array<any>=[];
    public tecnicos_qa: Array<any>=[];
    
    public mensajes_error: Array<any>=[];
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_asignacion_trabajo: boolean=false;
    public editar_asignacion_trabajo: boolean=false;
    
    public existe_marcado: boolean=false;
    
    public total_vin: number=0;
    public total_asignado: number=0;
    public total_pendiente: number=0;
    public total_seleccionado: number=0;
    
    public texto_seleccion: string='';
    public marcar_todo: boolean=true;
    
    public visible_tecnico: boolean=false;
    
    public visible_tecnico_qa: boolean=false;
    
    public cargando: boolean=false;
    
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
            this.ver_asignacion_trabajo=true;
            this.editar_asignacion_trabajo=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 94);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_asignacion_trabajo=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_asignacion_trabajo=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this._almacenesService.verasignaciontrabajotecnicos(this.token).subscribe(
            response =>{
                this.tecnicos = response.tecnicos;
                this.limpiarTecnicos();
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
        this._almacenesService.verasignaciontrabajotecnicosqa(this.token).subscribe(
            response =>{
                this.tecnicos_qa = response.tecnicos_qa;
                this.limpiarTecnicos();
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
        this.getAsignacionTrabajo();
    }

    filtrarTecnicos(){
        if(this.filtro_tecnico==''){
            this.tecnicos = this.tecnicos.map(t => ({
                ...t,
                mostrar: true
            }));
        }else{
            this.tecnicos.forEach(t => {
                t.mostrar = (t.nombre ?? '').toLowerCase().includes(this.filtro_tecnico.toLowerCase());
            });
        }
    }
    
    limpiarTecnicos(){
        this.tecnicos = this.tecnicos.map(t => ({
            ...t,
            marcado: false,
            mostrar: true
        }));

        /*
        this.tecnicos.forEach(
            agas => (agas.marcado = false)
        );
        */
    }
    
    limpiarTecnicosQA(){
        this.tecnicos_qa.forEach(
            agas => (agas.marcado = false)
        );
    }
    
    getAsignacionTrabajo(){
        this.asignaciontrabajo=[];
        this._almacenesService.verasignaciontrabajo(this.token).subscribe(
            response =>{
                this.asignaciontrabajo = response.asignaciontrabajo;
                this.asignaciontrabajo.forEach(
                    agas => (agas.marcado = false)
                );
                this.existe_marcado=false;
                this.filtrarAsignacionTrabajo();
                //this.getTotales();
                //this.verificarMarcado();
                /*
                this.ategas.forEach(
                    agas => (agas.created_at = new Date(agas.created_at.replace(/-/g, '\/')))
                );
                this.total_vin = this.ategas.length;
                this.total_pendiente = this.ategas.filter(item => item.fecha_recepcion == null).length;
                this.total_recepcion = this.total_vin-this.total_pendiente;
                */
                console.log(this.asignaciontrabajo);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    filtrarAsignacionTrabajo(){
        this.asignaciontrabajo_filtrado=[];
        if(this.filtro_chasis==''){
            this.asignaciontrabajo_filtrado = this.asignaciontrabajo;
        }else{
            this.asignaciontrabajo_filtrado = this.asignaciontrabajo.filter(product =>
                (product.chasis ?? "").toLowerCase().includes(this.filtro_chasis.toLowerCase())
            );
        }

        this.getTotales();
        this.verificarMarcado();

        
    }
    
    getTotales(){
        this.total_vin = this.asignaciontrabajo_filtrado.length;
        this.total_pendiente = this.asignaciontrabajo_filtrado.filter(item => item.tecnicos.length == 0).length;
        this.total_asignado = this.asignaciontrabajo_filtrado.filter(item => item.tecnicos.length > 0).length;
        this.total_seleccionado = this.asignaciontrabajo_filtrado.filter(item => item.marcado).length;
    }
    
    validarMarcado(indice: number){
        this.asignaciontrabajo_filtrado[indice].marcado=!this.asignaciontrabajo_filtrado[indice].marcado;
        this.existe_marcado=false;
        for (let at = 0; at < this.asignaciontrabajo_filtrado.length; at++){
            if(this.asignaciontrabajo_filtrado[at].marcado){
                this.existe_marcado=true;
            }
        }
        this.getTotales();
        this.verificarMarcado();
    }
    
    verificarMarcado(){
        this.texto_seleccion="Marcar Todos";
        this.marcar_todo=true;
        let todo_marcado=true;
        for (let at = 0; at < this.asignaciontrabajo_filtrado.length; at++){
            if(!this.asignaciontrabajo_filtrado[at].marcado && this.asignaciontrabajo_filtrado[at].idestado_etapa<2){
                todo_marcado=false;
            }
        }
        
        if (todo_marcado){
            this.texto_seleccion="Desmarcar Todos";
            this.marcar_todo=false;
        }
        
    }
    
    marcarTodo(){
        for (let at = 0; at < this.asignaciontrabajo_filtrado.length; at++){
            if (this.asignaciontrabajo_filtrado[at].idestado_etapa<2){
                this.asignaciontrabajo_filtrado[at].marcado=this.marcar_todo;
            }
        }
        /*
        this.asignaciontrabajo.forEach(
            agas => (agas.marcado = this.marcar_todo)
        );
        */
        this.existe_marcado=this.marcar_todo;
        this.verificarMarcado();
        this.getTotales();
    }
    
    prepararTecnico(){
        this.limpiarTecnicos();
        this.filtro_tecnico='';
        this.visible_tecnico=true;
    }
    
    prepararTecnicoQA(){
        this.limpiarTecnicosQA();
        this.visible_tecnico_qa=true;
    }
    
    asignarTecnico(){
        const idate_gas = this.asignaciontrabajo_filtrado
            .filter(item => item.marcado === true)
            .map(item => item.idate_gas);
            
        const idusuario = this.tecnicos
            .filter(item => item.marcado === true)
            .map(item => item.idusuario);

        let payload={
            idate_gas: idate_gas,
            idusuario: idusuario
        };
        this.cargando=true;
        this._almacenesService.asignaciontrabajotecnicos(this.token, payload).subscribe(
            response =>{
                this.cargando=false;
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getAsignacionTrabajo();
                    this.visible_tecnico=false;
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
                this.cargando=false;
            }
        );
    }
    
    asignarTecnicoQA(){
        const idate_gas = this.asignaciontrabajo_filtrado
            .filter(item => item.marcado === true)
            .map(item => item.idate_gas);
            
        const idusuario = this.tecnicos_qa
            .filter(item => item.marcado === true)
            .map(item => item.idusuario);

        let payload={
            idate_gas: idate_gas,
            idusuario: idusuario
        };
        this.cargando=true;
        this._almacenesService.asignaciontrabajotecnicosqa(this.token, payload).subscribe(
            response =>{
                this.cargando=false;
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getAsignacionTrabajo();
                    this.visible_tecnico_qa=false;
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
                this.cargando=false;
            }
        );
    }
    
    eliminarTecnico(indice: number, indice_tecnico: number){
        this.cargando=true;
        this._almacenesService.eliminarasignaciontrabajotecnicos(this.token, this.asignaciontrabajo_filtrado[indice].tecnicos[indice_tecnico].idate_gas_etapa_tecnico).subscribe(
            response =>{
                //console.log(response);
                this.cargando=false;
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getAsignacionTrabajo();
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
                this.cargando=false;
            }
        );
    }
    
    eliminarTecnicoQA(indice: number, indice_tecnico_qa: number){
        this.cargando=true;
        this._almacenesService.eliminarasignaciontrabajotecnicosqa(this.token, this.asignaciontrabajo_filtrado[indice].tecnicos_qa[indice_tecnico_qa].idate_gas_etapa_tecnico_qa).subscribe(
            response =>{
                //console.log(response);
                this.cargando=false;
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.getAsignacionTrabajo();
                }else{
                    this.toast_tipo="Error";
                }
                //$('#ventanaLoading').modal('hide');
                $("#liveToast").toast('show');
                
            },
            error=>{
                console.log(<any>error);
                this.cargando=false;
            }
        );
    }

}
