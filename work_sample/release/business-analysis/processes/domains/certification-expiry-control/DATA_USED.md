# Data Used

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Entity / Table | Business Meaning | Key Fields Observed |
| --- | --- | --- |
| `cc_registro_documentos` | Registro principal de certificado/documento controlado. | `tipo_documento_id`, fechas, `codigo_documento`, `modelo`, `ap_madre`, atributos vehiculo, alerta, extension, `notificacion_enviada`, `created_by`, `created_by_user` |
| `cc_archivos` | Archivos adjuntos del registro documental. | `registro_documento_id`, `archivo`, `created_by`, auditoria |
| `cc_mercancias` | Mercancias asociadas al documento. | `registro_documento_id`, `mercancia`, `created_by`, auditoria |
| `cc_tipos_documentos` | Catalogo de tipos documentales del control. | `id`, `tipo_documento`, `deleted_at` |
| `v_certificados_vencidos` | Vista de conteo de documentos vencidos por cliente. | `total_vencidos`, `created_by` |
| `f_estado_documento` | Funcion SQL de estado documental. | `fechaVencimiento`, `fechaVencimientoExtension`, `plazo`, `plazoUnidad` |
| `dav_productosproveedor` | Fuente de modelos, mercancia vehicular, AP madre y atributos de producto. | `codigoproducto`, `idmercancia`, `apmadre1`, `apmadre2`, `partidaarancelaria`, parametros |
| `dav_dato` | Catalogo para marca, clase, tipo y atributos de producto. | `idadta`, `dato` |
| `dav_autorizacionprevia` | Autorizacion previa por chasis. | `chasis`, `nodocumento`, `fechaemision`, `nropago` |
| `dav_estadoaps` | Estado de autorizacion previa usado en control AP. | `idestadoaps`, estado |
| `dav_partidas`, `dav_facturacomercial`, `dav_casos` | Cruce para control de AP y exclusion de casos anulados/liquidados. | `otroparametro10`, `idcasos`, `anulado`, `fechaliquidacion` |
| `dav_email_notificaciones` | Destinatarios de notificaciones documentales. | `email`, `cliente_id`, `tipo_notificacion_id`, `deleted_at` |

## Data Quality Notes

- `created_by` en `cc_registro_documentos` representa el cliente; `created_by_user` representa el usuario cliente.
- La funcion `f_estado_documento` contiene diferencias de calculo por unidad `M`, `Y` o dias que deben validarse.
- El nombre del endpoint `cetificados-vencidos.php` contiene una errata, pero se mantiene como evidencia tecnica.
