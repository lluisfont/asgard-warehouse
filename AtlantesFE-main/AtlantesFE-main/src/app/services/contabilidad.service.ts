import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class ContabilidadService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    verfacturas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/facturas',{headers:headers});
    }
    
    verrangofacturas(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/rangofacturas/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    generarfactura(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/generarfactura/'+idembarque,JSON.stringify(params),{headers:headers});
    }
    
    reservarfactura(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/reservarfactura/'+idembarque,null,{headers:headers});
    }
    
    downloadFactura(token: string, idfactura: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/facturas/download/'+idfactura,{headers:headers});
    }
    
    downloadFacturaMembretada(token: string, idfactura: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/facturas/download/membretada/'+idfactura,{headers:headers});
    }
    
    migrarFactura(token: string, idfactura: number, correos: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'contabilidad/facturas/migrarovp/'+idfactura,JSON.stringify(correos),{headers:headers});
    }
    
    anularFactura(token: string, idfactura: number, idmotivoanulacion: number, otro_motivoanulacion: string, uploadFileInput: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< uploadFileInput.length; i++){
            formData.append('uploads[]', uploadFileInput[i], uploadFileInput[i].name);
        }
        
        formData.append('idmotivoanulacion', idmotivoanulacion);
        formData.append('otro_motivoanulacion', otro_motivoanulacion);

        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'contabilidad/facturas/anular/'+idfactura,formData,{headers:headers});
    }
    
    downloadresplado(token: string, file: string): Observable<any>{
        let cuerpo={archivo: file};
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'contabilidad/downloadrespaldo',JSON.stringify(cuerpo),{headers:headers});
    }
    
    vernotascobranza(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/notascobranza',{headers:headers});
    }
    
    anularNotaCobranza(token: string, idnotadebito: number, idmotivoanulacion: number, otro_motivoanulacion: string, uploadFileInput: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< uploadFileInput.length; i++){
            formData.append('uploads[]', uploadFileInput[i], uploadFileInput[i].name);
        }
        
        formData.append('idmotivoanulacion', idmotivoanulacion);
        formData.append('otro_motivoanulacion', otro_motivoanulacion);

        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'contabilidad/notascobranza/anular/'+idnotadebito,formData,{headers:headers});
    }
    
    verrangonotascobranza(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/rangonotascobranza/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    downloadNC(token: string, idnotadebito: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/notascobranza/download/'+idnotadebito,{headers:headers});
    }
    
    downloadNCMembretada(token: string, idnotadebito: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/notascobranza/download/membretada/'+idnotadebito,{headers:headers});
    }
    
    generarnotacobranza(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/generarnotacobranza/'+idembarque,JSON.stringify(params),{headers:headers});
    }
    
    verinvoices(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/invoices',{headers:headers});
    }
    
    verrangoinvoices(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/rangoinvoices/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    reservarinvoice(token: string, idembarque: number): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/reservarinvoice/'+idembarque,null,{headers:headers});
    }
    
    generarinvoice(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/generarinvoice/'+idembarque,JSON.stringify(params),{headers:headers});
    }
    
    downloadInvoice(token: string, idinvoice: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/invoices/download/'+idinvoice,{headers:headers});
    }
    
    downloadInvoiceMembretada(token: string, idinvoice: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/invoices/download/membretada/'+idinvoice,{headers:headers});
    }
    
    verplanillas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/planillas',{headers:headers});
    }
    
    generarplanilla(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/generarplanilla/'+idembarque,JSON.stringify(params),{headers:headers});
    }
    
    downloadplanilla(token: string, idplanilla: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/planillas/download/'+idplanilla,{headers:headers});
    }
    
    verdevoluciones(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/devoluciones',{headers:headers});
    }
    
    verordenespago(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/ordenespago',{headers:headers});
    }
    
    verordenespagorango(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/ordenespago/rango/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    anularOrdenPago(token: string, idfacturapago: number, idmotivoanulacion: number, otro_motivoanulacion: string, uploadFileInput: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< uploadFileInput.length; i++){
            formData.append('uploads[]', uploadFileInput[i], uploadFileInput[i].name);
        }
        
        formData.append('idmotivoanulacion', idmotivoanulacion);
        formData.append('otro_motivoanulacion', otro_motivoanulacion);

        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'contabilidad/ordenespago/anular/'+idfacturapago,formData,{headers:headers});
    }
    
    verpagosagenteexterior(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/pagosagenteexterior',{headers:headers});
    }
    
    generarordenpago(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/generarordenpago/'+idembarque,JSON.stringify(params),{headers:headers});
    }
    
    downloadordenpago(token: string, idfacturapago: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/ordenespago/download/'+idfacturapago,{headers:headers});
    }
    
    migrarOrdenPago(token: string, idfacturapago: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'contabilidad/ordenespago/migrarovp/'+idfacturapago,null,{headers:headers});
    }
    
    generarordenservicio(token: string, idembarque: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'contabilidad/generarordenservicio/'+idembarque,JSON.stringify(params),{headers:headers});
    }
    
    downloadOrdenServicio(token: string, idordenservicio: number, tipo: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'contabilidad/ordenesservicio/'+tipo+'/download/'+idordenservicio,{headers:headers});
    }
    
    cobrosdetalle(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/cobros/detalle',{headers:headers});
    }
    
    cobros(token: string, idtipoentidad: number, id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/cobros/' + idtipoentidad+'/'+id,{headers:headers});
    }
    
    aplicarcobros(token: string, idtipoentidad: number, id: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'contabilidad/cobros/' + idtipoentidad+'/'+id+'/aplicar',JSON.stringify(params),{headers:headers});
    }
    
    historicopagos(token: string, idtipoentidad: number, id: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/cobros/' + idtipoentidad+'/'+id+'/historico/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    anticipos(token: string, idtipoentidad: number, id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/anticipos/' + idtipoentidad+'/'+id,{headers:headers});
    }
    
    saveAnticipo(token: string, idtipoentidad: number, id: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url + 'contabilidad/anticipos/' + idtipoentidad+'/'+id,JSON.stringify(params),{headers:headers});
    }
    
    downloadAnticipo(token: string, idanticipo: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/download/anticipos/' + idanticipo,{headers:headers});
    }
    
    downloadCobro(token: string, numero: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/download/cobros/' + numero,{headers:headers});
    }
    
    
    pagos(token: string, idtipoentidad: number, id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/pagos/' + idtipoentidad+'/'+id,{headers:headers});
    }
    
    aplicarpagos(token: string, idtipoentidad: number, id: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'contabilidad/pagos/' + idtipoentidad+'/'+id+'/aplicar',JSON.stringify(params),{headers:headers});
    }
    
    pagados(token: string, idtipoentidad: number, id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/pagado/' + idtipoentidad+'/'+id,{headers:headers});
    }
    
    downloadPago(token: string, idpago: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/download/pagos/' + idpago,{headers:headers});
    }
    
    saldos(token: string, idtipoentidad: number, id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/saldos/' + idtipoentidad+'/'+id,{headers:headers});
    }
    
    devolversaldos(token: string, idtipoentidad: number, id: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'contabilidad/saldos/' + idtipoentidad+'/'+id+'/devolver',JSON.stringify(params),{headers:headers});
    }
    
    devueltos(token: string, idtipoentidad: number, id: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/devuelto/' + idtipoentidad+'/'+id,{headers:headers});
    }
    
    
    
    downloadDevolucion(token: string, iddevolucion: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/download/devoluciones/' + iddevolucion,{headers:headers});
    }
    
    reporteCobranzas(token: string, idtipoentidad: number, id: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/reportes/cobranzas/' + idtipoentidad+'/'+id+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    reporteAnticipos(token: string, idtipoentidad: number, id: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/reportes/anticipos/' + idtipoentidad+'/'+id+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    reporteFacturasConcepto(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/reportes/facturas-concepto/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    reporteOrdenesPagoConcepto(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/reportes/ordenes-pago-concepto/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    reporteMontosConcepto(token: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'contabilidad/reportes/conceptos/'+fechainicial+'/'+fechafinal,{headers:headers});
    }

}