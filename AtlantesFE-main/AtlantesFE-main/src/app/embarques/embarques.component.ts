import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EmbarqueService} from '../services/embarque.service';
import { ToastrService } from 'ngx-toastr';
declare var $: any;
//import {EmbarqueModel} from '../embarque.model';
//import {FilterPipe, SortByPipe} from '../filter.pipe'

@Component({
  selector: 'app-embarques',
  templateUrl: './embarques.component.html',
  styleUrls: ['./embarques.component.css'],
  providers:[UsuarioService,EmbarqueService,DatoMaestroService]
})
export class EmbarquesComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public importacion_exportaciones:Array<any>;
    public tiposembarque:Array<any>;
    public entidades: Array<any>;

    public mostrarboton: boolean=false;
    
    public idcliente: number;
    public erroridcliente: boolean=false;
    public idtipoembarque: number;
    public idimportacion_exportacion: number;
    
    
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_embarques: boolean=false;
    public editar_embarques: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        
        private _embarqueService: EmbarqueService,
        private toastr: ToastrService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_embarques=true;
            this.editar_embarques=true;
        }else{
            let indiceVerEmbarques = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 38);
            if (indiceVerEmbarques>=0){
                if (this.tokenDetalle.permisos[indiceVerEmbarques].lectura){
                    this.ver_embarques=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEmbarques].escritura){
                    this.editar_embarques=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades=response.entidades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tiposembarque(this.token).subscribe(
            response =>{
                this.tiposembarque=response.tiposembarque;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.importacion_exportacion(this.token).subscribe(
            response =>{
                
                
                this.importacion_exportaciones=response.importacion_exportacion;
                
                
                
                for (let pp = 0; pp < this.importacion_exportaciones.length; pp++){
                    this.importacion_exportaciones[pp].p=1;
                    this.importacion_exportaciones[pp].items=10;
                    this.importacion_exportaciones[pp].filtro="";
                }
                
                this._embarqueService.embarques(this.token, []).subscribe(
                    response =>{
                        console.log(response.embarques);
                        /*
                        response.embarques.forEach(
                            embarques => (embarques.fecharealizacion = new Date(embarques.fecharealizacion.replace(/-/g, '\/')))
                        );
                        */
                        response.embarques.filter(embarque => embarque.fecharealizacion!=null).forEach(
                            embarque => (embarque.fecharealizacion = new Date(embarque.fecharealizacion.replace(/-/g, '\/')))
                        );
                        
                        
                        response.embarques.sort(function (a, b) {
                            if (a.gestion < b.gestion) {
                                return 1;
                            } else if (a.gestion > b.gestion) {
                                return -1;
                            } else if (a.importacion_exportacion > b.importacion_exportacion) {
                                return 1;
                            } else if (a.importacion_exportacion < b.importacion_exportacion) {
                                return -1;
                            } else if (a.idtipoembarque > b.idtipoembarque) {
                                return 1;
                            } else if (a.idtipoembarque < b.idtipoembarque) {
                                return -1;
                            } else if (a.correlativo < b.correlativo) {
                                return 1;
                            } else if (a.correlativo > b.correlativo) {
                                return -1;
                            } else {
                                return 0;
                            }
                        });
                        
                        
                        for (let em = 0; em < response.embarques.length; em++){
                            var indiceimp_exp = this.importacion_exportaciones.findIndex(x => x.importacion_exportacion === response.embarques[em].importacion_exportacion);
                            if(!('embarques' in this.importacion_exportaciones[indiceimp_exp])){
                                this.importacion_exportaciones[indiceimp_exp].embarques=[{
                                    'idembarque': response.embarques[em].idembarque,
                                    'embarque': response.embarques[em].embarque,
                                    'cliente': response.embarques[em].cliente,
                                    'valorcargado': response.embarques[em].valorcargado,
                                    'valorcosteado': response.embarques[em].valorcosteado,
                                    'balance': response.embarques[em].valorcargado-response.embarques[em].valorcosteado,
                                    'nodui': response.embarques[em].nodui,
                                    'fecharealizacion': response.embarques[em].fecharealizacion,
                                    'descripcioncarga': response.embarques[em].descripcioncarga,
                                    'finalizado': response.embarques[em].finalizado
                                }];
                            }else{
                                this.importacion_exportaciones[indiceimp_exp].embarques.push({
                                    'idembarque': response.embarques[em].idembarque,
                                    'embarque': response.embarques[em].embarque,
                                    'cliente': response.embarques[em].cliente,
                                    'valorcargado': response.embarques[em].valorcargado,
                                    'valorcosteado': response.embarques[em].valorcosteado,
                                    'balance': response.embarques[em].valorcargado-response.embarques[em].valorcosteado,
                                    'nodui': response.embarques[em].nodui,
                                    'fecharealizacion': response.embarques[em].fecharealizacion,
                                    'descripcioncarga': response.embarques[em].descripcioncarga,
                                    'finalizado': response.embarques[em].finalizado
                                });
                            }
                            //this.importacion_exportaciones[indiceimp_exp].indiceimp_exp=indiceimp_exp;
                        }
                        for (let em = 0; em < response.embarques.length; em++){
                            
                        }
                        
                        
                        //this.embarques=response.embarques;
                        
                        //console.log(this.embarques);
                        console.log(this.importacion_exportaciones);
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
                
                
                
                
                //console.log(this.importacion_exportaciones);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
            
    }
    
    preperarAgregar(){
        this.idcliente=null;
        this.idimportacion_exportacion=this.importacion_exportaciones[0].importacion_exportacion;
        this.idtipoembarque=this.tiposembarque[0].idtipoembarque;
    }
    
    crearEmbarque(){
        this.erroridcliente=false;
        if (this.idcliente==null){
            this.erroridcliente=true;
        }
        
        if(!this.erroridcliente){
            let datosembarque = {idcliente: this.idcliente, idtipoembarque: this.idtipoembarque, importacion_exportacion: this.idimportacion_exportacion};
            this._embarqueService.crearembarque(this.token, datosembarque).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        $('#nuevoEmbarque').modal('hide');
                        this.abrirDetalle(response.idembarque);
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

    abrirDetalle(idembarque:number){
        this._router.navigate(['/embarques-detalle',idembarque])
        //alert("abre en la misma pagina " + idembarque);
    }

    abrirDetalleNuevo(idembarque:number, event: any){
        let newRelativeUrl = this._router.createUrlTree(["/embarques-detalle",idembarque]);
        let baseUrl = window.location.href.replace(this._router.url, '');

        window.open(baseUrl + newRelativeUrl, '_blank');
        
        //alert("abre en nueva pestaña " + idembarque);
        event.stopPropagation();
    }

}
