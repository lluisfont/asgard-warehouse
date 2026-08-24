import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
declare var $: any; 

@Component({
    selector: 'app-ciudades',
    templateUrl: './ciudades.component.html',
    styleUrl: './ciudades.component.css',
    providers:[UsuarioService,DatoMaestroService]
})
export class CiudadesComponent {
    public token:string;
    public tokenDetalle: any;
    
    public ciudades: Array<any>;
    
    public idciudad: number;
    public ciudad: string;
    public errorciudad: boolean;
    public codigo: string;
    public errorcodigo: boolean;
    public mensajeerrorcodigo: string;
    public modotransporte: string;
    public pais: string;
    public timezone_name: string;
    public utc_offset_minutos: number;
    public errortimezone_name: boolean;
    public errorutc_offset_minutos: boolean;
    private zonasHorariasFallback: Array<any>=[
        { label: 'Bolivia (UTC-04)', timezone_name: 'America/La_Paz', utc_offset_minutos: -240 },
        { label: 'Peru (UTC-05)', timezone_name: 'America/Lima', utc_offset_minutos: -300 }
    ];
    public zonasHorarias: Array<any>=this.zonasHorariasFallback;
    
    
    public cabecera_modal: string;
    
    public toast_mensaje: string;
    public toast_tipo: string;
    
    public p: number=1;
    public items: number=10;
    public filtro: string= '';
    
    public ver_ciudades: boolean=false;
    public editar_ciudades: boolean=false;
    
    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _router: Router
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_ciudades=true;
            this.editar_ciudades=true;
        }else{
            let indiceVerCiudades= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 28);
            if (indiceVerCiudades>=0){
                if (this.tokenDetalle.permisos[indiceVerCiudades].lectura){
                    this.ver_ciudades=true;
                }
                if (this.tokenDetalle.permisos[indiceVerCiudades].escritura){
                    this.editar_ciudades=true;
                }
            }
        }
    }
    
    ngOnInit(): void {
        this.cargarTimezones();
        this.cargarCiudades();
    }

    cargarTimezones(){
        this._datomaestroService.timezones(this.token).subscribe(
            response =>{
                this.zonasHorarias = (response.timezones ?? []).map(timezone => this.prepararTimezoneOption(timezone));

                if(this.zonasHorarias.length==0){
                    this.zonasHorarias=this.zonasHorariasFallback;
                }
                this.sincronizarOffsetZonaHoraria();
            },
            error=>{
                this.zonasHorarias=this.zonasHorariasFallback;
                this.sincronizarOffsetZonaHoraria();
                console.log(<any>error)
            }
        );
    }
    
    cargarCiudades(){
        this._datomaestroService.ciudades(this.token).subscribe(
            response =>{
                
                this.ciudades = response.ciudades;
                console.log(this.ciudades);
            },
            error=>{
                console.log(<any>error)
            }
        );
    }
    
    prepararDatos(idciudad: number){
        this.idciudad=idciudad;
        if(idciudad==0){
            this.cabecera_modal="Nueva";
            this.ciudad='';
            this.errorciudad=false;
            this.codigo='';
            this.errorcodigo=false;
            this.mensajeerrorcodigo='';
            this.modotransporte='';
            this.pais='';
            this.timezone_name='America/La_Paz';
            this.sincronizarOffsetZonaHoraria();
            this.errortimezone_name=false;
            this.errorutc_offset_minutos=false;
        }else{
            this.cabecera_modal="Editar";
            let indice = this.ciudades.findIndex(x => x.idciudad === idciudad);
            this.ciudad = this.ciudades[indice].ciudad;
            this.errorciudad=false;
            this.codigo=this.ciudades[indice].codigo;
            this.errorcodigo=false;
            this.mensajeerrorcodigo='';
            this.modotransporte=this.ciudades[indice].modotransporte;
            this.pais=this.ciudades[indice].pais;
            this.timezone_name=this.ciudades[indice].timezone_name ?? 'America/La_Paz';
            this.utc_offset_minutos=Number(this.ciudades[indice].utc_offset_minutos ?? -240);
            this.agregarTimezoneSiNoExiste(this.timezone_name, this.utc_offset_minutos);
            this.errortimezone_name=false;
            this.errorutc_offset_minutos=false;
            
        }
    }

    cambiarZonaHoraria(){
        this.sincronizarOffsetZonaHoraria();
    }

    sincronizarOffsetZonaHoraria(){
        let indice = this.zonasHorarias.findIndex(x => x.timezone_name === this.timezone_name);
        if(indice>=0){
            this.utc_offset_minutos = this.zonasHorarias[indice].utc_offset_minutos;
        }
        this.errortimezone_name=false;
        this.errorutc_offset_minutos=false;
    }

    prepararTimezoneOption(timezone: any){
        const offset = Number(timezone.utc_offset_minutos ?? 0);

        return {
            idtimeszones: Number(timezone.idtimeszones ?? 0),
            timezone_name: timezone.timezone_name,
            utc_offset_minutos: offset,
            label: `${timezone.timezone_name} (${this.formatearOffset(offset)})`
        };
    }

    agregarTimezoneSiNoExiste(timezone_name: string, utc_offset_minutos: number){
        if(!timezone_name){
            return;
        }

        let indice = this.zonasHorarias.findIndex(x => x.timezone_name === timezone_name);
        if(indice<0){
            this.zonasHorarias.push(this.prepararTimezoneOption({
                idtimeszones: 0,
                timezone_name: timezone_name,
                utc_offset_minutos: utc_offset_minutos
            }));
        }
    }

    formatearOffset(offsetMinutos: number): string{
        const signo = offsetMinutos < 0 ? '-' : '+';
        const absoluto = Math.abs(offsetMinutos);
        const horas = String(Math.floor(absoluto / 60)).padStart(2, '0');
        const minutos = String(absoluto % 60).padStart(2, '0');

        return `UTC${signo}${horas}:${minutos}`;
    }
    
    guardarDatos(){
        let error=false;
        this.errorciudad=false;
        if (this.ciudad==''){
            this.errorciudad=true;
            error=true;
        }
        this.errorcodigo=false;
        if (this.codigo==''){
            this.errorcodigo=true;
            this.mensajeerrorcodigo='Campo Obligatorio';
            error=true;
        }
        this.errortimezone_name=false;
        if (this.timezone_name=='' || this.timezone_name==null){
            this.errortimezone_name=true;
            error=true;
        }
        this.errorutc_offset_minutos=false;
        if (this.utc_offset_minutos==null || Number.isNaN(Number(this.utc_offset_minutos))){
            this.errorutc_offset_minutos=true;
            error=true;
        }
        
        if(!error){
            let datosguardar;
            datosguardar={
                ciudad: this.ciudad,
                codigo: this.codigo,
                modotransporte: this.modotransporte,
                pais: this.pais,
                timezone_name: this.timezone_name,
                utc_offset_minutos: Number(this.utc_offset_minutos)
            };
            
            if (this.idciudad==0){
                this._datomaestroService.addciudad(this.token, datosguardar).subscribe(
                    response =>{
                        //console.log(response);
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaCiudad").modal('hide');
                            this.cargarCiudades();
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
                this._datomaestroService.saveciudad(this.token, datosguardar, this.idciudad).subscribe(
                    response =>{
                        this.toast_mensaje=response.mensaje;
                        if(response.codigo==200){
                            this.toast_tipo="Exito";
                            $("#ventanaCiudad").modal('hide');
                            this.cargarCiudades();
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
