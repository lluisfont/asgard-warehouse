# Data Used

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Entity / Table | Business Meaning | Key Fields Observed |
| --- | --- | --- |
| `rnc_mejoras_continuas` | Registro principal de mejora continua / NC / SNC. | `cliente_id`, `tipo_registro_id`, `origen_observacion_id`, `area_id`, `proceso_afectado_id`, `tipo_hallazgo_id`, `consecuencia_id`, `impacto_id`, `proveedor_id`, `nivel_riesgo_id`, descripcion, accion inmediata, plazos, responsables, estado, verificacion, cierre, reapertura |
| `rnc_analisis_causas` | Plan de analisis causal y acciones correctivas. | `mejora_continua_id`, `analisis_causa`, `accion_correctiva`, `resultado_esperado`, `responsable_id`, `fecha_inicio`, `fecha_fin`, `verificacion`, `archivo`, `verificado` |
| `rnc_mejora_continua_archivos` | Evidencias/archivos del flujo moderno de mejora continua. | `mejora_continua_id`, archivo, descripcion, auditoria |
| `rnc_archivos` | Archivos legacy de no conformidad. | `registro_noconformidad_id`, `archivo`, `descripcion`, `tipo` |
| `rnc_noconformidad` | Modelo legacy de no conformidad. | hallazgo, accion inmediata, accion correctiva, riesgo, requisitos, responsables |
| `rnc_derivaciones_analistas` | Derivaciones de casos entre analistas. | mejora continua, responsable/analista, accion correctiva, auditoria |
| `rnc_normativas_requisitos` | Relacion entre mejora continua y requisitos normativos incumplidos. | `mejora_continua_id`, normativa/procedimiento, requisito |
| `rnc_areas` | Areas para clasificacion del hallazgo. | `area`, `es_cliente`, `cliente_id`, auditoria |
| `rnc_procesosafectados` | Procesos afectados por area/cliente. | proceso, area, cliente, auditoria |
| `rnc_tiposhallazgos` | Tipos de hallazgo. | `hallazgo`, cliente, auditoria |
| `rnc_consecuencias` | Consecuencias asociadas a hallazgos. | `hallazgo_id`, `consecuencia`, cliente |
| `rnc_impactos` | Impacto y plazos de asignacion/atencion. | `nivel`, plazos, unidad de tiempo |
| `rnc_probabilidades`, `rnc_valoraciones_riesgos`, `rnc_matriz_evaluacion_riesgo` | Evaluacion de probabilidad/riesgo. | parametros, niveles y matriz |
| `rnc_controles_existentes`, `rnc_responsables_controles` | Controles y responsables existentes. | control, responsable, cliente |
| `rnc_normativas_procedimientos_estandares`, `rnc_requisitos_parametros`, `rnc_lista_requisitos` | Catalogos de normativa y requisitos. | normativa, requisito, descripcion |
| `rnc_equipos_analistas_cliente`, `rnc_usuarios_equipos`, `rnc_tipos_usuarios` | Roles/equipos de analisis por cliente. | usuario, equipo, tipo de usuario |

## Data Quality Notes

- Existen dos modelos: `rnc_mejoras_continuas` moderno y `rnc_noconformidad` legacy. La relacion exacta debe validarse.
- La API backend no esta dentro de los archivos PHP leidos; se documenta como dependencia externa/configurada.
- El campo `tipo_usuario_mejora_continua` aparece tambien en `dav_clienteusuarios`.
