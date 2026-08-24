import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class AsgardService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    transporteAsgard(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/transporteAsgard',{headers:headers});
    }
    
    carpetaAsgard(token: string, carpeta: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/datosCarpeta/'+carpeta,{headers:headers});
    }
    
    vehiculosAsgard(token: string, partida: string, idcliente: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/datosPartida/' + partida + '/' + idcliente,{headers:headers});
    }
    
    bitacoraChasisAsgard(token: string, chasis: string, idcliente: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/buscar-chasis/'+idcliente+'/'+chasis,{headers:headers});
    }
    
    bitacoraResumenInventarioAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/resumen-inventario/' + idcliente + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    bitacoraAccesoriosListaAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/accesorios/lista/' + idcliente + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    bitacoraDesperfectosListaAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/desperfectos/lista/' + idcliente + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    bitacoraContaminacionListaAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/contaminacion/lista/' + idcliente + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    downloadFileAsgard(token: string, chasis: string, idcliente: number, tipo: number, filename: string): Observable<any>{
        let params={
            tipo: tipo,
            filename: filename
        }
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'asgard/inventario/file/download/'+idcliente+'/'+ chasis,JSON.stringify(params),{headers:headers});
    }
    
    bitacoraNacionalResumenInventarioAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number, embarque_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/nacional/resumen-inventario/' + idcliente + '/' + embarque_id + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    bitacoraNacionalAccesoriosListaAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number, embarque_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/nacional/accesorios/lista/' + idcliente + '/' + embarque_id + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    bitacoraNacionalDesperfectosListaAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number, embarque_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/nacional/desperfectos/lista/' + idcliente + '/' + embarque_id + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
    bitacoraNacionalContaminacionListaAsgard(token: string, chasis: string, idcliente: number, tipo_inventario_id: number, embarque_id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'asgard/inventario/nacional/contaminacion/lista/' + idcliente + '/' + embarque_id + '/' + chasis + '/' + tipo_inventario_id,{headers:headers});
    }
    
}