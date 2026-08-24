import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class CommonService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    verubicacionbase64(token: string, ubicacion: string): Observable<{estado: string, codigo: number, mensaje: string, base64: string}>{
        let params={
            ubicacion: ubicacion
        };
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post<{estado: string, codigo: number, mensaje: string, base64: string}>(this.url+'common/ubicacion/base64', JSON.stringify(params), {headers:headers});
    }
}