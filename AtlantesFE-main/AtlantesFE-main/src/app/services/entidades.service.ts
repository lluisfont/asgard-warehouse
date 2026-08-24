import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class EntidadesService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    verclientes(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades/clientes',{headers:headers});
    }
    
    vercliente(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades/cliente',{headers:headers});
    }
    
    verificarusername(token: string, username: string, idcliente: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'entidades/clientes/verificarusername/' + idcliente+'/' + username,{headers:headers});
    }
    
    vernoconfnoconsiderar(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'entidades/clientes/no-conf-no-considerar/' + idcliente,{headers:headers});
    }
    
    savecliente(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'entidades/clientes',JSON.stringify(params),{headers:headers});
    }
    
    addcliente(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'entidades/clientes',JSON.stringify(params),{headers:headers});
    }
    
    vertransportistas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades/transportistas',{headers:headers});
    }
    
    savetransportista(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'entidades/transportistas',JSON.stringify(params),{headers:headers});
    }
    
    addtransportista(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'entidades/transportistas',JSON.stringify(params),{headers:headers});
    }
    
    veragentescarga(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades/agentes-carga',{headers:headers});
    }
    
    saveagentescarga(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'entidades/agentes-carga',JSON.stringify(params),{headers:headers});
    }
    
    addagentescarga(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'entidades/agentes-carga',JSON.stringify(params),{headers:headers});
    }
    
    verproveedores(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades/proveedores',{headers:headers});
    }
    
    saveproveedores(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'entidades/proveedores',JSON.stringify(params),{headers:headers});
    }
    
    addproveedores(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'entidades/proveedores',JSON.stringify(params),{headers:headers});
    }
    
    verprestadores(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades/prestadores-servicio',{headers:headers});
    }
    
    saveprestadores(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'entidades/prestadores-servicio',JSON.stringify(params),{headers:headers});
    }
    
    addprestadores(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'entidades/prestadores-servicio',JSON.stringify(params),{headers:headers});
    }
}