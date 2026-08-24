import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';
import { CookieService } from "ngx-cookie-service";
import jwt_decode from "jwt-decode";

@Injectable()
export class UsuarioService {
    public url: string;

    private readonly TOKEN_KEY = 'token';
    
    constructor(
        public _http: HttpClient,
        private cookies: CookieService
        ){
        this.url=GLOBAL.url;
    }
    
    verificardoblefactor(username: string, contrasena: string): Observable<any>{
        //let params = 'username='+username+'&contrasena='+contrasena;
        const body = new HttpParams()
            .set('username', username)
            .set('contrasena', contrasena);

        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded'});
        return this._http.post(this.url+'verificardoblefactor',body.toString(),{headers:headers});
    }
    
    verificarcodigodoblefactor(params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json'});
        return this._http.post(this.url + 'verificarcodigodoblefactor',this.stringify(params),{headers:headers});
    }
    
    login(username: string, contrasena: string): Observable<any>{
        //let params = 'username='+username+'&contrasena='+contrasena;
        const body = new HttpParams()
            .set('username', username)
            .set('contrasena', contrasena);
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded'});
        return this._http.post(this.url+'login',body.toString(),{headers:headers});
    }
    
    logout(){
        //this.cookies.delete("token");
        //sessionStorage.removeItem(this.TOKEN_KEY);
        localStorage.removeItem(this.TOKEN_KEY);
    }
    setToken(token: string) {
        //this.cookies.set("token", token);
        //sessionStorage.setItem(this.TOKEN_KEY, token);
        localStorage.setItem(this.TOKEN_KEY, token);
    }
    getToken() {
        //return this.cookies.get("token");
        //return sessionStorage.getItem(this.TOKEN_KEY) ?? '';
        return localStorage.getItem(this.TOKEN_KEY) ?? '';
    }
    getTokenDetalle(): any {
        if(this.getToken()){
            var decoded: any = jwt_decode(this.getToken());
            return decoded;
        }else{
            return null;
        }
        
        //return this.cookies.get("token");
    }

    getTimezoneName(): string {
        return this.getTokenDetalle()?.timezone_name ?? 'America/La_Paz';
    }

    getUtcOffsetMinutes(): number {
        const offset = Number(this.getTokenDetalle()?.utc_offset_minutos ?? -240);
        return Number.isFinite(offset) ? offset : -240;
    }

    getCurrentDateFilterValue(): string {
        const parts = this.getCurrentUserDateTimeParts();

        return `${parts.year}-${parts.month}-${parts.day}`;
    }

    getCurrentDateTimeValue(): string {
        const parts = this.getCurrentUserDateTimeParts();

        return `${parts.year}-${parts.month}-${parts.day} ${parts.hour}:${parts.minute}:${parts.second}`;
    }

    private getCurrentUserDateTimeParts(): any {
        try {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: this.getTimezoneName(),
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const formattedParts: any = {};

            formatter.formatToParts(new Date()).forEach(part => {
                if (part.type !== 'literal') {
                    formattedParts[part.type] = part.value;
                }
            });

            return {
                year: formattedParts.year,
                month: formattedParts.month,
                day: formattedParts.day,
                hour: formattedParts.hour === '24' ? '00' : formattedParts.hour,
                minute: formattedParts.minute,
                second: formattedParts.second
            };
        } catch (e) {
            const userLocalDate = new Date(Date.now() + this.getUtcOffsetMinutes() * 60000);

            return {
                year: String(userLocalDate.getUTCFullYear()),
                month: String(userLocalDate.getUTCMonth() + 1).padStart(2, '0'),
                day: String(userLocalDate.getUTCDate()).padStart(2, '0'),
                hour: String(userLocalDate.getUTCHours()).padStart(2, '0'),
                minute: String(userLocalDate.getUTCMinutes()).padStart(2, '0'),
                second: String(userLocalDate.getUTCSeconds()).padStart(2, '0')
            };
        }
    }
    
    usuarios(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'usuarios',{headers:headers});
    }
    
    recuperarcontrasena(params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json'});
        return this._http.post(this.url + 'recuperarconstrasena',this.stringify(params),{headers:headers});
    }
    
    verificarcodigo(params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json'});
        return this._http.post(this.url + 'verificarcodigo',this.stringify(params),{headers:headers});
    }
    
    resetearcontrasena(params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json'});
        return this._http.post(this.url + 'resetearcontrasena',this.stringify(params),{headers:headers});
    }
    
    verusuario(token: string, idusuario: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'usuario/' + idusuario,{headers:headers});
    }
    
    verusuariosalmacen(token: string, idusuario: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'usuario/almacenes/' + idusuario,{headers:headers});
    }
    
    tiposusuario(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposusuario',{headers:headers});
    }
    
    verificarusername(token: string, username: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'usuarios/' + username,{headers:headers});
    }
    
    saveusuario(token: string, params: {}, idusuario: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'usuarios/' + idusuario,this.stringify(params),{headers:headers});
    }
    
    saveperfil(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'editarperfil',JSON.stringify(params),{headers:headers});
    }
    
    addusuario(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'usuarios',this.stringify(params),{headers:headers});
    }
    
    cambiarcontrasena(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'cambiarcontrasena',JSON.stringify(params),{headers:headers});
    }
    
    savecolumnas_mover_dividir(token: string, params: {}, idusuario: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'usuarios/columnas_moverdividir/' + idusuario, JSON.stringify(params), {headers:headers});
    }
    
    savecolumnas_pedido(token: string, params: {}, idusuario: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'usuarios/columnas_pedido/' + idusuario, JSON.stringify(params), {headers:headers});
    }
    
    cambiarclientealmacen(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'cambiarclientealmacen',JSON.stringify(params),{headers:headers});
    }
    
    cambiaralmacen(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'cambiaralmacen',JSON.stringify(params),{headers:headers});
    }
    
    stringify(obj) {
        let cache = [];
        let str = JSON.stringify(obj, function(key, value) {
          if (typeof value === "object" && value !== null) {
            if (cache.indexOf(value) !== -1) {
              // Circular reference found, discard key
              return;
            }
            // Store value in our collection
            cache.push(value);
          }
          return value;
        });
        cache = null; // reset the cache
        return str;
    }
    
    
  
}
