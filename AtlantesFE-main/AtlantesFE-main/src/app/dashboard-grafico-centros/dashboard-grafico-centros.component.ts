import { Component, OnInit } from '@angular/core';
import { formatDate } from '@angular/common';
import {UsuarioService} from '../services/usuario.service';
import {DatoMaestroService} from '../services/datomaestro.service';
import {AlmacenesService} from '../services/almacenes.service';

@Component({
    selector: 'app-dashboard-grafico-centros',
    templateUrl: './dashboard-grafico-centros.component.html',
    styleUrl: './dashboard-grafico-centros.component.css',
    providers:[UsuarioService,DatoMaestroService,AlmacenesService]
})
export class DashboardGraficoCentrosComponent {
    public token: string;
    public tokenDetalle: any;
    
    public entidades: Array<any>;

    public idcliente: number;
    public error_idcliente: boolean=false;
    public fecha?: Date;
    public error_fecha: boolean=false;
    public generado: boolean=false;
    
    fechasHabilitadasStr = [];
    minDate!: Date;
    maxDate!: Date;
    invalidDates: Date[] = [];
    
    chartStyle: any;
    chartHeight = '300px';

    private fechasHabilitadasSet = new Set<number>();
    
    private parseYMDLocal(s: string): Date {
        const [y, m, d] = s.split('-').map(n => parseInt(n, 10));
        // Crear fecha en horario local (evita desfases por zona horaria)
        return new Date(y, m - 1, d, 0, 0, 0, 0);
    }
    private toKey(d: Date): number {
        // Normaliza a medianoche local
        return new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
    }

    
    public ver_dashboard_grafico_centros: boolean=false;
    
    data: any;

    options: any;
    
    public estados: Array<any> = [
        { idestado_conteo: 1, estado_conteo: 'ENCONTRADO', color: '#198754' },
        { idestado_conteo: 2, estado_conteo: 'SOBRANTE', color: '#0dcaf0' },
        { idestado_conteo: 3, estado_conteo: 'FALTANTE', color: '#dc3545' },
        { idestado_conteo: 4, estado_conteo: 'PENDIENTE', color: '#ffc107' }
    ];

    constructor(
        private _usuarioService: UsuarioService,
        private _datomaestroService: DatoMaestroService,
        private _almacenesService: AlmacenesService,
    ) { 
        this.token = this._usuarioService.getToken();
        this.tokenDetalle = this._usuarioService.getTokenDetalle();
        if(this.tokenDetalle.idtipousuario==1){
            this.ver_dashboard_grafico_centros=true;
        }else{
            let indiceVerReporte= this.tokenDetalle.permisos.findIndex(x => x.idmodulo === 87);
            if (indiceVerReporte>=0){
                if (this.tokenDetalle.permisos[indiceVerReporte].lectura){
                    this.ver_dashboard_grafico_centros=true;
                }
            }
        }
    
    }
    
    ngOnInit(): void {
        this._datomaestroService.entidades(this.token).subscribe(
            response =>{

                this.entidades = response.entidades.filter(function (el) {
                    return el.idtipoentidad==1;
                });

                //console.log(response.entidades);
                //console.log(this.entidades);
            },
            error=>{
                console.log(<any>error)
            }
        );
        this.verificarFechas();
    }
    
    ngOnChanges() {
        
    }
    
    verificarFechas(){
        this.fecha=null;
        if (this.fechasHabilitadasStr.length === 0) {
            // 🚫 no hay fechas permitidas → bloquear todo
            this.invalidDates = []; // no hace falta llenarla, se bloquea con min/max
            this.minDate = new Date(9999, 0, 1); // fecha muy lejana en el futuro
            this.maxDate = new Date(0, 0, 1);    // fecha muy lejana en el pasado
            return;
        }
        
        const permitidas = this.fechasHabilitadasStr.map(s => this.parseYMDLocal(s));
        permitidas.forEach(d => this.fechasHabilitadasSet.add(this.toKey(d)));

        // 2) min/max
        permitidas.sort((a,b) => a.getTime() - b.getTime());
        this.minDate = permitidas[0];
        this.maxDate = permitidas[permitidas.length - 1];

        // 3) Construir TODAS las inválidas en el rango min-max
        this.invalidDates = this.buildInvalidDatesBetween(this.minDate, this.maxDate);
    }
    
    private buildInvalidDatesBetween(start: Date, end: Date): Date[] {
        const out: Date[] = [];
        // Clonar y normalizar
        let cursor = new Date(start.getFullYear(), start.getMonth(), start.getDate(), 0, 0, 0, 0);
        const endKey = this.toKey(end);

        while (this.toKey(cursor) <= endKey) {
          const key = this.toKey(cursor);
          if (!this.fechasHabilitadasSet.has(key)) {
            // Push una copia (no el mismo objeto que se va mutando)
            out.push(new Date(cursor.getFullYear(), cursor.getMonth(), cursor.getDate(), 0, 0, 0, 0));
          }
          // +1 día
          cursor.setDate(cursor.getDate() + 1);
        }
        return out;
    }
    
