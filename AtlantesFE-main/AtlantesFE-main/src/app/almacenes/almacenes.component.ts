import { Component, OnInit } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {AlmacenesService} from '../services/almacenes.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-almacenes',
  templateUrl: './almacenes.component.html',
  providers:[UsuarioService,AlmacenesService]
})
export class AlmacenesComponent implements OnInit {
    public token: string;
    public tokenDetalle: any;
    
    public almacenes: Array<any>;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_almacenes: boolean=false;
    public editar_almacenes: boolean=false;

    constructor(
        private _usuarioService: UsuarioService,
        private _almacenesService: AlmacenesService,
        private _router: Router
    ) {
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_almacenes=true;
            this.editar_almacenes=true;
        }else{
            let indiceVerAlmacenes = this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 8);
            if (indiceVerAlmacenes>=0){
                if (this.tokenDetalle.permisos[indiceVerAlmacenes].lectura){
                    this.ver_almacenes=true;
                }
                if (this.tokenDetalle.permisos[indiceVerAlmacenes].escritura){
                    this.editar_almacenes=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this._almacenesService.veralmacenes(this.token).subscribe(
            response =>{
                this.almacenes=response.almacenes;
                console.log(this.almacenes);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    preperarAgregar(){
        
    }
    
    crearAlmacen(){
        
    }
    
    abrirDetalle(idalmacen: number){
        this._router.navigate(['/almacenes-detalle',idalmacen])
    }

}
