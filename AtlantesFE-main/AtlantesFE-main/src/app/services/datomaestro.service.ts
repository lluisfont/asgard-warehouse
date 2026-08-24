import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class DatoMaestroService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    importacion_exportacion(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'importacion_exportacion',{headers:headers});
    }
    
    tiposembarque(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposembarque',{headers:headers});
    }
    
    incoterms(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'incoterms',{headers:headers});
    }
    
    empresas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'empresas',{headers:headers});
    }
    
    ciudades(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'ciudades',{headers:headers});
    }

    timezones(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'timezones',{headers:headers});
    }
    
    addciudad(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'ciudades',JSON.stringify(params),{headers:headers});
    }
    
    saveciudad(token: string, params: {}, idciudad: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'ciudades/' + idciudad,JSON.stringify(params),{headers:headers});
    }
    
    entidades(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'entidades',{headers:headers});
    }
    
    mediostransporte(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'mediostransporte',{headers:headers});
    }
    
    tiposcarga(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposcarga',{headers:headers});
    }
    
    aduanas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'aduanas',{headers:headers});
    }
    
    temperaturas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'temperaturas',{headers:headers});
    }
    
    horarios(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'horarios',{headers:headers});
    }
    
    conceptos(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'conceptos',{headers:headers});
    }
    
    addconcepto(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'conceptos',JSON.stringify(params),{headers:headers});
    }
    
    saveconcepto(token: string, params: {}, idconcepto: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'conceptos/' + idconcepto,JSON.stringify(params),{headers:headers});
    }
    
    listadivisas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'lista-divisas',{headers:headers});
    }
    
    divisas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'divisas',{headers:headers});
    }
    
    savedivisas(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'divisas',JSON.stringify(params),{headers:headers});
    }
    
    divisasordenservicio(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'divisas/ordenservicio',{headers:headers});
    }
    
    tiposcambio(token: string, fecha: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url+'tipo-cambio/'+fecha,JSON.stringify(params),{headers:headers});
    }
    
    savetiposcambio(token: string, fecha: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url+'tipo-cambio/'+fecha,JSON.stringify(params),{headers:headers});
    }
    
    tiposevento(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposevento',{headers:headers});
    }
    
    eventodescripcion(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'eventodescripcion',{headers:headers});
    }
    
    contemplaciones(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contemplaciones',{headers:headers});
    }
    
    addcontemplacion(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'contemplaciones',JSON.stringify(params),{headers:headers});
    }
    
    savecontemplacion(token: string, params: {}, idcontemplacion: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'contemplaciones/' + idcontemplacion,JSON.stringify(params),{headers:headers});
    }
    
    consideraciones(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'consideraciones',{headers:headers});
    }
    
    addconsideracion(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'consideraciones',JSON.stringify(params),{headers:headers});
    }
    
    saveconsideracion(token: string, params: {}, idconsideraciones: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'consideraciones/' + idconsideraciones,JSON.stringify(params),{headers:headers});
    }
    
    cuentas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'cuentas',{headers:headers});
    }
    
    addcuenta(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'cuentas',JSON.stringify(params),{headers:headers});
    }
    
    savecuenta(token: string, params: {}, idcuenta: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'cuentas/' + idcuenta,JSON.stringify(params),{headers:headers});
    }
    
    tiposplanilla(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposplanilla',{headers:headers});
    }
    
    nombrefactura(token: string, idtipodocumento: number, nit: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'nombrefactura/'+idtipodocumento+'/'+nit,{headers:headers});
    }
    
    correosfactura(token: string, idtipodocumento: number, nit: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'correosfactura/'+idtipodocumento+'/'+nit,{headers:headers});
    }
    
    tiposdescarga(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposdescarga',{headers:headers});
    }
    
    tiposcontenedor(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposcontenedor',{headers:headers});
    }
    
    tiposproducto(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposproducto',{headers:headers});
    }
    
    tiposingreso(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposingreso',{headers:headers});
    }
    
    embalajes(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'embalajes',{headers:headers});
    }
    
    no_confs(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'no_confs',{headers:headers});
    }

    clasificaciones(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'clasificaciones',{headers:headers});
    }
    
    mermas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'mermas',{headers:headers});
    }
    
    tipostransferencia(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tipostransferencia',{headers:headers});
    }
    
    tiposliquidacion(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposliquidacion',{headers:headers});
    }
    
    productoscliente(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'productos_cliente',{headers:headers});
    }
    
    addproductoscliente(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'productos_cliente',JSON.stringify(params),{headers:headers});
    }
    
    addproductosclientemasivo(token: string, idcliente: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'productos_cliente/' + idcliente+ '/cargamasiva',formData,{headers:headers});
    }
    
    saveproductoscliente(token: string, idbaseproductos: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.put(this.url + 'productos_cliente/'+idbaseproductos,JSON.stringify(params),{headers:headers});
    }
    
    eliminarproductoscliente(token: string, idbaseproductos: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url+'productos_cliente/'+idbaseproductos,{headers:headers});
    }
    
    referencia_salida(token: string, idcliente: string, contrato_no: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'referencia_salida/'+idcliente+'/'+contrato_no,{headers:headers});
    }
    
    cargardocumento(token: string, ubicacion: string, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        formData.append('ubicacion', ubicacion);
        
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'cargardocumento',formData,{headers:headers});
    }
    
    downloaddocumento(token: string, ubicacion: string, filename: string): Observable<any>{
        var formData: any = new FormData();
        formData.append('ubicacion', ubicacion);
        formData.append('filename', filename);
        
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'downloaddocumento',formData,{headers:headers});
    }
    
    eliminardocumento(token: string, ubicacion: string, filename: string): Observable<any>{
        var formData: any = new FormData();
        formData.append('ubicacion', ubicacion);
        formData.append('filename', filename);
        
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'eliminardocumento',formData,{headers:headers});
    }
    
    columnas_mover_dividir(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'columnas_mover_dividir',{headers:headers});
    }
    
    columnas_pedido(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'columnas_pedido',{headers:headers});
    }
    
    tiposdocumento(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tiposdocumento',{headers:headers});
    }
    
    centros_rubro(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'centros_rubro/'+idcliente,{headers:headers});
    }

    
    tipospedido(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tipospedido',{headers:headers});
    }
    
    motivosanulacion(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'motivosanulacion',{headers:headers});
    }
    
    status(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'status',{headers:headers});
    }
    
    docs_errada(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'docs_errada',{headers:headers});
    }
    
    listado_permisos(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'listado-permisos',{headers:headers});
    }

    accesorios_vehiculos(token: string, idcliente: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'accesorios_vehiculos/'+idcliente,{headers:headers});
    }
    /*
    accesorios_vehiculos_salidas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'accesorios_vehiculos_salidas',{headers:headers});
    }
    */
    destinos_cargo(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'destinos_cargo',{headers:headers});
    }
    
    tipos_bulto(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'tipos_bulto',{headers:headers});
    }

    timbrados_turno(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'timbrados_turno',{headers:headers});
    }
    
    inventariofisico_etiquetas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'inventariofisico_etiquetas',{headers:headers});
    }
    
    solicitantes(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'solicitantes/' + idcliente,{headers:headers});
    }
    
    movilizadores(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'movilizadores/' + idcliente,{headers:headers});
    }

    etapas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'etapas',{headers:headers});
    }

    ate_gas_motivos_pausa(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'ate_gas_motivos_pausa',{headers:headers});
    }
}
