import { Component, OnInit, ViewChild, ElementRef  } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {GLOBAL} from './../global';
declare var $: any;

@Component({
    selector: 'app-productos-cliente',
    templateUrl: './productos-cliente.component.html',
    styleUrls: ['./productos-cliente.component.css'],
    providers:[UsuarioService,DatoMaestroService]
})
export class ProductosClienteComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;

    public productos_cliente: Array<any>;
    public entidades: Array<any>;
    public embalajes: Array<any>;
    public timbrados_turno: Array<any>;

    public textomodal: string;
    public idbaseproductos: number;
    public idcliente: number;
    public erroridcliente: boolean;
    public rubro: string;
    public codigo: string;
    public errorcodigo: boolean;
    public textoerrorcodigo: string;
    public serie: string;
    public descripcion: string;
    public categoria: string;
    public idembalaje: number;
    public codigoembalaje: string;
    public erroridembalaje: boolean;
    public umcompra: string;
    public umalterna: string;
    public alto: number;
    public ancho: number;
    public largo: number;
    public volumen: number;
    public centro_distribucion: string;
    public color: string;
    public idembalaje_salida: number;
    public codigoembalaje_salida: string;
    public factor_conversion: number;
    public meta_timbrado: number;
    public preciotimbrado: Array<any>

    public colores: Array<any>;


    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_productos_cliente: boolean=false;
    public editar_productos_cliente: boolean=false;
    
    public urlFormatoProductos: string;
    
    @ViewChild('UploadFileInput') myInputVariable: ElementRef;
    public errorarchivo: boolean;
    public archivosCargados: Array<File> = [];
    public archivocargado: boolean=false;
    public mensajes_error: Array<any>;
    public carga_archivo: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService
        ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_productos_cliente=true;
            this.editar_productos_cliente=true;
        }else{
            let indiceVerproductosCliente = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 9);
            if (indiceVerproductosCliente>=0){
                if (this.tokenDetalle.permisos[indiceVerproductosCliente].lectura){
                    this.ver_productos_cliente=true;
                }
                if (this.tokenDetalle.permisos[indiceVerproductosCliente].escritura){
                    this.editar_productos_cliente=true;
                }
            }
        }
        
        this.urlFormatoProductos=GLOBAL.urlFiles+'FormatoProductosCliente.xlsx';
        this.mensajes_error=[];
    }

    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{
                this.entidades = response.entidades.filter(function(cc){
                    return (cc.idtipoentidad==1)
                });
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this._datomaestroService.embalajes(this.token).subscribe(
            response =>{
                this.embalajes=response.embalajes;
                //console.log(this.embalajes);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.timbrados_turno(this.token).subscribe(
            response =>{
                this.timbrados_turno=response.timbrados_turno;
                console.log(this.timbrados_turno);
            },
            error=>{
                console.log(<any>error)
            }
        );

        this.cargarProductos();
    }

    cargarProductos(){
        this._datomaestroService.productoscliente(this.token).subscribe(
            response =>{
                this.productos_cliente=response.productos_cliente;
                console.log(this.productos_cliente);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }

    verDetalle(idbaseproductos: number){
        //console.log('editar '+idbaseproductos);
        this.idbaseproductos=idbaseproductos;
        this.erroridcliente=false;
        this.errorcodigo=false;
        this.erroridembalaje=false;
        if (idbaseproductos==0){
            this.textomodal='Nuevo Producto';
            this.idcliente=null;
            this.rubro='';
            this.codigo='';
            this.serie='';
            this.categoria='';
            this.descripcion='';
            this.idembalaje=null;
            this.codigoembalaje='';
            this.umcompra='';
            this.umalterna='';
            this.alto=0;
            this.ancho=0;
            this.largo=0;
            this.centro_distribucion='';
            this.color=null;
            this.idembalaje_salida=null;
            this.codigoembalaje_salida='';
            this.factor_conversion=1;
            this.meta_timbrado=null;
            this.preciotimbrado=[];
        }else{
            this.textomodal='Editar Producto';
            let indiceProducto = this.productos_cliente.findIndex(x => (x.idbaseproductos === idbaseproductos));
            this.idcliente = this.productos_cliente[indiceProducto].idcliente;
            this.rubro = this.productos_cliente[indiceProducto].rubro;
            this.codigo = this.productos_cliente[indiceProducto].codigo;
            this.serie = this.productos_cliente[indiceProducto].serie;
            this.descripcion = this.productos_cliente[indiceProducto].descripcion;
            this.categoria = this.productos_cliente[indiceProducto].categoria;
            this.idembalaje = this.productos_cliente[indiceProducto].idembalaje;
            this.codigoembalaje = this.productos_cliente[indiceProducto].codigoembalaje;
            this.umcompra = this.productos_cliente[indiceProducto].umcompra;
            this.umalterna = this.productos_cliente[indiceProducto].umalterna;
            this.alto = this.productos_cliente[indiceProducto].alto;
            this.ancho = this.productos_cliente[indiceProducto].ancho;
            this.largo = this.productos_cliente[indiceProducto].largo;
            this.centro_distribucion = this.productos_cliente[indiceProducto].centro_distribucion;
            this.color = this.productos_cliente[indiceProducto].color;
            this.idembalaje_salida = this.productos_cliente[indiceProducto].idembalaje_salida;
            this.codigoembalaje_salida = this.productos_cliente[indiceProducto].codigoembalaje_salida;
            this.factor_conversion = this.productos_cliente[indiceProducto].factor_conversion;
            this.meta_timbrado = this.productos_cliente[indiceProducto].meta_timbrado;
            this.preciotimbrado = JSON.parse(JSON.stringify(this.productos_cliente[indiceProducto].preciotimbrado));
        }

        this.prepararColores();
    }

    prepararColores(){
        this.colores=[];

        this.colores.push({
            'color': 'Sin Color',
            'valor': null,
            'fondo': '#ffffff'
        });

        let idclienteselect=this.idcliente;
        let productos = this.productos_cliente.filter(function (el) {
            return el.idcliente==idclienteselect;
        });

        let colorescliente = productos.map(item => item.color);

        let coloresclienteunique=this.array_unique(colorescliente);

        for(let cc=0; cc<coloresclienteunique.length; cc++){
            this.colores.push({
                'color': coloresclienteunique[cc],
                'valor': coloresclienteunique[cc],
                'fondo': coloresclienteunique[cc]
            });
        }
        //console.log(this.colores);





    }
    
    cambioEmbalaje(){
        this.erroridembalaje=false;
        this.codigoembalaje='';
        let indiceEmbalaje = this.embalajes.findIndex(x => (x.idembalaje == this.idembalaje));
        if(indiceEmbalaje>=0){
            this.codigoembalaje = this.embalajes[indiceEmbalaje].codigoembalaje;
        }
        
    }
    
    cambioEmbalajeSalida(){
        this.codigoembalaje_salida='';
        let indiceEmbalaje = this.embalajes.findIndex(x => (x.idembalaje == this.idembalaje_salida));
        if(indiceEmbalaje>=0){
            this.codigoembalaje_salida = this.embalajes[indiceEmbalaje].codigoembalaje;
        }
        
    }
    
    trackByFn(index: number, item: any) {
        return index;
    }
    
    eliminarPrecioTimbrado(indice){
        this.preciotimbrado.splice(indice, 1);
    }
    
    agregarPrecioTimbrado(){
        this.preciotimbrado.push({
            idpreciotimbradoproducto: 0,
            idtimbradoturno: null,
            precio: 0
        });
    }

    array_unique(arr) {
        return [...new Set(arr)];
    }

    prepararEliminar(idbaseproductos: number){
        this.idbaseproductos=idbaseproductos;
    }

    eliminarDatos(){
        this._datomaestroService.eliminarproductoscliente(this.token, this.idbaseproductos).subscribe(
            response =>{
                //console.log(response);
                this.toast_mensaje=response.mensaje;
                if(response.codigo==200){
                    this.toast_tipo="Exito";
                    $("#confirmarEliminar").modal('hide');
                    this.cargarProductos();
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

    guardarDatos(){
        let error=false;
        this.erroridcliente=false;
        if (this.idcliente==null){
            this.erroridcliente=true;
            error=true;
        }
        this.errorcodigo=false;
        if (this.codigo==''){
            this.errorcodigo=true;
            this.textoerrorcodigo='Campo requerido';
            error=true;
        }else{
            let indiceCodigo = this.productos_cliente.findIndex(x => (x.codigo === this.codigo && x.idcliente === this.idcliente && x.idbaseproductos !== this.idbaseproductos));
            if(indiceCodigo>=0){
                this.errorcodigo=true;
                this.textoerrorcodigo='Ya existe en la base de datos';
                error=true;
            }
        }
        this.erroridembalaje=false;
        if (this.idembalaje==null){
            this.erroridembalaje=true;
            error=true;
        }
        
        for (let pt = 0; pt < this.preciotimbrado.length; pt++){
            if (this.preciotimbrado[pt].idtimbradoturno==null){
                this.preciotimbrado[pt].erroridtimbradoturno=true;
                error=true;
            }
        }

        if (!error){
            let datosguardar;
            datosguardar={
                idcliente: this.idcliente,
                rubro: this.rubro,
                codigo: this.codigo,
                serie: this.serie,
                descripcion: this.descripcion,
                categoria: this.categoria,
                idembalaje: this.idembalaje,
                umcompra: this.umcompra,
                umalterna: this.umalterna,
                alto: this.alto,
                ancho: this.ancho,
                largo: this.largo,
                centro_distribucion: this.centro_distribucion,
                color: this.color,
                idembalaje_salida: this.idembalaje_salida,
                factor_conversion: this.factor_conversion,
                meta_timbrado: this.meta_timbrado,
                preciotimbrado: this.preciotimbrado
            };
            if(this.idbaseproductos==0){
                this._datomaestroService.addproductoscliente(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#nuevoProducto").modal('hide');
                            this.cargarProductos();
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
                this._datomaestroService.saveproductoscliente(this.token, this.idbaseproductos, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#nuevoProducto").modal('hide');
                            this.cargarProductos();
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
    
    prepararCargaMasiva(){
        this.idcliente=null;
        this.erroridcliente=false;
        this.mensajes_error=[];
        this.resetearInput();
    }
    
    fileChangeEvent(fileInput: any) {
        this.errorarchivo=false;
        if (fileInput.target.files && fileInput.target.files.length > 0) {
            this.archivosCargados = Array.from(fileInput.target.files);
            this.archivocargado = true;
        } else {
            this.archivocargado = false;
        }
    }
    
    cargarDatos(){
        this.mensajes_error=[];
        let error=false;
        this.erroridcliente=false;
        if (this.idcliente==null){
            this.erroridcliente=true;
            error=true;
        }
        if(!error){
            //console.log(this.idcliente);
            this.carga_archivo=true;
            this._datomaestroService.addproductosclientemasivo(this.token, this.idcliente, this.archivosCargados).subscribe(
                response =>{
                    console.log(response);
                    this.mensajes_error=response.mensajes_error;
                    this.toast_mensaje=response.mensaje;
                    this.carga_archivo=false;
                    this.resetearInput();
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        $("#nuevoProductoMasivo").modal('hide');
                        this.cargarProductos();
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
    
    resetearInput(): void {
    if (this.myInputVariable) {
        this.myInputVariable.nativeElement.value = '';  // Limpia el input file
        this.archivosCargados = [];                     // Limpia el array local
        this.archivocargado = false;
    }
}




}
