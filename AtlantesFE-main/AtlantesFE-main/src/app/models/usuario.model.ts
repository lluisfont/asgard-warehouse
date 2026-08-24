export class UsuarioModel {
    constructor(
        public idusuario: number,
        public nombre: string,
        public ci: string,
        public idempresa: number,
        public empresa: string,
        public idciudad: number,
        public ciudad: string,
        public idalmacen: number,
        public almacen: string,
        public username: string,
        public idtipousuario: number,
        public tipousuario: string,
        public email: string,
        public telefono: string,
        public usuario_almacen: boolean,
        public activo: boolean,
        public columnas_moverdividir: [],
        public columnas_pedido: [],
        public permisos: []
    ){}
}