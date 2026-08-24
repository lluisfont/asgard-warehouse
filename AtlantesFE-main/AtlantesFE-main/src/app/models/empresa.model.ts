export class EmpresaModel {
    constructor(
        public empresa: string,
        public titulo: string,
        public operaciones: boolean,
        public contabilidad: boolean,
        public almacen: boolean,
        public imagenes?: []
    ){}
}