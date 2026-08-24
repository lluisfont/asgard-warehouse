import { Component, ViewChild, ElementRef } from '@angular/core';
import {UsuarioService} from '../services/usuario.service';
import {EmpresaService} from '../services/empresa.service';
import {EmpresaModel} from '../models/empresa.model';
declare var $: any; 

@Component({
    selector: 'app-empresa',
    templateUrl: './empresa.component.html',
    styleUrl: './empresa.component.css',
    providers:[EmpresaService,UsuarioService]
})
export class EmpresaComponent {
    public token:string;
    public tokenDetalle:any;
    
    public empresa: EmpresaModel;
    public errorempresa:boolean=false;
    
    @ViewChild('UploadLogoFileInput')
    myInputVariable: ElementRef;
    public errorlogo: boolean;
    public uploadLogoFileInput: any;
    public logocargado: boolean;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public ver_empresa: boolean=false;
    public editar_empresa: boolean=false;

    constructor(
    private _usuarioService: UsuarioService,
        private _empresaService: EmpresaService,
        //private _almacenesService: AlmacenesService,
        //private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_empresa=true;
            this.editar_empresa=true;
        }else{
            let indiceVerEmpresa= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 32);
            if (indiceVerEmpresa>=0){
                if (this.tokenDetalle.permisos[indiceVerEmpresa].lectura){
                    this.ver_empresa=true;
                }
                if (this.tokenDetalle.permisos[indiceVerEmpresa].escritura){
                    this.editar_empresa=true;
                }
            }
        }
    }

    ngOnInit(): void {
        this.cargarEmpresa();
        
    }
    
    cargarEmpresa(){
        this._empresaService.empresadetalle(this.token).subscribe(
            response =>{
                this.empresa=response.empresa;
                console.log(this.empresa);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    saveEmpresa(){
        let error=false;
        if (!this.empresa.empresa){
            this.errorempresa=true;
            error=true;
        }
        
        if (!error){
            this._empresaService.saveempresa(this.token, this.empresa).subscribe(
                response =>{
                    //console.log(response);
                    this.toast_mensaje=response.mensaje;
                    if(response.codigo==200){
                        this.toast_tipo="Exito";
                        //$("#ventanaUsuario").modal('hide');
                        //this.cargarUsuarios();
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
    
    logoFileChangeEvent(fileInput: any) {
        this.errorlogo=false;
        if(fileInput.target.files){
            this.uploadLogoFileInput=<Array<File>>fileInput.target.files;
            this.logocargado=true;
        }else {
            //this.myfilename = 'Seleccione un Archivo';
        }
    }
    
    cargarLogo(){
        this.errorlogo=false;
        if (!this.logocargado){
            this.errorlogo=true;
        }
        if (!this.errorlogo){
            alert("Cargar Logo");
        }
    }

}
