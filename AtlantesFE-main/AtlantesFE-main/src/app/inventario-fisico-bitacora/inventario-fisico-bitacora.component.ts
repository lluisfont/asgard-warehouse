import { Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {AsgardService} from '../services/asgard.service';

import { AccordionTabOpenEvent } from 'primeng/accordion';

interface bitacoraDetalleModel {
    partida?: string,
    codigo?: string,
    origen?: string,
    marca?: string,
    modelo?: string,
    tipo?: string,
    cilindrada?: string,
    traccion?: string,
    chasis?: string,
    motor?: string,
    color?: string,
    anio_comercial?: string,
    anio_fabricacion?: string,
    transmision?: string,
    fecha_validacion_inventario?: string,
    fecha_validacion_operador_logistico_inventario?: string,
    observaciones_validacion_inventario?: string,
    observaciones_validacion_operador_logistico_inventario?: string,
    tipo_inventario_cliente_local_id?: number,
    realizado_por?: string,
    created_at?: Date,
    nombre_usuario_firmante?: string,
    ubicacion_almacen?: string,
    codigo_ubicacion_almacen?: string,
    embarque_id?; number,
    cantidad_con_desperfecto?: number
    
}

interface bitacoraModel {
    detalle_dep_transitorio?: bitacoraDetalleModel[],
    detalle_post_nacional?: bitacoraDetalleModel[],
    detalle_puerto?: bitacoraDetalleModel[],
    detalle_despacho_nacional?: bitacoraDetalleModel[],
    detalle_recepcion_nacional?: bitacoraDetalleModel[],
    detalle_despacho_local?: bitacoraDetalleModel[],
    detalle_recepcion_local?: bitacoraDetalleModel[],
}

interface cabeceraModel {
    origen?: string,
    marca?: string,
    modelo?: string,
    tipo?: string,
    cilindrada?: string,
    traccion?: string,
    chasis?: string,
    motor?: string,
    color?: string,
    anio_comercial?: string,
    anio_fabricacion?: string,
    transmision?: string,
}

@Component({
    selector: 'app-inventario-fisico-bitacora',
    templateUrl: './inventario-fisico-bitacora.component.html',
    styleUrl: './inventario-fisico-bitacora.component.css',
    providers:[UsuarioService,AsgardService]
})
export class InventarioFisicoBitacoraComponent implements OnChanges {
    public token:string;
    public tokenDetalle:any;
    
    @Input() chasis: string = '';
    @Input() idcliente: number = 0;
    
    public errorImagen: boolean=false;
    
    public bitacora: bitacoraModel={detalle_dep_transitorio: [], detalle_post_nacional: [], detalle_puerto: [], detalle_despacho_nacional: [], detalle_recepcion_nacional: [], detalle_despacho_local: [], detalle_recepcion_local: []};
    public buscando_chasis:boolean=false;
    public chasis_encontrado:boolean=false;
    public cabecera_encontrada:boolean=false;
    public cabecera: cabeceraModel={};
    public maxKey: string=null;
    
    public tipo: number=0;
    public tipo_inventario_id: number=0;
    public embarque_id: number=0;
    
    public activeTabIndex: number=0;
    
    public accesorios: Array<any>=[];
    public cargando_accesorios:boolean=false;
    public accesorios_encontrado:boolean=false;
    
    public danos_generado: boolean=false;
    public danos: Array<any>=[];
    public cargando_danos:boolean=false;
    public danos_encontrado:boolean=false;
    
    public contaminaciones_generado: boolean=false;
    public contaminaciones: Array<any>=[];
    public cargando_contaminacion:boolean=false;
    public contaminacion_encontrado:boolean=false;
    
    public realizado_por: string='';
    public fecha_realizacion: Date=null;
    public firmado_por: string='';
    public ubicacion: string='';
    public codigo_ubicacion: string='';
    
    public mostrar_detalle: boolean=false;
    public mostrar_imagen: boolean=false;
    public fotoB64: string='';
    //expandedRows = [];

    constructor(
        private _usuarioService: UsuarioService,
        private _asgardService: AsgardService
        //private _almacenesService: AlmacenesService,
        //private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
    }
    
    ngOnChanges(changes: SimpleChanges) {
        if (changes['chasis']) {
            this.cargarBitacora();
          // Aquí puedes hacer lo que necesites cuando el dato cambia
        }
    }
    
    cargarBitacora(){
        this.buscando_chasis=true;
        //this.bitacora={detalle_dep_transitorio: [], detalle_post_nacional: [], detalle_puerto: [], detalle_despacho_nacional: [], detalle_recepcion_nacional: [], detalle_despacho_local: [], detalle_recepcion_local: []};
        this._asgardService.bitacoraChasisAsgard(this.token, this.chasis, this.idcliente).subscribe(
            response =>{
                this.buscando_chasis=false;
                this.cabecera_encontrada=false;
                let detalle_cabecera={};
                //console.log(response);
                if(response.response.error){
                    this.chasis_encontrado=false;
                }else{
                    this.chasis_encontrado=true;
                    
                    
                    
                    this.bitacora.detalle_dep_transitorio=response.detalle_dep_transitorio.data;
                    if (this.bitacora.detalle_dep_transitorio.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_dep_transitorio.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_dep_transitorio[bd];
                            this.cabecera_encontrada=true;
                            break;
                        }
                    }
                    this.bitacora.detalle_post_nacional=response.detalle_post_nacional.data;
                    if (this.bitacora.detalle_post_nacional.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_post_nacional.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_post_nacional[bd];
                            this.cabecera_encontrada=true;
                            break;
                        }
                    }
                    this.bitacora.detalle_puerto=response.detalle_puerto.data;
                    if (this.bitacora.detalle_puerto.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_puerto.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_puerto[bd];
                            this.cabecera_encontrada=true;
                            break;
                        }
                            
                    }
                    this.bitacora.detalle_despacho_nacional=response.detalle_despacho_nacional.data;
                    /*
                    this.bitacora.detalle_despacho_nacional = this.bitacora.detalle_despacho_nacional.map(e => ({
                        ...e,
                        // si viene null, lo dejamos null; si no, lo convertimos a Date ISO
                        created_at: e.created_at
                          ? new Date(e.created_at)
                          : null
                    }));
                    */
                    if (this.bitacora.detalle_despacho_nacional.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_despacho_nacional.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_despacho_nacional[bd];
                            this.cabecera_encontrada=true;
                            break;
                        }
                    }
                    this.bitacora.detalle_recepcion_nacional=response.detalle_recepcion_nacional.data;
                    if (this.bitacora.detalle_recepcion_nacional.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_recepcion_nacional.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_recepcion_nacional[bd];
                            this.cabecera_encontrada=true;
                            break;
                        }
                    }
                    this.bitacora.detalle_despacho_local=response.detalle_despacho_local.data;
                    if (this.bitacora.detalle_despacho_local.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_despacho_local.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_despacho_local[bd];
                            this.cabecera_encontrada=true;
                            break
                        }
                    }
                    this.bitacora.detalle_recepcion_local=response.detalle_recepcion_local.data;
                    if (this.bitacora.detalle_recepcion_local.length>0 && !this.cabecera_encontrada){
                        for (let bd = 0; bd < this.bitacora.detalle_recepcion_local.length; bd++){
                            detalle_cabecera = this.bitacora.detalle_recepcion_local[bd];
                            this.cabecera_encontrada=true;
                            break;
                        }
                    }
                    
                    //console.log(detalle_cabecera);
                    
                    if (this.cabecera_encontrada){
                        const clavesCabecera: (keyof cabeceraModel)[] = [
                            'origen', 'marca', 'modelo', 'tipo', 'cilindrada', 'traccion',
                            'chasis', 'motor', 'color', 'anio_comercial', 'anio_fabricacion',
                            'transmision'
                        ];
                        clavesCabecera.forEach(k => {
                            if (k in detalle_cabecera) {
                                this.cabecera[k] = detalle_cabecera[k] as cabeceraModel[typeof k];
                        }
                      });
                    }
                    
                    console.log(this.cabecera);
                    
                }
                console.log(this.bitacora);
                const toMillis = str =>
                    typeof str === "string" && str
                        ? new Date(str.replace(" ", "T")).getTime()
                        : NaN;

                  // ---------- búsqueda de la clave con la fecha máxima ----------
                  let maxMs  = -Infinity;          // mayor timestamp hallado
                  this.maxKey = null;               // clave donde ocurre

                  for (const [key, arr] of Object.entries(this.bitacora)) {
                    for (const { created_at } of arr) {
                      const ms = toMillis(created_at);
                      if (!isNaN(ms) && ms > maxMs) {
                        maxMs  = ms;
                        this.maxKey = key;              // guarda la CLAVE, no un índice
                      }
                    }
                  }

                  //console.log(maxKey);  // → "detalle_despacho_local"
                
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    descargarPDF(tipo_inventario_id: number, embarque_id: number){
        let request$;
        if (this.tipo === 0) {
            request$ = this._asgardService.bitacoraResumenInventarioAsgard(this.token, this.chasis, this.idcliente, tipo_inventario_id);
        } else if (this.tipo === 1) {
            request$ = this._asgardService.bitacoraNacionalResumenInventarioAsgard(this.token, this.chasis, this.idcliente, tipo_inventario_id, embarque_id);
        }
        
        if (request$) {
            request$.subscribe(
                response =>{
                    //console.log(response.response.data.file);
                    const byteCharacters = atob(response.response.data.file);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], {type: 'application/pdf'});
                    var url = window.URL.createObjectURL(blob);
                    const downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.target = "_blank";
                    downloadLink.click();
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }

    }
    
    get fotoUrl(): string {
        return `data:image/jpeg;base64,${this.fotoB64}`;
    }
    
    hayDanios(detalle: bitacoraDetalleModel[]): boolean {
        return detalle.some(item => item.cantidad_con_desperfecto > 0);
    }
    
    verDetalle(tipo_inventario_id: number){
        this.errorImagen=false;
        this.tipo_inventario_id=tipo_inventario_id;
        //this.mostrar_detalle=true;
        this.activeTabIndex=0;
        this.danos_generado=false;
        this.contaminaciones_generado=false;
        this.verAccesorios();
    }
    
    verDetalleLocal(tipo_inventario_id: number, embarque_id: number){
        this.mostrar_detalle=true;
        this.errorImagen=false;
        this.tipo_inventario_id=tipo_inventario_id;
        this.embarque_id = embarque_id;
        this.activeTabIndex=0;
        this.danos_generado=false;
        this.contaminaciones_generado=false;
        this.verAccesorios();
    }
    
    activeIndexChange(event: AccordionTabOpenEvent){
        let tipo_inventario_id=0;
        switch(event.index){
            case 0:
                this.tipo=0;
                tipo_inventario_id=2;
                this.verDetalle(tipo_inventario_id);
                break;
            case 1:
                this.tipo=0;
                tipo_inventario_id=1;
                this.verDetalle(tipo_inventario_id);
                break;
            /*
            case 2:
                this.tipo=0;
                tipo_inventario_id=3;
                this.verDetalle(tipo_inventario_id);
                break;
            */
            case 2:
                this.tipo=1;
                this.tipo_inventario_id=1;
                break;
            case 3:
                this.tipo=1;
                this.tipo_inventario_id=2;
                break;
            case 4:
                this.tipo=1;
                this.tipo_inventario_id=3;
                break;
            case 5:
                this.tipo=1;
                this.tipo_inventario_id=4;
                break;
        }
        
    }
    
    onTabChange(event: any) {
        switch (event.index) {
            case 1:
                if (!this.danos_generado){
                    this.verDanos();
                }
                break;
            case 2:
                if (!this.contaminaciones_generado){
                    this.verContaminacion();
                }
                break;
        }
    }
    
    verAccesorios(){
        this.cargando_accesorios=true;
        this.accesorios_encontrado=false;
        this.accesorios=[];
        let request$;

        if (this.tipo === 0) {
            request$ = this._asgardService.bitacoraAccesoriosListaAsgard(this.token, this.chasis, this.idcliente, this.tipo_inventario_id);
        } else if (this.tipo === 1) {
            request$ = this._asgardService.bitacoraNacionalAccesoriosListaAsgard(this.token, this.chasis, this.idcliente, this.tipo_inventario_id, this.embarque_id);
        }
        
        if (request$) {
            request$.subscribe(
                response => {
                    this.cargando_accesorios = false;
                    if (response.response.data.length > 0) {
                        this.accesorios_encontrado = true;
                        this.accesorios = response.response.data;
                        this.realizado_por = this.accesorios[0].registrado_por.nombres;
                        this.fecha_realizacion = this.parseFecha(this.accesorios[0].created_at);
                        this.firmado_por = this.accesorios[0].firmado_por;
                        this.ubicacion = this.accesorios[0].ubicacion_almacen;
                        this.codigo_ubicacion = this.accesorios[0].codigo_ubicacion_almacen;
                    }
                },
                error => {
                    this.cargando_accesorios = false;
                    console.log(<any>error);
                }
            );
        }
    }
    
    verDanos(){
        this.cargando_danos=true;
        this.danos_encontrado=false;
        this.danos=[];
        let request$;

        if (this.tipo === 0) {
            request$ = this._asgardService.bitacoraDesperfectosListaAsgard(this.token, this.chasis, this.idcliente, this.tipo_inventario_id);
        } else if (this.tipo === 1) {
            request$ = this._asgardService.bitacoraNacionalDesperfectosListaAsgard(this.token, this.chasis, this.idcliente, this.tipo_inventario_id, this.embarque_id);
        }
        
        if (request$) {
            request$.subscribe(
                response =>{
                    this.cargando_danos=false;
                    if(response.response.data.length>0){
                        this.danos_encontrado=true;
                        this.danos=response.response.data;
                        this.danos_generado=true;
                        //this.realizado_por=this.danos[0].registrado_por.nombres;
                        //this.fecha_realizacion=this.parseFecha(this.danos[0].created_at);
                        //this.firmado_por=this.danos[0].firmado_por;
                        //this.ubicacion=this.danos[0].ubicacion_almacen;
                        //this.codigo_ubicacion=this.danos[0].codigo_ubicacion_almacen;
                        for (let dd = 0; dd < this.danos.length; dd++){
                            const [ubicacion, filename] = this.danos[dd].ubicacion?.split('|') || ['-', null];
                            this.danos[dd].tiene_ubicacion=false;
                            this.danos[dd].ubicacion_danio='-';
                            this.danos[dd].filename=null;
                            if(filename){
                                this.danos[dd].tiene_ubicacion=true;
                                this.danos[dd].ubicacion_danio=ubicacion;
                                this.danos[dd].filename=filename;
                            }
                        }
                    }

                },
                error=>{
                    this.cargando_danos=false;
                    console.log(<any>error)
                }
            );
        }
    }
    
    verContaminacion(){
        this.cargando_contaminacion=true;
        this.contaminacion_encontrado=false;
        this.contaminaciones=[];
        let request$;

        if (this.tipo === 0) {
            request$ = this._asgardService.bitacoraContaminacionListaAsgard(this.token, this.chasis, this.idcliente, this.tipo_inventario_id);
        } else if (this.tipo === 1) {
            request$ = this._asgardService.bitacoraNacionalContaminacionListaAsgard(this.token, this.chasis, this.idcliente, this.tipo_inventario_id, this.embarque_id);
        }
        
        if (request$) {
            request$.subscribe(
                response =>{
                    this.cargando_contaminacion=false;
                    if(response.response.data.length>0){
                        this.contaminacion_encontrado=true;
                        this.contaminaciones=response.response.data;
                        this.contaminaciones_generado=true;
                        //this.realizado_por=this.contaminaciones[0].registrado_por.nombres;
                        //this.fecha_realizacion=this.parseFecha(this.contaminaciones[0].created_at);
                        //this.firmado_por=this.contaminaciones[0].firmado_por;
                        //this.ubicacion=this.contaminaciones[0].ubicacion_almacen;
                        //this.codigo_ubicacion=this.contaminaciones[0].codigo_ubicacion_almacen;
                        for (let dd = 0; dd < this.contaminaciones.length; dd++){
                            this.contaminaciones[dd].tiene_imagen=false;
                            this.contaminaciones[dd].filename=null;
                            if(this.contaminaciones[dd].imagen){
                                this.contaminaciones[dd].tiene_imagen=true;
                                this.contaminaciones[dd].filename=this.contaminaciones[dd].imagen;
                            }
                        }
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }
    
    verImagen(filename: string, tipo: number){
        this.errorImagen=false;
        this._asgardService.downloadFileAsgard(this.token, this.chasis, this.idcliente, tipo, filename).subscribe(
            response =>{
                //console.log(response);
                if(!response.response.error){
                    this.fotoB64=response.response.data.file;
                    this.mostrar_imagen=true;
                }else{
                    this.errorImagen=response.response.message;
                }
                    
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    parseFecha(fechaStr: string | null | undefined): Date | null {
        if (!fechaStr) {
          return null; // o podrías retornar new Date() si quieres fecha actual como fallback
        }

        const fecha = new Date(fechaStr);
        return isNaN(fecha.getTime()) ? null : fecha;
    }
    

}
