import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-conceptos',
    templateUrl: './conceptos.component.html',
    styleUrls: ['./conceptos.component.css'],
    providers:[UsuarioService,DatoMaestroService]
})
export class ConceptosComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public conceptos: Array<any>;
    
    public idconcepto: number;
    public activo: boolean;
    public concepto: string;
    public errorconcepto: boolean;
    public codigo: string;
    public errorcodigo: boolean;
    public mensajeerrorcodigo: string;
    public concepto_en: string;
    public id_OVP: string;
    public id_OVPRef: string;
    public conceptocosto: string;
    public codigocosto: string;
    public concepto_encosto: string;

    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_conceptos: boolean=false;
    public editar_conceptos: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_conceptos=true;
            this.editar_conceptos=true;
        }else{
            let indiceVerConceptos= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 7);
            if (indiceVerConceptos>=0){
                if (this.tokenDetalle.permisos[indiceVerConceptos].lectura){
                    this.ver_conceptos=true;
                }
                if (this.tokenDetalle.permisos[indiceVerConceptos].escritura){
                    this.editar_conceptos=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarConceptos();
    }
    
    cargarConceptos(){
        this._datomaestroService.conceptos(this.token).subscribe(
            response =>{
                console.log(response.conceptos);
                this.conceptos = response.conceptos.filter(function(cc){
                    return cc.tipo==0
                });
                
                for (let cc = 0; cc < this.conceptos.length; cc++){
                    let indice = response.conceptos.findIndex(x => x.idconceptocargo === this.conceptos[cc].idconcepto);
                    if (indice>=0){
                        this.conceptos[cc].idconceptocosto=response.conceptos[indice].idconcepto;
                        this.conceptos[cc].conceptocosto=response.conceptos[indice].concepto;
                        this.conceptos[cc].codigocosto=response.conceptos[indice].codigo;
                        this.conceptos[cc].concepto_encosto=response.conceptos[indice].concepto_en;
                        this.conceptos[cc].id_OVPRef=response.conceptos[indice].id_OVPRef;
                    }
                }
                
                //this.conceptos=response.conceptos;
                console.log(this.conceptos);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idconcepto: number){
        this.idconcepto=idconcepto;
        
        if (idconcepto==0){
            this.cabecera_modal="Nuevo";
            this.activo=true;
            this.concepto='';
            this.errorconcepto=false;
            this.codigo='';
            this.errorcodigo=false;
            this.mensajeerrorcodigo='';
            this.concepto_en='';
            this.id_OVP='';
            this.id_OVPRef='';
            this.conceptocosto='';
            this.codigocosto='';
            this.concepto_encosto='';
        }else{
            this.cabecera_modal="Editar";
            
            let indice = this.conceptos.findIndex(x => x.idconcepto === idconcepto);
            
            this.activo = this.conceptos[indice].activo;
            this.concepto = this.conceptos[indice].concepto;
            this.errorconcepto=false
            this.codigo=this.conceptos[indice].codigo;
            this.errorcodigo=false;
            this.mensajeerrorcodigo='';
            this.concepto_en = this.conceptos[indice].concepto_en;
            this.id_OVP = this.conceptos[indice].id_OVP;
            this.id_OVPRef = this.conceptos[indice].id_OVPRef;
            this.conceptocosto=this.conceptos[indice].conceptocosto;
            this.codigocosto=this.conceptos[indice].codigocosto;
            this.concepto_encosto=this.conceptos[indice].concepto_encosto;
        }
        
    }
    
    guardarDatos(){
        this.errorconcepto=false;
        if (this.concepto==''){
            this.errorconcepto=true;
        }
        this.errorcodigo=false;
        if (this.codigo==''){
            this.errorcodigo=true;
            this.mensajeerrorcodigo='Campo Obligatorio';
        }
        
        if (!this.errorconcepto && !this.errorcodigo){
            let datosguardar;
            datosguardar={
                activo: this.activo,
                concepto: this.concepto,
                codigo: this.codigo,
                concepto_en: this.concepto_en,
                id_OVP: this.id_OVP,
                id_OVPRef: this.id_OVPRef,
                conceptocosto: this.conceptocosto,
                codigocosto: this.codigocosto,
                concepto_encosto: this.concepto_encosto
            };
            
            if (this.idconcepto==0){
                this._datomaestroService.addconcepto(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaConcepto").modal('hide');
                            this.cargarConceptos();
                        }else if(response.codigo==401){
                            this.errorcodigo=true;
                            this.mensajeerrorcodigo=response.mensaje;
                        }else{
                            this.toast_tipo="Error";
                        }

                        $("#liveToast").toast('show');
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }else{
                this._datomaestroService.saveconcepto(this.token, datosguardar, this.idconcepto).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaConcepto").modal('hide');
                            this.cargarConceptos();
                        }else if(response.codigo==401){
                            this.errorcodigo=true;
                            this.mensajeerrorcodigo=response.mensaje;
                        }else{
                            this.toast_tipo="Error";
                        }

                        $("#liveToast").toast('show');
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }
        }
    }

}
