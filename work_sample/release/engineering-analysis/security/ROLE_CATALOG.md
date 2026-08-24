# Role Catalog

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Rol candidato | Base observada | Notas de seguridad |
| --- | --- | --- |
| Cliente usuario lector | `dav_clienteusuariospermisos.lectura` | Acceso a pantallas/reportes habilitados por cliente. |
| Cliente usuario escritor | `dav_clienteusuariospermisos.escritura` | Puede crear/editar segun `idreportescliente`; requiere verificacion server-side por endpoint. |
| Cliente con MFA | `dav_clienteusuarios.2fa` | Entra por flujo `2fa.php`; requiere hardening de payload/codigo. |
| Cliente sin MFA | `dav_clienteusuarios.2fa` vacio | Entra directo desde `veriflogin.php`; controles modernos observados en login primario. |
| Usuario interno | `dav_usuario`, `dav_permisos` | Permisos internos no reconstruidos completamente en esta pasada. |
| Proveedor/transportista | `ASGARD_TYPE=PROVEEDORES`, `prov_usuarioaccesoproveedor` | Acceso condicionado por proveedor/tipo; revisar tenant isolation. |
| Agente aduana | `ada_*`, `ada_clienteagenteaduana` | Tercero relacionado a cliente; participa en solicitudes/documentos. |
| Agente seguro | `ads_*` | Tercero documental y SOAT/seguros. |
| Gestor transporte | `ges_*` | Tercero asociado a gestion/logistica. |
| Operador logistico | `logis_operadores`, tracking/costos | Puede recibir solicitudes/costos/tracking segun token/contexto. |
| Master password / break-glass | `master_pass` | Rol tecnico de alto riesgo; requiere politica, auditoria y rotacion. |

## Pendiente

- Extraer roles internos oficiales desde catalogos `dav_tipousuario`, `dav_permisos` y menus.
- Confirmar si `tipo_usuario` y `tipo_usuario_mejora_continua` son roles de autorizacion o solo etiquetas de contexto.
