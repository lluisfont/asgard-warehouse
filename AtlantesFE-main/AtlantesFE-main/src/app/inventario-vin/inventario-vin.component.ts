import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EntidadesService} from '../services/entidades.service';
import {CommonService} from '../services/common.service';

import { finalize, map, shareReplay, take } from 'rxjs/operators';
import { Observable } from 'rxjs';


import {GLOBAL} from './../global';

declare var $: any;

interface DatosGenerales {
    chasis?: string,
    marca?: string,
}

type ImagenInv = {
  itemImageSrc: string;   // thumbnail
  ubicacion: string;      // key para pedir el full
  previewSrc?: string;    // data URL full (se llena al abrir preview)
  previewLoading?: boolean;
};


@Component({
    selector: 'app-inventario-vin',
    templateUrl: './inventario-vin.component.html',
    styleUrl: './inventario-vin.component.css',
    providers:[UsuarioService,AlmacenesService,DatoMaestroService,EntidadesService,CommonService]
})
export class InventarioVinComponent {
    public token: string;
    public tokenDetalle: any;

    public mensajes_error: Array<any>=[];
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_inventario_vin: boolean=false;
    public editar_inventario_vin: boolean=false;
    
    public cargando: boolean=false;

    public error_filtro: boolean=false;
    //public filtro_chasis: string='LS4ASE2E9TA991666';
    public filtro_chasis: string='';
    public error_filtro_chasis: boolean=false;
    public filtro_marca: string='';
    public error_filtro_marca: boolean=false;
    

    public inventario_vin: Array<any>=[];

    public inventario_vin_generado: boolean=false;

    public estadopedido: Array<any>=[];
    public datos_generales: DatosGenerales={};

    public idate_gas: number=null;

    private previewReqCache = new Map<string, Observable<string>>();

    trackByUbicacion = (_: number, img: ImagenInv) => img.ubicacion;

    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _commonService: CommonService,
        private _datomaestroService: DatoMaestroService,
        private _entidadesService: EntidadesService,
        //private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_inventario_vin=true;
            this.editar_inventario_vin=true;
        }else{
            let indiceVerInventarioFisicoGestion = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 99);
            if(indiceVerInventarioFisicoGestion>=0){
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].lectura){
                    this.ver_inventario_vin=true;
                }
                if(this.tokenDetalle.permisos[indiceVerInventarioFisicoGestion].escritura){
                    this.editar_inventario_vin=true;
                }
            }
        }
    }


    getInventarioVIN(){
        let error=false;
        if(this.filtro_chasis.length==0 && this.filtro_marca.length==0){
            error=true;
            this.error_filtro=true;
        }

        if(this.filtro_chasis.length>0 && this.filtro_chasis.length<5){
            error=true;
            this.error_filtro_chasis=true;
        }

        if(this.filtro_marca.length>0 && this.filtro_marca.length<3){
            error=true;
            this.error_filtro_marca=true;
        }

        if(!error){
          this.cargando=true;
            this.inventario_vin_generado=false;
            this.inventario_vin=[];
            this.idate_gas=null;
            let payload={
                chasis: this.filtro_chasis,
                marca: this.filtro_marca,
            };
            this._almacenesService.verinventariovin(this.token, payload).subscribe(
                response =>{
                    this.inventario_vin = response.inventario_vin;
                    console.log(this.inventario_vin);
                    this.cargando=false;
                },
                error=>{
                    console.log(<any>error);
                    this.cargando=false;
                }
            );
        }
        
    }

    verInventarioVIN(){
      this.cargando=true;
        this.inventario_vin_generado=false;
        this._almacenesService.verdetalleestadopedidos(this.token, this.idate_gas).subscribe(
            response =>{
                console.log(response);
                this.inventario_vin_generado=true;
                this.estadopedido = response.estadopedido;
                this.datos_generales = response.datosgenerales;
                this.cargando=false;
                
                
            },
            error=>{
                console.log(<any>error);
                this.cargando=false;
            }
        );
    }

    private getPreviewRequest$(ubicacion: string): Observable<string> {
        const cached = this.previewReqCache.get(ubicacion);
        if (cached) return cached;

        const req$ = this._commonService.verubicacionbase64(this.token, ubicacion).pipe(
            take(1),
            map(r => {
                // Construir un Data URL: data:[MIME];base64,[DATA] :contentReference[oaicite:4]{index=4}
                return r.base64;;
            }),
            shareReplay(1)
        );

        this.previewReqCache.set(ubicacion, req$);
        return req$;
    }

    loadPreview(img: ImagenInv): void {
        // ya está cargada
        if (img.previewSrc || img.previewLoading) return;

        img.previewLoading = true;

        this.getPreviewRequest$(img.ubicacion)
            .pipe(finalize(() => (img.previewLoading = false)))
            .subscribe({
                next: (dataUrl) => (img.previewSrc = dataUrl),
                error: (err) => {
                    console.error(err);
                    // opcional: puedes dejar el thumbnail como fallback
                }
            });
    }

    releasePreview(img: ImagenInv): void {
        img.previewSrc = undefined;

        // Si quieres que al reabrir vuelva a pedirlo siempre:
        // this.previewReqCache.delete(img.ubicacion);

        // Si quieres cachear, NO borres el cache.
    }

    generarInventario(){
        this._almacenesService.downloadAteGasInventario(this.token, this.idate_gas).subscribe(
            response =>{
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

    generarInventarioEtapa(idate_gas_etapa: number){
        this._almacenesService.downloadAteGasEtapaInventario(this.token, idate_gas_etapa).subscribe(
            response =>{
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
