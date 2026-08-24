import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {EntidadesService} from '../services/entidades.service';
import {DatoMaestroService} from '../services/datomaestro.service';

declare var $: any;

@Component({
    selector: 'app-clientes',
    templateUrl: './clientes.component.html',
    styleUrls: ['./clientes.component.css'],
    providers:[UsuarioService,EntidadesService,DatoMaestroService]
})
export class ClientesComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public clientes: Array<any>;
    
    public tiposliquidacion: Array<any>
    
    public idcliente: number=0;
    public cliente: string='';
    public errorcliente: boolean=false;
    public numeroidentificacion: string='';
    public telefono: string='';
    public fax: string='';
    public email: string='';
    public nombrecontacto: string='';
    public web: string='';
    public direccion: string='';
    public numerocuenta: string='';
    public plazo: number=0;
    public id_OVP: number=0;
    public idtipoliquidacion: number=0;
    public monto_fee_mensual: number=0;
    public tarifa_adicional: number=0;
    public descarguio_adicional: number=0;
    public inbound: number=0;
    public outbound: number=0;
    public servicios_administrativos: number=0;
    public servicio_nocturno: number=0;
    public servicio_fin_semana: number=0;
    public estibadores: number=0;
    public posiciones_fee: number=0;
    public alto: number=0;
    public ancho: number=0;
    public largo: number=0;
    public alto_adicional: number=0;
    public ancho_adicional: number=0;
    public largo_adicional: number=0;

    
    public representante_legal: string='';
    public telefono_representante: string='';
    public email_representante: string='';
    
    public idtipodocumento: number= null;
    public numerofacturacion: number=null;
    public razonsocial: string='';
    public correosfacturacion: Array<any>=[];
    
    public direcciones: Array<any>=[];
    
    public tiposdocumento: Array<any>
    
    
    public diasvencimiento: Array<any>=[];
    public serviciologistico: Array<any>=[];
    public error_serviciologistico: boolean=false;
    
    public conceptos: Array<any>=[];
    public divisas: Array<any>=[];
    
    public importacion_exportacion: Array<any>=[];
    
    public mediostransporte: Array<any>;
    public tiposcarga: Array<any>;
    public aduanas: Array<any>;
    public destinos: Array<any>;
    public temperaturas: Array<any>;
    public horarios: Array<any>;
    
    public username: string='';
    public errorusername: boolean=false;
    public errorusernameexistente: boolean=false;
    public contrasena: string='';
    public errorcontrasena: boolean=false;
    
    public metodotimbrado: Array<any>;
    
    

    /*
    public idcliente: number;
    public erroridcliente: boolean=false;
    public idtipoembarque: number;
    public idimportacion_exportacion: number;
    */
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_entidades_clientes: boolean=false;
    public editar_entidades_clientes: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _entidadesService: EntidadesService,
        private _datosmaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_entidades_clientes=true;
            this.editar_entidades_clientes=true;
        }else{
            let indiceVerEntidadesClientes = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 2);
            if (indiceVerEntidadesClientes>=0){
                if (this.tokenDetalle.permisos[indiceVerEntidadesClientes].lectura){
                    this.ver_entidades_clientes=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEntidadesClientes].escritura){
                    this.editar_entidades_clientes=true;
                }
            }
        }
        
        //console.log(this.tokenDetalle);
    }

    ngOnInit(): void {
        this._datosmaestroService.tiposliquidacion(this.token).subscribe(
            response =>{
                this.tiposliquidacion=response.tiposliquidacion;
                
                
                //console.log(this.tiposliquidacion);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
	this._datosmaestroService.tiposdocumento(this.token).subscribe(
            response =>{
                this.tiposdocumento=response.tiposdocumento;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datosmaestroService.importacion_exportacion(this.token).subscribe(
            response =>{
                
                this.importacion_exportacion = response.importacion_exportacion.filter(function(item) {
                    return item.parametrizacion;
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.mediostransporte(this.token).subscribe(
            response =>{
                this.mediostransporte=response.mediostransporte.filter(function(item) {
                    return item.parametrizacion;
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.tiposcarga(this.token).subscribe(
            response =>{
                //this.tiposcarga=response.tiposcarga;
                this.tiposcarga=response.tiposcarga.filter((tc) => tc.activo == 1);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.aduanas(this.token).subscribe(
            response =>{
                this.aduanas=response.aduanas;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.temperaturas(this.token).subscribe(
            response =>{
                //this.temperaturas=response.temperaturas;
                this.temperaturas=response.temperaturas.filter((tm) => tm.activo == 1);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.horarios(this.token).subscribe(
            response =>{
                this.horarios=response.horarios;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.ciudades(this.token).subscribe(
            response =>{
                this.destinos=response.ciudades.filter(function(item) {
                    return (item.parametrizacion);
                });
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.conceptos(this.token).subscribe(
            response =>{
                
                this.conceptos = response.conceptos.filter(function(item) {
                    return (item.activo && item.id_OVP!=null && item.id_OVP!=0);
                });
                
                
                //console.log(this.conceptos);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datosmaestroService.divisas(this.token).subscribe(
            response =>{
                
                this.divisas = response.divisas;
                
                
                //console.log(this.conceptos);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        
        
        this.cargarClientes();
    }
    
    cargarClientes(){
        this._entidadesService.verclientes(this.token).subscribe(
            response =>{
                this.clientes=response.clientes;
                
                
                console.log(this.clientes);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idcliente: number){
        if(idcliente==0){
            this.cabecera_modal="Nuevo";
            
            this.idcliente = 0;
            this.cliente = '';
            this.numeroidentificacion = '';
            this.telefono = '';
            this.fax = '';
            this.email = '';
            this.nombrecontacto = '';
            this.web = '';
            this.direccion = '';
            this.numerocuenta = '';
            this.plazo = 0;
            this.id_OVP = 0;
            this.idtipoliquidacion=null;
            this.monto_fee_mensual=0;
            this.tarifa_adicional=0;
            this.descarguio_adicional=0;
            this.inbound=0;
            this.outbound=0;
            this.servicios_administrativos=0;
            this.servicio_nocturno=0;
            this.servicio_fin_semana=0;
            this.estibadores=0;
            this.posiciones_fee=0;
            this.alto=0;
            this.ancho=0;
            this.largo=0;
            this.alto_adicional=0;
            this.ancho_adicional=0;
            this.largo_adicional=0;


            this.representante_legal = '';
            this.telefono_representante = '';
            this.email_representante = '';
            
            this.idtipodocumento=null;
            this.numerofacturacion=0;
            this.razonsocial='S/N';
            this.correosfacturacion=[];
            
            this.direcciones=[];
            this.diasvencimiento=[];
            this.serviciologistico=[];
            for (let ie = 0; ie<this.importacion_exportacion.length;ie++){
                this.importacion_exportacion[ie].gestionlogistica=[];
            }
            
            this.username='';
            this.contrasena='';
            this.metodotimbrado=[];
            
        }else{
            this.cabecera_modal="Editar";
            let indicecliente = this.clientes.findIndex(x => x.idcliente === idcliente);
            
            this.idcliente = idcliente;
            this.cliente = this.clientes[indicecliente].cliente;
            this.numeroidentificacion = this.clientes[indicecliente].numeroidentificacion;
            this.telefono = this.clientes[indicecliente].telefono;
            this.fax = this.clientes[indicecliente].fax;
            this.email = this.clientes[indicecliente].email;
            this.nombrecontacto = this.clientes[indicecliente].nombrecontacto;
            this.web = this.clientes[indicecliente].web;
            this.direccion = this.clientes[indicecliente].direccion;
            this.numerocuenta = this.clientes[indicecliente].numerocuenta;
            this.plazo = this.clientes[indicecliente].plazo;
            this.id_OVP = this.clientes[indicecliente].id_OVP;
            this.idtipoliquidacion = this.clientes[indicecliente].idtipoliquidacion;
            this.monto_fee_mensual = this.clientes[indicecliente].monto_fee_mensual;
            this.tarifa_adicional = this.clientes[indicecliente].tarifa_adicional;
            this.descarguio_adicional = this.clientes[indicecliente].descarguio_adicional;
            this.inbound = this.clientes[indicecliente].inbound;
            this.outbound = this.clientes[indicecliente].outbound;
            this.servicios_administrativos = this.clientes[indicecliente].servicios_administrativos;
            this.servicio_nocturno = this.clientes[indicecliente].servicio_nocturno;
            this.servicio_fin_semana = this.clientes[indicecliente].servicio_fin_semana;
            this.estibadores = this.clientes[indicecliente].estibadores;
            this.posiciones_fee = this.clientes[indicecliente].posiciones_fee;
            this.alto = this.clientes[indicecliente].alto;
            this.ancho = this.clientes[indicecliente].ancho;
            this.largo = this.clientes[indicecliente].largo;
            this.alto_adicional = this.clientes[indicecliente].alto_adicional;
            this.ancho_adicional = this.clientes[indicecliente].ancho_adicional;
            this.largo_adicional = this.clientes[indicecliente].largo_adicional;


            this.representante_legal = this.clientes[indicecliente].representante_legal;
            this.telefono_representante = this.clientes[indicecliente].telefono_representante;
            this.email_representante = this.clientes[indicecliente].email_representante;
            
            this.idtipodocumento=this.clientes[indicecliente].idtipodocumento;
            this.numerofacturacion=this.clientes[indicecliente].numerofacturacion;
            this.razonsocial=this.clientes[indicecliente].razonsocial;
            this.correosfacturacion=JSON.parse(JSON.stringify(this.clientes[indicecliente].correosfacturacion));
            
            this.direcciones=JSON.parse(JSON.stringify(this.clientes[indicecliente].direcciones));
            this.diasvencimiento=[];
            this.diasvencimiento = JSON.parse(JSON.stringify(this.clientes[indicecliente].diasvencimiento));
            this.serviciologistico = JSON.parse(JSON.stringify(this.clientes[indicecliente].serviciologistico));
            for (let ie = 0; ie<this.importacion_exportacion.length;ie++){
                let importacion_exportacion=this.importacion_exportacion[ie].importacion_exportacion;
                this.importacion_exportacion[ie].gestionlogistica=this.clientes[indicecliente].gestionlogistica.filter(function(item){
                    return importacion_exportacion==item.importacion_exportacion;
                });
            }
            this.username=this.clientes[indicecliente].username;
            this.contrasena=this.clientes[indicecliente].contrasena;
            this.metodotimbrado=JSON.parse(JSON.stringify(this.clientes[indicecliente].metodotimbrado));
            
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
            'idclientedireccion': identidaddireccion,
            'direccion': '',
            'ciudad': '',
            'pais': '',
            'nombrecontacto': '',
            'email': ''
        });
        
    }
    
    eliminarDireccion(idclientedireccion: number){
        let indiceclientedireccion = this.direcciones.findIndex(x => x.idclientedireccion === idclientedireccion);
        this.direcciones.splice(indiceclientedireccion, 1);
    }
    
    agregarServicioLogistico(){
        this.serviciologistico.push({
            idclienteserviciologistico: 0,
            idconcepto: null,
            error_concepto: false,
            monto: 0,
            error_monto: false,
            iddivisa: null,
            error_divisa: false,
            montofijo: false
        });
    }
    
    eliminarServicioLogistico(indice: number){
        this.serviciologistico.splice(indice, 1);
    }
    
    cambioConcepto(indice: number){
        let indiceconcepto = this.conceptos.findIndex(x => x.idconcepto === this.serviciologistico[indice].idconcepto);
        this.serviciologistico[indice].concepto = this.conceptos[indiceconcepto].concepto;
        this.serviciologistico[indice].error_concepto=false
    }
    
    cambioDivisa(indice: number){
        let indicedivisa = this.divisas.findIndex(x => x.iddivisa === this.serviciologistico[indice].iddivisa);
        this.serviciologistico[indice].divisa = this.divisas[indicedivisa].codigo;
        this.serviciologistico[indice].error_divisa=false
    }
    
    agregarGestionLogistica(indice_ie: number){
        this.importacion_exportacion[indice_ie].gestionlogistica.push({
            idclientegestionlogistica: 0,
            importacion_exportacion: this.importacion_exportacion[indice_ie].importacion_exportacion,
            idmediotransporte: null,
            idtipocarga: null,
            idaduana: null,
            iddestino: null,
            idtemperatura: null,
            idhorario: null,
            volumen: 0,
            peso_desde: 0,
            peso_hasta: 0,
            cantidad_pallets: 0,
            monto_fijo: 0,
            monto_por_peso: 0
        });
    }
    
    eliminarGestionLogistica(indice_ie: number, indice: number){
        this.importacion_exportacion[indice_ie].gestionlogistica.splice(indice, 1);
    }
    
    cambioMedioTransporte(indice_ie: number, indice: number){
        let indicemediotransporte = this.mediostransporte.findIndex(x => x.idmediotransporte === this.importacion_exportacion[indice_ie].gestionlogistica[indice].idmediotransporte);
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].mediotransporte = this.mediostransporte[indicemediotransporte].mediotransporte;
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].error_mediotransporte=false
    }
    
    cambioTipoCarga(indice_ie: number, indice: number){
        let indicetipocarga = this.tiposcarga.findIndex(x => x.idtipocarga === this.importacion_exportacion[indice_ie].gestionlogistica[indice].idtipocarga);
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].tipocarga = this.tiposcarga[indicetipocarga].tipocarga;
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].error_tipocarga=false
        
        if (this.tiposcarga[indicetipocarga].idtemperatura>0){
            this.importacion_exportacion[indice_ie].gestionlogistica[indice].idtemperatura = this.tiposcarga[indicetipocarga].idtemperatura;
            this.cambioTemperatura(indice_ie, indice);
        }
        
    }
    
    cambioAduana(indice_ie: number, indice: number){
        let indiceaduana = this.aduanas.findIndex(x => x.idaduana === this.importacion_exportacion[indice_ie].gestionlogistica[indice].idaduana);
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].aduana = this.aduanas[indiceaduana].aduana;
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].error_aduana=false
    }
    
    cambioDestino(indice_ie: number, indice: number){
        let indicedestino = this.destinos.findIndex(x => x.idciudad === this.importacion_exportacion[indice_ie].gestionlogistica[indice].iddestino);
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].destino = this.destinos[indicedestino].ciudad;
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].error_destino=false
    }
    
    cambioTemperatura(indice_ie: number, indice: number){
        let indicetemperatura = this.temperaturas.findIndex(x => x.idtemperatura === this.importacion_exportacion[indice_ie].gestionlogistica[indice].idtemperatura);
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].temperatura = this.temperaturas[indicetemperatura].temperatura;
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].error_temperatura=false
    }
    
    cambioHorario(indice_ie: number, indice: number){
        let indicehorario = this.horarios.findIndex(x => x.idhorario === this.importacion_exportacion[indice_ie].gestionlogistica[indice].idhorario);
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].horario = this.horarios[indicehorario].horario;
        this.importacion_exportacion[indice_ie].gestionlogistica[indice].error_horario=false
    }
    
    agregarDiaVencimiento(){
        this.diasvencimiento.push({
            idclientediasvencimiento: 0,
            rubro_producto: '',
            diasvencimiento: 0
        });
    }
    
    eliminarDiaVencimiento(indicediasvencimiento: number){
        this.diasvencimiento.splice(indicediasvencimiento, 1);
    }
    
    agregarMetodoTimbrado(){
        this.metodotimbrado.push({
            idcleintemetodotimbrado: 0,
            metodotimbrado: '',
            monto: 0,
            iddivisa: null
        });
    }
    
    eliminarMetodoTimbrado(indice: number){
        this.metodotimbrado.splice(indice, 1);
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
    
    verificarDatos(){
        this.errorcliente=false;
        if (this.cliente==''){
            this.errorcliente=true;
        }
        
        this.errorusername=false;
        if (this.username !== '' && this.username.length<=5){
            this.errorusername=true;
        }
        
        this.errorcontrasena=false;
        if (!this.errorusername){
            if (this.username !== '' && this.contrasena.length<=5){
                this.errorcontrasena=true;
            }
        }

	let error_correos=false;
        for (let cc = 0; cc<this.correosfacturacion.length; cc++){
            if (!this.ValidateEmail(this.correosfacturacion[cc].correo)){
                error_correos=true;
                this.correosfacturacion[cc].error=true;
            }
        }

	let error_gestionlogistica: boolean=false;
        for (let ie = 0; ie < this.importacion_exportacion.length; ie++){
            for (let gl = 0; gl < this.importacion_exportacion[ie].gestionlogistica.length; gl++){
                if(this.importacion_exportacion[ie].gestionlogistica[gl].idmediotransporte==0 || this.importacion_exportacion[ie].gestionlogistica[gl].idmediotransporte==null){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_mediotransporte=true;
                    error_gestionlogistica=true;
                }
                if(this.importacion_exportacion[ie].gestionlogistica[gl].idtipocarga==0 || this.importacion_exportacion[ie].gestionlogistica[gl].idtipocarga==null){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_tipocarga=true;
                    error_gestionlogistica=true;
                }
                if(this.importacion_exportacion[ie].gestionlogistica[gl].idaduana==0 || this.importacion_exportacion[ie].gestionlogistica[gl].idaduana==null){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_aduana=true;
                    error_gestionlogistica=true;
                }
                if(this.importacion_exportacion[ie].gestionlogistica[gl].iddestino==0 || this.importacion_exportacion[ie].gestionlogistica[gl].iddestino==null){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_destino=true;
                    error_gestionlogistica=true;
                }
                if(this.importacion_exportacion[ie].gestionlogistica[gl].idtemperatura==0 || this.importacion_exportacion[ie].gestionlogistica[gl].idtemperatura==null){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_temperatura=true;
                    error_gestionlogistica=true;
                }
                if(this.importacion_exportacion[ie].gestionlogistica[gl].idhorario==0 || this.importacion_exportacion[ie].gestionlogistica[gl].idhorario==null){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_horario=true;
                    error_gestionlogistica=true;
                }
                if(this.importacion_exportacion[ie].gestionlogistica[gl].peso_desde>this.importacion_exportacion[ie].gestionlogistica[gl].peso_hasta){
                    this.importacion_exportacion[ie].gestionlogistica[gl].error_peso=true;
                    error_gestionlogistica=true;
                }
            }
        }

        
        this.error_serviciologistico=false;
        for (let sl = 0; sl < this.serviciologistico.length; sl++){
            this.serviciologistico[sl].error_concepto=false;
            this.serviciologistico[sl].error_monto=false;
            this.serviciologistico[sl].error_divisa=false;
            if (this.serviciologistico[sl].idconcepto=='' || this.serviciologistico[sl].idconcepto==null){
                this.serviciologistico[sl].error_concepto=true;
                this.error_serviciologistico=true;
            }
            if(this.serviciologistico[sl].monto<=0){
                this.serviciologistico[sl].error_monto=true;
                this.error_serviciologistico=true;
            }
            if (this.serviciologistico[sl].iddivisa=='' || this.serviciologistico[sl].iddivisa==null){
                this.serviciologistico[sl].error_divisa=true;
                this.error_serviciologistico=true;
            }
        }
        
        let errortimbrado=false;
        for (let tt = 0; tt < this.metodotimbrado.length; tt++){
            if (this.metodotimbrado[tt].metodotimbrado==''){
                errortimbrado=true;
                this.metodotimbrado[tt].errormetodotimbrado=true;
            }
            if (this.metodotimbrado[tt].iddivisa==null){
                errortimbrado=true;
                this.metodotimbrado[tt].erroriddivisa=true;
            }
        }
        console.log(this.metodotimbrado);
        
        if (!this.errorcliente && !this.errorusername && !this.errorcontrasena && !error_correos && !this.error_serviciologistico && !error_gestionlogistica && !errortimbrado){
            this.errorusernameexistente=false;
            if (this.username !== '' && this.username.length>5){
                this._entidadesService.verificarusername(this.token, this.username, this.idcliente).subscribe(
                    response =>{
                        this.errorusernameexistente=response.existe;
                        if(!this.errorusernameexistente){
                            this.guardarDatos();
                        }
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }else{
                this.guardarDatos();
            }
        }
        
    }
    
    
    guardarDatos(){
        let datosguardar;
        let gestionlogistica: Array<any>=[];
        for (let ie = 0; ie < this.importacion_exportacion.length; ie++){
            for(let i=0; i<this.importacion_exportacion[ie].gestionlogistica.length; i++) {
                gestionlogistica.push(this.importacion_exportacion[ie].gestionlogistica[i]);
            }
                
        }
        
        //console.log(gestionlogistica);
        
        datosguardar={
            idcliente: this.idcliente,
            cliente: this.cliente,
            numeroidentificacion: this.numeroidentificacion,
            direccion: this.direccion,
            telefono: this.telefono,
            fax: this.fax,
            web: this.web,
            email: this.email,
            nombrecontacto: this.nombrecontacto,
            representante_legal: this.representante_legal,
            telefono_representante: this.telefono_representante,
            email_representante: this.email_representante,
            idtipodocumento: this.idtipodocumento,
            numerofacturacion: this.numerofacturacion,
            razonsocial: this.razonsocial,
            username: this.username,
            contrasena: this.contrasena,
            numerocuenta: this.numerocuenta,
            plazo: this.plazo,
            id_OVP: this.id_OVP,
            idtipoliquidacion: this.idtipoliquidacion,
            monto_fee_mensual: this.monto_fee_mensual,
            tarifa_adicional: this.tarifa_adicional,
            descarguio_adicional: this.descarguio_adicional,
            inbound: this.inbound,
            outbound: this.outbound,
            servicios_administrativos: this.servicios_administrativos,
            servicio_nocturno: this.servicio_nocturno,
            servicio_fin_semana: this.servicio_fin_semana,
            estibadores: this.estibadores,
            posiciones_fee: this.posiciones_fee,
            alto: this.alto,
            ancho: this.ancho,
            largo: this.largo,
            alto_adicional: this.alto_adicional,
            ancho_adicional: this.ancho_adicional,
            largo_adicional: this.largo_adicional,
            direcciones: this.direcciones,
            diasvencimiento: this.diasvencimiento,
            correosfacturacion: this.correosfacturacion,
            serviciologistico: this.serviciologistico,
            gestionlogistica: gestionlogistica,
            metodotimbrado: this.metodotimbrado
        };
        
        if (this.idcliente==0){
            this._entidadesService.addcliente(this.token, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaCliente").modal('hide');
                        this.cargarClientes();
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
            this._entidadesService.savecliente(this.token, datosguardar).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#ventanaCliente").modal('hide');
                        this.cargarClientes();
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
    
    ValidateEmail(inputText: string){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(inputText.match(mailformat)){
            return true;
        }else{
            return false;
        }
    }

}
