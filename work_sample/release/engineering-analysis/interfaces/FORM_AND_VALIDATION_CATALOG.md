# Form and validation catalog

Estado: inferred_from_static_evidence  
Confianza: media

| Formulario/familia | Validaciones candidatas | Ubicacion de reglas |
|---|---|---|
| Login y 2FA | Usuario, clave, codigo, tipo de segundo factor, sesion | PHP, JavaScript, base de datos |
| Recuperacion/cambio de clave | Complejidad, vencimiento, correo, token | PHP y tablas de usuario |
| Gestion aduanera | Cliente, embarque, proveedor, agente, division, tipo solicitud, estados | Formularios PHP, AJAX, tablas `dav_*`/`ada_*` |
| DAV/partidas/mercancia | Partidas, unidades, acuerdos, parametros, datos de mercancia | PHP y tablas `dav_*` |
| Logistica/cotizacion | Origen/destino, puerto, carga, mercancia, contenedor, costos | PHP, SQL y catalogos logisticos |
| Exportaciones/transporte | Documentos, comparaciones, rutas, viaje, carga | AJAX, SQL y reportes |
| Proveedores/contactos/token | Tipo contacto, correo, telefono, empresa, token | PHP y tablas de contacto/proveedor |
| Reporteria | Rango de fechas, cliente, estado, area, filtros | PHP/SQL y parametros de dashboard |

## Hallazgos transversales

- La validacion esta distribuida, no centralizada.
- Parte de las reglas funcionales vive en SQL o en combos/catalogos.
- Los mensajes de validacion forman parte del contrato de usuario.
- Las reglas deben documentarse como inferidas hasta ser confirmadas por negocio.
