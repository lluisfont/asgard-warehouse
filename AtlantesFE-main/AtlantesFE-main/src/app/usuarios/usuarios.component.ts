import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';
import { TreeNode } from 'primeng/api';
import { Tab } from 'bootstrap';
declare var $: any; 

@Component({
    selector: 'app-usuarios',
    templateUrl: './usuarios.component.html',
    styleUrls: ['./usuarios.component.css'],
    providers:[UsuarioService,DatoMaestroService,AlmacenesService]
})
export class UsuariosComponent implements OnInit {
    public token:string;
    public tokenDetalle: any;
    
    public usuarios: Array<any>;
    public tiposusuario: Array<any>;
    public empresas: Array<any>;
    public ciudades: Array<any>;
    public almacenes: Array<any>;
    public usuario_almacenes: Array<any>;
    //public permisos: Array<any>;
    
    permisos!: TreeNode[];
    
    public idusuario: number=0;
    public nombre: string='';
    public errornombre: boolean=false;
    public idtipousuario: number=null;
    public erroridtipousuario: boolean=false;
    public email: string='';
    public erroremail: boolean=false;
    public mensajeerroremail: string='';
    public ci: string='';
    public telefono: string='';
    public idempresa: number=null;
    public erroridempresa: boolean=false;
    public idciudad: number=null;
    public erroridciudad: boolean=false;
    public idalmacen: number=null;
    public erroridalmacen: boolean=false;
    public username: string='';
    public errorusername: boolean=false;
    public mensajeerrorusername: string='';
    public contrasena: string='';
    public errorcontrasena: boolean= false;
    public mensajeerrorcontrasena: string='';
    public contrasena_2: string='';
    public errorcontrasena_2: boolean= false;
    public mensajeerrorcontrasena_2: string='';
    public activo: boolean=true;
    public permisos_usuario: Array<any>=[];
    
