import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class EmpresaService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    empresadetalle(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'empresa',{headers:headers});
    }
    
    saveempresa(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'empresa', JSON.stringify(params),{headers:headers});
    }
    
    cargarimagen(token: string, tipo: string, archivo: Array<File>): Observable<any>{
        //console.log(archivo);
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        /*
        formData.append("idembarque", idembarque);
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }
        */
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'empresa/cargardocumento/'+tipo,formData,{headers:headers});
    }
    
}