import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-proveedores',
    templateUrl: './proveedores.component.html',
    styleUrls: ['./proveedores.component.css'],
    providers:[UsuarioService,EntidadesService,DatoMaestroService]
})
export class ProveedoresComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public proveedores: Array<any>;
    
    public idproveedor: number=0;
    public proveedor: string='';
    public errorproveedor: boolean=false;
    public numeroidentificacion: string='';
    public telefono: string='';
    public fax: string='';
    public email: string='';
    public nombrecontacto: string='';
    public numerocuenta: string='';
    public plazo: number=0;
    public id_OVPProv: number=0;
    public direcciones: Array<any>=[];
    
    public tiposdocumento: Array<any>
    
    public idtipodocumento: number= null;
    public numerofacturacion: number=null;
    public razonsocial: string='';
    public correosfacturacion: Array<any>=[];
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_entidades_proveedores: boolean=false;
    public editar_entidades_proveedores: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _entidadesService: EntidadesService,
        private _datosmaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_proveedores=true;
            this.editar_entidades_proveedores=true;
        }else{
            let indiceVerEntidadesProveedores = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 5);
            if (indiceVerEntidadesProveedores>=0){
                if (this.tokenDetalle.permisos[indiceVerEntidadesProveedores].lectura){
                    this.ver_entidades_proveedores=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEntidadesProveedores].escritura){
                    this.editar_entidades_proveedores=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._datosmaestroService.tiposdocumento(this.token).subscribe(
            response =>{
                this.tiposdocumento=response.tiposdocumento;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.cargarProveedores();
    }
    
    cargarProveedores(){
        this._entidadesService.verproveedores(this.token).subscribe(
            response =>{
                this.proveedores=response.proveedores;
                console.log(this.proveedores);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idproveedor: number){
        if(idproveedor==0){
            this.cabecera_modal="Nuevo";
            
            this.idproveedor = 0;
            this.proveedor = '';
            this.numeroidentificacion = '';
            this.telefono = '';
            this.fax = '';
            this.email = '';
            this.nombrecontacto = '';
            this.numerocuenta = '';
            this.plazo = 0;
            this.id_OVPProv = 0;

            this.direcciones=[];
            
            
            this.idtipodocumento=null;
            this.numerofacturacion=0;
            this.razonsocial='S/N';
            this.correosfacturacion=[];
            
        }else{
            this.cabecera_modal="Editar";
            let indiceproveedor = this.proveedores.findIndex(x => x.idproveedor === idproveedor);
            
            
            this.idproveedor = idproveedor;
            this.proveedor = this.proveedores[indiceproveedor].proveedor;
            this.numeroidentificacion = this.proveedores[indiceproveedor].numeroidentificacion;
            this.telefono = this.proveedores[indiceproveedor].telefono;
            this.fax = this.proveedores[indiceproveedor].fax;
            this.email = this.proveedores[indiceproveedor].email;
            this.nombrecontacto = this.proveedores[indiceproveedor].nombrecontacto;
            this.numerocuenta = this.proveedores[indiceproveedor].numerocuenta;
            this.plazo = this.proveedores[indiceproveedor].plazo;
            this.id_OVPProv = this.proveedores[indiceproveedor].id_OVPProv;

            this.direcciones=this.proveedores[indiceproveedor].direcciones;
            
            this.idtipodocumento=this.proveedores[indiceproveedor].idtipodocumento;
            this.numerofacturacion=this.proveedores[indiceproveedor].numerofacturacion;
            this.razonsocial=this.proveedores[indiceproveedor].razonsocial;
            this.correosfacturacion=this.proveedores[indiceproveedor].correosfacturacion;
            
            
        }
    }
    
    randomInteger(min: number, max: number) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    
    trackByFn(index: number, item: any) {
        return index;
    }
    
    agregarDireccion(){
        let identidaddireccion=this.randomInteger(1000,9999)*(-1);
        this.direcciones.push({
            'idproveedordireccion': identidaddireccion,
            'direccion': '',
            'ciudad': '',
            'pais': ''
        });
        
    }
    
    eliminarDireccion(idproveedordireccion: number){
        let indiceproveedordireccion = this.direcciones.findIndex(x => x.idproveedordireccion === idproveedordireccion);
        this.direcciones.splice(indiceproveedordireccion, 1);
    }
    
    agregarCorreoFactura(){
        this.correosfacturacion.push({
            'idclientecorreofacturacion': 0,
            'correo': '',
            'error': false
        });
    }
    
    eliminarCorreoFactura(indice: number){
        this.correosfacturacion.splice(indice, 1);
    }
    
    guardarDatos(){
        let error_correos=false;
        for (let cc = 0; cc<this.correosfacturacion.length; cc++){
            if (!this.ValidateEmail(this.correosfacturacion[cc].correo)){
                error_correos=true;
                this.correosfacturacion[cc].error=true;
            }
        }
        
        if(!error_correos){
            let datosguardar;
            datosguardar={
                idproveedor: this.idproveedor,
                proveedor: this.proveedor,
                numeroidentificacion: this.numeroidentificacion,
                telefono: this.telefono,
                fax: this.fax,
                email: this.email,
                nombrecontacto: this.nombrecontacto,
                numerocuenta: this.numerocuenta,
                plazo: this.plazo,
                id_OVPProv: this.id_OVPProv,
                direcciones: this.direcciones,
                idtipodocumento: this.idtipodocumento,
                numerofacturacion: this.numerofacturacion,
                razonsocial: this.razonsocial,
                correosfacturacion: this.correosfacturacion
            };

            if (this.idproveedor==0){
                this._entidadesService.addproveedores(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaProveedor").modal('hide');
                            this.cargarProveedores();
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
                this._entidadesService.saveproveedores(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaProveedor").modal('hide');
                            this.cargarProveedores();
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
    
    ValidateEmail(inputText: string){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(inputText.match(mailformat)){
            return true;
        }else{
            return false;
        }
    }

}
