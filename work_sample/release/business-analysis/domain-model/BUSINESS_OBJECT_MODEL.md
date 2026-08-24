# Business object model

Estado: candidate_reconstruction  
Confianza: media

| Objeto de negocio | Descripcion candidata |
|---|---|
| Cliente | Empresa propietaria o beneficiaria de operaciones, permisos, reportes y variantes |
| Usuario | Persona autenticada con rol, cliente, permisos y trazabilidad de sesion |
| Caso/expediente | Unidad operacional que agrupa solicitud, documentos, estados, costos y seguimiento |
| Solicitud | Entrada de servicio o gestion, manual o masiva, que inicia un flujo |
| Embarque | Movimiento de mercancia con informacion aduanera/logistica/documental |
| Documento | Evidencia o archivo asociado a tramite, embarque, factura, BL, SOAT, etc. |
| Partida/Mercancia | Linea declarativa o item objeto de comercio exterior |
| Proveedor/Agente | Tercero asociado a coordinacion, aduana, transporte o documentacion |
| Ruta/Viaje/Carga | Entidades logisticas para planificacion y tracking |
| Factura/Planilla/Pago | Objetos financieros derivados de la operacion |
| Reporte/KPI | Vista agregada de datos para control operativo o ejecutivo |
