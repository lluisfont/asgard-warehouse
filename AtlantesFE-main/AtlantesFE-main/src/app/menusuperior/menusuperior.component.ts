import { Component, OnInit, Output, EventEmitter, Input, OnDestroy } from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import {DatoMaestroService} from '../services/datomaestro.service';

declare var $: any;

@Component({
  selector: 'app-menusuperior',
  templateUrl: './menusuperior.component.html',
  styleUrl: './menusuperior.component.css',
  providers: [UsuarioService, DatoMaestroService, AlmacenesService]
})
export class MenusuperiorComponent implements OnInit, OnDestroy {
    public tokenDetalle:any;
    public token: string;
    public fechaHoraSistema: string='';
    
    public titulo: string='';
    
    public almacenes: Array<any>;
    @Input() permite_cambio_almacen: boolean = true;
    public cambio_almacen: boolean=false;
    private fechaHoraIntervalo: any;
    
    public ver_cambio_almacen: boolean=false;
    //@Output() classApplied: boolean = false;
    //@Output() change: EventEmitter<boolean> = new EventEmitter<boolean>();
    constructor(
        private _usuarioService: UsuarioService,
        private _datoMaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
        private _router: Router
        ) {
        //this.change.emit(this.classApplied);
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_cambio_almacen=true;
        }else{
            let indiceVerCambioAlmacen = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 24);
            if(indiceVerCambioAlmacen>=0){
                if(this.tokenDetalle.permisos[indiceVerCambioAlmacen].lectura){
                    this.ver_cambio_almacen=true;
                }
            }
        }
        
    }

    ngOnInit(): void {
        if(this.token=='' || this.token==null || this.tokenDetalle.cambiocontrasena){
            this._router.navigate(['/login']);
        }else{
            this.tokenDetalle = this._usuarioService.getTokenDetalle();
            this.iniciarFechaHoraSistema();
            this._datoMaestroService.empresas(this.token).subscribe(
                response =>{
                    let indice = response.empresas.findIndex(x => x.idempresa == this.tokenDetalle.idempresa);
                    if(indice>=0){
                        this.titulo=response.empresas[indice].titulo;
                    }
                },
                error=>{
                    console.log(<any>error)
                }
            );
            if(this.tokenDetalle.idtipousuario==1){
                this._almacenesService.veralmacenes(this.token).subscribe(
                    response =>{
                        this.almacenes=response.almacenes;
                    	this.almacenes.forEach(o => {
                        	const partes = [o.codigo_almacen, o.almacen]
	                          .filter(v => v != null && String(v).trim() !== "");
        	                o.codigo_y_almacen = partes.join(" ") || "";
                	});

                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }else{
                this._usuarioService.verusuariosalmacen(this.token, this.tokenDetalle.idusuario).subscribe(
                    response =>{
                        this.almacenes=response.almacenes;
	                this.almacenes.forEach(o => {
                        	const partes = [o.codigo_almacen, o.almacen]
                          	.filter(v => v != null && String(v).trim() !== "");
                        	o.codigo_y_almacen = partes.join(" ") || "";
                    	});

                    },
                    error=>{
                        console.log(<any>error)
                    }
                );
            }
            
                
        }
        
        /*
        $(document).ready(function(){
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#sidebarCollapse').toggleClass('active');
            });
        });
        */
        
        $(document).ready(function(){
            $('.toggle-sidebar-btn').on('click', function () {
                $('#principal').toggleClass('toggle-sidebar');
                //$('#botonmenu').toggleClass('bi-list bi-x');
            });
            
            $('.search-bar-toggle').on('click', function () {
                $('.search-bar').toggleClass('search-bar-show');
                //$('#botonmenu').toggleClass('bi-list bi-x');
            });
            
            
            
            
            
            
        });
        
        
        
        
    }

    ngOnDestroy(): void {
        if(this.fechaHoraIntervalo){
            clearInterval(this.fechaHoraIntervalo);
        }
    }

    iniciarFechaHoraSistema(){
        this.actualizarFechaHoraSistema();
        this.fechaHoraIntervalo = setInterval(() => {
            this.actualizarFechaHoraSistema();
        }, 1000);
    }

    actualizarFechaHoraSistema(){
        try {
            const formatter = new Intl.DateTimeFormat('en-GB', {
                timeZone: this._usuarioService.getTimezoneName(),
                year: '2-digit',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            this.fechaHoraSistema = formatter.format(new Date()).replace(',', '');
        } catch (e) {
            const fecha = new Date();
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const anio = String(fecha.getFullYear()).slice(-2);
            const hora = String(fecha.getHours()).padStart(2, '0');
            const minuto = String(fecha.getMinutes()).padStart(2, '0');

            this.fechaHoraSistema = `${dia}/${mes}/${anio} ${hora}:${minuto}`;
        }
    }
    
    logOut(){
        this._usuarioService.logout();
        this._router.navigate(['/login']);
    }
    
    cambioAlmacen(){
        this.cambio_almacen=true;
        
        let indice_almacen = this.almacenes.findIndex(x => x.idalmacen === this.tokenDetalle.idalmacen);
        if(indice_almacen>=0){
            let idciudad = this.almacenes[indice_almacen].idciudad;
            
            this._usuarioService.cambiaralmacen(this.token, {'idalmacen': this.tokenDetalle.idalmacen, 'idciudad': idciudad}).subscribe(
                response =>{
                    //console.log(response);
                    //this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        //this.toast_tipo="Exito";
                        this._usuarioService.setToken(response.token);
                         window.location.reload();
                    }else{
                        //this.toast_tipo="Error";
                    }
                    //$("#liveToast").toast('show');
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
        
            
    }
    
    /*
    toggleClass() {
        this.classApplied = !this.classApplied;
        this.change.emit(this.classApplied);
    }
    */
}
