import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {formatDate} from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {EmbarqueService} from '../services/embarque.service';
import {ContabilidadService} from '../services/contabilidad.service';
import {AsgardService} from '../services/asgard.service';
import { ToastrService } from 'ngx-toastr';
import {EmbarqueModel} from '../models/embarque.model';
import { MenuItem, MessageService } from 'primeng/api';


declare var $: any;

@Component({
  selector: 'app-embarques-detalle',
  templateUrl: './embarques-detalle.component.html',
  styleUrls: ['./embarques-detalle.component.css'],
  providers:[UsuarioService,EmbarqueService,DatoMaestroService,ContabilidadService,AsgardService]
})
export class EmbarquesDetalleComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    public idembarque:number;
    public embarque: EmbarqueModel;
    public error_carpetapacena: boolean=false;
    public incoterms: Array<any>;
    public ciudades: Array<any>;
    public aduanas: Array<any>;
    public entidades: Array<any>;
    public mediostransporte: Array<any>;
    public tiposcarga: Array<any>;
    public horarios: Array<any>;
    public temperaturas: Array<any>;
    public conceptos: Array<any>;
    public divisas: Array<any>;
    public divisasordenservicio: Array<any>;
    public tiposevento: Array<any>;
    public eventodescripcion: Array<any>;
    public cuentas: Array<any>;
    public tiposplanilla: Array<any>;
    public destinos_cargo: Array<any>;
    public tipos_bulto: Array<any>;

    public indiceexpedidor: number;
    public indiceultimoconsignatario: number;
    public indiceentidadnotificar: number;
    public indiceagentecarga: number;
    public indiceagentedestino: number;

    public arrayconceptos: Array<any>;

    public cabeceraventanaDatos: string;
    public accionventanaDatos: string;
    public tipo: string;
    public idtipo: number;
    public tienedocumento: boolean;
    public idconcepto: number;
    public erroridconcepto: boolean;
    public cantidad: number;
    public errorcantidad: boolean;
    public monto: number;
    public errormonto: boolean;
    public iddivisa: number;
    public erroriddivisa: boolean;
    public identidad: string;
    public notas: string;
    public factura: string;
    public errorfactura: boolean;
    public nota_entrega: string;
    public errornota_entrega: boolean;
    public factura_cargo: string;
    public errorfactura_cargo: boolean;
    public iddestinocargo: number;
    public erroriddestinocargo: boolean;
    
    
    public esagente: boolean;
    public tipoeliminar: string;
    public idtipoeliminar: number;

    public cabeceraventanaEventos: string;
    public accionventanaEventos: string;
    public idevento: number;
    public ideventoeliminar: number;
    public idtipoevento: number;
    public erroridtipoevento: boolean;
    public evento: string;
    public fecharegistro: string;
    public fechaplanificada: string;
    public errorfechaplanificada: boolean;
    public con_observacion: boolean;
    public ideventodescripcion: number;
    public nombre: string
    public enviado: boolean;
    public correosconerror: Array<number>=[];
    public errorcorreos: boolean;
    public errorenviarmaileventoscorreos: boolean=false;
    public errorenviarmaileventos: boolean=false;

    public indicedocumentoeliminar: number;
    @ViewChild('UploadFileInput')
    myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public uploadFileInput: any;
    public archivocargado: boolean;
    public nombredocumentocargar: Array<any>=[];
    public existedocumento: boolean;
    public erroresdocumento: Array<any>=[];

    public fechaactual: string;

    public titulomodalfactura: string='';
    public idfactura_pendiente: number=0;
    public fechafactura: string;
    public idcobrarafactura: string;
    public erroridcobrarafactura: boolean;
    public nitf: number;
    public errornitf: boolean;
    public nombref: string;
    public errornombref: boolean;
    public palletsfactura: string;
    public rotacionfactura: string;
    public conceptosfacturardebitar: Array<any>;
    public totalfacturabs: number;
    public errortotalfacturabs: boolean;
    public totalfacturaus: number;
    public tiposdocumento: Array<any>
    public idtipodocumento: number;
    public correos_factura: Array<any>;
    public facturando: boolean;


    public idcobrarnc: string;
    public erroridcobrarnc: boolean;
    public idcuentanc: number;
    public erroridcuentanc: boolean;
    public iddivisanc: number;
    public erroriddivisanc: boolean;
    public observacionesnc: string;
    public totalNCbs: number;
    public totalNCus: number;
    public errortotalNCbs: boolean;

    public idagentei: number;
    public erroridagentei: boolean;
    public iddireccionagentei: number;
    public indiceagentei: number;
    public conceptosinvoice: Array<any>;
    public totalinvoicebs: number;
    public errortotalinvoicebs: boolean;
    public totalinvoiceus: number;

    public pacenainvoice: string;
    public slginvoice: string;
    public alloginvoice: string;
    public textoadicional: string;
    public conceptosplanilla: Array<any>;
    public totalplanillaus: number;
    public errortotalplanillaus: boolean;
    public titulomodalinvoice: string;
    public idinvoice_pendiente: number=0;
    public fechainvoice: string;

    public idpagaraop: string;
    public erroridpagaraop: boolean;
    public indiceidpagaraop: number;
    public idpagaradireccionop: number;
    public fechadocop: string;
    public errorfechadocop: boolean;
    public idtransportistaop: number;
    public idcobraraop: string;
    public erroridcobraraop: boolean;
    public iddivisaop: number;
    public erroriddivisaop: boolean;
    public tipoop: number;
    public observacionesop: string;
    public conceptosordenpago: Array<any>;
    public totalopbs: number;
    public errortotalopbs: boolean;
    public totalopus: number;

    public fechadocpa: string;
    public errorfechadocpa: boolean;
    public idpagarapa: number;
    public erroridpagarapa: boolean;
    public indiceidpagarapa: number;
    public idpagaradireccionpa: number;
    public idtransportistapa: number;
    public idcobrarapa: string;
    public erroridcobrarapa: boolean;
    public iddivisapa: number;
    public erroriddivisapa: boolean;
    public tipocambiopa: number;
    public errortipocambiopa: boolean;
    public observacionespa: string;
    public conceptospagoagente: Array<any>;
    public totalpabs: number;
    public errortotalpabs: boolean;
    public totalpaus: number;

    public tipoos: string;
    public tituloordenservicio: string;
    public texto_cred_deb: string;
    public idsolicitadopor: number;
    public erroridsolicitadopor: boolean;
    public iddivisaos: number;
    public erroriddivisaos: boolean;
    public tipocambioos: number;
    public errortipocambioos: boolean;
    public creditnot: string;
    public conceptosordenservicio: Array<any>;
    public totalosbs: number;
    public errortotalosbs: boolean;
    public totalosus: number;
    
    public items_documentos: MenuItem[];
    
    public imp_exp_validar: Array<number>=[0,1,2,3,5];
    public costos_validar: Array<number>=[331,329,330];
    public cargos_validar: Array<number>=[281,279,280];
    
    public error_numeroguia: boolean=false;
    public error_noidentificacion: boolean=false;
    public error_descripcioncarga: boolean=false;
    public error_peso: boolean=false;
    public error_piezas: boolean=false;
    public error_idtipobulto: boolean=false;
    public error_idincoterms: boolean=false;
    
    
    
    public error_idexpedidor: boolean=false;
    public error_idexpedidordireccion: boolean=false;
    public error_idultimoconsignatario: boolean=false;
    public error_idultimoconsignatariodireccion: boolean=false;
    
    
    public error_idmediotransporte: boolean=false;
    public error_idtipocarga: boolean=false;
    public error_idtransportista: boolean=false;
    public error_numerovehiculo: boolean=false;
    public error_idsalida: boolean=false;
    public error_fechasalida: boolean=false;
    public error_idarribo: boolean=false;
    public error_fechaarribo: boolean=false;
    public error_idhorario: boolean=false;
    public error_idtemperatura: boolean=false;
    public error_numero_precinto: boolean=false;
    public error_estibadoresSLG: boolean=false;
    public error_estibadores: boolean=false;
    public error_costo_operador_transporte: boolean=false;


    //public mensajeexito: string;
    //public mensajeerror: string;

    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_embarques: boolean=false;
    public editar_embarques: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _embarqueService: EmbarqueService,
        private _contabilidadService: ContabilidadService,
        private _asgardService: AsgardService,
        private toastr: ToastrService,
        private _route: ActivatedRoute,
        private _router: Router
        ){
            this._route.params.forEach((params: Params)=>{
                this.idembarque = params["idembarque"];
            });
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
            this.arrayconceptos=[];
            this.fechaactual = this._usuarioService.getCurrentDateFilterValue();
            
            this.items_documentos = [
                {
                    label: 'Factura',
                    command: () => {
                        this.prepararFactura(0);
                        $('#ventanaNuevaFactura').modal('show');
                    }
                },
                {
                    label: 'Nota de Cobranza',
                    command: () => {
                        this.prepararNC();
                        $('#ventanaNuevaNotaCobranza').modal('show');
                    }
                },
                {
                    label: 'Invoice',
                    command: () => {
                        this.prepararInvoice(0);
                        $('#ventanaNuevaInvoice').modal('show');
                    }
                },
                {
                    label: 'Planilla',
                    command: () => {
                        this.prepararPlanilla();
                        $('#ventanaNuevaPlanilla').modal('show');
                    }
                },
                {
                    label: 'Orden de Pago',
                    command: () => {
                        this.prepararOP();
                        $('#ventanaNuevaOrdenPago').modal('show');
                    }
                },
                {
                    label: 'Pago Agente',
                    command: () => {
                        this.prepararPA();
                        $('#ventanaNuevoPagoAgente').modal('show');
                    }
                },
                {
                    label: 'Orden de Servicio Ingreso',
                    command: () => {
                        this.prepararOS('i');
                        $('#ventanaNuevaOrdenServicio').modal('show');
                    }
                },
                {
                    label: 'Orden de Servicio Egreso',
                    command: () => {
                        this.prepararOS('e');
                        $('#ventanaNuevaOrdenServicio').modal('show');
                    }
                }
            ];
            
            //this.indiceexpedidor=0;
            //this.indiceexpedidor=0;
        }

    ngOnInit(): void {
        $('#ventanaLoading').modal('show');
        this._datomaestroService.ciudades(this.token).subscribe(
            response =>{
                this.ciudades=response.ciudades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.aduanas(this.token).subscribe(
            response =>{
                this.aduanas=response.aduanas;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.incoterms(this.token).subscribe(
            response =>{
                this.incoterms=response.incoterms;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.mediostransporte(this.token).subscribe(
            response =>{
                this.mediostransporte=response.mediostransporte;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tiposcarga(this.token).subscribe(
            response =>{
                this.tiposcarga=response.tiposcarga;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.horarios(this.token).subscribe(
            response =>{
                this.horarios=response.horarios;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.temperaturas(this.token).subscribe(
            response =>{
                this.temperaturas=response.temperaturas;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.conceptos(this.token).subscribe(
            response =>{
                this.conceptos=response.conceptos;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.divisas(this.token).subscribe(
            response =>{
                this.divisas=response.divisas;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.divisasordenservicio(this.token).subscribe(
            response =>{
                this.divisasordenservicio=response.divisas;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.tiposevento(this.token).subscribe(
            response =>{
                this.tiposevento=response.tiposevento;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.eventodescripcion(this.token).subscribe(
            response =>{
                this.eventodescripcion=response.eventodescripcion;
                //console.log(this.eventodescripcion);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.cuentas(this.token).subscribe(
            response =>{
                this.cuentas=response.cuentas;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.tiposplanilla(this.token).subscribe(
            response =>{
                this.tiposplanilla=response.tiposplanilla;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.destinos_cargo(this.token).subscribe(
            response =>{
                this.destinos_cargo=response.destinos_cargo;
            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.tiposdocumento(this.token).subscribe(
            response =>{
                this.tiposdocumento=response.tiposdocumento;

            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.tipos_bulto(this.token).subscribe(
            response =>{
                this.tipos_bulto=response.tipos_bulto;

            },
            error=>{
                console.log(<any>error)
            }
        );

        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades=response.entidades;
                this.entidades.unshift({
                    'idtipoentidad': 0,
                    'identidad': '0-0',
                    'id': 0,
                    'entidad': '[Ninguno]',
                    'tipoentidad': '',
                    'direcciones': {
                        'identidaddireccion': 0,
                        'direccion': '[Ninguno]',
                        'ciudad': '',
                        'pais': ''
                    }
                });
                
                
                this.getEmbarqueData();
                
                
                //this.indiceentidadnotificar = this.entidades.findIndex(x => x.identidad === this.embarque.identidadnotificar);
                //this.indiceagentecarga = this.entidades.findIndex(x => x.id === this.embarque.idagentecarga && x.idtipoentidad === 5);
                //this.indiceagentedestino = this.entidades.findIndex(x => x.id === this.embarque.idagentedestino && x.idtipoentidad === 5);
                //console.log(this.entidades);
                //console.log(this.indiceexpedidor);
                //console.log(this.indiceultimoconsignatario);
                $('#ventanaLoading').modal('hide');
            },
            error=>{
                console.log(<any>error)
            }
        );

        
    }
    
    getEmbarqueData(){
        this._embarqueService.embarquesdetalle(this.token, this.idembarque).subscribe(
            response =>{
                this.embarque=response.embarque;
                this.indiceexpedidor = this.entidades.findIndex(x => x.identidad === this.embarque.idexpedidor);
                this.indiceultimoconsignatario = this.entidades.findIndex(x => x.identidad === this.embarque.idultimoconsignatario);
                console.log(this.embarque);

            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    getDireccionesExpedidor(){
        this.error_idexpedidor=false;
        this.error_idexpedidordireccion=false;
        this.embarque.idexpedidordireccion=null;
        this.indiceexpedidor = this.entidades.findIndex(x => x.identidad === this.embarque.idexpedidor);
        if(this.indiceexpedidor>=0){
            if (this.entidades[this.indiceexpedidor].direcciones.length==1){
                this.embarque.idexpedidordireccion=this.entidades[this.indiceexpedidor].direcciones[0].identidaddireccion;
            }
        }
    }

    getDireccionesUltimoConsignatario(){
        this.embarque.idultimoconsignatariodireccion=null;
        this.error_idultimoconsignatario=false;
        this.error_idultimoconsignatariodireccion=false;
        this.indiceultimoconsignatario = this.entidades.findIndex(x => x.identidad === this.embarque.idultimoconsignatario);
        if(this.indiceultimoconsignatario>=0){
            if (this.entidades[this.indiceultimoconsignatario].direcciones.length==1){
                this.embarque.idultimoconsignatariodireccion=this.entidades[this.indiceultimoconsignatario].direcciones[0].identidaddireccion;
            }
        }
    }

    getDireccionesEntidadNotificar(){
        /*
        this.embarque.identidadnotificardireccion=null;
        this.indiceentidadnotificar = this.entidades.findIndex(x => x.identidad === this.embarque.identidadnotificar);
        */
    }

    getDireccionesAgenteCarga(){
        /*
        this.embarque.idagentecargadireccion=null;
        this.indiceagentecarga = this.entidades.findIndex(x => x.id === this.embarque.idagentecarga && x.idtipoentidad === 5);
        */
    }

    getDireccionesAgenteDestino(){
        /*
        this.embarque.idagentedestinodireccion=null;
        this.indiceagentedestino = this.entidades.findIndex(x => x.id === this.embarque.idagentedestino && x.idtipoentidad === 5);
        */
    }

    prepararDatos(tipo: string, idtipo: number, esagente: boolean){
        this.erroridconcepto=false;
        this.errorcantidad=false;
        this.errormonto=false;
        this.erroriddivisa=false;
        this.errorfactura=false;
        this.errornota_entrega=false;
        this.errorfactura_cargo=false;
        this.erroriddestinocargo=false;
        this.arrayconceptos=[];
        switch (tipo) {
            case '0':
                this.cabeceraventanaDatos='Cargo'

                break;
            case '1':
                this.cabeceraventanaDatos='Costo'
                break;
        }

        if(idtipo==0){
            this.accionventanaDatos='Agregar';
            this.idtipo=idtipo;
            this.tienedocumento=false;
            this.idconcepto=null;
            this.cantidad=0;
            this.monto=0;
            this.iddivisa=1;
            this.identidad='0-0';
            this.notas='';
            this.factura='';
            this.nota_entrega='';
            this.factura_cargo='';
            this.iddestinocargo=null;
        }else{
            this.accionventanaDatos='Editar';
            this.idtipo=idtipo;
            if(tipo=='0'){
                var indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === idtipo);
                this.idconcepto = this.embarque.cargos[indicecargo].idconcepto;
                this.cantidad = this.embarque.cargos[indicecargo].cantidad;
                this.monto = this.embarque.cargos[indicecargo].monto;
                this.iddivisa = this.embarque.cargos[indicecargo].iddivisa;
                this.identidad = this.embarque.cargos[indicecargo].identidad;
                this.notas = this.embarque.cargos[indicecargo].notas;
                this.tienedocumento = this.embarque.cargos[indicecargo].tienedocumento;
                this.factura = '';
                this.nota_entrega = '';
                this.factura_cargo = this.embarque.cargos[indicecargo].factura;
                this.iddestinocargo = this.embarque.cargos[indicecargo].iddestinocargo;
            }else{
                var indicecosto = this.embarque.costos.findIndex(x => x.idcosto === idtipo);
                this.idconcepto = this.embarque.costos[indicecosto].idconcepto;
                this.cantidad = this.embarque.costos[indicecosto].cantidad;
                this.monto = this.embarque.costos[indicecosto].monto;
                this.iddivisa = this.embarque.costos[indicecosto].iddivisa;
                this.identidad = this.embarque.costos[indicecosto].identidad;
                this.notas = this.embarque.costos[indicecosto].notas;
                this.tienedocumento = this.embarque.costos[indicecosto].tienedocumento;
                this.factura = this.embarque.costos[indicecosto].factura;
                this.nota_entrega = this.embarque.costos[indicecosto].nota_entrega;
                this.factura_cargo = '';
                this.iddestinocargo = null;
            }


        }

        this.tipo = tipo;
        this.esagente = esagente;
    }
    
    obtenerCargosParametrizados(){
        this._embarqueService.getcargosprametros(this.token, this.idembarque).subscribe(
            response =>{
                let cargos=response.nuevoscargos;
                for(let cc=0; cc<cargos.length; cc++){
                    let idcargo=this.randomInteger(100,999)*(-1);
                    this.embarque.cargos.push({
                        'idcargo': idcargo,
                        'idconcepto': cargos[cc].idconcepto,
                        'concepto': '',
                        'concepto_ovp': '',
                        'id_OVP': 0,
                        'cantidad': cargos[cc].cantidad,
                        'monto': cargos[cc].monto,
                        'iddivisa': cargos[cc].iddivisa,
                        'codigodivisa': '',
                        'tipocambio': 1,
                        'tipocambious': 1,
                        'idtipodestinatario': cargos[cc].idtipodestinatario,
                        'iddestinatario': cargos[cc].iddestinatario,
                        'identidad': this.identidad,
                        'destinatario': '',
                        'notas': '',
                        'esagente': false,
                        'idfacturanotadebito': 0,
                        'documento': '',
                        'facturanotadebito': '',
                        'idestadofacturanotadebito': null,
                        'idplanilla': 0,
                        'idinvoice': 0,
                        'idordenservicioi': 0,
                        'tienedocumento': false,
                        'factura': cargos[cc].factura,
                        'iddestinocargo': cargos[cc].iddestinocargo,
                        'destinocargo': '',
                        'usuario': null,	
                        'created_at': null	
                    });	

                }
                this.saveCargos();
            },
                error=>{
                    console.log(<any>error)
                }
            );
        
    }

    buscarAsgard(){
        this.error_carpetapacena=false;
        if (this.embarque.carpetapacena.length<10){
            this.error_carpetapacena=true;
        }

        if(!this.error_carpetapacena){
            this._asgardService.carpetaAsgard(this.token, this.embarque.carpetapacena).subscribe(
                response =>{
                    if(response.codigo==200){
                        console.log(response)
                        if(response.carpetaAsgard.idcasos>0){
                            switch (this.embarque.idtipoembarque) {
                                case 1: //terrestre
                                    this.embarque.numeroguia=response.carpetaAsgard.documento_terrestre;
                                    break;
                                case 3: //aereo
                                    this.embarque.numeroguia=response.carpetaAsgard.documento_aereo;
                                    break;
                                case 5: //multimodal
                                    this.embarque.numeroguia=response.carpetaAsgard.documento_multimodal;
                                    break;
                            }
                            this.error_numeroguia=false;
                            this.embarque.noidentificacion=response.carpetaAsgard.pedido;
                            this.error_noidentificacion=false;
                            this.embarque.descripcioncarga=response.carpetaAsgard.descripciongeneral;
                            this.error_descripcioncarga=false;
                            this.embarque.nodui=response.carpetaAsgard.nodui;
                            this.embarque.peso=response.carpetaAsgard.pesobruto;
                            this.error_peso=false;
                            this.embarque.piezas=response.carpetaAsgard.bultos;
                            this.error_piezas=false;
                            let indicetipobulto = this.tipos_bulto.findIndex(x => x.codigo === response.carpetaAsgard.tipobulto);
                            if(indicetipobulto>=0){
                                this.embarque.idtipobulto=this.tipos_bulto[indicetipobulto].idtipobulto;
                            }
                            this.error_idtipobulto=false;
                            this.embarque.servicio_logistico=response.carpetaAsgard.servicioSLG;
                            
                            let indiceincoterms = this.incoterms.findIndex(x => x.incoterms === response.carpetaAsgard.incoterms);
                            
                            if(indiceincoterms>=0){
                                this.embarque.idincoterms = this.incoterms[indiceincoterms].idincoterms;
                                this.error_idincoterms=false;
                            }
                            
                            if(response.carpetaAsgard.id_ATLANTES!='' && response.carpetaAsgard.id_ATLANTES!=null){
                                this.embarque.idultimoconsignatario='1-'+response.carpetaAsgard.id_ATLANTES;
                                this.getDireccionesUltimoConsignatario();
                            }
                            
                            if(response.carpetaAsgard.id_proveedor_atlantes!='' && response.carpetaAsgard.id_proveedor_atlantes!=null){
                                this.embarque.idexpedidor='2-'+response.carpetaAsgard.id_proveedor_atlantes;
                                this.getDireccionesExpedidor();
                            }
                            
                            if(response.carpetaAsgard.idtipotransporte>0){
                                this.embarque.idmediotransporte=response.carpetaAsgard.idtipotransporte;
                            }
                            if(response.carpetaAsgard.idtipocarga>0){
                                this.embarque.idtipocarga=response.carpetaAsgard.idtipocarga;
                            }
                            
                            /*
                            let indicetransportista = this.entidades.findIndex(x => (x.entidad == response.carpetaAsgard.transportista && x.idtipoentidad == 4));
                            if(indicetransportista>=0){
                                this.embarque.idtransportista = this.entidades[indicetransportista].id;
                            }
                            */
                            this.embarque.idtransportista=response.carpetaAsgard.idtransportista_slg;
                            this.error_idtransportista=false;
                            
                            this.embarque.numerovehiculo=response.carpetaAsgard.nroplaca;
                            this.error_numerovehiculo=false;
                            
                            this.embarque.fechasalida=response.carpetaAsgard.fechalevante;
                            this.error_fechasalida=false;
                            this.embarque.fechaarribo=response.carpetaAsgard.fechaentregaalmacen;
                            this.error_fechaarribo=false;
                            
                            let idaduana=null;
                            if(response.carpetaAsgard.idaduana_interiorscz>0){
                                idaduana=response.carpetaAsgard.idaduana_interiorscz;
                            }else{
                                var indiceaduana = this.aduanas.findIndex(x => x.aduana.substring(0, 3) == response.carpetaAsgard.aduana);
                                if(indiceaduana>=0){
                                    idaduana=this.aduanas[indiceaduana].idaduana;
                                }
                            }
                            
                            if(idaduana!=null){
                                var indiceciudad = this.ciudades.findIndex(x => x.idaduana == idaduana);
                                if(indiceciudad>=0){
                                    this.embarque.idsalida = this.ciudades[indiceciudad].idciudad;
                                    this.error_idsalida=false;
                                }
                            }
                            
                            
                            if(response.carpetaAsgard.idlugardestino>0){
                                this.embarque.idarribo=response.carpetaAsgard.idlugardestino;
                                this.error_idarribo=false;
                            }
                            
                            if(response.carpetaAsgard.idhorario>0){
                                this.embarque.idhorario=response.carpetaAsgard.idhorario;
                                this.error_idhorario=false;
                            }
                            
                            if(response.carpetaAsgard.idtemperatura>0){
                                this.embarque.idtemperatura=response.carpetaAsgard.idtemperatura;
                                this.error_idtemperatura=false;
                            }
                            
                            this.embarque.numero_precinto=response.carpetaAsgard.numero_precinto;
                            this.embarque.estibadoresSLG=response.carpetaAsgard.estibadoresSLG;
                            this.embarque.estibadores=response.carpetaAsgard.estibadores;
                            this.embarque.costo_operador_transporte=response.carpetaAsgard.costo_operador_transporte;
                            
                        }else{
                            this.toast_mensaje="La carpeta no existe";
                            this.toast_tipo="Error";
                            $("#liveToast").toast('show');
                        }
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }

    }

    randomInteger(min: number, max: number) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    guardarCargoCosto(){
        let error=false;
        this.erroridconcepto=false;
        if (this.idconcepto==null){
            this.erroridconcepto=true;
            error=true;
        }
        this.errorcantidad=false;
        if (this.cantidad<=0){
            this.errorcantidad=true;
            error=true;
        }
        this.errormonto=false;
        if (this.monto<=0){
            this.errormonto=true;
            error=true;
        }
        this.erroriddivisa=false;
        if (this.iddivisa==null){
            this.erroriddivisa=true;
            error=true;
        }
        this.errorfactura=false;
        if (this.factura == '' && this.tipo == '1' && this.tokenDetalle.contabilidad){
            this.errorfactura=true;
            error=true;
        }
        this.errornota_entrega=false;
        if (this.nota_entrega=='' && this.tipo=='1' && this.tokenDetalle.contabilidad){
            this.errornota_entrega=true;
            error=true;
        }
        
        this.errorfactura_cargo=false;
        if (this.factura_cargo=='' && this.tipo=='0' && this.tokenDetalle.contabilidad){
            this.errorfactura_cargo=true;
            error=true;
        }
        
        this.erroriddestinocargo=false;
        if (this.iddestinocargo==null && this.tipo=='0' && this.tokenDetalle.contabilidad){
            this.erroriddestinocargo=true;
            error=true;
        }

        if (!error){
            var indiceconcepto = this.conceptos.findIndex(x => x.idconcepto === this.idconcepto);
            var indicedivisa = this.divisas.findIndex(x => x.iddivisa === this.iddivisa);
            if(this.identidad==null){
                this.identidad='0-0';
            }
            let identidadslit=this.identidad.split("-");
            var indiceentidad = this.entidades.findIndex(x => x.identidad === this.identidad);
            if (this.tipo=='0'){
                if (this.idtipo==0){
                    let idcargo=this.randomInteger(100,999)*(-1);
                    let indicedestipocargo = this.destinos_cargo.findIndex(x => x.iddestinocargo === this.iddestinocargo);
                    let destinocargo='';
                    if(indicedestipocargo>=0){
                        destinocargo = this.destinos_cargo[indicedestipocargo].destinocargo;
                    }
                    
                    
                    this.embarque.cargos.push({
                        'idcargo': idcargo,
                        'idconcepto': this.idconcepto,
                        'concepto': this.conceptos[indiceconcepto].concepto,
                        'concepto_ovp': this.conceptos[indiceconcepto].concepto_ovp,
                        'id_OVP': this.conceptos[indiceconcepto].id_OVP,
                        'cantidad': this.cantidad,
                        'monto': this.monto,
                        'iddivisa': this.iddivisa,
                        'codigodivisa': this.divisas[indicedivisa].codigo,
                        'tipocambio': 1,
                        'tipocambious': 1,
                        'idtipodestinatario': parseInt(identidadslit[0]),
                        'iddestinatario': parseInt(identidadslit[1]),
                        'identidad': this.identidad,
                        'destinatario': this.entidades[indiceentidad ].entidad,
                        'notas': this.notas,
                        'esagente': this.esagente,
                        'idfacturanotadebito': 0,
                        'documento': '',
                        'facturanotadebito': '',
                        'idestadofacturanotadebito': null,
                        'idplanilla': 0,
                        'idinvoice': 0,
                        'idordenservicioi': 0,
                        'tienedocumento': false,
                        'factura': this.factura_cargo,
                        'iddestinocargo': this.iddestinocargo,
                        'destinocargo': destinocargo,
                        'usuario': null,
                        'created_at': null
                    });
                }else{
                    var indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === this.idtipo);
                    this.embarque.cargos[indicecargo].idconcepto = this.idconcepto;

                    this.embarque.cargos[indicecargo].concepto = this.conceptos[indiceconcepto].concepto;
                    this.embarque.cargos[indicecargo].concepto_ovp = this.conceptos[indiceconcepto].concepto_ovp;
                    this.embarque.cargos[indicecargo].id_OVP = this.conceptos[indiceconcepto].id_OVP;
                    this.embarque.cargos[indicecargo].cantidad = this.cantidad;
                    this.embarque.cargos[indicecargo].monto = this.monto;
                    this.embarque.cargos[indicecargo].iddivisa = this.iddivisa;

                    this.embarque.cargos[indicecargo].codigodivisa = this.divisas[indicedivisa].codigo;
                    this.embarque.cargos[indicecargo].identidad = this.identidad;

                    this.embarque.cargos[indicecargo].destinatario = this.entidades[indiceentidad].entidad;

                    this.embarque.cargos[indicecargo].idtipodestinatario = parseInt(identidadslit[0]);
                    this.embarque.cargos[indicecargo].iddestinatario = parseInt(identidadslit[1]);
                    this.embarque.cargos[indicecargo].notas = this.notas;
                    
                    this.embarque.cargos[indicecargo].factura = this.factura_cargo;
                    this.embarque.cargos[indicecargo].iddestinocargo = this.iddestinocargo;
                }
                if(this.esagente){
                    this.saveCargosCostosAgente();
                }else{
                    this.saveCargos();
                }

            }


            if (this.tipo=='1'){
                if (this.idtipo==0){
                    let idcosto=this.randomInteger(1000,9999)*(-1);
                    this.embarque.costos.push({
                        'idcosto': idcosto,
                        'idconcepto': this.idconcepto,
                        'concepto': this.conceptos[indiceconcepto].concepto,
                        'concepto_ovp': this.conceptos[indiceconcepto].concepto_ovp,
                        'id_OVPRef': this.conceptos[indiceconcepto].id_OVPRef,
                        'cantidad': this.cantidad,
                        'monto': this.monto,
                        'iddivisa': this.iddivisa,
                        'codigodivisa': this.divisas[indicedivisa].codigo,
                        'tipocambio': 1,
                        'tipocambious': 1,
                        'idtipodestinatario': parseInt(identidadslit[0]),
                        'iddestinatario': parseInt(identidadslit[1]),
                        'identidad': this.identidad,
                        'destinatario': this.entidades[indiceentidad].entidad,
                        'notas': this.notas,
                        'esagente': this.esagente,
                        'idfacturanotadebito': 0,
                        'documento': null,
                        'facturanotadebito': null,
                        'idestadofacturanotadebito': null,
                        'idordenservicioe': 0,
                        'tienedocumento': false,
                        'factura': this.factura,
                        'nota_entrega': this.nota_entrega,
                        'usuario': '',
                        'created_at': null
                    });

                    let idcargo=this.randomInteger(100,999)*(-1);
                    let idconceptocargo=this.conceptos[indiceconcepto].idconceptocargo;
                    let indiceconceptocargo=this.conceptos.findIndex(x => x.idconcepto === idconceptocargo);


                    this.embarque.cargos.push({
                        'idcargo': idcargo,
                        'idconcepto': idconceptocargo,
                        'concepto': this.conceptos[indiceconceptocargo].concepto,
                        'concepto_ovp': this.conceptos[indiceconceptocargo].concepto_ovp,
                        'id_OVP': this.conceptos[indiceconceptocargo].id_OVP,
                        'cantidad': this.cantidad,
                        'monto': this.monto,
                        'iddivisa': this.iddivisa,
                        'codigodivisa': this.divisas[indicedivisa].codigo,
                        'tipocambio': 1,
                        'tipocambious': 1,
                        'idtipodestinatario': parseInt(identidadslit[0]),
                        'iddestinatario': parseInt(identidadslit[1]),
                        'identidad': this.identidad,
                        'destinatario': this.entidades[indiceentidad ].entidad,
                        'notas': this.notas,
                        'esagente': this.esagente,
                        'idfacturanotadebito': 0,
                        'documento': null,
                        'facturanotadebito': null,
                        'idestadofacturanotadebito': null,
                        'idplanilla': 0,
                        'idinvoice': 0,
                        'idordenservicioi': 0,
                        'tienedocumento': false,
                        'factura': null,
                        'iddestinocargo': null,
                        'destinocargo': null,
                        'usuario': null,
                        'created_at': null
                    });

                }else{
                    var indicecosto = this.embarque.costos.findIndex(x => x.idcosto === this.idtipo);
                    this.embarque.costos[indicecosto].idconcepto = this.idconcepto;

                    this.embarque.costos[indicecosto].concepto = this.conceptos[indiceconcepto].concepto;
                    this.embarque.costos[indicecosto].concepto_ovp = this.conceptos[indiceconcepto].concepto_ovp;
                    this.embarque.costos[indicecosto].id_OVPRef = this.conceptos[indiceconcepto].id_OVPRef;
                    this.embarque.costos[indicecosto].cantidad = this.cantidad;
                    this.embarque.costos[indicecosto].monto = this.monto;
                    this.embarque.costos[indicecosto].iddivisa = this.iddivisa;

                    this.embarque.costos[indicecosto].codigodivisa = this.divisas[indicedivisa].codigo;
                    this.embarque.costos[indicecosto].identidad = this.identidad;

                    this.embarque.costos[indicecosto].destinatario = this.entidades[indiceentidad].entidad;

                    this.embarque.costos[indicecosto].idtipodestinatario = parseInt(identidadslit[0]);
                    this.embarque.costos[indicecosto].iddestinatario = parseInt(identidadslit[1]);
                    this.embarque.costos[indicecosto].notas = this.notas;
                    this.embarque.costos[indicecosto].factura = this.factura;
                    this.embarque.costos[indicecosto].nota_entrega = this.nota_entrega;
                }
                if(this.esagente){
                    this.saveCargosCostosAgente();
                }else{
                    this.saveCostos();
                    this.saveCargos();
                }

            }


            $('#ventanaCargoCosto').modal('hide');
        }



    }

    prepararEliminar(tipo: string, idtipo: number){
        this.tipoeliminar=tipo;
        this.idtipoeliminar=idtipo;
    }

    eliminarCargoCosto(){
        if(this.tipoeliminar=='0'){
            var indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === this.idtipoeliminar);
            this.embarque.cargos.splice(indicecargo, 1);
            this.saveCargos();
        }
        if(this.tipoeliminar=='1'){
            var indicecosto = this.embarque.costos.findIndex(x => x.idcosto === this.idtipoeliminar);
            this.embarque.costos.splice(indicecosto, 1);
            this.saveCostos();
        }

        this.saveCargosCostosAgente();

        $('#confirmarEliminarCargoCosto').modal('hide');
    }

    ventanaEditarEvento(idevento: number){
        this.cabeceraventanaEventos="Editar";
        this.idevento=idevento;
        var indiceevento = this.embarque.eventos.findIndex(x => x.idevento === this.idevento);
        this.fecharegistro = this.embarque.eventos[indiceevento].fecharegistro;
        this.fechaplanificada = this.embarque.eventos[indiceevento].fechaplanificada;
        this.con_observacion = this.embarque.eventos[indiceevento].con_observacion;
        this.ideventodescripcion = this.embarque.eventos[indiceevento].ideventodescripcion;
        this.idtipoevento = this.embarque.eventos[indiceevento].idtipoevento;
        this.evento = this.embarque.eventos[indiceevento].evento;
        this.nombre = this.embarque.eventos[indiceevento].nombre;
        this.enviado = this.embarque.eventos[indiceevento].enviado;

        this.errorfechaplanificada=false;
        this.erroridtipoevento=false;



        $('#ventanaEvento').modal('show');
    }

    ventanaAgregarEvento(){

        this.cabeceraventanaEventos="Agregar";
        this.idevento=0;
        this.fecharegistro = this._usuarioService.getCurrentDateFilterValue();
        this.fechaplanificada = this._usuarioService.getCurrentDateFilterValue();
        this.con_observacion = false;
        this.ideventodescripcion = null;
        this.idtipoevento = null;
        this.evento = '';
        this.nombre = this.tokenDetalle.nombre;
        this.enviado = false;
        this.errorfechaplanificada=false;
        this.erroridtipoevento=false;
    }

    guardarEvento(){
        this.erroridtipoevento=false;
        if (this.idtipoevento==null){
            this.erroridtipoevento=true;
        }
        this.errorfechaplanificada=false;
        if (this.fechaplanificada==''){
            this.errorfechaplanificada=true;
        }

        if (!this.errorfechaplanificada && !this.erroridtipoevento){
            var indicetipoevento = this.tiposevento.findIndex(x => x.idtipoevento === this.idtipoevento);

            var eventodescripcion='';
            if(this.con_observacion){
                var indiceeventodescripcion = this.eventodescripcion.findIndex(x => x.ideventodescripcion === this.ideventodescripcion);
                if(indiceeventodescripcion>=0){
                    eventodescripcion=this.eventodescripcion[indiceeventodescripcion].eventodescripcion;
                }
            }

            if (this.idevento==0){
                this.errorenviarmaileventos=false;
                let idevento=this.randomInteger(1000,9999)*(-1);
                this.embarque.eventos.push({
                    'idevento': idevento,
                    'fecharegistro': this.fecharegistro,
                    'fechaplanificada': this.fechaplanificada,
                    'con_observacion': this.con_observacion,
                    'ideventodescripcion': this.ideventodescripcion,
                    'eventodescripcion': eventodescripcion,
                    'idtipoevento': this.idtipoevento,
                    'tipoevento': this.tiposevento[indicetipoevento].tipoevento,
                    'evento': this.evento,
                    'idusuario': this.tokenDetalle.idusuario,
                    'nombre': this.tokenDetalle.nombre,
                    'enviado': false
                });
            }else{
                var indiceevento = this.embarque.eventos.findIndex(x => x.idevento === this.idevento);
                this.embarque.eventos[indiceevento].idtipoevento = this.idtipoevento;
                this.embarque.eventos[indiceevento].tipoevento = this.tiposevento[indicetipoevento].tipoevento;
                this.embarque.eventos[indiceevento].fechaplanificada = this.fechaplanificada;
                this.embarque.eventos[indiceevento].con_observacion = this.con_observacion;
                this.embarque.eventos[indiceevento].ideventodescripcion = this.ideventodescripcion;
                this.embarque.eventos[indiceevento].eventodescripcion = eventodescripcion;
                this.embarque.eventos[indiceevento].evento = this.evento;
                this.embarque.eventos[indiceevento].idtipoevento = this.idtipoevento;
            }
            $("#ventanaEvento").modal('hide');
        }

    }

    ventanaEliminarEvento(idevento: number, event: any){
        this.ideventoeliminar=idevento;
        $("#confirmarEliminarEvento").modal('show');
        event.stopPropagation();
    }

    eliminarEvento(){
        var indiceevento = this.embarque.eventos.findIndex(x => x.idevento === this.ideventoeliminar);
        this.embarque.eventos.splice(indiceevento, 1);
        $("#confirmarEliminarEvento").modal('hide');
        //alert("evento eliminado");
    }

    agregarCorreo(){
        this.errorenviarmaileventoscorreos=false;
        this.embarque.correosembarque.push({
            'idcorreosembarque': this.randomInteger(1000,9999)*(-1),
            'correo': ''
        });
    }

    eliminarCorreo(idcorreosembarque: number){
        var indicecorreo = this.embarque.correosembarque.findIndex(x => x.idcorreosembarque === idcorreosembarque);
        this.embarque.correosembarque.splice(indicecorreo, 1);
        this.correosconerror=[];
    }

    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if(fileInput.target.files){
            this.uploadFileInput=<Array<File>>fileInput.target.files;
            this.archivocargado=true;
            //console.log(this.uploadFileInput);
            //this.myfilename=this.uploadFileInput[0].name;
        }else {
            //this.myfilename = 'Seleccione un Archivo';
        }
    }

    validarDocumento(){
        this.nombredocumentocargar=[];
        this.errorarchivo=false;
        this.existedocumento=false;
        if (!this.archivocargado){
            this.errorarchivo=true;
        }
        if (!this.errorarchivo){
            for(let dd=0; dd<this.uploadFileInput.length; dd++){
                let indicedoc = this.embarque.documentosembarque.findIndex(x => x.documento === this.uploadFileInput[dd].name);
                if(indicedoc>=0){
                    this.nombredocumentocargar.push(this.uploadFileInput[dd].name);
                    this.existedocumento=true;

                }
            }
            if (this.existedocumento){
                $("#confirmarSobreescribirDocumento").modal('show');
            }else{
                this.cargarDocumento();
            }

            /*

            if(indicedoc>=0){
                this.nombredocumentocargar=this.uploadFileInput[0].name;
                this.existedocumento=true;
                $("#confirmarSobreescribirDocumento").modal('show');
                //console.log("existe");
            }else{
                this.existedocumento=false;
                this.cargarDocumento();
            }
            */
        }

    }

    cargarDocumento(){
        this.erroresdocumento=[];
        this._embarqueService.cargardocumento(this.token, this.idembarque, this.uploadFileInput).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    for(let aa=0;aa<response.file_name.length;aa++){
                        if(response.file_name[aa].error){
                            this.erroresdocumento.push({
                                'name': response.file_name[aa].name,
                                'mensaje': response.file_name[aa].mensaje
                            });
                        }else{
                            let indicedoc = this.embarque.documentosembarque.findIndex(x => x.documento === response.file_name[aa].name);
                            if(indicedoc==-1){
                                this.embarque.documentosembarque.push({
                                    'iddocumento': (this.embarque.documentosembarque.length+1),
                                    'documento': response.file_name[aa].name
                                });
                            }
                        }
                    }

                    if (this.existedocumento){
                        $("#confirmarSobreescribirDocumento").modal('hide');
                    }
                    //console.log()
                    //this.toast_tipo="Exito";
                }else{
                    //this.toast_tipo="Error";
                }
                //$("#liveToast").toast('show');

                this.myInputVariable.nativeElement.value = "";
                this.archivocargado = false;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    downloadDocumento(indiceDocumento: number){
        this._embarqueService.download(this.token, this.idembarque,this.embarque.documentosembarque[indiceDocumento].documento).subscribe(
            response =>{
                if(response.codigo==200){
                    const linkSource = 'data:'+response.pathinfo+';base64,'+response.data;
                    const downloadLink = document.createElement("a");
                    const fileName = this.embarque.documentosembarque[indiceDocumento].documento;

                    downloadLink.href = linkSource;
                    downloadLink.download = fileName;
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

    ventanaEliminarDocumento(indiceDocumento: number, event: any){
        this.indicedocumentoeliminar=indiceDocumento;
        $("#confirmarEliminarDocumento").modal('show');
        event.stopPropagation();
    }

    eliminarDocumento(){
        let documentoeliminar = this.embarque.documentosembarque[this.indicedocumentoeliminar].documento;
        this._embarqueService.eliminardocumento(this.token, this.idembarque, documentoeliminar).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.embarque.documentosembarque.splice(this.indicedocumentoeliminar, 1);
                    /*
                    this.mensajeexito=response.mensaje;
                    $("#liveToast").toast('show');
                    */
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                    /*
                    this.mensajeerror=response.mensaje;
                    $("#liveToastError").toast('show');
                    */
                }
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );

        $('#confirmarEliminarDocumento').modal('hide');
    }

    prepararFactura(idfactura: number){
        this.facturando=false;
        this.idfactura_pendiente=idfactura;
        this.idtipodocumento=5;
        this.titulomodalfactura='Nueva Factura';
        this.fechafactura = this.fechaactual;
        if (idfactura>0){
            let indiceFactura = this.embarque.facturas.findIndex(x => x.idfactura === idfactura);
            this.titulomodalfactura = 'Registrar Factura No ' + this.embarque.facturas[indiceFactura].numero;
            this.fechafactura = this.embarque.facturas[indiceFactura].fecha;
        }
        this.erroridcobrarafactura=false;
        this.errornitf=false;
        this.errornombref=false;
        this.errortotalfacturabs=false;
        this.idcobrarafactura = '1-' + this.embarque.idcliente;
        this.getDataFacturacion();
        this.palletsfactura='';
        this.rotacionfactura='';
        this.conceptosfacturardebitar=[];
        for (let cc = 0; cc < this.embarque.cargos.length; cc++){
            if (this.embarque.cargos[cc].idfacturanotadebito == 0 && this.embarque.cargos[cc].idcargo > 0 && this.embarque.cargos[cc].id_OVP != null && (this.embarque.cargos[cc].iddestinocargo==1 || this.embarque.cargos[cc].iddestinocargo==5)){
                this.conceptosfacturardebitar.push({
                    'idcargo': this.embarque.cargos[cc].idcargo,
                    'concepto': this.embarque.cargos[cc].concepto_ovp,
                    'destinatario': this.embarque.cargos[cc].destinatario,
                    'montobs': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambio,
                    'montous': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambious,
                    'marcado': false
                });
            }
        }
        this.getSumaFactura();
        //this.getDataFacturacionCorreos();
    }

    getDataFacturacion(){
        this.erroridcobrarafactura=false;
        this.errornitf=false;
        this.errornombref=false;
        if (this.idcobrarafactura!==null){
            let indiceCliente = this.entidades.findIndex(x => x.identidad === this.idcobrarafactura);
            this.idtipodocumento = this.entidades[indiceCliente].idtipodocumento;
            this.nitf = this.entidades[indiceCliente].numerofacturacion;
            this.nombref=this.entidades[indiceCliente].razonsocial;
            this.correos_factura=this.entidades[indiceCliente].correosfacturacion;
        }
        //this.getDataFacturacionCorreos();
    }

    getDataFacturacionNIT(){
        this.errornitf=false;
        if(this.nitf>0){
            this._datomaestroService.nombrefactura(this.token, this.idtipodocumento, this.nitf).subscribe(
                response =>{
                    //console.log(response);

                    if(response.codigo==200){
                        this.nombref=response.nombre
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
            this.getDataFacturacionCorreos();
        }
    }

    getDataFacturacionCorreos(){
        this.correos_factura=[];
        if(this.nitf>0){
            this._datomaestroService.correosfactura(this.token, this.idtipodocumento, this.nitf).subscribe(
                response =>{
                    //console.log(response);

                    if(response.codigo==200){
                        this.correos_factura=response.correos;
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    agregarCorreoFactura(){
        this.correos_factura.push({
            'idcorreofacturacion': 0,
            'correo': '',
            'error': false
        });
        //console.log(this.correos_factura);
    }

    eliminarCorreoFactura(indice: number){
        this.correos_factura.splice(indice, 1);
        //console.log(this.correos_factura);
    }

    getSumaFactura(){
        //console.log(this.conceptosfacturardebitar);
        this.totalfacturabs=0;
        this.totalfacturaus=0;
        this.errortotalfacturabs=false;
        for (let cc = 0; cc < this.conceptosfacturardebitar.length; cc++){
            //console.log(this.conceptosfacturardebitar[cc].marcado);
            if (this.conceptosfacturardebitar[cc].marcado){
                this.totalfacturabs=this.totalfacturabs+this.conceptosfacturardebitar[cc].montobs;
                this.totalfacturaus=this.totalfacturaus+this.conceptosfacturardebitar[cc].montous;
            }
        }
        //console.log(this.conceptosfacturardebitar);
    }

    reservarFactura(){
        this._contabilidadService.reservarfactura(this.token, this.idembarque).subscribe(
            response =>{
                //console.log(response);

                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.embarque.facturas=response.facturas;
                }else{
                    this.toast_tipo="Error";
                }
                $("#ventanaNuevaFactura").modal('hide');
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    facturar(){
        this.erroridcobrarafactura=false;
        if(this.idcobrarafactura==null){
            this.erroridcobrarafactura=true;
        }

        this.errornitf=false;
        if(this.nitf<0){
            this.errornitf=true;
        }
        this.errornombref=false;
        if (this.nombref==''){
            this.errornombref=true;
        }

        this.errortotalfacturabs=false;
        if (this.totalfacturabs<=0){
            this.errortotalfacturabs=true;
        }

        let errorcorreos=false;
        for (let cf = 0; cf<this.correos_factura.length;cf++){
            if (!this.ValidateEmail(this.correos_factura[cf].correo)){
                errorcorreos=true;
                this.correos_factura[cf].error=true;
            }
        }

        if (!this.erroridcobrarafactura && !this.errornitf && !this.errornombref && !this.errortotalfacturabs && !errorcorreos){
            let conceptosfactura=[];
            for(let cc=0; cc<this.conceptosfacturardebitar.length; cc++){
                if(this.conceptosfacturardebitar[cc].marcado){
                    conceptosfactura.push(this.conceptosfacturardebitar[cc].idcargo);
                }
            }
            let datosfactura = {idfactura: this.idfactura_pendiente, idcobrara: this.idcobrarafactura, nombre: this.nombref, idtipodocumento: this.idtipodocumento, nit: this.nitf, pallets: this.palletsfactura, rotacion: this.rotacionfactura, totalfacturabs: this.totalfacturabs, cargos: conceptosfactura, correos: this.correos_factura};
            this.facturando=true;
            this._contabilidadService.generarfactura(this.token, this.idembarque, datosfactura).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.facturas=response.facturas;
                        for (let cc = 0; cc < conceptosfactura.length; cc++){
                            let indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === conceptosfactura[cc]);
                            this.embarque.cargos[indicecargo].documento='Factura';
                            this.embarque.cargos[indicecargo].facturanotadebito=response.nrofactura;
                            this.embarque.cargos[indicecargo].idestadofacturanotadebito=1;
                            this.embarque.cargos[indicecargo].idfacturanotadebito=response.idfactura;
                            this.embarque.cargos[indicecargo].tienedocumento=true;
                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevaFactura").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }

    }

    downloadFactura(idfactura: number){
        this._contabilidadService.downloadFactura(this.token, idfactura).subscribe(
            response =>{
                //console.log(response);
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

    downloadFacturaMembretada(idfactura:number, event: any){

        this._contabilidadService.downloadFacturaMembretada(this.token, idfactura).subscribe(
            response =>{
                //console.log(response);
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

        event.stopPropagation();
    }

    prepararNC(){
        this.erroridcobrarnc=false;
        this.erroridcuentanc=false;
        this.erroriddivisanc=false;
        this.idcobrarnc='1-' + this.embarque.idcliente;
        this.observacionesnc='';
        this.conceptosfacturardebitar=[];
        for (let cc = 0; cc < this.embarque.cargos.length; cc++){
            if (this.embarque.cargos[cc].idfacturanotadebito == 0 && this.embarque.cargos[cc].idcargo>0 && (this.embarque.cargos[cc].iddestinocargo==2 || this.embarque.cargos[cc].iddestinocargo==6)){
                this.conceptosfacturardebitar.push({
                    'idcargo': this.embarque.cargos[cc].idcargo,
                    'concepto': this.embarque.cargos[cc].concepto_ovp,
                    'montobs': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambio,
                    'montous': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambious,
                    'marcado': false
                });
            }
        }
        this.getSumaNC();
    }

    getSumaNC(){
        this.totalNCbs=0;
        this.totalNCus=0;
        this.errortotalNCbs=false;
        for (let cc = 0; cc < this.conceptosfacturardebitar.length; cc++){
            //console.log(this.conceptosfacturardebitar[cc].marcado);
            if (this.conceptosfacturardebitar[cc].marcado){
                this.totalNCbs=this.totalNCbs+this.conceptosfacturardebitar[cc].montobs;
                this.totalNCus=this.totalNCus+this.conceptosfacturardebitar[cc].montous;
            }
        }
    }

    generarNC(){
        this.erroridcobrarnc=false;
        if (this.idcobrarnc==null){
            this.erroridcobrarnc=true;
        }
        this.erroridcuentanc=false;
        if (this.idcuentanc==null){
            this.erroridcuentanc=true;
        }
        this.erroriddivisanc=false;
        if (this.iddivisanc==null){
            this.erroriddivisanc=true;
        }
        this.errortotalNCbs=false;
        if (this.totalNCbs<=0){
            this.errortotalNCbs=true;
        }
        if (!this.erroridcobrarnc && !this.erroridcuentanc && !this.erroriddivisanc && !this.errortotalNCbs){
            let conceptosNC=[];
            for(let cc=0; cc<this.conceptosfacturardebitar.length; cc++){
                if(this.conceptosfacturardebitar[cc].marcado){
                    conceptosNC.push(this.conceptosfacturardebitar[cc].idcargo);
                }
            }
            let datosNC = {idcobrara: this.idcobrarnc, idcuenta: this.idcuentanc, iddivisa: this.iddivisanc, observaciones: this.observacionesnc, cargos: conceptosNC}
            this._contabilidadService.generarnotacobranza(this.token, this.idembarque, datosNC).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.notascobranza=response.notascobranza;
                        for (let cc = 0; cc < conceptosNC.length; cc++){
                            let indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === conceptosNC[cc]);
                            this.embarque.cargos[indicecargo].documento='Nota de Cobranza';
                            this.embarque.cargos[indicecargo].facturanotadebito=response.nronotadebito;
                            this.embarque.cargos[indicecargo].idestadofacturanotadebito=1;
                            this.embarque.cargos[indicecargo].idfacturanotadebito=response.idnotadebito;
                            this.embarque.cargos[indicecargo].tienedocumento=true;
                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevaNotaCobranza").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    downloadNC(idnotadebito: number){
        this._contabilidadService.downloadNC(this.token, idnotadebito).subscribe(
            response =>{
                //console.log(response);
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

    downloadNCMembretada(idnotadebito:number, event: any){

        this._contabilidadService.downloadNCMembretada(this.token, idnotadebito).subscribe(
            response =>{
                //console.log(response);
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

        event.stopPropagation();
    }

    prepararInvoice(idinvoice: number){
        this.titulomodalinvoice="Nueva Invoice";
        this.fechainvoice=this.fechaactual;
        this.idinvoice_pendiente=idinvoice;
        if (idinvoice>0){
            let indiceInvoice = this.embarque.invoices.findIndex(x => x.idinvoice === idinvoice);
            this.titulomodalinvoice = 'Registrar Invoice No ' + this.embarque.invoices[indiceInvoice].numero;
            this.fechainvoice = this.embarque.invoices[indiceInvoice].fecha;
        }


        this.idagentei=null;
        this.iddireccionagentei=null;
        this.erroridagentei=false;
        this.conceptosinvoice=[];
        for (let cc = 0; cc < this.embarque.cargos.length; cc++){
            if (this.embarque.cargos[cc].idinvoice == 0 && this.embarque.cargos[cc].idcargo>0 && (this.embarque.cargos[cc].iddestinocargo==3 || this.embarque.cargos[cc].iddestinocargo==5 || this.embarque.cargos[cc].iddestinocargo==6)){
                this.conceptosinvoice.push({
                    'idcargo': this.embarque.cargos[cc].idcargo,
                    'concepto': this.embarque.cargos[cc].concepto_ovp,
                    'montobs': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambio,
                    'montous': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambious,
                    'marcado': false
                });
            }
        }
        this.getSumaInvoice();
    }

    getDireccionesAgenteInvoice(){
        this.erroridagentei=false;
        this.iddireccionagentei=null;
        this.indiceagentei = this.entidades.findIndex(x => x.id === this.idagentei && x.idtipoentidad === 5);
    }

    getSumaInvoice(){
        this.totalinvoicebs=0;
        this.totalinvoiceus=0;
        this.errortotalinvoicebs=false;
        for (let cc = 0; cc < this.conceptosinvoice.length; cc++){
            //console.log(this.conceptosfacturardebitar[cc].marcado);
            if (this.conceptosinvoice[cc].marcado){
                this.totalinvoicebs = this.totalinvoicebs+this.conceptosinvoice[cc].montobs;
                this.totalinvoiceus = this.totalinvoiceus+this.conceptosinvoice[cc].montous;
            }
        }
    }

    reservarInvoice(){
        this._contabilidadService.reservarinvoice(this.token, this.idembarque).subscribe(
            response =>{
                //console.log(response);

                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.embarque.invoices=response.invoices;
                }else{
                    this.toast_tipo="Error";
                }
                $("#ventanaNuevaInvoice").modal('hide');
                $("#liveToast").toast('show');
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    generarInvoice(){
        this.erroridagentei=false;
        if (this.idagentei==null){
            this.erroridagentei=true;
        }
        this.errortotalinvoicebs=false;
        if (this.totalinvoicebs<=0){
            this.errortotalinvoicebs=true;
        }
        if (!this.erroridagentei && !this.errortotalinvoicebs){
            let conceptoscargos=[];
            for(let cc=0; cc<this.conceptosinvoice.length; cc++){
                if(this.conceptosinvoice[cc].marcado){
                    conceptoscargos.push(this.conceptosinvoice[cc].idcargo);
                }
            }
            let datosInvoice = {idinvoice: this.idinvoice_pendiente, idagentecarga: this.idagentei, idagentecargadireccion: this.iddireccionagentei, cargos: conceptoscargos}
            this._contabilidadService.generarinvoice(this.token, this.idembarque, datosInvoice).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.invoices=response.invoices;
                        for (let cc = 0; cc < conceptoscargos.length; cc++){
                            let indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === conceptoscargos[cc]);
                            this.embarque.cargos[indicecargo].idinvoice=response.idinvoice;
                            this.embarque.cargos[indicecargo].tienedocumento=true;
                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevaInvoice").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    downloadInvoice(idinvoice: number){
        this._contabilidadService.downloadInvoice(this.token, idinvoice).subscribe(
            response =>{
                //console.log(response);
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

    downloadInvoiceMembretada(idinvoice:number, event: any){

        this._contabilidadService.downloadInvoiceMembretada(this.token, idinvoice).subscribe(
            response =>{
                //console.log(response);
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

        event.stopPropagation();
    }

    prepararPlanilla(){
        this.pacenainvoice='';
        this.slginvoice='';
        this.alloginvoice='';
        this.textoadicional='';
        this.conceptosplanilla=[];
        for (let cc = 0; cc < this.embarque.cargos.length; cc++){
            if (this.embarque.cargos[cc].idplanilla == 0 && this.embarque.cargos[cc].idcargo>0 && this.embarque.cargos[cc].id_OVP==null){
                this.conceptosplanilla.push({
                    'idcargo': this.embarque.cargos[cc].idcargo,
                    'concepto': this.embarque.cargos[cc].concepto,
                    'montous': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambious,
                    'idtipoplanilla': null,
                    'erroridtipoplanilla': false,
                    'marcado': false
                });
            }
        }
        this.getSumaPlanilla();
    }

    getSumaPlanilla(){
        this.totalplanillaus=0;
        this.errortotalplanillaus=false;
        for (let cc = 0; cc < this.conceptosplanilla.length; cc++){

            if (this.conceptosplanilla[cc].marcado){
                this.totalplanillaus = this.totalplanillaus+this.conceptosplanilla[cc].montous;
            }else{
                this.conceptosplanilla[cc].erroridtipoplanilla=false;
            }
        }
    }

    generarPlanilla(){
        this.errortotalplanillaus=false;
        if (this.totalplanillaus<=0){
            this.errortotalplanillaus=true;
        }
        let erroridtipoplanilla=false;
        for (let cc = 0; cc < this.conceptosplanilla.length; cc++){
            if (this.conceptosplanilla[cc].marcado && this.conceptosplanilla[cc].idtipoplanilla==null){
                this.conceptosplanilla[cc].erroridtipoplanilla=true;
                erroridtipoplanilla=true;
            }
        }


        if (!this.errortotalplanillaus && !erroridtipoplanilla){
            let conceptoscargos=[];
            for(let cc=0; cc<this.conceptosplanilla.length; cc++){
                if(this.conceptosplanilla[cc].marcado){
                    conceptoscargos.push({
                        idcargo: this.conceptosplanilla[cc].idcargo,
                        idtipoplanilla: this.conceptosplanilla[cc].idtipoplanilla
                    });
                }
            }
            let datosPlanilla = {textoadicional: this.textoadicional, pacenainvoice: this.pacenainvoice, slginvoice: this.slginvoice, alloginvoice: this.alloginvoice, cargos: conceptoscargos}
            this._contabilidadService.generarplanilla(this.token, this.idembarque, datosPlanilla).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.planillas=response.planillas;
                        for (let cc = 0; cc < conceptoscargos.length; cc++){
                            let indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === conceptoscargos[cc].idcargo);
                            this.embarque.cargos[indicecargo].idplanilla=response.idplanilla;
                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevaPlanilla").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    downloadPlanilla(idplanilla: number){
        this._contabilidadService.downloadplanilla(this.token, idplanilla).subscribe(
            response =>{
                //console.log(response);
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

    prepararOP(){
        this.fechadocop=this._usuarioService.getCurrentDateFilterValue();
        this.tipoop=1;
        this.idtransportistaop = this.embarque.idtransportista;
        this.iddivisaop=null;
        this.idpagaraop=null;
        this.idpagaradireccionop=null;
        this.idcobraraop=null;
        this.errorfechadocop=false;
        this.erroridpagaraop=false;
        this.erroridcobraraop=false;
        this.erroriddivisaop=false;
        this.observacionesop='';
        this.cambiarTipoOP(1);
        this.getSumaOP();
    }

    cambiarTipoOP(tipo: number){

        this.conceptosordenpago=[];
        for (let cc = 0; cc < this.embarque.costos.length; cc++){
            let agregar=true;
            if(tipo==2){
                if(this.embarque.costos[cc].id_OVPRef.length==0){
                    agregar=false;
                }
            }

            if (agregar && this.embarque.costos[cc].idfacturanotadebito == 0 && this.embarque.costos[cc].idcosto > 0 && !this.embarque.costos[cc].esagente && (this.embarque.costos[cc].idtipodestinatario==2 || this.embarque.costos[cc].idtipodestinatario==3 || this.embarque.costos[cc].idtipodestinatario==4)){
                this.conceptosordenpago.push({
                    'idcosto': this.embarque.costos[cc].idcosto,
                    'concepto': this.embarque.costos[cc].concepto_ovp,
                    'montobs': this.embarque.costos[cc].monto * this.embarque.costos[cc].cantidad * this.embarque.costos[cc].tipocambio,
                    'montous': this.embarque.costos[cc].monto * this.embarque.costos[cc].cantidad * this.embarque.costos[cc].tipocambious,
                    'factura': this.embarque.costos[cc].factura,
                    'nota_entrega': this.embarque.costos[cc].nota_entrega,
                    'identidad': this.embarque.costos[cc].identidad,
                    'marcado': false,
                    'bloqueado': false
                });
            }
        }
        
        this.getSumaOP();
        
    }

    getSumaOP(){
        this.totalopbs=0;
        this.totalopus=0;
        this.errortotalopbs=false;
        this.idpagaraop=null;
        this.idpagaradireccionop=null;
        //let identidadmarcada=null;
        for (let cc = 0; cc < this.conceptosordenpago.length; cc++){
            this.conceptosordenpago[cc].bloqueado=false;
            if (this.conceptosordenpago[cc].marcado){
                this.idpagaraop=this.conceptosordenpago[cc].identidad;
                this.totalopbs = this.totalopbs+this.conceptosordenpago[cc].montobs;
                this.totalopus = this.totalopus+this.conceptosordenpago[cc].montous;
            }
        }
        if(this.idpagaraop!=null){
            //this.idpagaraop=
            this.getDireccionesPagarOP();
            for (let cc = 0; cc < this.conceptosordenpago.length; cc++){
                if(this.conceptosordenpago[cc].identidad!=this.idpagaraop){
                    this.conceptosordenpago[cc].bloqueado=true;
                }
            }
        }

    }

    getDireccionesPagarOP(){
        this.erroridpagaraop=false;
        this.idpagaradireccionop=null;
        this.indiceidpagaraop = this.entidades.findIndex(x => x.identidad === this.idpagaraop);
        if(this.entidades[this.indiceidpagaraop].direcciones){
            this.idpagaradireccionop=this.entidades[this.indiceidpagaraop].direcciones[0].identidaddireccion;
        }
    }

    generarOrdenPago(){
        this.errortotalopbs=false;
        if (this.totalopbs<=0){
            this.errortotalopbs=true;
        }

        this.errorfechadocop=false;
        if (this.fechadocop==''){
            this.errorfechadocop=true;
        }

        this.erroriddivisaop=false;
        if (this.iddivisaop==null){
            this.erroriddivisaop=true;
        }

        this.erroridpagaraop=false;
        if (this.idpagaraop==null){
            this.erroridpagaraop=true;
        }

        this.erroridcobraraop=false;
        if (this.idcobraraop==null){
            this.erroridcobraraop=true;
        }

        if (!this.errortotalopbs && !this.errorfechadocop && !this.erroriddivisaop && !this.erroridpagaraop && !this.erroridcobraraop){
            let conceptoscostos=[];
            for (let cc = 0; cc < this.conceptosordenpago.length; cc++){
                if(this.conceptosordenpago[cc].marcado){
                    conceptoscostos.push(this.conceptosordenpago[cc].idcosto);
                }
            }
            let datosOP = {idtipofacturapago: 1, idtransportista: this.idtransportistaop, idpagara: this.idpagaraop, idpagaradireccion: this.idpagaradireccionop, fechadocumento: this.fechadocop, idcobrara: this.idcobraraop, tipocambio: null, observaciones: this.observacionesop, iddivisa: this.iddivisaop, tipoop: this.tipoop, costos: conceptoscostos}
            this._contabilidadService.generarordenpago(this.token, this.idembarque, datosOP).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.facturaspago=response.facturaspago;
                        for (let cc = 0; cc < conceptoscostos.length; cc++){
                            let indicecosto = this.embarque.costos.findIndex(x => x.idcosto === conceptoscostos[cc]);
                            this.embarque.costos[indicecosto].idfacturanotadebito=response.idfacturapago;
                            this.embarque.costos[indicecosto].tienedocumento=true;
                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevaOrdenPago").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    downloadOrdenPago(idfacturapago: number){
        this._contabilidadService.downloadordenpago(this.token, idfacturapago).subscribe(
            response =>{
                //console.log(response);
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

    prepararPA(){
        this.fechadocpa=this._usuarioService.getCurrentDateFilterValue();
        this.idtransportistapa = this.embarque.idtransportista;
        this.iddivisapa=null;
        this.tipocambiopa=1;
        this.observacionespa='';
        this.idpagarapa=null;
        this.idpagaradireccionpa=null;
        this.idcobrarapa=null;
        this.conceptospagoagente=[];
        for (let cc = 0; cc < this.embarque.costos.length; cc++){
            if (this.embarque.costos[cc].idfacturanotadebito == 0 && this.embarque.costos[cc].idcosto > 0 && this.embarque.costos[cc].esagente){
                this.conceptospagoagente.push({
                    'idcosto': this.embarque.costos[cc].idcosto,
                    'concepto': this.embarque.costos[cc].concepto,
                    'montobs': this.embarque.costos[cc].monto * this.embarque.costos[cc].cantidad * this.embarque.costos[cc].tipocambio,
                    'montous': this.embarque.costos[cc].monto * this.embarque.costos[cc].cantidad * this.embarque.costos[cc].tipocambious,
                    'marcado': false
                });
            }
        }
        this.getSumaPA();
    }

    getSumaPA(){
        this.totalpabs=0;
        this.totalpaus=0;
        this.errortotalpabs=false;
        for (let cc = 0; cc < this.conceptospagoagente.length; cc++){
            if (this.conceptospagoagente[cc].marcado){
                this.totalpabs = this.totalpabs+this.conceptospagoagente[cc].montobs;
                this.totalpaus = this.totalpaus+this.conceptospagoagente[cc].montous;
            }
        }
    }

    getDireccionesPagarPA(){
        this.erroridpagarapa=false;
        this.idpagaradireccionpa=null;
        this.indiceidpagarapa = this.entidades.findIndex(x => x.id === this.idpagarapa && x.idtipoentidad === 5);
    }

    generarPagoAgente(){
        this.errortotalpabs=false;
        if (this.totalpabs<=0){
            this.errortotalpabs=true;
        }

        this.errorfechadocpa=false;
        if (this.fechadocpa==''){
            this.errorfechadocpa=true;
        }

        this.erroriddivisapa=false;
        if (this.iddivisapa==null){
            this.erroriddivisapa=true;
        }

        this.erroridpagarapa=false;
        if (this.idpagarapa==null){
            this.erroridpagarapa=true;
        }

        this.erroridcobrarapa=false;
        if (this.idcobrarapa==null){
            this.erroridcobrarapa=true;
        }

        this.errortipocambiopa=false;
        if (this.tipocambiopa<=0){
            this.errortipocambiopa=true;
        }

        if (!this.errortotalpabs && !this.errorfechadocpa && !this.erroriddivisapa && !this.erroridpagarapa && !this.erroridcobrarapa && !this.errortipocambiopa){
            let conceptoscostos=[];
            for (let cc = 0; cc < this.conceptospagoagente.length; cc++){
                if(this.conceptospagoagente[cc].marcado){
                    conceptoscostos.push(this.conceptospagoagente[cc].idcosto);
                }
            }
            let datosPA = {idtipofacturapago: 2, idtransportista: this.idtransportistapa, idpagara: '5-'+this.idpagarapa, idpagaradireccion: this.idpagaradireccionpa, fechadocumento: this.fechadocpa, idcobrara: this.idcobrarapa, tipocambio: this.tipocambiopa, observaciones: this.observacionespa, iddivisa: this.iddivisapa, tipoop: null, costos: conceptoscostos}
            this._contabilidadService.generarordenpago(this.token, this.idembarque, datosPA).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.facturaspago=response.facturaspago;
                        for (let cc = 0; cc < conceptoscostos.length; cc++){
                            let indicecosto = this.embarque.costos.findIndex(x => x.idcosto === conceptoscostos[cc]);
                            this.embarque.costos[indicecosto].idfacturanotadebito=response.idfacturapago;
                            this.embarque.costos[indicecosto].tienedocumento=true;
                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevoPagoAgente").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    prepararOS(tipoOS: string){
        this.tipoos=tipoOS;
        this.idsolicitadopor=null;
        this.erroridsolicitadopor=false;
        this.iddivisaos=null;
        this.erroriddivisaos=false;
        this.tipocambioos=1;
        this.creditnot='';
        this.conceptosordenservicio=[];
        switch (this.tipoos) {
            case 'i':
                this.tituloordenservicio="Ingreso";
                this.texto_cred_deb="CREDIT";
                for (let cc = 0; cc < this.embarque.cargos.length; cc++){
                    if (this.embarque.cargos[cc].idordenservicioi == 0 && this.embarque.cargos[cc].idfacturanotadebito == 0 && this.embarque.cargos[cc].idcargo > 0 && this.embarque.cargos[cc].esagente && this.embarque.cargos[cc].id_OVP==null && (this.embarque.cargos[cc].iddestinocargo==4)){
                        this.conceptosordenservicio.push({
                            'id': this.embarque.cargos[cc].idcargo,
                            'concepto': this.embarque.cargos[cc].concepto,
                            'montobs': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambio,
                            'montous': this.embarque.cargos[cc].monto * this.embarque.cargos[cc].cantidad * this.embarque.cargos[cc].tipocambious,
                            'marcado': false
                        });
                    }
                }

                break;
            case 'e':
                this.tituloordenservicio="Egreso";
                this.texto_cred_deb="DEBIT";
                for (let cc = 0; cc < this.embarque.costos.length; cc++){
                    if (this.embarque.costos[cc].idordenservicioe == 0 && this.embarque.costos[cc].idcosto > 0 && this.embarque.costos[cc].esagente){
                        this.conceptosordenservicio.push({
                            'id': this.embarque.costos[cc].idcosto,
                            'concepto': this.embarque.costos[cc].concepto,
                            'montobs': this.embarque.costos[cc].monto * this.embarque.costos[cc].cantidad * this.embarque.costos[cc].tipocambio,
                            'montous': this.embarque.costos[cc].monto * this.embarque.costos[cc].cantidad * this.embarque.costos[cc].tipocambious,
                            'marcado': false
                        });
                    }
                }
                break;
        }
        this.getSumaOS();

    }

    getSumaOS(){
        this.totalosbs=0;
        this.totalosus=0;
        this.errortotalosbs=false;
        for (let cc = 0; cc < this.conceptosordenservicio.length; cc++){
            if (this.conceptosordenservicio[cc].marcado){
                this.totalosbs = this.totalosbs+this.conceptosordenservicio[cc].montobs;
                this.totalosus = this.totalosus+this.conceptosordenservicio[cc].montous;
            }
        }
    }

    generarOrdenServicio(){
        this.erroridsolicitadopor=false;
        if (this.idsolicitadopor==null){
            this.erroridsolicitadopor=true;
        }

        this.erroriddivisaos=false;
        if (this.iddivisaos==null){
            this.erroriddivisaos=true;
        }

        this.errortipocambioos=false;
        if (this.tipocambioos<=0){
            this.errortipocambioos=true;
        }

        this.errortotalosbs=false;
        if (this.totalosbs<=0){
            this.errortotalosbs=true;
        }

        if (!this.erroridsolicitadopor && !this.erroriddivisaos && !this.errortipocambioos && !this.errortotalosbs){
            let conceptos=[];
            for (let cc = 0; cc < this.conceptosordenservicio.length; cc++){
                if(this.conceptosordenservicio[cc].marcado){
                    conceptos.push(this.conceptosordenservicio[cc].id);
                }
            }
            let datosOS = {idsolicitadopor: this.idsolicitadopor, iddivisaordenservicio: this.iddivisaos, tipocambio: this.tipocambioos, creditnot: this.creditnot, tipoos: this.tipoos, idusuario: this.tokenDetalle.idusuario, conceptos: conceptos}
            this._contabilidadService.generarordenservicio(this.token, this.idembarque, datosOS).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        this.embarque.ordenserviciosi=response.ordenserviciosi;
                        this.embarque.ordenserviciose=response.ordenserviciose;
                        for (let cc = 0; cc < conceptos.length; cc++){
                            switch (this.tipoos) {
                                case 'i':
                                    let indicecargo = this.embarque.cargos.findIndex(x => x.idcargo === conceptos[cc]);
                                    this.embarque.cargos[indicecargo].idordenservicioi=response.idordenservicio;
                                    this.embarque.cargos[indicecargo].tienedocumento=true;
                                break;
                                case 'e':
                                    let indicecosto = this.embarque.costos.findIndex(x => x.idcosto === conceptos[cc]);
                                    this.embarque.costos[indicecosto].idordenservicioe=response.idordenservicio;
                                    this.embarque.costos[indicecosto].tienedocumento=true;
                                break;
                            }

                        }
                    }else{
                        this.toast_tipo="Error";
                    }
                    $("#ventanaNuevaOrdenServicio").modal('hide');
                    $("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
    }

    downloadOrdenServicio(idordenservicio: number, tipo: string){
        this._contabilidadService.downloadOrdenServicio(this.token, idordenservicio, tipo).subscribe(
            response =>{
                //console.log(response);
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

    getSumContabilidad(tabla: string, esagente: boolean, tienedocumento: boolean, idfacturanotadebito: boolean, idestadofacturanotadebito: number, tipocambio: string) : number {
        let sum = 0;
        for (let i = 0; i < this.embarque[tabla].length; i++) {
            if ((esagente == null || this.embarque[tabla][i].esagente == esagente) && (tienedocumento == null || this.embarque[tabla][i].tienedocumento == tienedocumento) && (idfacturanotadebito == null || (idfacturanotadebito && this.embarque[tabla][i].idfacturanotadebito > 0)) && (idestadofacturanotadebito == null || this.embarque[tabla][i].idestadofacturanotadebito == idestadofacturanotadebito)){
                sum += this.embarque[tabla][i].monto * this.embarque[tabla][i][tipocambio] * this.embarque[tabla][i].cantidad;
            }
        }
        return sum;
    }

    saveGeneral(){
        let error=false;
        if (this.imp_exp_validar.includes(this.embarque.importacion_exportacion)){
            if (!this.embarque.numeroguia){
                error=true;
                this.error_numeroguia=true;
            }
            if (!this.embarque.noidentificacion){
                //error=true;
                //this.error_noidentificacion=true;
            }
            if (!this.embarque.descripcioncarga){
                error=true;
                this.error_descripcioncarga=true;
            }
            if (!this.embarque.peso){
                error=true;
                this.error_peso=true;
            }
            if (!this.embarque.piezas){
                error=true;
                this.error_piezas=true;
            }
            if (!this.embarque.idtipobulto){
                error=true;
                this.error_idtipobulto=true;
            }
            if (!this.embarque.idincoterms){
                //error=true;
                //this.error_idincoterms=true;
            }
        }
            
        if(!error){
            this._embarqueService.savegeneral(this.token, this.idembarque, this.embarque).subscribe(
                response =>{
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
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

    saveEntidades(){
        let error=false;
        
        if (this.imp_exp_validar.includes(this.embarque.importacion_exportacion)){
            if (!this.embarque.idexpedidor || this.embarque.idexpedidor=='0-0'){
                this.error_idexpedidor=true;
                error=true;
            }
            if (!this.embarque.idexpedidordireccion){
                this.error_idexpedidordireccion=true;
                error=true;
            }
            if (!this.embarque.idultimoconsignatario || this.embarque.idultimoconsignatario=='0-0'){
                this.error_idultimoconsignatario=true;
                error=true;
            }
            if (!this.embarque.idultimoconsignatariodireccion){
                this.error_idultimoconsignatariodireccion=true;
                error=true;
            }
        }
        
            
        if(!error){
            this._embarqueService.saveentidades(this.token, this.idembarque, this.embarque).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
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

    saveRuta(){
        let error=false;
        
        this.error_idmediotransporte=false;
        this.error_idtipocarga=false;
        this.error_idtransportista=false;
        this.error_numerovehiculo=false;
        this.error_idsalida=false;
        this.error_fechasalida=false;
        this.error_idarribo=false;
        this.error_fechaarribo=false;
        this.error_idhorario=false;
        this.error_idtemperatura=false;
        this.error_numero_precinto=false;
        this.error_estibadoresSLG=false;
        this.error_estibadores=false;
        this.error_costo_operador_transporte=false;
        
        let verificar_obligatorio=false;
        
        if (this.embarque.importacion_exportacion == 2 || (this.imp_exp_validar.includes(this.embarque.importacion_exportacion) && ((this.embarque.costos.some(costos => this.costos_validar.includes(costos.idconcepto))) || (this.embarque.cargos.some(cargos => this.cargos_validar.includes(cargos.idconcepto)))))){
            verificar_obligatorio=true;
        }
        
        if (verificar_obligatorio){
            if (!this.embarque.idmediotransporte){
                this.error_idmediotransporte=true;
                error=true;
            }
            if (!this.embarque.idtipocarga){
                this.error_idtipocarga=true;
                error=true;
            }
            if (!this.embarque.idtransportista){
                this.error_idtransportista=true;
                error=true;
            }
            if (!this.embarque.numerovehiculo){
                this.error_numerovehiculo=true;
                error=true;
            }
            if (!this.embarque.idsalida){
                this.error_idsalida=true;
                error=true;
            }
            if (!this.embarque.fechasalida){
                this.error_fechasalida=true;
                error=true;
            }
            if (!this.embarque.idarribo){
                this.error_idarribo=true;
                error=true;
            }
            if (!this.embarque.fechaarribo){
                this.error_fechaarribo=true;
                error=true;
            }
            if (!this.embarque.idhorario){
                this.error_idhorario=true;
                error=true;
            }
            if (!this.embarque.idtemperatura){
                this.error_idtemperatura=true;
                error=true;
            }
            
            if (!this.embarque.carpetapacena){
                if (!this.embarque.numero_precinto){
                    this.error_numero_precinto=true;
                    error=true;
                }
                if (!this.embarque.estibadoresSLG){
                    this.error_estibadoresSLG=true;
                    error=true;
                }
                if (!this.embarque.estibadores){
                    this.error_estibadores=true;
                    error=true;
                }
                if (!this.embarque.costo_operador_transporte){
                    this.error_costo_operador_transporte=true;
                    error=true;
                }
            }
            
            
            
        }
        
        if(!error){
            this._embarqueService.saveruta(this.token, this.idembarque, this.embarque).subscribe(
                response =>{
                    //console.log(response);

                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
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

    saveCargos(){
        this._embarqueService.savecargos(this.token, this.idembarque, this.embarque.cargos).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                this.getEmbarqueData();
                /*
                for(let ncg=0; ncg<response.nuevoscargos.length; ncg++){
                    let llaveactual = this.embarque.cargos.findIndex(x => x.idcargo === response.nuevoscargos[ncg].idcargo_viejo);
                    this.embarque.cargos[llaveactual].idcargo=response.nuevoscargos[ncg].idcargo_nuevo;
                    this.embarque.cargos[llaveactual].tipocambio=response.nuevoscargos[ncg].tipocambio;
                    this.embarque.cargos[llaveactual].tipocambious=response.nuevoscargos[ncg].tipocambious;
                }
                */
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    saveCostos(){
        this._embarqueService.savecostos(this.token, this.idembarque, this.embarque.costos).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                this.getEmbarqueData();
                /*
                for(let ncs=0; ncs<response.nuevoscostos.length; ncs++){
                    let llaveactual = this.embarque.costos.findIndex(x => x.idcosto === response.nuevoscostos[ncs].idcosto_viejo);
                    this.embarque.costos[llaveactual].idcosto=response.nuevoscostos[ncs].idcosto_nuevo;
                    this.embarque.costos[llaveactual].tipocambio=response.nuevoscostos[ncs].tipocambio;
                    this.embarque.costos[llaveactual].tipocambious=response.nuevoscostos[ncs].tipocambious;
                }
                */
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    saveCargosCostosAgente(){
        let cargoscostos;
        cargoscostos={cargos: this.embarque.cargos, costos: this.embarque.costos};

        this._embarqueService.savecargoscostosagente(this.token, this.idembarque, cargoscostos).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                }else{
                    this.toast_tipo="Error";
                }
                this.getEmbarqueData();
                /*
                for(let ncg=0; ncg<response.nuevoscargos.length; ncg++){
                    let llaveactual = this.embarque.cargos.findIndex(x => x.idcargo === response.nuevoscargos[ncg].idcargo_viejo);
                    this.embarque.cargos[llaveactual].idcargo=response.nuevoscargos[ncg].idcargo_nuevo;
                    this.embarque.cargos[llaveactual].tipocambio=response.nuevoscargos[ncg].tipocambio;
                    this.embarque.cargos[llaveactual].tipocambious=response.nuevoscargos[ncg].tipocambious;
                }
                for(let ncs=0; ncs<response.nuevoscostos.length; ncs++){
                    let llaveactual = this.embarque.costos.findIndex(x => x.idcosto === response.nuevoscostos[ncs].idcosto_viejo);
                    this.embarque.costos[llaveactual].idcosto=response.nuevoscostos[ncs].idcosto_nuevo;
                    this.embarque.costos[llaveactual].tipocambio=response.nuevoscostos[ncs].tipocambio;
                    this.embarque.costos[llaveactual].tipocambious=response.nuevoscostos[ncs].tipocambious;
                }
                */
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    ValidateEmail(inputText: string){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(inputText.match(mailformat)){
            return true;
        }else{
            return false;
        }
    }

    saveEventos(){
        this.errorcorreos=false;
        this.correosconerror=[];
        for (let cc = 0; cc < this.embarque.correosembarque.length; cc++){
            if (!this.ValidateEmail(this.embarque.correosembarque[cc].correo)){
                this.errorcorreos=true;
                this.correosconerror.push(cc);
            }
        }

        if(!this.errorcorreos){
            let eventoscorreos;
            eventoscorreos = {eventos: this.embarque.eventos, correos: this.embarque.correosembarque};
            //console.log(eventoscorreos);
            this._embarqueService.saveeventos(this.token, this.idembarque, eventoscorreos).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                    }else{
                        this.toast_tipo="Error";
                    }

                    for(let ncg=0; ncg<response.nuevoseventos.length; ncg++){
                        let llaveactual = this.embarque.eventos.findIndex(x => x.idevento === response.nuevoseventos[ncg].idevento_viejo);
                        this.embarque.eventos[llaveactual].idevento=response.nuevoseventos[ncg].idevento_nuevo;
                    }
                    for(let ncs=0; ncs<response.nuevoscorreos.length; ncs++){
                        let llaveactual = this.embarque.correosembarque.findIndex(x => x.idcorreosembarque === response.nuevoscorreos[ncs].idcorreosembarque_viejo);
                        this.embarque.correosembarque[llaveactual].idcorreosembarque=response.nuevoscorreos[ncs].idcorreosembarque_nuevo;
                    }

                    $("#liveToast").toast('show');

                },
                error=>{
                    console.log(<any>error)
                }
            );
        }


    }

    enviarMailEventos(){
        this.errorenviarmaileventoscorreos=false;
        let cantidadcorreos=0;
        for (let cc = 0; cc < this.embarque.correosembarque.length; cc++){
            if (this.embarque.correosembarque[cc].idcorreosembarque>0){
                cantidadcorreos++;
            }
        }
        if (cantidadcorreos==0){
            this.errorenviarmaileventoscorreos=true;
        }
        //console.log(cantidadcorreos);

        this.errorenviarmaileventos=false;
        let cantidadeventos=0;
        for (let cc = 0; cc < this.embarque.eventos.length; cc++){
            if (this.embarque.eventos[cc].idevento > 0 && !this.embarque.eventos[cc].enviado){
                cantidadeventos++;
            }
        }
        if (cantidadeventos==0){
            this.errorenviarmaileventos=true;
        }

        if (!this.errorenviarmaileventos && !this.errorenviarmaileventoscorreos){
            this._embarqueService.correoeventos(this.token, this.idembarque, []).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.embarque.eventos.forEach(
                            evento => (evento.enviado = true)
                        );
                        this.toast_tipo="Exito";
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

    generarCaratula(){
        this._embarqueService.downloadCaratula(this.token, this.idembarque).subscribe(
            response =>{
                //console.log(response);
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

    finalizarEmbarque(){
        this._embarqueService.finalizarembarque(this.token, this.idembarque).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.embarque.finalizado=true;
                    this.embarque.fechafinalizacion = this.fechaactual;
                }else{
                    this.toast_tipo="Error";
                }

                $('#confirmarFanilizar').modal('hide');
                $("#liveToast").toast('show');

            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    duplicarEmbarque(){
        this._embarqueService.duplicarembarque(this.token, this.idembarque).subscribe(
            response =>{
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    this.abrirDetalleNuevo(response.idembarque);
                    
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
    
    abrirDetalle(idembarque:number){
        this._router.navigate(['/embarques-detalle',idembarque])
        //alert("abre en la misma pagina " + idembarque);
    }
    
    abrirDetalleNuevo(idembarque:number){
        let newRelativeUrl = this._router.createUrlTree(["/embarques-detalle",idembarque]);
        let baseUrl = window.location.href.replace(this._router.url, '');
        window.open(baseUrl + newRelativeUrl, '_blank');
    }

    downloadDocumentoFin(){
        this._embarqueService.downloadDocCierre(this.token, this.idembarque).subscribe(
            response =>{
                //console.log(response);
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

    trackByFn(index: number, item: any) {
        return item.id;
    }


    guardarCambios(){
        //$('#ventanaLoading').modal('show');

        //$('#ventanaLoading').modal('hide');
        //this.mensajeexito='Datos guardados correctamente';
        this.toast_mensaje="Datos guardados correctamente"
        this.toast_tipo="Exito";
        $("#liveToast").toast('show');
    }

}
