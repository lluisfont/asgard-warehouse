export class AlmacenModel {
    constructor(
        public idalmacen: number,
        public almacen: string,
        public idciudad: number,
        public ciudad: string,
        public direccion: string,
        //public filas: number,
        //public columnas: number,
        public detalle: Array<any>
    ){}
}