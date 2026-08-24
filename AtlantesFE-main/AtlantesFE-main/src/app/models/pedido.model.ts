export class PedidoModel {
    constructor(
        public idpedido: string,
        public idalmacen: number,
        public numero: number,
        public gestion: number,
        public numeropedido: number,
        public idcliente: string,
        public cliente: string,
        public fecha: string,
        public no_pedido: string,
        public fecha_entrega: string,
        public nombre: string,
        public rubro: string,
        public idusuario_revisado: number,
        public nombre_revisado: string,
        public nota_adicional: string,
        public pedidodetalle: [{
            idpedidodetalle: string,
            codigo: string,
            descripcion: string,
            serie: string,
            unidadmedida: string,
            total: number,
            pedidodisponibilidad: [{
                idpedidodisponibilidad: number,
                cantidad: number,
                ubicacionalmacen: string,
                sector: string,
                ppt: string,
                fechavencimiento: string,
                diasavencer: number,
                lote: string,
                fechaingreso: string
            }]
        }],
        public pedidotienda: [{
            idpedidotienda: number,
            tienda: string,
            no_pedido: string,
            marcado: boolean,
            total_disponible_tienda: number,
            total_pedido_tienda: number
        }],
        public pedidotabla: Array<any>,
        public pedidodetalletienda: [{
            idpedidotienda2: number,
            idpediodetalletienda: string,
            idpedidodetalle: string,
            idpedidotienda: string,
            cantidad: number
        }],
        public ubicaciones: [{
            ubicacion: number
        }],
        public preparacion: [{
            idpedidopreparacion: number,
            idpreparador: number,
            preparador: string,
            sector: Array<any>,
            texto_sector: string,
            hora_inicio: string,
            hora_fin: string,
            demora: number,
            conforme: boolean,
            conforme2: boolean,
            conforme3: boolean,
            notas: string,
            bultos: number
        }],
        public salidas: Array<any>,
        public salidas_automaticas: Array<any>
    ){}
}
