import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import {GLOBAL} from './../global';

@Injectable()
export class AlmacenesService {
    public url: string;
    
    constructor(
        public _http: HttpClient
        ){
        this.url=GLOBAL.url;
    }
    
    veralmacenes(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'almacenes',{headers:headers});
    }
    
    veralmacen(token: string, idalmacen: number, fechacorte: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'almacenes/' + idalmacen+'/'+fechacorte,{headers:headers});
    }
    
    veralmacenubicaciones(token: string, idalmacen: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'almacenes/' + idalmacen,{headers:headers});
    }
    
    vertipoalmacendetalle(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'tipoalmacenesdetalle',{headers:headers});
    }
    
    veringresos(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ingresos/'+idcliente,{headers:headers});
    }
    
    veringresos_pendientes(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ingresos/pendientes/'+idcliente,{headers:headers});
    }

    guardaraccesoriospendiente(token: string, idsalidadetalle: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'ingresos/pendientes/'+idsalidadetalle,JSON.stringify(params),{headers:headers});
    }
    
    veringreso(token: string, idingreso: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ingresos/detalle/'+idingreso,{headers:headers});
    }
    
    crearingreso(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'ingresos',JSON.stringify(params),{headers:headers});
    }
    
    duplicaringreso(token: string, idingreso: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url + 'ingresos/' + idingreso + '/duplicar',null,{headers:headers});
    }
    
    aprobaringresopendiente(token: string, idsalida: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url + 'ingresos/pendientes/' + idsalida,null,{headers:headers});
    }
    
    guardaringreso(token: string, idingreso: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'ingresos/'+idingreso,JSON.stringify(params),{headers:headers});
    }
    
    verproducto(token: string, idcliente: number, codigo: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'productos/'+idcliente+'/'+codigo,{headers:headers});
    }
    
    ingresocargamasiva(token: string, idingreso: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'ingresos/'+idingreso+'/cargamasiva',formData,{headers:headers});
    }
    
    reporteingresos(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ingresos/reporte/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }

    reportedescarga(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'descarga/reporte/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    reporteliquidacion(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'reporteliquidacion/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    reporteposicionesdia(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'reporteposicionesdia/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    reporteinventariovencimiento(token: string, idcliente: string, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'reporteinventariovencimiento/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
        
    downloadActaIngreso(token: string, idingreso: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'ingresos/actaingreso/'+idingreso,{headers:headers});
    }
    
    downloadConstanciaIngreso(token: string, idingreso: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'ingresos/constancia/'+idingreso,{headers:headers});
    }
    
    versalidas(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'salidas/'+idcliente,{headers:headers});
    }
    
    versalida(token: string, idsalida: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'salidas/detalle/'+idsalida,{headers:headers});
    }
    
    verimagenessalidadetalle(token: string, idsalidadetalle: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'salidas/detalle/'+idsalidadetalle+'/imagenes',{headers:headers});
    }

    verimagenessalidapendientedetalle(token: string, idsalidadetalle: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'salidas/pendiente/detalle/'+idsalidadetalle+'/imagenes',{headers:headers});
    }
    
    crearsalida(token: string, idcliente: string, es_vehiculo: boolean, movimiento: boolean, es_no_conf: boolean, cargamasiva: boolean, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        if(cargamasiva){
            for(var i=0; i< archivo.length; i++){
                formData.append('uploads[]', archivo[i], archivo[i].name);
            }
        }
        formData.append('cargamasiva',cargamasiva);
        formData.append('idcliente',idcliente);
        formData.append('es_vehiculo',es_vehiculo);
        formData.append('movimiento',movimiento);
        formData.append('es_no_conf',es_no_conf);
        //console.log(formData);
        
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'salidas',formData,{headers:headers});
        
    }
    
    inventario(token: string, idcliente: string, fecha: string, corte: boolean): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventario/'+idcliente+'/'+fecha+'/'+corte,{headers:headers});
    }
    
    agregarimagenessalidaaccesorios(token: string, idsalidadetalle: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'salidas/accesorios/'+idsalidadetalle+'/imagenes',JSON.stringify(params),{headers:headers});
    }
    
    guardarsalida(token: string, idsalida: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'salidas/'+idsalida,JSON.stringify(params),{headers:headers});
    }
    
    downloadActaSalida(token: string, idsalida: string, unidad_salida: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'salidas/actasalida/'+idsalida+'/'+unidad_salida,{headers:headers});
    }
    
    downloadConstanciaSalida(token: string, idsalida: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'salidas/constancia/'+idsalida,{headers:headers});
    }
    
    finalizarSalida(token: string, idsalida: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'salidas/' + idsalida + '/finalizar',null,{headers:headers});
    }
    
    habilitarSalida(token: string, idsalida: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'salidas/' + idsalida + '/habilitar',null,{headers:headers});
    }
    
    
    moverdividir(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'inventario/moverdividir',JSON.stringify(params),{headers:headers});
    }
    
    ubicaralmacen(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inventario/ubicaralmacen',JSON.stringify(params),{headers:headers});
    }

    inventariocatualizacionmasiva(token: string, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'inventario/actualizacionmasiva',formData,{headers:headers});
    }
    
    historial(token: string, idingresodetalle: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'almacenes/historial/detalle/'+idingresodetalle,{headers:headers});
    }
    
    reporteinventario(token: string, idcliente: string, fecha: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'reporteinventario/'+idcliente+'/'+fecha,{headers:headers});
    }
    
    reportesalidas(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'salidas/reporte/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    verpedidos(token: string, idcliente: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'pedidos/' + idcliente,{headers:headers});
    }
    
    verpedido(token: string, idpedido: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'pedidos/detalle/'+idpedido,{headers:headers});
    }
    
    crearpedido(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'pedidos',JSON.stringify(params),{headers:headers});
    }
    
    agruparpedido(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'agruparpedidos',JSON.stringify(params),{headers:headers});
    }
    
    pedidocarga(token: string, idpedido: string, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'pedidos/'+idpedido+'/carga',formData,{headers:headers});
    }
    
    crearsalidapedido(token: string, idpedido: string, tiendas: Array<string>): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url + 'pedidos/' + idpedido+'/salidas',JSON.stringify(tiendas),{headers:headers});
    }
    
    pedidodisponibilidad(token: string, idpedido: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'pedidos/'+idpedido+'/disponibilidad',null,{headers:headers});
    }
    
    eliminardatospedido(token: string, idpedido: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url+'pedidos/detalle/'+idpedido,{headers:headers});
    }
    
    eliminarpedido(token: string, idpedido: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url+'pedidos/'+idpedido,{headers:headers});
    }
    
    downloadActaPedido(token: string, idpedido: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'pedidos/actapedido/'+idpedido,{headers:headers});
    }
    
    guardarpedido(token: string, idpedido: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url + 'pedidos/' + idpedido,JSON.stringify(params),{headers:headers});
    }
    
    reportePedido(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'pedidos/reporte/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    reportemovimiento(token: string, idcliente: number, idingresodetalle: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'reportemovimientos/'+idcliente+'/'+idingresodetalle+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    reportemovimientodetalle(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'reportemovimientosdetalle/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    reportemovimientotienda(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'reportemovimientostienda/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    verinventariosfisico(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'inventariosfisico/listado',JSON.stringify(params),{headers:headers});
    }
    
    verinventariosfisicofechas(token: string, idcliente: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventariosfisico/' + idcliente+'/fechas',{headers:headers});
    }
    
    crearinventariofisico(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'inventariosfisico',JSON.stringify(params),{headers:headers});
    }
    
    crearinventariofisicocargamasiva(token: string, idcliente: number, es_vehiculo: boolean, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        formData.append('idcliente', idcliente);
        formData.append('es_vehiculo', es_vehiculo);
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'inventariosfisico/cargamasiva',formData,{headers:headers});
    }
    
    verinventariofisico(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventariosfisico/detalle/'+idinventariofisico,{headers:headers});
    }
    
    vermonitoreocentros(token: string, idcliente: number, idinventariofisico: number, fechainicial: string, fechafinal: string, params: {}): Observable<any>{
        if(!idinventariofisico){
            idinventariofisico=0;
        }
        if(!fechainicial){
            fechainicial='0';
        }
        if(!fechafinal){
            fechafinal='0';
        }
        
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'inventariosfisico/monitoreo-centros/' + idcliente+'/'+idinventariofisico+'/'+fechainicial+'/'+fechafinal,JSON.stringify(params),{headers:headers});
    }
    
    inventariofisicocargamasiva(token: string, idinventariofisico: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'inventariosfisico/'+idinventariofisico+'/cargamasiva',formData,{headers:headers});
    }
    
    guardarinventariofisico(token: string, idinventariofisico: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inventariosfisico/'+idinventariofisico,JSON.stringify(params),{headers:headers});
    }
    
    finalizarinventariofisico(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inventariosfisico/'+idinventariofisico+'/finalizar',null,{headers:headers});
    }
    
    eliminarinventariofisico(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.delete(this.url+'inventariosfisico/'+idinventariofisico+'/eliminar',{headers:headers});
    }
    
    downloadinventariofisicodetallearchivo(token: string, idinventariofisicodetallearchivo: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'inventariosfisico/download/'+idinventariofisicodetallearchivo,{headers:headers});
    }
    
    downloadTomaInventarioFisico(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'inventariosfisico/tomainventariofisico/'+idinventariofisico,{headers:headers});
    }
    
    verinventariofisicoconteo(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventariosfisico/conteo/'+idinventariofisico,{headers:headers});
    }
    
    verimagenesinventariofisicoconteo(token: string, idinventariofisicoconteo: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventariosfisico/conteo/'+idinventariofisicoconteo+'/imagenes',{headers:headers});
    }
    
    inicializarinventariofisicoconteo(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'inventariosfisico/conteo/'+idinventariofisico+'/inicializar',null,{headers:headers});
    }
    
    agregarinventariofisicoconteo(token: string, idinventariofisico: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'inventariosfisico/conteo/'+idinventariofisico,JSON.stringify(params),{headers:headers});
    }
    
    agregarimagenesinventariofisicoconteo(token: string, idinventariofisico: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'inventariosfisico/conteo/'+idinventariofisico+'/imagenes',JSON.stringify(params),{headers:headers});
    }
    
    guardarinventariofisicoconteo(token: string, idinventariofisico: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'inventariosfisico/conteo/'+idinventariofisico,JSON.stringify(params),{headers:headers});
    }
    
    eliminarinventariofisicoconteo(token: string, idinventariofisico: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inventariosfisico/conteo/'+idinventariofisico+'/eliminar',JSON.stringify(params),{headers:headers});
    }
    
    verinventariofisicoconteoseries(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventariosfisico/conteo/'+idinventariofisico+'/series',{headers:headers});
    }
    
    finalizarconteoinventariofisico(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inventariosfisico/conteo/'+idinventariofisico+'/finalizar',null,{headers:headers});
    }
    
    reabrirconteoinventariofisico(token: string, idinventariofisico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inventariosfisico/conteo/'+idinventariofisico+'/reabrir',null,{headers:headers});
    }
    
    reporteInventarioFisico(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'inventariosfisico/reporte/' + idcliente + '/' + fechainicial + '/' + fechafinal,{headers:headers});
    }
    
    reportarDelosiAPI(token: string, idingreso: number){
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'inter_company/'+idingreso,null,{headers:headers});
    }
    
    creartimbrado(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.post(this.url+'timbrado',JSON.stringify(params),{headers:headers});
    }
    
    vertimbrados(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'timbrados',{headers:headers});
    }
    
    vertimbrado(token: string, idtimbrado: number, fecha_inicial: string, fecha_final: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'timbrado/detalle/'+idtimbrado+'/'+fecha_inicial+'/'+fecha_final,{headers:headers});
    }
    
    timbradocargamasiva(token: string, idtimbrado: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'timbrado/'+idtimbrado+'/cargamasiva',formData,{headers:headers});
    }
    
    timbradoactualizacionmasiva(token: string, idtimbrado: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'timbrado/'+idtimbrado+'/actualizacionmasiva',formData,{headers:headers});
    }
    
    eliminartimbradodetalle(token: string, idtimbradodetalle: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url+'timbrado/detalle/'+idtimbradodetalle,{headers:headers});
    }
    
    crearategascargamasiva(token: string, idcliente: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        formData.append('idcliente', idcliente);
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'ate-gas/cargamasiva',formData,{headers:headers});
    }
    
    verategas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas',{headers:headers});
    }
    
    recepcionarategas(token: string, idate_gas: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'ate-gas/recepcionar/'+idate_gas,JSON.stringify(params),{headers:headers});
    }

    ubicarategas(token: string, idate_gas: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'ate-gas/ubicar/'+idate_gas,JSON.stringify(params),{headers:headers});
    }

    editarategas(token: string, idate_gas: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.put(this.url+'ate-gas/editar/'+idate_gas,JSON.stringify(params),{headers:headers});
    }

    eliminarategas(token: string, idate_gas: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/json','Authorization':token});
        return this._http.delete(this.url+'ate-gas/'+idate_gas,{headers:headers});
    }
    
    verasignaciontrabajo(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/asignacion-trabajo',{headers:headers});
    }
    
    verasignaciontrabajotecnicos(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/asignacion-trabajo/tecnicos',{headers:headers});
    }
    
    asignaciontrabajotecnicos(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'ate-gas/asignacion-trabajo/tecnicos',JSON.stringify(params),{headers:headers});
    }
    
    eliminarasignaciontrabajotecnicos(token: string, idate_gas_etapa_tecnico: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url + 'ate-gas/asignacion-trabajo/tecnicos/'+idate_gas_etapa_tecnico,{headers:headers});
    }
    
    verasignaciontrabajotecnicosqa(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/asignacion-trabajo/tecnicos_qa',{headers:headers});
    }
    
    asignaciontrabajotecnicosqa(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'ate-gas/asignacion-trabajo/tecnicos_qa',JSON.stringify(params),{headers:headers});
    }
    
    eliminarasignaciontrabajotecnicosqa(token: string, idate_gas_etapa_tecnico_qa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.delete(this.url + 'ate-gas/asignacion-trabajo/tecnicos_qa/'+idate_gas_etapa_tecnico_qa,{headers:headers});
    }
    
    vergestionmovimiento(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/gestion-movimiento',{headers:headers});
    }
    
    guardargestionmovimientovista(token: string, idate_gas_etapa: number, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.put(this.url + 'ate-gas/gestion-movimiento/vista/'+idate_gas_etapa,JSON.stringify(params),{headers:headers});
    }
    
    vergestionmovimientoinventario(token: string, idate_gas_etapa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/gestion-movimiento/inventario/' + idate_gas_etapa,{headers:headers});
    }

    vergestionmovimientoimagenes(token: string, idate_gas_etapa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/gestion-movimiento/' + idate_gas_etapa+'/imagenes',{headers:headers});
    }
    
    guardargestionmovimientoinventario(token: string, idate_gas_etapa: number, observaciones_inventario: string, inventario: Array<any>,uploadedFiles: File[][],uploadedFilesMain: File[]): Observable<any> {
        const formData = new FormData();
        // 1) inventario como JSON
        

        // 2) archivos: files[<inventario_id>][]
        for (let i = 0; i < inventario.length; i++) {
            const invId = inventario[i].iddanios_vehiculos;
            for (const file of (uploadedFiles[i] ?? [])) {
                formData.append(`files[${invId}][]`, file, file.name);
            }
        }

        for (const file of (uploadedFilesMain ?? [])) {
            formData.append(`filesMain[]`, file, file.name);
        }
        
        formData.append('inventario', JSON.stringify(inventario.filter(item => item.marcado)));
        formData.append('observaciones_inventario', observaciones_inventario);

        const headers = new HttpHeaders({ 'Authorization': token });
        return this._http.post(this.url + 'ate-gas/gestion-movimiento/inventario/'+idate_gas_etapa, formData, { headers });
    }
    
    iniciargestionmovimiento(token: string, idate_gas_etapa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'ate-gas/gestion-movimiento/iniciar/'+idate_gas_etapa,null,{headers:headers});
    }
    
    pausargestionmovimiento(token: string, idate_gas_etapa: number, idate_gas_motivo_pausa: number, motivo_pausa: string): Observable<any>{
        let params={idate_gas_motivo_pausa: idate_gas_motivo_pausa, motivo_pausa: motivo_pausa};
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'ate-gas/gestion-movimiento/pausar/'+idate_gas_etapa,JSON.stringify(params),{headers:headers});
    }
    
    finalizargestionmovimiento(token: string, idate_gas_etapa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'ate-gas/gestion-movimiento/finalizar/'+idate_gas_etapa,null,{headers:headers});
    }
    
    verestadopedidos(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'ate-gas/estado-pedidos',JSON.stringify(params),{headers:headers});
    }
    
    verdetalleestadopedidos(token: string, idate_gas_etapa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/estado-pedidos/'+idate_gas_etapa,{headers:headers});
    }

    verinventariovin(token: string, params: {}): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.post(this.url + 'ate-gas/inventario-vin',JSON.stringify(params),{headers:headers});
    }

    downloadAteGasInventario(token: string, idate_gas: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'ate-gas/inventario/'+idate_gas,{headers:headers});
    }

    downloadAteGasEtapaInventario(token: string, idate_gas_etapa: number): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url+'ate-gas/etapa/inventario/'+idate_gas_etapa,{headers:headers});
    }

    crearategassalidascargamasiva(token: string, idcliente: number, archivo: Array<File>): Observable<any>{
        var formData: any = new FormData();
        for(var i=0; i< archivo.length; i++){
            formData.append('uploads[]', archivo[i], archivo[i].name);
        }
        formData.append('idcliente', idcliente);
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'ate-gas/salidas/cargamasiva',formData,{headers:headers});
    }

    verategassalidas(token: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/salidas',{headers:headers});
    }

    sacarategas(token: string, idate_gas: number, formData: FormData): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.post(this.url+'ate-gas/salidas/'+idate_gas,formData,{headers:headers});
    }

    descargarGuiaRemisionAteGas(token: string, idate_gas: number): Observable<any>{
        let headers = new HttpHeaders({'Authorization':token});
        return this._http.get(this.url+'ate-gas/salidas/'+idate_gas+'/guia-remision',{
            headers:headers,
            observe:'response',
            responseType:'blob'
        });
    }

    reportetiemposproceso(token: string, idcliente: number, tipo_filtro: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/reporte-tiempos-proceso/'+idcliente+'/'+tipo_filtro+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }

    reporteategasdemanda(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/reporte-demanda/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }

    reporteategasstatus(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/reporte-status/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }

    reporteategasingresos(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/reporte-ingresos/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }

    reporteategassalidas(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/reporte-salidas/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }

    reporteategasproduccion(token: string, idcliente: number, fechainicial: string, fechafinal: string): Observable<any>{
        let headers = new HttpHeaders({'Content-Type':'application/x-www-form-urlencoded','Authorization':token});
        return this._http.get(this.url + 'ate-gas/reporte-produccion/'+idcliente+'/'+fechainicial+'/'+fechafinal,{headers:headers});
    }
    
    
}