    public mostrarAlmacenes: boolean=false;
    public marcar_todos: boolean=false;
    
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_usuarios: boolean=false;
    public editar_usuarios: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_usuarios=true;
            this.editar_usuarios=true;
        }else{
            let indiceVerUsuarios= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 10);
            if (indiceVerUsuarios>=0){
                if (this.tokenDetalle.permisos[indiceVerUsuarios].lectura){
                    this.ver_usuarios=true;
                }
                if (this.tokenDetalle.permisos[indiceVerUsuarios].escritura){
                    this.editar_usuarios=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarUsuarios();
        this._usuarioService.tiposusuario(this.token).subscribe(
            response =>{
                this.tiposusuario=response.tiposusuario;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this._datomaestroService.empresas(this.token).subscribe(
            response =>{
                this.empresas=response.empresas;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.ciudades=[];
        this._datomaestroService.ciudades(this.token).subscribe(
            response_ciudades =>{
                this.ciudades=response_ciudades.ciudades;
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.permisos=[];
        this._datomaestroService.listado_permisos(this.token).subscribe(
            response_listado =>{
                this.permisos=response_listado.permisos;
                this.permisos = [...this.permisos];
                console.log(this.permisos);
            },
            error=>{
                console.log(<any>error)
            }
        );
        
        this.almacenes=[];
        this._almacenesService.veralmacenes(this.token).subscribe(
            response =>{
                this.almacenes=response.almacenes;
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    procesarPermisos(nodes: TreeNode[]){
        
    }
    
    cargarUsuarios(){
        this._usuarioService.usuarios(this.token).subscribe(
            response =>{
                this.usuarios=response.usuarios;
                console.log(this.usuarios);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idusuario: number){
        $('#general-tab').tab('show');
        this.idusuario=idusuario;
        this.mostrarAlmacenes=false;
        this.usuario_almacenes=[];
        if(idusuario==0){
            this.cabecera_modal="Nuevo";
            
            this.nombre='';
            this.errornombre=false;
            this.idtipousuario=null;
            this.erroridtipousuario=false;
            this.email='';
            this.erroremail=false;
            this.ci='';
            this.telefono='';
            this.idempresa=this.tokenDetalle.idempresa;
            this.erroridempresa=false;
            this.idciudad=null;
            this.erroridciudad=false;
            this.idalmacen=null;
            this.erroridalmacen=false;
            this.username='';
            this.errorusername=false;
            this.mensajeerrorusername='';
            this.contrasena='';
            this.errorcontrasena= false;
            this.mensajeerrorcontrasena='';
            this.contrasena_2='';
            this.errorcontrasena_2= false;
            this.mensajeerrorcontrasena_2='';
            this.activo=true;
            this.permisos_usuario=[];
            for (let aa = 0; aa < this.almacenes.length; aa++){
                this.usuario_almacenes.push({
                    idalmacen: this.almacenes[aa].idalmacen,
                    almacen: this.almacenes[aa].almacen,
                    almacen_marcado: false
                });
            }
            this.verificarPermisos(this.permisos, false, idusuario);
        }else{
            this.cabecera_modal="Editar";
            
            this._usuarioService.verusuario(this.token, this.idusuario).subscribe(
                response =>{
                    console.log(response);
                    
                    this.nombre = response.usuario.nombre;
                    this.errornombre=false;
                    this.idtipousuario=response.usuario.idtipousuario;
                    this.erroridtipousuario=false;
                    this.email=response.usuario.email;
                    this.erroremail=false;
                    this.ci=response.usuario.ci;
                    this.telefono=response.usuario.telefono;
                    this.idempresa=response.usuario.idempresa;
                    this.erroridempresa=false;
                    this.idciudad=response.usuario.idciudad;
                    this.erroridciudad=false;
                    this.idalmacen=response.usuario.idalmacen;
                    this.erroridalmacen=false;
                    this.username=response.usuario.username;
                    this.errorusername=false;
                    this.mensajeerrorusername='';
                    this.activo=response.usuario.activo;
                    this.permisos_usuario=response.usuario.permisos;
                    this.usuario_almacenes=response.usuario.almacenes;
                    console.log(this.permisos_usuario);
                    this.verificarPermisos(this.permisos, true, idusuario);
                    this.verificarCambioAlmacen();
                },
                error=>{
                    console.log(<any>error)
                }
            );
            
            

            
                    
        }
        
        
    }
    
    verificarPermisos(nodes: TreeNode[], editar: boolean, idusuario: number){
        let indicePermisos=null;
        //let indiceUsuario = this.usuarios.findIndex(x => x.idusuario == idusuario);
        for (const node of nodes) {
            node.data.lectura=false;
            node.data.escritura=false;
            if(editar){
                
                indicePermisos = this.permisos_usuario.findIndex(x => x.idmodulo == node.data.id);
                if(indicePermisos>=0){
                    node.data.lectura = this.permisos_usuario[indicePermisos].lectura;
                    if(node.data.id==24){
                        this.mostrarAlmacenes=node.data.lectura;
                    }
                    node.data.escritura = this.permisos_usuario[indicePermisos].escritura;
                }
                
            }
            
            
            //console.log(`ID: ${node.id}, Modulo: ${node.modulo}`);

            // Realiza aquí cualquier procesamiento adicional para el nodo actual

            // Si el nodo tiene hijos, procesarlos recursivamente
            if (node.children && node.children.length > 0) {
                this.verificarPermisos(node.children, editar, idusuario);
            }
        }
    }
    
    marcaPermiso(node: TreeNode, tipo: 'lectura' | 'escritura', estado: boolean): void {
        //console.log(node);
        
        // 1. Si se marca "lectura", permite marcar "escritura"
        if (tipo === 'lectura' && !estado) {
            // Si se desmarca lectura, desmarca también escritura
            node.data.escritura = false;
        } else if (tipo === 'escritura' && estado && !node.data.lectura) {
            // Si intento marcar escritura sin que lectura esté marcada, no hace nada
            return;
        }
        // Actualiza el estado del checkbox actual
        node.data[tipo] = estado;
        
        if(node.data.id==24 && tipo === 'lectura'){
            this.mostrarAlmacenes=estado;
        }

        // 2. Actualizar todos los hijos si se marca/desmarca el nodo actual
        if (node.children && node.children.length > 0) {
            for (const child of node.children) {
                this.marcaPermiso(child, tipo, estado); // Llama recursivamente para los hijos
                if (tipo === 'lectura' && !estado) {
                    this.marcaPermiso(child, 'escritura', false); // Desmarcar escritura también si lectura es desmarcada
                }
            }
        }

        // 3. Verificar el estado de los padres si es necesario
        if (estado) {
            this.marcaParent(node, tipo); // Marca los padres si todos los hijos están marcados
        } else {
            this.desmarcaParent(node, tipo); // Desmarca los padres si algún hijo es desmarcado
        }
        
    }
    
    marcaParent(node: TreeNode, tipo: 'lectura' | 'escritura'): void {
        if (!node.parent) return; // Si no tiene padre, no hace nada

        const parent = node.parent;

        // Verifica si todos los hijos están marcados
        const allChildrenChecked = parent.children?.every((child) => child.data[tipo]) ?? false;

        if (allChildrenChecked) {
          parent.data[tipo] = true; // Marca el padre
          this.marcaParent(parent, tipo); // Llama recursivamente para los padres
        }
    }
    
    desmarcaParent(node: TreeNode, tipo: 'lectura' | 'escritura'): void {
        if (!node.parent) return; // Si no tiene padre, no hace nada

        const parent = node.parent;
        parent.data[tipo] = false; // Desmarca el padre
        if (tipo === 'lectura') {
            parent.data.escritura = false;
        }
        this.desmarcaParent(parent, tipo); // Llama recursivamente para los padres
    }
    
    verificarCambioAlmacen(){
        let indiceAlmacen = this.usuario_almacenes.findIndex(x => x.idalmacen == this.idalmacen);
        
        if(indiceAlmacen>=0){
            this.usuario_almacenes[indiceAlmacen].almacen_marcado=true;
        }
        
        
    }
    
    
    /*
    marcaPermiso(id: number, tipo: number){
        let indicePermisos = this.permisos.findIndex(x => x.data.id == id);
        let elemento=null;
        let verificar_padre=null;
        if (indicePermisos >= 0){
            elemento=this.permisos[indicePermisos];
        }else{
            for (let pp = 0; pp < this.permisos.length; pp++){
                if("children" in this.permisos[pp]){
                    indicePermisos = this.permisos[pp].children.findIndex(x => x.data.id == id);
                    if(indicePermisos>=0){
                        elemento=this.permisos[pp].children[indicePermisos];
                        verificar_padre=pp;
                        break;
                    }
                    
                }
                    
            }
        }
        if (indicePermisos >= 0){
            if(tipo == 0 && !elemento.data.lectura){
                elemento.data.escritura=false;
            }
            
            if("children" in elemento){
                for(let cc=0;cc<elemento.children.length;cc++){
                    if(tipo==0){
                        elemento.children[cc].data.lectura=elemento.data.lectura;
                        if(!elemento.children[cc].data.lectura){
                            elemento.children[cc].data.escritura=false;
                        }
                    }
                    if(tipo==1){
                        elemento.children[cc].data.escritura=elemento.data.escritura;
                    }
                }
                //this.verificarMarcado(indicePermisos);
            }
        }
        if (verificar_padre!=null){
            this.verificarMarcado(verificar_padre);
        }
    }
    
    verificarMarcado(indice_permisos: number){
        //console.log("verifica");
        let todo_marcado_lectura=true;
        let todo_marcado_escritura=true;
        for (let mm = 0; mm < this.permisos[indice_permisos].children.length; mm++){
            if (!this.permisos[indice_permisos].children[mm].data.lectura){
                todo_marcado_lectura=false;
            }

            if (!this.permisos[indice_permisos].children[mm].data.escritura){
                todo_marcado_escritura=false;
            }
        }
        this.permisos[indice_permisos].data.lectura = todo_marcado_lectura;
        this.permisos[indice_permisos].data.escritura = todo_marcado_escritura;
    }
    */
    
    ValidateEmail(inputText: string){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(inputText.match(mailformat)){
            return true;
        }else{
            return false;
        }
    }
    
    cambioEmpresa(){
        this.erroridempresa=false;
        this.idciudad=null;
        this.cambioCiudad();
    }
    
    cambioCiudad(){
        this.erroridciudad=null;
        this.idalmacen=null;
        this.erroridalmacen=null;
    }
    
    marcarTodos(){
        this.marcar_todos=!this.marcar_todos;
        for(let ua=0; ua<this.usuario_almacenes.length; ua++){
            this.usuario_almacenes[ua].almacen_marcado=this.marcar_todos;
        }
        this.verificarCambioAlmacen();
    }
    
    verificarDatos(){
        this.errornombre=false;
        if (this.nombre==''){
            this.errornombre=true;
        }
        this.erroridtipousuario=false;
        if (this.idtipousuario==null){
            this.erroridtipousuario=true;
        }
        this.erroremail=false;
        if (!this.ValidateEmail(this.email)){
            this.erroremail=true;
            this.mensajeerroremail="Email inválido"
        }
        this.erroridciudad=false;
        if (this.idciudad==null){
            this.erroridciudad=true;
        }
        /*
        this.erroridalmacen=false;
        if (this.idalmacen==null){
            this.erroridalmacen=true;
        }
        */
        this.errorusername=false;
        if (this.idusuario==0){
            if (this.username.length<=7){
                this.errorusername=true;
                this.mensajeerrorusername='Debe tener al menos 8 caracteres';
            }
        }
        
        this.errorcontrasena=false;
        /*
        if (this.idusuario==0){
            if (this.contrasena.length<=7){
                this.errorcontrasena=true;
                this.mensajeerrorcontrasena='Debe tener al menos 8 caracteres';
            }else{
                if (!/[A-Z]/.test(this.contrasena)){
                    this.errorcontrasena=true;
                    this.mensajeerrorcontrasena='Debe contener al menos una mayuscula';
                }else{
                    if (!/[a-z]/.test(this.contrasena)){
                        this.errorcontrasena=true;
                        this.mensajeerrorcontrasena='Debe contener al menos una minuscula';
                    }else{
                        if (!/[0-9]/.test(this.contrasena)){
                            this.errorcontrasena=true;
                            this.mensajeerrorcontrasena='Debe contener al menos un Número';
                        }else{

                        }
                    }
                }
            }
        }
        */
        this.errorcontrasena_2=false;
        /*
        if (this.idusuario==0){
            if (this.contrasena != this.contrasena_2){
                this.errorcontrasena_2=true;
            }
        }
        */
        if (!this.errornombre && !this.erroridtipousuario && !this.erroremail && !this.erroridciudad && !this.erroridalmacen && !this.errorusername && !this.errorcontrasena && !this.errorcontrasena_2){
            let datosguardar;
            datosguardar={
                nombre: this.nombre,
                idtipousuario: this.idtipousuario,
                email: this.email,
                ci: this.ci,
                telefono: this.telefono,
                idciudad: this.idciudad,
                idalmacen: this.idalmacen,
                username: this.username,
                //contrasena: this.contrasena,
                activo: this.activo,
                almacenes: this.usuario_almacenes,
                permisos: this.permisos
            };
            //console.log(datosguardar);
            
            if (this.idusuario==0){
                this._usuarioService.addusuario(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaUsuario").modal('hide');
                            this.cargarUsuarios();
                        }else{
                            if(response.existeusername){
                                this.errorusername=true;
                                this.mensajeerrorusername="Ya existe, elija otra opción"
                            }
                            if(response.existeemail){
                                this.erroremail=true;
                                this.mensajeerroremail="Ya existe, elija otra opción"
                            }
                            this.toast_tipo="Error";
                        }

                        $("#liveToast").toast('show');
                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }else{
                this._usuarioService.saveusuario(this.token, datosguardar, this.idusuario).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaUsuario").modal('hide');
                            this.cargarUsuarios();
                        }else{
                            this.toast_tipo="Error";
                            if(response.existeemail){
                                this.erroremail=true;
                                this.mensajeerroremail="Ya existe, elija otra opción"
                            }
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
    
    guardarDatos(){
        
        
        
    }
    
}
