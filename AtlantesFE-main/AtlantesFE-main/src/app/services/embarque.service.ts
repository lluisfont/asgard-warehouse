import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class EmbarqueService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    cotizaciones(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'cotizaciones',{headers:headers});
    }
    
    cotizaciondetalle(token: string, idcotizacion: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'cotizaciones/'+idcotizacion,{headers:headers});
    }
    
    crearcotizacion(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'cotizaciones',JSON.stringify(params),{headers:headers});
    }
    
    savecotizacion(token: string, idcotizacion: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'cotizaciones/'+idcotizacion,JSON.stringify(params),{headers:headers});
    }
    
    descargarcotizacion(token: string, idcotizacion: number, iddivisa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'cotizaciones/' + idcotizacion + '/documento/' + iddivisa,{headers:headers});
    }
    
    convertircotizacion(token: string, idcotizacion: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url + 'cotizaciones/' + idcotizacion+'/crearembarque',null,{headers:headers});
    }
    
    
    embarques(token: string, filtros: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'embarques',JSON.stringify(filtros),{headers:headers});
    }
    
    crearembarque(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'embarques/crear',JSON.stringify(params),{headers:headers});
    }
    
    embarquesdetalle(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'embarques/'+idembarque,{headers:headers});
    }
    
    cargardocumento(token: string, idembarque: number, archivo: Array<File>): Observable<any>{
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
        return this._http.post(this.url+'embarques/'+idembarque+'/cargardocumento',formData,{headers:headers});
    }
    
    download(token: string, idembarque: number, file: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'embarques/'+idembarque+'/download/'+file,{headers:headers});
    }
    
    eliminardocumento(token: string, idembarque: number, documento: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url+'embarques/'+idembarque+'/eliminardocumento/'+documento,{headers:headers});
    }
    
    savegeneral(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/general',JSON.stringify(params),{headers:headers});
    }
    
    saveentidades(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/entidades',JSON.stringify(params),{headers:headers});
    }
    
    saveruta(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/ruta',JSON.stringify(params),{headers:headers});
    }
    
    savecargos(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/cargos',JSON.stringify(params),{headers:headers});
    }
    
    getcargosprametros(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'embarques/'+idembarque+'/importarcargos',null,{headers:headers});
    }
    
    savecostos(token: string, idembarque: number, params: {}): Observable<any>{
        //console.log(params);
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/costos',JSON.stringify(params),{headers:headers});
    }
    
    savecargoscostosagente(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/cargoscostosagente',JSON.stringify(params),{headers:headers});
    }
    
    saveeventos(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/'+idembarque+'/eventos',JSON.stringify(params),{headers:headers});
    }
    
    correoeventos(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'embarques/'+idembarque+'/eventos/enviarcorreo',JSON.stringify(params),{headers:headers});
    }
    
    finalizarembarque(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'embarques/finalizar/'+idembarque,null,{headers:headers});
    }
    
    duplicarembarque(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'embarques/'+idembarque+'/duplicar',null,{headers:headers});
    }
    
    downloadDocCierre(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.get(this.url+'embarques/'+idembarque+'/documentocierre',{headers:headers});
    }
    
    downloadCaratula(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.get(this.url+'embarques/'+idembarque+'/caratula',{headers:headers});
    }
    
    /*
    cargardocumentoA(token: string, idembarque: number, archivo: Array<File>){
        return new Promise((resolve, reject)=>{
            var formData: any = new FormData();
            var xhr = new XMLHttpRequest();
            for(var i=0; i< archivo.length; i++){
                formData.append('uploads[]', archivo[i], archivo[i].name);
            }
            formData.append("idembarque", idembarque);
            
            xhr.onreadystatechange = function(){
                if(xhr.readyState==4){
                    if(xhr.status==200){
                        resolve(JSON.parse(xhr.response));
                    }else{
                        reject(xhr.response);
                    }
                }
            };
            console.log(formData);
            xhr.setRequestHeader('Authorization', token);
            xhr.open("POST", this.url+'cargardocumento', true);
            xhr.send(formData);
        });
    }
    */
}