    cambioCliente(){
        this.error_idcliente=false;
        this.fechasHabilitadasStr=[];
        if (this.idcliente){
            this._almacenesService.verinventariosfisicofechas(this.token, this.idcliente).subscribe(
                response =>{
                    this.fechasHabilitadasStr=response.fechas;
                    this.verificarFechas();
                    

                    //console.log(response.entidades);
                    //console.log(this.entidades);
                },
                error=>{
                    console.log(<any>error)
                }
            );
        }
        
            
    }
    
    generarDashBoard(){
        let error=false;
        if (!this.idcliente){
            this.error_idcliente=true;
            error=true;
        }
        if (!this.fecha){
            this.error_fecha=true;
            error=true;
        }
        if(!error){
            this.data=[];
            this._almacenesService.vermonitoreocentros(this.token, this.idcliente, 0, formatDate(this.fecha, 'yyyy-MM-dd', 'en-US'), formatDate(this.fecha, 'yyyy-MM-dd', 'en-US'), {ciudades: [], almacenes: []}).subscribe(
                response => {

                    let respuesta = this.agruparPorAlmacen(response.detalle, this.estados);
                    let labels=[];
                    for(let rr=0;rr<respuesta.length;rr++){
                        labels.push(respuesta[rr].codigo_almacen+' '+respuesta[rr].almacen+' ('+respuesta[rr].idinventariofisico+')');
                    }
                    let datasets=[];
                    for (let ee = 0; ee < this.estados.length; ee++){
                        let data=[];
                        for(let rr=0;rr<respuesta.length;rr++){
                            let indiceData = respuesta[rr].estados.findIndex(x => x.idestado_conteo === this.estados[ee].idestado_conteo);
                            data.push(respuesta[rr].estados[indiceData].cantidad);
                        }
                        datasets.push({
                            type: 'bar',
                            label: this.estados[ee].estado_conteo,
                            backgroundColor: this.estados[ee].color,
                            data: data
                        });
                    }
                    /*
                    console.log(respuesta);
                    console.log(labels);
                    console.log(datasets);
                    */
                    const documentStyle = getComputedStyle(document.documentElement);
                    const textColor = documentStyle.getPropertyValue('--text-color');
                    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
                    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');


                    this.data = {
                        labels: labels,
                        datasets: datasets
                    };
                    
                    const n = this.data?.labels?.length ?? 0;
                    const pxPorFila = 28;
                    this.chartHeight = `${Math.max(300, n * pxPorFila)}px`;
                    
                    
                    
                    console.log(this.data);

                    this.options = {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        //aspectRatio: 0.8,
                        interaction: {
                            mode: 'index',                // muestra todos los datasets del índice
                            intersect: false,             // no exige estar justo encima de una barra
                            axis: 'y'                     // IMPORTANTÍSIMO al usar indexAxis: 'y'
                        },
                        plugins: {
                            tooltip: {
                                enabled: true,
                                //intersect: false
                            },
                            legend: {
                                labels: {
                                    color: textColor
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                ticks: {
                                    color: textColorSecondary
                                },
                                grid: {
                                    color: surfaceBorder,
                                    drawBorder: false
                                }
                            },
                            y: {
                                stacked: true,
                                ticks: {
                                    autoSkip: false,
                                    color: textColorSecondary
                                },
                                grid: {
                                    color: surfaceBorder,
                                    drawBorder: false
                                }
                            }
                        }
                    };

                    /*
                    this.cuadros[0].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '1' ? 1 : 0), 0);
                    this.cuadros[1].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '4' ? 1 : 0), 0);
                    this.cuadros[2].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '2' ? 1 : 0), 0);
                    this.cuadros[3].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '2' ? 1 : 0), 0);
                    this.cuadros[4].cantidad = response.detalle.reduce((acum, item) => acum + (item.idestado_conteo == '3' ? 1 : 0), 0);
                    this.cuadros[5].cantidad = response.detalle.reduce((acum, item) => acum + (item.cantidad === 1 ? 1 : 0), 0);

                    this.inventariofisico_detalle = response.detalle;
                    */
                    console.log(response.detalle);
                },
                error => {
                    console.log(<any>error)
                }
            );
        }
            
    }
    
    agruparPorAlmacen(datos: any[], estados: any[]) {
        const mapa = new Map();

        datos.forEach(item => {
          const key = `${item.idinventariofisico}-${item.almacen}`;

          if (!mapa.has(key)) {
            mapa.set(key, {
              idinventariofisico: item.idinventariofisico,
              almacen: item.almacen,
              codigo_almacen: item.codigo_almacen,
              estados: estados.map(e => ({
                idestado_conteo: e.idestado_conteo,
                estado_conteo: e.estado_conteo,
                cantidad: 0
              }))
            });
          }

          // buscar el estado correspondiente y aumentar su cantidad
          const grupo = mapa.get(key);
          const estado = grupo.estados.find(
            (e: any) => e.idestado_conteo === item.idestado_conteo
          );
          if (estado) {
            estado.cantidad += 1;
          }
        });

        return Array.from(mapa.values());
    }

}
